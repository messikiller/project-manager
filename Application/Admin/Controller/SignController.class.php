<?php
namespace Admin\Controller;
use Think\Controller;

class SignController extends CommonController
{
    public function _initialize()
	{
		parent::_initialize();

        if (! $this->is_admin) {
            $this->redirect('admin//error/deny');
        }
    }

    public function index()
    {
        $pageno   = I('get.p', 1, 'intval');
        $pagesize = C('pagesize');
        $limit    = $pageno . ',' . $pagesize;

        $where = array();

        $user_id = I('get.user_id', false, 'intval');
        if ($user_id !== false) {
            $where['user_id'] = array('EQ', $user_id);
            $this->assign('is_searched', true);
        }

        $truename = I('get.truename', false, 'trim');
        if ($truename !== false) {
            $this->assign('searched_truename' ,$truename);
            $this->assign('is_searched', true);
        }

        $ip = I('get.ip', false, 'intval');
        if ($ip !== false) {
            $where['ip'] = array('EQ', $ip);
            $this->assign('searched_ip', $ip);
            $this->assign('is_searched', true);
        }

        $s_time = I('get.s_time', false, 'intval');
        if ($s_time !== false) {
            $where['c_time'] = array('EGT', $s_time);
            $this->assign('searched_s_time', date('Y-m-d', $s_time));
            $this->assign('is_searched', true);
        }

        $e_time = I('get.e_time', false, 'intval');
        if ($e_time !== false) {
            $this->assign('searched_e_time', date('Y-m-d', $e_time));
            $this->assign('is_searched', true);
            $where['c_time'] = array('ELT', $e_time);
        }

        $signModel = M('sign_records');
        $signArr   = $signModel
            ->where($where)
            ->order('c_time desc, user_id asc')
            ->page($limit)
            ->select();

        $total = $signModel->count();
        $Page  = new \Think\Page($total, $pagesize);
        $Page->setConfig('prev', '&laquo;上一页');
        $Page->setConfig('next', '下一页&raquo;');
        $show  = $Page->show();

        $user_ids = makeImplode($signArr, 'user_id');
        $userModel = M('user');
        $userArr = $userModel
            ->field('id, username, truename')
            ->where(array('id' => array('IN', "$user_ids")))
            ->select();
        $userIdsList = makeIndex($userArr, 'id');

        $data = array();
        foreach ($signArr as $sign) {
            $arr = array();
            $sign_user_id = $sign['user_id'];

            $arr['id']      = $sign['id'];
            $arr['user_id'] = $sign_user_id;
            $arr['ip']      = $sign['ip'];
            $arr['c_time']  = $sign['c_time'];

            $arr['username'] = isset($userIdsList[$sign_user_id])
                ? $userIdsList[$sign_user_id]['username'] : NULL;
            $arr['truename'] = isset($userIdsList[$sign_user_id])
                ? $userIdsList[$sign_user_id]['truename'] : NULL;

            $data[] = $arr;
        }

        $this->assign('data',    $data);
        $this->assign('show',    $show);
        $this->assign('pagenum', $Page->totalPages);
        $this->assign('index',   $Page->firstRow+1);
        $this->display();
    }

    public function search()
    {
        if (! IS_POST) {
            $this->redirect('admin/index/index');
        }

        $truename = I('post.search-truename', false, 'trim');
        $ip       = I('post.search-ip', false, 'trim');
        $s_time   = I('post.search-s-time', false, 'trim');
        $e_time   = I('post.search-e-time', false, 'trim');

        $param = array();

        if ($truename !== false) {
            $userModel = M('user');
            $userArr = $userModel->where(array('truename' => $truename))->find();
            if (! empty($userArr)) {
                $param['truename'] = $truename;
                $param['user_id'] = $userArr['id'];
            }
        }

        if (! empty($ip)) {
            $param['ip'] = ip2long($ip);
        }

        if (! empty($s_time)) {
            $param['s_time'] = strtotime(trim($s_time));
        }

        if (! empty($e_time)) {
            $param['e_time'] = strtotime(trim($e_time));
        }

        $this->redirect('admin/sign/index', $param);
    }

    public function delete()
    {
        $id = I('get.id', false, 'intval');
        if ($id === false) {
            alert_back('参数错误！');
        }

        $signModel = M('sign_records');
        $sign_res = $signModel->where(array('id' => $id))->delete();
        if ($sign_res === false) {
            if ($id === false) {
                alert_back('删除记录失败！');
            }
        }

        alert_go('删除记录成功！', 'admin/sign/index');
    }
}
