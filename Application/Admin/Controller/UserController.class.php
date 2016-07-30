<?php
namespace Admin\Controller;
use Think\Controller;

class UserController extends CommonController
{
	public function _initialize()
	{
		parent::_initialize();

        if (! $this->is_admin) {
            $this->redirect('admin/error/deny');
        }
    }

	public function index()
	{
        $userModel = M('user');
        $authModel = M('auth');

        $positionModel   = M('position');
        $stationModel    = M('station');
        $work_placeModel = M('work_place');

        $positionArr   = $positionModel->order('id asc')->select();
        $stationArr    = $stationModel->order('id asc')->select();
        $work_placeArr = $work_placeModel->order('id asc')->select();

        $positionIdsList   = makeIndex($positionArr, 'id');
        $stationIdsList    = makeIndex($stationArr, 'id');
        $work_placeIdsList = makeIndex($work_placeArr, 'id');

		$where    = "username <> 'admin'";
		$pageno   = I('get.p', 1, 'intval');
		$pagesize = C('pagesize');

		$limit = $pageno . ',' . $pagesize;

        $userArr = $userModel
            ->field('id, username, truename, phone, position_id, station_id, work_place_id')
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
		$userIdsList = makeImplode($userArr, 'id');

		$authArr   = $authModel->field('user_id, level')
			->where(array('user_id' => array('in', $userIdsList)))
			->select();

		$authIdsArr = makeIndex($authArr, 'user_id');

        $data = array();
        foreach ($userArr as $user) {
			$user_id = $user['id'];

            $position_id   = $user['position_id'];
            $station_id    = $user['station_id'];
            $work_place_id = $user['work_place_id'];

            $arr = array();

            $arr['id'] = $user['id'];
            $arr['username'] = $user['username'];
            $arr['truename'] = $user['truename'];
            $arr['phone']    = $user['phone'];

            $arr['position'] = isset($positionIdsList[$position_id])
                ? $positionIdsList[$position_id]['position'] : NULL;
            $arr['station']  = isset($stationIdsList[$station_id])
                ? $stationIdsList[$station_id]['station'] : NULL;
            $arr['work_place'] = isset($work_placeIdsList[$work_place_id])
                ? $work_placeIdsList[$work_place_id]['work_place'] : NULL;

            $arr['level'] = isset($authIdsArr[$user_id])
                ? $authIdsArr[$user_id]['level'] : NULL;

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
        $positionModel   = M('position');
        $stationModel    = M('station');
        $work_placeModel = M('work_place');

        $positionArr   = $positionModel->order('id asc')->select();
        $stationArr    = $stationModel->order('id asc')->select();
        $work_placeArr = $work_placeModel->order('id asc')->select();

        $this->assign('position_list',   $positionArr);
        $this->assign('station_list',    $stationArr);
        $this->assign('work_place_list', $work_placeArr);

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
            'username'      => $username,
            'truename'      => $truename,
            'phone'         => $phone,
            'position_id'   => $position,
            'station_id'    => $station,
            'work_place_id' => $work_place
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
            'id'            => $userArr['id'],
            'username'      => $userArr['username'],
            'truename'      => $userArr['truename'],
            'phone'         => $userArr['phone'],
            'position_id'   => $userArr['position'],
            'station_id'    => $userArr['station'],
            'work_place_id' => $userArr['work_place'],
            'level'         => $authArr['level']
        );

        $positionModel   = M('position');
        $stationModel    = M('station');
        $work_placeModel = M('work_place');

        $positionArr   = $positionModel->order('id asc')->select();
        $stationArr    = $stationModel->order('id asc')->select();
        $work_placeArr = $work_placeModel->order('id asc')->select();

        $this->assign('position_list',   $positionArr);
        $this->assign('station_list',    $stationArr);
        $this->assign('work_place_list', $work_placeArr);
        $this->assign('data', $data);
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
            'position_id'   => $position,
			'station_id'    => $station,
			'work_place_id' => $work_place
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
		$id = I('get.id', NULL, 'intval');
        if ($id === NULL) {
            $alert_back('参数错误！');
        }

        $userModel = M('user');
        $authModel = M('auth');

        $user_res = $userModel->where(array('id' => $id))->delete();
        $auth_res = $authModel->where(array('user_id' => $id))->delete();

        if ($user_res === false || $auth_res === false) {
            alert_back('删除账号失败！');
        }

        alert_back('删除账号信息成功！');
	}
}
