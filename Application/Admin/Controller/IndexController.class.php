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
		$project_schedule_nums = get_startable_projects_num($this->uid)
			+ get_markable_projects_num($this->uid);

		$this->assign('project_schedule_nums', $project_schedule_nums);
		$this->assign('uid', $this->uid);
		$this->assign('username', $this->username);
		$this->assign('user_level', $this->user_level);
        $this->display();
    }
}
