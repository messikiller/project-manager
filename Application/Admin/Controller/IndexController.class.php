<?php
namespace Admin\Controller;
use Think\Controller;

class IndexController extends CommonController
{
	public function _initialize()
	{
		parent::_initialize();
	}

	public function index()
	{
		$project_schedule_nums = get_startable_projects_num($this->uid) + get_markable_projects_num($this->uid);
		$work_schedule_nums = get_startable_works_num($this->uid) + get_finished_works_num($this->uid);
		
		$this->assign('project_schedule_nums', $project_schedule_nums);
		$this->assign('work_schedule_nums', $work_schedule_nums);

		$this->assign('uid', $this->uid);
		$this->assign('username', $this->username);
		$this->assign('user_level', $this->user_level);

		$this->display();
    }

	public function welcome()
	{
		$uid = $this->uid;
		$e_time = time();
		$s_time = $e_time - (7*24*60*60);

		$where = array(
			'user_id' => array('EQ', $uid),
			'c_time'  => array('BETWEEN', "{$s_time}, {$e_time}")
		);

		$signModel = M('sign_records');
		$signArr = $signModel->where(array('user_id' => $uid))->order('c_time desc')->select();
		$this->assign('data', $signArr);
		$this->assign('uid', $this->uid);
		$this->assign('username', $this->username);
		$this->assign('index', 1);
		$this->display();
	}
}
