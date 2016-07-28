<?php
namespace Admin\Controller;
use Think\Controller;

class CommonController extends Controller
{
	protected $is_admin  = false;
	protected $is_leader = false;
	protected $is_member = false;

	public function _initialize()
	{
		if (! session('?username') || ! session('?user_id') || ! session('?user_level')) {
			$this->redirect('admin/login/index');
		}

		switch (session('user_level')) {
			case 0:
				$this->is_admin = true;
				break;

			case 1:
				$this->is_leader = true;
				break;

			case 2:
				$this->is_member = true;
				break;
		}
	}

}
