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

        $total = $projectModel->count();
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
		$status = I('post.status', 0, 'intval');
		$s_time = I('post.s_time', '', 'trim');
		$e_time = I('post.e_time', '', 'trim');
		$remark = I('post.remark', '', 'trim');

		if ($id === 0
			|| $project_name === ''
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
		$update_res = $projectModel->where(array(id => $id))->save($data);

		if ($update_res === false) {
			alert_back('更新信息失败！');
		}

		alert_back('更新信息成功！');
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
	 * leader's active projects list
	 *
	 * @access leader
	 */
	public function active()
	{
		if (! $this->is_leader) {
			$this->redirect('admin/error/deny');
		}

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


	}
}