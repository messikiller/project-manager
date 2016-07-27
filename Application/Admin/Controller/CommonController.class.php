<?php
namespace Admin\Controller;
use Think\Controller;

class CommonController extends Controller
{
	public function _initialize()
	{
		if (! session('?username') || ! session('?user_id')) {
			alert_go('请登录后再执行操作！', 'admin/login/index');
		}

	}

}
