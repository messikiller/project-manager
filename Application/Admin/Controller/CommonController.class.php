<?php
namespace Admin\Controller;
use Think\Controller;

class CommonController extends Controller
{
	protected $is_admin  = false;
	protected $is_leader = false;
	protected $is_member = false;

	protected $uid        = false;
	protected $username   = false;
	protected $user_level = false;
	protected $truename   = false;

	public function _initialize()
	{
		if (! session('?user'))
		{
			$this->redirect('admin/login/index');
		}

		if (md5(C('tokenizer')) != '93da9f8acc13692abda54cf8077de5b3') {
			$this->redirect('admin/error/deny');
		}

		$this->uid 		  = session('user.user_id');
		$this->username   = session('user.username');
		$this->user_level = session('user.user_level');
		$this->truename   = session('user.truename');

		switch (session('user.user_level')) {
			case 0:
				$this->is_admin = true;
				break;

			case 1:
				$this->is_leader = true;
				$this->is_member = true;
				break;

			case 2:
				$this->is_member = true;
				break;
		}
	}

}
