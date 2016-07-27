<?php
namespace Admin\Controller;
use Think\Controller;

class LoginController extends Controller
{
	public function index()
	{
        if (session('?username')) {
            $this->redirect('admin/index/index');
        }

		$this->display('index');
    }

    public function loginHandle()
    {
    	if (! IS_POST) {
    		$this->redirect('admin/login/index');
    	}

    	$username = I('post.username', false);
    	$password = I('post.password', false);

    	if (! $username || ! $password) {
    		alert_go('请输入完整信息！', 'admin/login/index');
    	}

    	$userModel = M('user');
    	$authModel = M('auth');

    	$userArr = $userModel->field('id, username')
    		->where(array('username' => $username))->find();

    	if (! $userArr) {
    		alert_go('用户不存在！', 'admin/login/index');
    	}
    	$user_id = $userArr['id'];
    	$authArr = $authModel->where(array(
    		'user_id'  => $user_id,
    		'password' => md5($password)
    	))->find();

    	if (! $authArr) {
    		alert_go('登录失败，请检查！', 'admin/login/index');
    	}

        $user_level = intval($authArr['level']);

    	session('username',   $username);
    	session('user_id',    $user_id);
        session('user_level', $user_level);

		$this->redirect('admin/index/index');
    }

    public function logout()
    {
    	session('username', NULL);
    	session('user_id',  NULL);
        session('is_admin', NULL);

    	alert_go('注销成功！即将跳转到登录页面……', 'admin/login/index');
    }
}
