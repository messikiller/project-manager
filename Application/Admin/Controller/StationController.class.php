<?php
namespace Admin\Controller;
use Think\Controller;

class StationController extends CommonController
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

        $stationModel = M('station');
        $stationArr   = $stationModel
            ->order('id asc')
            ->page($limit)
            ->select();

        $total = $stationModel->count();
        $Page  = new \Think\Page($total, $pagesize);
        $Page->setConfig('prev', '&laquo;上一页');
        $Page->setConfig('next', '下一页&raquo;');
        $show  = $Page->show();

        $this->assign('data',    $stationArr);
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

        $station = I('post.station', NULL, 'trim');
        
        if ($station === NULL) {
            alert_go('岗位名称不能为空！', 'admin/station/add');
        }

        $stationModel = M('station');

        $totals = $stationModel->where(array('station' => $station))->count();
        if ($totals > 0) {
            alert_go('岗位名称已经存在！', 'admin/station/add');
        }

        $station_data = array('station' => $station);
        $station_id   = $stationModel->add($station_data);

        if (empty($station_id)) {
            alert_go('添加岗位失败！', 'admin/station/add');
        }

        alert_go('添加岗位成功！', 'admin/station/index');
    }

    public function edit()
    {
        $id = I('get.id', NULL, 'intval');
        if (empty($id)) {
            $this->redirect('admin/station/index');
        }

        $stationModel = M('station');
        $stationArr   = $stationModel->where(array('id' => $id))->find();

        if (empty($stationArr)) {
            alert_back('要编辑的信息不存在！');
        }

        $this->data = $stationArr;
        $this->display();
    }

    public function editHandle()
    {
        if (! IS_POST) {
            $this->redirect('admin/index/index');
        }

        $id       = I('post.id', NULL, 'intval');
        $station = I('post.station', NULL, 'trim');
        if ($id === NULL || $station === NULL) {
            alert_back('岗位名称不能为空！');
        }

        $stationModel = M('station');
        $station_data = array('station' => $station);
        $station_res  = $stationModel->where(array('id' => $id))->save($station_data);
        if ($user_res === false) {
            alert_back('更新信息失败！');
        }

        alert_go('信息更新成功！', 'admin/station/index');
    }

    public function delete()
    {
        $id = I('get.id', NULL, 'intval');
        if ($id === NULL) {
            $alert_back('参数错误！');
        }

        $stationModel = M('station');
        $station_res  = $stationModel->where(array('id' => $id))->delete();

        if ($station_res === false) {
            alert_back('删除岗位失败！');
        }

        alert_go('删除岗位信息成功！', 'admin/station/index');
    }
}