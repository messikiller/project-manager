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

    	$userArr = $userModel->field('Id, username, level')
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

        $is_admin = intval($userArr['level']) === 1 ? true : false;

    	session('username', $username);
    	session('user_id',  $user_id);
        session('is_admin', $is_admin);

		$this->redirect('admin/index/index');
    }

    public function register()
    {
    	$this->display('register');
    }

    public function registerHandle()
    {
    	if (! IS_POST) {
    		$this->redirect('admin/register/index');
    	}

    	$username = I('post.username', false);
    	$password = I('post.password', false);
    	$confirm  = I('post.confirm',  false);

    	if(! $username || ! $password || ! $confirm) {
    		alert_go('请输入完整信息后提交！', 'admin/login/register');
    	}

    	if ($password != $confirm) {
    		alert_go('两次输入的密码不一致！', 'admin/login/register');
    	}

    	$userModel = M('user');

    	$userArr = $userModel->where(array('username' => $username))->find();
    	if ($userArr) {
    		alert_go('用户名已经存在！', 'admin/login/register');
    	}

    	$time = time();

    	$userData = array('username' => $username, 'o_time' => $time);
    	$user_id = $userModel->add($userData);

    	if (! $user_id) {
    		alert_go('创建用户失败！', 'admin/login/register');
    	}

    	$authModel = M('auth');

    	$authData = array('user_id' => $user_id, 'password' => md5($password));
    	$authModel->add($authData);

    	alert_go('注册成功！', 'admin/login/index');
    }

    public function logout()
    {
    	session('username', NULL);
    	session('user_id',  NULL);
        session('is_admin', NULL);

    	alert_go('注销成功！即将跳转到登录页面……', 'admin/login/index');
    }
}
