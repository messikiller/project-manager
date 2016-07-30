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

        $where = array();

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
        $userArr = $userModel
        	->field('id, truename')
        	->where(array('id' => array('IN', "$leaderUids")))
        	->select();

        $leaderIdsList = makeIndex($userArr, 'id');

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

        $this->assign('data',    $data);
        $this->assign('show',    $show);
        $this->assign('pagenum', $Page->totalPages);
        $this->assign('index',   $Page->firstRow+1);
		$this->display();
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

	}

	/**
	 * @access admin
	 */
	public function editHandle()
	{
		if (! $this->is_admin) {
			$this->redirect('admin/error/deny');
		}

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