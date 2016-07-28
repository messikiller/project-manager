<?php
namespace Admin\Controller;
use Think\Controller;

class WorkplaceController extends CommonController
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
        $pageno   = I('get.p', 1, 'intval');
        $pagesize = C('pagesize');
        $limit    = $pageno . ',' . $pagesize;

        $work_placeModel = M('work_place');
        $work_placeArr   = $work_placeModel
            ->order('id asc')
            ->page($limit)
            ->select();

        $total = $work_placeModel->count();
        $Page  = new \Think\Page($total, $pagesize);
        $Page->setConfig('prev', '&laquo;上一页');
        $Page->setConfig('next', '下一页&raquo;');
        $show  = $Page->show();

        $this->assign('data',    $work_placeArr);
        $this->assign('show',    $show);
        $this->assign('pagenum', $Page->totalPages);
        $this->assign('index',   $Page->firstRow+1);
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

        $work_place = I('post.work_place', NULL, 'trim');
        
        if ($work_place === NULL) {
            alert_go('工作地点名称不能为空！', 'admin/workplace/add');
        }

        $work_placeModel = M('work_place');

        $totals = $work_placeModel->where(array('work_place' => $work_place))->count();
        if ($totals > 0) {
            alert_go('工作地点名称已经存在！', 'admin/workplace/add');
        }

        $work_place_data = array('work_place' => $work_place);
        $work_place_id   = $work_placeModel->add($work_place_data);

        if (empty($work_place_id)) {
            alert_go('添加工作地点失败！', 'admin/workplace/add');
        }

        alert_go('添加工作地点成功！', 'admin/workplace/index');
    }

    public function edit()
    {
        $id = I('get.id', NULL, 'intval');
        if (empty($id)) {
            $this->redirect('admin/workplace/index');
        }

        $work_placeModel = M('work_place');
        $work_placeArr   = $work_placeModel->where(array('id' => $id))->find();

        if (empty($work_placeArr)) {
            alert_back('要编辑的信息不存在！');
        }

        $this->data = $work_placeArr;
        $this->display();
    }

    public function editHandle()
    {
        if (! IS_POST) {
            $this->redirect('admin/index/index');
        }

        $id       = I('post.id', NULL, 'intval');
        $work_place = I('post.work_place', NULL, 'trim');
        if ($id === NULL || $work_place === NULL) {
            alert_back('工作地点名称不能为空！');
        }

        $work_placeModel = M('work_place');
        $work_place_data = array('work_place' => $work_place);
        $work_place_res  = $work_placeModel->where(array('id' => $id))->save($work_place_data);
        if ($user_res === false) {
            alert_back('更新信息失败！');
        }

        alert_go('信息更新成功！', 'admin/workplace/index');
    }

    public function delete()
    {
        $id = I('get.id', NULL, 'intval');
        if ($id === NULL) {
            $alert_back('参数错误！');
        }

        $work_placeModel = M('work_place');
        $work_place_res  = $work_placeModel->where(array('id' => $id))->delete();

        if ($work_place_res === false) {
            alert_back('删除地点失败！');
        }

        alert_go('删除地点信息成功！', 'admin/workplace/index');
    }
}