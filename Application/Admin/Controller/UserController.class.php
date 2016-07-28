<?php
namespace Admin\Controller;
use Think\Controller;

class UserController extends CommonController
{
	public function _initialize()
	{
		parent::_initialize();

        if (! $this->is_admin) {
            $this->redirect('admin/index/index');
        }
    }

	public function index()
	{
        $userModel = M('user');

		$where    = "username <> 'admin'";
		$pageno   = I('get.p', 1, 'intval');
		$pagesize = 20;

		$limit = $pageno . ',' . $pagesize;

        $userArr = $userModel->field('id, username, truename, phone, position, station, work_place')
            ->where($where)
			->order('id asc')
			->page($limit)
            ->select();

		$total = $userModel->where($where)->count();
		$Page  = new \Think\Page($total, $pagesize);
		$Page->setConfig('prev', '&laquo;上一页');
		$Page->setConfig('next', '下一页&raquo;');
		$show  = $Page->show();

        $userArr = empty($userArr) ? array() : $userArr;

        $data = array();

		$userIdsList = makeImplode($userArr, 'id');

		$authModel = M('auth');
		$authArr   = $authModel->field('user_id, level')
			->where(array('user_id' => array('in', $userIdsList)))
			->select();

		$authIdsArr = makeIndex($authArr, 'user_id');

        foreach ($userArr as $user) {
			$user_id = $user['id'];
			if (! isset($authIdsArr[$user_id])) {
				continue;
			}
            $arr = $user;
            $arr['level'] = $authIdsArr[$user_id]['level'];

            $data[] = $arr;
        }

        $this->assign('data',  $data);
		$this->assign('show',  $show);
		$this->assign('pagenum', $Page->totalPages);
		$this->assign('index', $Page->firstRow+1);
        $this->display();
    }

    public function add()
    {
        $this->display();
    }

    public function addHandle()
    {
        if (! IS_POST) {
            $this->redirect('admin/index/index');
        }

        $username   = I('post.username',   NULL, 'trim');
        $truename   = I('post.truename',   NULL, 'trim');
        $phone      = I('post.phone',      NULL, 'trim');
        $position   = I('post.position',   NULL, 'trim');
        $station    = I('post.station',    NULL, 'trim');
        $work_place = I('post.work_place', NULL, 'trim');

        $password = I('post.password',     NULL, 'trim');
        $password = md5($password);
        $level    = I('post.level',        NULL, 'intval');

        if (($username === NULL)
            || ($truename   === NULL)
            || ($phone      === NULL)
            || ($position   === NULL)
            || ($station    === NULL)
            || ($work_place === NULL)
            || ($password   === NULL)
            || ($level      === NULL))
        {
            alert_go('表单填写不完整！', 'admin/user/add');
        }

        $userModel = M('user');
        $authModel = M('auth');

        $total = $userModel->where(array('username' => $username))->count();
        if ($total > 0) {
            alert_go('账户名称已经存在', 'admin/user/add');
        }

        $user_data = array(
            'username'   => $username,
            'truename'   => $truename,
            'phone'      => $phone,
            'position'   => $position,
            'station'    => $station,
            'work_place' => $work_place
        );

        $user_id = $userModel->add($user_data);

        if (empty($user_id)) {
            alert_go('添加用户失败！', 'admin/user/add');
        }

        $auth_data = array(
            'user_id'  => $user_id,
            'password' => $password,
            'level'    => $level
        );

        $auth_id = $authModel->add($auth_data);

        if (empty($auth_id)) {
            alert_go('添加账户失败！', 'admin/user/add');
        }

        alert_go('添加账户成功！', 'admin/user/add');
    }

    public function edit()
    {
        $id = I('get.id', NULL, 'intval');
        if (empty($id)) {
            $this->redirect('admin/index/index');
        }

        $userModel = M('user');
        $authModel = M('auth');

        $userArr = $userModel->where(array('id' => $id))->find();
        $authArr = $authModel->where(array('user_id' => $id))->find();

        if (empty($userArr) || empty($authArr)) {
            alert_back('要编辑的信息不存在！');
        }

        $data = array();

        $data = array(
            'id'         => $userArr['id'],
            'username'   => $userArr['username'],
            'truename'   => $userArr['truename'],
            'phone'      => $userArr['phone'],
            'position'   => $userArr['position'],
            'station'    => $userArr['station'],
            'work_place' => $userArr['work_place'],
            'level'      => $authArr['level']
        );

        $this->data = $data;
        $this->display();
    }

    public function editHandle()
    {
        if (! IS_POST) {
            $this->redirect('admin/index/index');
        }

        $id         = I('post.id',         NULL, 'intval');
        $username   = I('post.username',   NULL, 'trim');
        $truename   = I('post.truename',   NULL, 'trim');
        $phone      = I('post.phone',      NULL, 'trim');
        $position   = I('post.position',   NULL, 'trim');
        $station    = I('post.station',    NULL, 'trim');
        $work_place = I('post.work_place', NULL, 'trim');
        $level      = I('post.level',      NULL, 'intval');

        if (($id === NULL)
            || ($username   === NULL)
            || ($truename   === NULL)
            || ($phone      === NULL)
            || ($position   === NULL)
            || ($station    === NULL)
            || ($work_place === NULL)
            || ($level      === NULL))
        {
            alert_back('表单填写不完整！');
        }

        $userModel = M('user');
        $authModel = M('auth');

        $user_data = array();
        $user_data = array(
            'username'   => $username,
            'truename'   => $truename,
            'phone'      => $phone,
            'position'   => $position,
			'station'    => $station,
			'work_place' => $work_place
        );

		$user_res = $userModel->where(array('id' => $id))->save($user_data);
		if ($user_res === false) {
			alert_back('基础信息更新失败！');
		}

		$auth_data = array();
		$auth_data = array(
			'level' => $level
		);

		$auth_res = $authModel->where(array('user_id' => $id))->save($auth_data);
		if ($auth_res === false) {
			alert_back('用户等级更新失败！');
		}

		alert_back('信息更新成功！');
	}

	public function resetPassword()
	{
		$id = I('get.id', NULL, 'intval');
		if ($id === NULL) {
			$this->redirect('admin/user/index');
		}

		$userModel = M('user');

		$userArr = $userModel->where(array('id' => $id))->find();
		if (empty($userArr)) {
			alert_back('查询结果集为空！');
		}

		$data = array();
		$data = array(
			'id' => $id,
			'username' => $userArr['username']
		);

		$this->data = $data;
		$this->display();
	}

	public function resetPasswordHandle()
	{
		if (! IS_POST) {
			$this->redirect('admin/index/index');
		}

		$id       = I('post.id',       NULL, 'intval');
		$password = I('post.password', NULL, 'trim');

		if (($id === NULL) || ($password === NULL)) {
			$alert_back('表单填写错误！');
		}

		$authModel = M('auth');

		$data = array();
		$data = array(
			'password' => md5($password)
		);

		$auth_res = $authModel->where(array('user_id' => $id))->save($data);
		if ($auth_res === false) {
			alert_back('密码重置失败！');
		}

		alert_back('密码重置成功！');
	}

	public function delete()
	{
		// code
	}
}
