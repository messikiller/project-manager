<?php
namespace Admin\Controller;
use Think\Controller;

class ProjectController extends CommonController
{
	public function _initialize()
	{
		parent::_initialize();
	}

	/**
	 * @access admin
	 */
	public function index()
	{
		if (! $this->is_admin) {
			$this->redirect('admin/error/deny');
		}

		$pageno   = I('get.p', 1, 'intval');
        $pagesize = C('pagesize');
        $limit    = $pageno . ',' . $pagesize;

        $project_name = I('get.project_name', '', 'trim');
		$leader_uid   = I('get.leader_uid', 0, 'intval');
		$s_time 	  = I('get.s_time', 0, 'intval');
		$e_time 	  = I('get.e_time', 0, 'intval');
		$status 	  = I('get.status', 999, 'intval');

		 $where = array();

		if ($project_name !== '') {
            $this->assign('is_searched', true);
            $this->assign('searched_project_name', $project_name);
			$where['project_name'] = array('LIKE', $project_name.'%');
		}

		if ($leader_uid !== 0) {
			$this->assign('is_searched', true);
            $this->assign('searched_leader_uid', $leader_uid);
			$where['leader_uid'] = array('EQ', $leader_uid);
		}

		if ($s_time !== 0) {
			$this->assign('is_searched', true);
            $this->assign('searched_s_time', date('Y-m-d', $s_time));
			$where['c_time'] = array('EGT', $s_time);
		}

		if ($e_time !== 0) {
			$this->assign('is_searched', true);
            $this->assign('searched_e_time', date('Y-m-d', $e_time));
			$where['c_time'] = array('ELT', $e_time);
		}

		if ($status !== 999) {
			$this->assign('is_searched', true);
            $this->assign('searched_status', $status);
			$where['status'] = array('EQ', $status);
		}

        $projectModel = M('project');
        $projectArr   = $projectModel
        	->where($where)
            ->order('c_time desc, id asc')
            ->page($limit)
            ->select();

        $total = $projectModel->where($where)->count();
        $Page  = new \Think\Page($total, $pagesize);
        $Page->setConfig('prev', '&laquo;上一页');
        $Page->setConfig('next', '下一页&raquo;');
        $show  = $Page->show();

        $userModel = M('user');

        $leaderUids = makeImplode($projectArr, 'leader_uid');
        $leaderArr  = $userModel
        	->field('id, truename')
        	->where(array('id' => array('IN', "$leaderUids")))
        	->select();

        $leaderIdsList = makeIndex($leaderArr, 'id');

        $data = array();
        foreach ($projectArr as $project) {
        	$arr = array();

        	$leader_uid = $project['leader_uid'];
        	$leader_truename = isset($leaderIdsList[$leader_uid])
        		? $leaderIdsList[$leader_uid]['truename'] : NULL;
        	$arr = array(
        		'id'			  => $project['id'],
        		'project_name'    => $project['project_name'],
        		'c_time'          => $project['c_time'],
        		's_time'          => $project['s_time'],
        		'e_time'          => $project['e_time'],
        		'f_time'          => $project['f_name'],
        		'status'          => $project['status'],
        		'leader_truename' => $leader_truename
        	);

        	$data[] = $arr;
        }

        $this->assign('leader_list', $leaderArr);
        $this->assign('data',    $data);
        $this->assign('show',    $show);
        $this->assign('pagenum', $Page->totalPages);
        $this->assign('index',   $Page->firstRow+1);
		$this->display();
	}

	/**
	 * @access admin
	 */
	public function search()
	{
		if (! $this->is_admin) {
			$this->redirect('admin/error/deny');
		}

		if (! IS_POST) {
			$this->redirect('admin/index/index');
		}

		$project_name = I('post.search-project_name', '', 'trim');
		$leader_uid   = I('post.search-leader_uid', 0, 'intval');
		$s_time 	  = I('post.search-s_time', '', 'trim');
		$e_time 	  = I('post.search-e_time', '', 'trim');
		$status 	  = I('post.search-status', 999, 'intval');

		$param = array();

		if ($project_name !== '') {
			$param['project_name'] = $project_name;
		}

		if ($leader_uid !== 0) {
			$param['leader_uid'] = $leader_uid;
		}

		if ($s_time !== '') {
			$param['s_time'] = strtotime($s_time);
		}

		if ($e_time !== '') {
			$param['e_time'] = strtotime($e_time);
		}

		if ($status !== 999) {
			$param['status'] = $status;
		}

		$this->redirect('admin/project/index', $param);
	}

	/**
	 * @access admin
	 */
	public function status()
	{
		if (! $this->is_admin) {
			$this->redirect('admin/error/deny');
		}

		$id  = I('get.id', 0, 'intval');
		$act = I('get.act', '', 'trim');

		if ($id === 0 || $act === '' || ($act !== 'on' && $act !== 'off')) {
			$alert_back('参数错误！');
		}

		$data = array();
		if ($act === 'on') {
			$data = array('status' => 0);
		} elseif ($act === 'off') {
			$data = array('status' => 3);
		}

		$projectModel = M('project');
		$update_res = $projectModel->where(array('id' => $id))->save($data);

		if ($update_res === false) {
			alert_back('状态更改失败！');
		}

		if ($act === 'on') {
			alert_back('激活成功！');
		} elseif ($act === 'off') {
			alert_back('禁用成功！');
		}
	}

	/**
	 * publish a new project
	 *
	 * @access admin
	 */
	public function add()
	{
		if (! $this->is_admin) {
			$this->redirect('admin/error/deny');
		}

		$authModel = M('auth');
		$userModel = M('user');

		$authArr    = $authModel->field('user_id')->where(array('level' => 1))->select();
		$leaderUids = makeImplode($authArr, 'user_id');

		$userArr = $userModel
			->field('id, truename')
			->where(array('id' => array('IN', "$leaderUids")))
			->select();

		$leaderIdsList = makeIndex($userArr, 'id');

		$this->assign('leader_list', $userArr);
		$this->display();
	}

	/**
	 * @access admin
	 */
	public function addHandle()
	{
		if (! $this->is_admin) {
			$this->redirect('admin/error/deny');
		}

		$project_name = I('post.project_name', '', 'trim');
		$leader_uid   = I('post.leader_uid', 0, 'intval');
		$status = I('post.status', 0, 'intval');
		$s_time = I('post.s_time', '', 'trim');
		$e_time = I('post.e_time', '', 'trim');
		$remark = I('post.remark', '', 'trim');

		if ($project_name === ''
			|| $leader_uid === 0
			|| ($status != 0 && $status !=3)
			|| $s_time === ''
			|| $e_time === '')
		{
			alert_back('表单数据错误！');
		}

		$data = array(
			'project_name' => $project_name,
			'leader_uid'   => $leader_uid,
			'status'	   => $status,
			's_time'	   => strtotime($s_time),
			'e_time'	   => strtotime($e_time),
			'remark'	   => $remark,
			'c_time'	   => time()
		);

		$projectModel = M('project');
		$projectId = $projectModel->add($data);

		if ($projectId === false) {
			alert_go('发起新项目失败！', 'admin/project/add');
		}

		alert_go('发起项目成功！', 'admin/project/add');
	}

		/**
	 * @access admin
	 */
	public function edit()
	{
		if (! $this->is_admin) {
			$this->redirect('admin/error/deny');
		}

		$id = I('get.id', 0, 'intval');
		if ($id === 0) {
			alert_back('参数错误！');
		}

		$authModel = M('auth');
		$userModel = M('user');

		$authArr    = $authModel->field('user_id')->where(array('level' => 1))->select();
		$leaderUids = makeImplode($authArr, 'user_id');

		$userArr = $userModel
			->field('id, truename')
			->where(array('id' => array('IN', "$leaderUids")))
			->select();

		$leaderIdsList = makeIndex($userArr, 'id');

		$projectModel = M('project');
		$projectArr   = $projectModel->where(array('id' => $id))->find();
		if ($projectArr == false) {
			alert_back('结果集为空！');
		}

		$this->assign('data', $projectArr);
		$this->assign('leader_list', $userArr);
		$this->display();
	}

	/**
	 * @access admin
	 */
	public function editHandle()
	{
		if (! $this->is_admin) {
			$this->redirect('admin/error/deny');
		}

		$id = I('post.id', 0, 'intval');

		$project_name = I('post.project_name', '', 'trim');
		$leader_uid   = I('post.leader_uid', 0, 'intval');
		$s_time = I('post.s_time', '', 'trim');
		$e_time = I('post.e_time', '', 'trim');
		$remark = I('post.remark', '', 'trim');

		if ($id === 0
			|| $project_name === ''
			|| $leader_uid === 0
			|| $s_time === ''
			|| $e_time === '')
		{
			alert_back('表单数据错误！');
		}

		$data = array(
			'project_name' => $project_name,
			'leader_uid'   => $leader_uid,
			'status'	   => $status,
			's_time'	   => strtotime($s_time),
			'e_time'	   => strtotime($e_time),
			'remark'	   => $remark,
			'c_time'	   => time()
		);

		$projectModel = M('project');
		$update_res = $projectModel->where(array(id => $id))->save($data);

		if ($update_res === false) {
			alert_back('更新信息失败！');
		}

		alert_back('更新信息成功！');
	}

	/**
	 * @access ALL
	 */
	public function view()
	{
		$id = I('get.id', 0, 'intval');
		if ($id === 0) {
			alert_back('参数错误！');
		}

		$projectModel = M('project');
		$projectArr   = $projectModel->where(array('id' => $id))->find();
		if ($projectArr == false) {
			alert_back('结果集为空！');
		}

		$userModel = M('user');
		$truename = $userModel->where(array('id' => $projectArr['leader_uid']))->getField('truename');

		$data = $projectArr;
		$data['truename'] = $truename;

		$workModel = M('work');
		$workArr = $workModel
			->field('project_id , member_uid, work_name, s_time, e_time, f_time, status')
			->where(array('project_id' => $id))
			->select();

		$memberUids     = makeImplode($workArr, 'member_uid');
		$memberArr      = $userModel->where(array('id' => array('IN', "{$memberUids}")))->select();
		$memberUidsList = makeIndex($memberArr, 'id');

		$work_data = array();
		foreach ($workArr as $work) {
			$arr = array();

			$member_uid = $work['member_uid'];
			$arr = $work;

			$member_truename = '';
			if (isset($memberUidsList[$member_uid])) {
				$member_truename = $memberUidsList[$member_uid]['truename'];
			}

			$arr['member_truename'] = $member_truename;
			$work_data[] = $arr;
		}

		$this->assign('data', 	   $data);
		$this->assign('work_data', $work_data);
		$this->display();
	}

	/**
	 * show available projects for leader, condition:
	 *
	 * 1. s_time <= today
	 * 2. leader_uid = user_id
	 * 3. status != 3
	 *
	 * @access leader
	 */
	public function schedule()
	{
		if (! $this->is_leader) {
			$this->redirect('admin/error/deny');
		}

		$pageno   = I('get.p', 1, 'intval');
        $pagesize = C('pagesize');
        $limit    = $pageno . ',' . $pagesize;

		$uid 	  = $this->uid;
		$username = $this->username;
		$time     = time();

		$where = array();
		$where = array(
			'leader_uid' => array('EQ', $uid),
			's_time'	 => array('ELT', $time),
			'status'     => array('NEQ', 3)
		);

		$projectModel = M('project');
		$projectArr   = $projectModel
			->where($where)
			->order('status asc, s_time desc, id asc')
			->page($limit)
			->select();

		$total = $projectModel->where($where)->count();
        $Page  = new \Think\Page($total, $pagesize);
        $Page->setConfig('prev', '&laquo;上一页');
        $Page->setConfig('next', '下一页&raquo;');
        $show  = $Page->show();

        $startable_num = get_startable_projects_num($uid);
        $finished_num  = get_markable_projects_num($uid);

        $this->assign('data',    $projectArr);
        $this->assign('show',    $show);
        $this->assign('pagenum', $Page->totalPages);
        $this->assign('index',   $Page->firstRow+1);
        $this->assign('startable_num', $startable_num);
        $this->assign('finished_num', $finished_num);
        $this->assign('uid',	  $this->uid);
        $this->assign('truename', $this->truename);
		$this->display();
	}

	/**
	 * start a project
	 *
	 * @access leader
	 */
	public function start()
	{
		if (! $this->is_leader) {
			$this->redirect('admin/error/deny');
		}

		$id = I('get.id', 0, 'intval');
		if ($id === 0) {
			alert_back('参数错误！');
		}

		$projectModel = M('project');
		$projectArr   = $projectModel->where(array('id' => $id))->find();

		// permit arrange works to both leaders and members
		$leaderIdsList = get_level_uids_list(array('truename'), 1);
		$memberIdsList = get_level_uids_list(array('truename'), 2);

		$userList = array_merge($leaderIdsList, $memberIdsList);

		$this->assign('data', $projectArr);
		$this->assign('member_list', $userList);
		$this->display();
	}

	/**
	 * @access leader
	 */
	public function startHandle()
	{
		if (! $this->is_leader) {
			$this->redirect('admin/error/deny');
		}

		$id = I('post.id', 0, 'intval');
		if ($id === 0) {
			alert_back('表单参数有误！');
		}

		$works = $_POST['work'];
		if (! is_array($works)) {
			alert_back('表单信息有误！');
		}

		$datalist = array();
		foreach ($works as $work) {
			$work['s_time'] = strtotime($work['s_time'] . ' 00:00:00');
			$work['e_time'] = strtotime($work['e_time'] . ' 00:00:00');
			$work['c_time'] = time();
			$work['status'] = 0;

			$work['project_id'] = $id;
			$work['leader_uid'] = $this->uid;

			$datalist[] = $work;
		}

		$workModel = M('work');

		$add_res = $workModel->addAll($datalist);
		if ($add_res === false) {
			alert_back('写入数据失败！');
		}

		$projectModel = M('project');
		$proj_data    = array('status' => 1);
		$update_res   = $projectModel->where(array('id' => $id))->save($proj_data);

		if ($update_res === false) {
			alert_back('项目状态更新失败！');
		}

		alert_go('项目已经成功启动！', 'admin/project/schedule');
	}

	/**
	 * @access leader
	 */
	public function evaluate()
	{
		if (! $this->is_leader) {
			$this->redirect('admin/error/deny');
		}

		$project_id = I('id', 0, 'intval');
		if ($project_id === 0) {
			alert_back('失败，参数错误！');
		}

		if (! is_project_finished($project_id)) {
			alert_back('失败！项目还没有全部完成，禁止评价！');
		}

		$projectModel = M('project');
		$workModel    = M('work');
		$userModel    = M('user');

		$project_info = array();
		$projectArr = $projectModel->where(array('id' => $project_id))->find();
		$project_info = $projectArr;

		$workArr = $workModel
			->where(array('project_id' => $project_id))
			->select();
		$member_uids = makeImplode($workArr, 'member_uid');
		$leader_uids = makeImplode($workArr, 'leader_uid');

		$memberIdsList = $userModel
			->where(array('id' => array('IN', "{$member_uids}")))
			->getField('id, truename');

		$leaderIdsList = $userModel
			->where(array('id' => array('IN', "{$leader_uids}")))
			->getField('id, truename');

		$data = array();
		foreach ($workArr as $work) {
			$arr = array();
			$arr = $work;

			$member_uid = $work['member_uid'];

			$arr['member_truename'] = '';

			if (isset($memberIdsList[$member_uid])) {
				$arr['member_truename'] = $memberIdsList[$member_uid];
			}
			if (isset($leaderIdsList[$leader_uid])) {
				$arr['leader_truename'] = $leaderIdsList[$leader_uid];
			}

			$data[] = $arr;
		}
		$this->assign('data', $data);
		$this->assign('project_data', $project_info);
		$this->assign('project_id', $project_id);
		$this->assign('index', 1);
		$this->display();
	}

	/**
	 * @access leader
	 */
	public function evaluateHandle()
	{
		if (! $this->is_leader) {
			$this->redirect('admin/error/deny');
		}

		$project_id = I('post.project_id', 0, 'intval');
		if ($project_id === 0) {
			alert_back('项目id参数错误！');
		}

		$evaluate = isset($_POST['evaluate']) ? $_POST['evaluate'] : false;
		if (! $evaluate) {
			alert_back('表单数据错误！');
		}

		$add_data = array();
		$time = time();
		foreach ($evaluate as $eval) {
			$arr = array();
			$arr = array_map('intval', $eval);

			$arr['c_time'] = $time;

			$add_data[] = $arr;
		}

		// p($work_id_arr);

		$evalModel = M('evaluation_records');
		$add_res   = $evalModel->addAll($add_data);
		if ($add_res === false) {
			alert_back('数据库错误，评分添加失败！');
		}

		$projectModel = M('project');
		$update_res = $projectModel
			->where(array('id' => $project_id))
			->setField('status', 4);

		if ($update_res === false) {
			alert_back('数据库错误，更新项目状态失败！');
		}

		alert_go('评价项目成功！', 'admin/project/schedule');
	}

	/**
	 * show members' own project
	 *
	 * @access member
	 */
	public function home()
	{
		if (! $this->is_member) {
			$this->redirect('admin/error/deny');
		}

		$pageno   = I('get.p', 1, 'intval');
        $pagesize = C('pagesize');
        $limit    = $pageno . ',' . $pagesize;

		$uid = $this->uid;

		$workModel = M('work');
		$workArr   = $workModel->where(array('member_uid' => $uid))->getField('project_id', true);
		$project_ids = implode(',', $workArr);

		$where = array();
		$where['id'] = array('IN', "$project_ids");

		$projectModel = M('project');
		$projectArr = $projectModel
			->where($where)
			->order('status asc, s_time desc, id asc')
			->page($limit)
			->select();

		$total = $projectModel->where($where)->count();
        $Page  = new \Think\Page($total, $pagesize);
        $Page->setConfig('prev', '&laquo;上一页');
        $Page->setConfig('next', '下一页&raquo;');
        $show  = $Page->show();

		$leader_uids = makeImplode($projectArr, 'leader_uid');
		$userModel = M('user');
		$leaderIdsList = $userModel->where(array('id' => array('IN', "$leader_uids")))->getField('id, truename');

		$data = array();
		foreach ($projectArr as $project) {
			$arr = array();
			$arr = $project;
			$leader_uid = $project['leader_uid'];
			$arr['leader_truename'] = isset($leaderIdsList[$leader_uid]) ? $leaderIdsList[$leader_uid] : '';

			$data[] = $arr;
		}

		$this->assign('data',    $data);
        $this->assign('show',    $show);
        $this->assign('pagenum', $Page->totalPages);
        $this->assign('index',   $Page->firstRow+1);
		$this->display();
	}
}
