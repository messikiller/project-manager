<?php
namespace Admin\Controller;
use Think\Controller;

class PositionController extends CommonController
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

        $positionModel = M('position');
        $positionArr   = $positionModel
            ->order('id asc')
            ->page($limit)
            ->select();

        $total = $positionModel->count();
        $Page  = new \Think\Page($total, $pagesize);
        $Page->setConfig('prev', '&laquo;上一页');
        $Page->setConfig('next', '下一页&raquo;');
        $show  = $Page->show();

        $this->assign('data',    $positionArr);
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

        $position = I('post.position', NULL, 'trim');
        
        if ($position === NULL) {
            alert_go('职务名称不能为空！', 'admin/position/add');
        }

        $positionModel = M('position');

        $totals = $positionModel->where(array('position' => $position))->count();
        if ($totals > 0) {
            alert_go('职务名称已经存在！', 'admin/position/add');
        }

        $position_data = array('position' => $position);
        $position_id   = $positionModel->add($position_data);

        if (empty($position_id)) {
            alert_go('添加职务失败！', 'admin/position/add');
        }

        alert_go('添加职务成功！', 'admin/position/index');
    }

    public function edit()
    {
        $id = I('get.id', NULL, 'intval');
        if (empty($id)) {
            $this->redirect('admin/position/index');
        }

        $positionModel = M('position');
        $positionArr   = $positionModel->where(array('id' => $id))->find();

        if (empty($positionArr)) {
            alert_back('要编辑的信息不存在！');
        }

        $this->data = $positionArr;
        $this->display();
    }

    public function editHandle()
    {
        if (! IS_POST) {
            $this->redirect('admin/index/index');
        }

        $id       = I('post.id', NULL, 'intval');
        $position = I('post.position', NULL, 'trim');
        if ($id === NULL || $position === NULL) {
            alert_back('职务名称不能为空！');
        }

        $positionModel = M('position');
        $position_data = array('position' => $position);
        $position_res  = $positionModel->where(array('id' => $id))->save($position_data);
        if ($user_res === false) {
            alert_back('更新信息失败！');
        }

        alert_go('信息更新成功！', 'admin/position/index');
    }

    public function delete()
    {
        $id = I('get.id', NULL, 'intval');
        if ($id === NULL) {
            $alert_back('参数错误！');
        }

        $positionModel = M('position');
        $position_res  = $positionModel->where(array('id' => $id))->delete();

        if ($position_res === false) {
            alert_back('删除职务失败！');
        }

        alert_go('删除职务信息成功！', 'admin/position/index');
    }
}