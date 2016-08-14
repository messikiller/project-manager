<?php
namespace Admin\Controller;
use Think\Controller;

class SummaryController extends CommonController
{
    public function _initialize()
    {
        parent::_initialize();
    }

    /**
     * @access admin
     */
    public function index()
    {
        if (! $this->is_admin) {
            $this->redirect('admin/error/deny');
        }

        $where = array();

        $pageno   = I('get.p', 1, 'intval');
        $pagesize = C('pagesize');
        $limit    = $pageno . ',' . $pagesize;

        $summaryModel = M('summary');
        $summaryArr   = $summaryModel
            ->where($where)
            ->field('id, member_uid, work_id, c_time')
            ->order('c_time desc, id asc')
            ->page($limit)
            ->select();

        $total = $summaryModel->where($where)->count();
        $Page  = new \Think\Page($total, $pagesize);
        $Page->setConfig('prev', '&laquo;上一页');
        $Page->setConfig('next', '下一页&raquo;');
        $show  = $Page->show();

        $work_ids    = makeImplode($summaryArr, 'work_id');
        $member_uids = makeImplode($summaryArr, 'member_uid');
        $workModel = M('work');
        $workIdsList = $workModel
            ->where(array('id' => array('IN', "$work_ids")))
            ->getField('id, work_name');

        $userModel = M('user');
        $userIdsList = $userModel
            ->where(array('id' => array('IN', "$member_uids")))
            ->getField('id, truename');

        $data = array();
        foreach ($summaryArr as $summary) {
            $project_id = $summary['project_id'];
            $member_uid = $summary['member_uid'];

            $summary['work_name'] = isset($workIdsList[$work_id])
                ? $workIdsList[$work_id] : '';
            $summary['member_truename'] = isset($userIdsList[$member_uid])
                ? $userIdsList[$member_uid] : '';

            $data[] = $summary;
        }

        $this->assign('data',    $data);
        $this->assign('show',    $show);
        $this->assign('pagenum', $Page->totalPages);
        $this->assign('index',   $Page->firstRow+1);
		$this->display();
    }

    /**
     * @access admin
     */
    public function indexSearch()
    {
        if (! $this->is_admin) {
            $this->redirect('admin/error/deny');
        }

        // @TODO
    }

    /**
     * @access admin
     */
    public function delete()
    {
        if (! $this->is_admin) {
            $this->redirect('admin/error/deny');
        }

        $id = I('get.id', 0, 'intval');
        if ($id === 0) {
            alert_back('参数错误！');
        }

        $summaryModel = M('summary');

        $work_id = $summaryModel->where(array('id' => $id))->getField('work_id');
        if (empty($work_id)) {
            alert_back('获取工作ID失败！');
        }

        $del_res = $summaryModel->where(array('id' => $id))->delete();
        if ($del_res === false) {
            alert_back('删除总结记录失败！');
        }

        $workModel = M('work');
        $up_res = $workModel->where(array('id' => $work_id))->setField('status', 2);
        if ($up_res === false) {
            alert_back('更新工作状态失败');
        }

        alert_back('删除总结成功！');
    }

    /**
     * user's summary records
     *
     * @access member
     */
    public function home()
    {
        if (! $this->is_member) {
            $this->redirect('admin/error/deny');
        }

        $pageno   = I('get.p', 1, 'intval');
        $pagesize = C('pagesize');
        $limit    = $pageno . ',' . $pagesize;

        $uid = $this->uid;

        $summaryModel = M('summary');

        $where = array();
        $where['member_uid'] = array('EQ', $uid);

        $summaryArr = $summaryModel
            ->where($where)
            ->field('id, member_uid, work_id, c_time')
            ->order('c_time desc, id asc')
            ->page($limit)
            ->select();

        $total = $summaryModel->where($where)->count();
        $Page  = new \Think\Page($total, $pagesize);
        $Page->setConfig('prev', '&laquo;上一页');
        $Page->setConfig('next', '下一页&raquo;');
        $show  = $Page->show();

        $workModel = M('work');
        $work_ids = makeImplode($summaryArr, 'work_id');
        $workIdsList = $workModel->where(array('id' => array('IN', "$work_ids")))->getField('id, work_name');

        $data = array();
        foreach ($summaryArr as $summary) {
            $work_id = $summary['work_id'];
            $summary['work_name'] = isset($workIdsList[$work_id]) ? $workIdsList[$work_id] : '';
            $data[] = $summary;
        }

        $this->assign('data',    $data);
        $this->assign('show',    $show);
        $this->assign('pagenum', $Page->totalPages);
        $this->assign('index',   $Page->firstRow+1);
		$this->display();
    }

    /**
     * @access member
     */
    public function homeSearch()
    {
        if (! $this->is_member) {
            $this->redirect('admin/error/deny');
        }

        // @TODO
    }

    /**
     * @access member
     */
    public function add()
    {
        if (! $this->is_member) {
            $this->redirect('admin/error/deny');
        }

        $work_id = I('get.work_id', 0, 'intval');
        if ($work_id === 0) {
            alert_back('参数错误！');
        }

        // if summary record exists, redirect to edit
        $summaryModel = M('summary');
        $summaryWhere = array(
            'member_uid' => array('EQ', $this->uid),
            'work_id'    => array('EQ', $work_id)
        );
        $summaryTotal = $summaryModel->where($where)->count();
        if ($summaryTotal > 0) {
            alert_back('这项工作已经总结过了！');
        }

        $workModel = M('work');
        $workArr = $workModel
            ->where(array('id' => $work_id))
            ->find();

        $project_id = $workArr['project_id'];
        $projectModel = M('project');
        $project_name = $projectModel->where(array('id' => $project_id))->getField('project_name');
        $workArr['project_name'] = $project_name;

        $this->assign('work',       $workArr);
        $this->assign('member_uid', $this->uid);
        $this->display();
    }

    /**
     * @access member
     */
    public function addHandle()
    {
        if (! $this->is_member) {
            $this->redirect('admin/error/deny');
        }

        if (! IS_POST) {
            $this->redirect('admin/index/index');
        }

        $member_uid = I('member_uid', 0, 'intval');
        $work_id    = I('work_id', 0, 'intval');
        $content    = I('content', false, 'text_store');
        $c_time     = time();

        if ($member_uid === 0 || $work_id === 0 || $content === false) {
            alert_back('表单数据错误！');
        }

        $add_data = array(
            'member_uid' => $member_uid,
            'work_id'    => $work_id,
            'content'    => $content,
            'c_time'     => $c_time
        );

        $summaryModel = M('summary');
        $res = $summaryModel->add($add_data);
        if ($res === false) {
            alert_back('添加总结失败！');
        }

        $update_data = array('status' => 3);
        $workModel   = M('work');
        $update_res  = $workModel->where(array('id' => $work_id))->save($update_data);
        if ($update_res === false) {
            alert_back('更新工作状态失败！');
        }

        alert_go('添加总结成功！', 'admin/work/schedule');
    }

    /**
     * @access member
     */
    public function edit()
    {
        $id = I('get.id', 0, 'intval');
        if ($id === 0) {
            alert_go('参数错误！', 'admin/summary/home');
        }

        $summaryModel = M('summary');
        $summaryArr = $summaryModel->where(array('id' => $id))->find();

        $work_id = $summaryArr['work_id'];

        $workModel = M('work');
        $workArr = $workModel
            ->where(array('id' => $work_id))
            ->find();

        $this->assign('work',    $workArr);
        $this->assign('summary', $summaryArr);
        $this->display();
    }

    /**
     * @access member
     */
    public function editHandle()
    {
        if (! $this->is_member) {
            $this->redirect('admin/error/deny');
        }

        $id = I('post.id', 0, 'intval');
        if ($id === 0) {
            alert_back('参数错误！');
        }

        $content = I('post.content', false, 'text_store');
        if (! $content) {
            alert_back('表单数据错误！');
        }

        $data = array('content' => $content);
        $summaryModel = M('summary');
        $res = $summaryModel->where(array('id' => $id))->setField('content', $content);
        if ($res === false) {
            alert_back('更新总结失败！');
        }

        alert_back('更新总结成功！');
    }

    /**
     * @access ALL
     */
    public function view()
    {
        $id = I('id', 0, 'intval');
        if ($id === 0) {
            alert_go('参数错误！', 'admin/summary/home');
        }

        $summaryModel = M('summary');
        $summaryArr = $summaryModel->where(array('id' => $id))->find();

        $work_id = $summaryArr['work_id'];

        $workModel = M('work');
        $workArr = $workModel
            ->where(array('id' => $work_id))
            ->find();

        $this->assign('work',    $workArr);
        $this->assign('summary', $summaryArr);
        $this->display();
    }
}
