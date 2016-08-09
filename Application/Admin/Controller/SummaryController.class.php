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
            ->field('id, member_uid, project_id, c_time')
            ->order('c_time desc, id asc')
            ->page($limit)
            ->select();

        $total = $summaryModel->where($where)->count();
        $Page  = new \Think\Page($total, $pagesize);
        $Page->setConfig('prev', '&laquo;上一页');
        $Page->setConfig('next', '下一页&raquo;');
        $show  = $Page->show();

        $project_ids = makeImplode($summaryArr, 'project_id');
        $member_uids = makeImplode($summaryArr, 'member_uid');
        $projectModel = M('project');
        $projectIdsList = $projectModel
            ->where(array('id' => array('IN', "$project_ids")))
            ->getField('id, project_name');

        $leader_uids = makeImplode($projectIdsList, 'leader_uid');
        $user_ids = $leader_uids . ', ' . $member_uids;

        $userModel = M('user');
        $userIdsList = $userModel
            ->where(array('id' => array('IN', "$user_ids")))
            ->getField('id, truename');

        $data = array();
        foreach ($summaryArr as $summary) {
            $project_id = $summary['project_id'];
            $member_uid = $summary['member_uid'];

            $summary['project_name'] = isset($projectIdsList[$project_id])
                ? $projectIdsList[$project_id] : '';
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
        $res = $summaryModel->where(array('id' => $id))->delete();
        if ($res === false) {
            alert_back('删除记录失败！');
        }

        alert_back('删除记录成功！');
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
            ->field('id, member_uid, project_id, c_time')
            ->order('c_time desc, id asc')
            ->page($limit)
            ->select();

        $total = $summaryModel->where($where)->count();
        $Page  = new \Think\Page($total, $pagesize);
        $Page->setConfig('prev', '&laquo;上一页');
        $Page->setConfig('next', '下一页&raquo;');
        $show  = $Page->show();

        $projectModel = M('project');
        $project_ids = makeImplode($summaryArr, 'project_id');
        $projectIdsList = $projectModel->where(array('id' => array('IN', "$project_ids")))->getField('id, project_name');

        $data = array();
        foreach ($summaryArr as $summary) {
            $project_id = $summary['project_id'];
            $summary['project_name'] = isset($projectIdsList[$project_id]) ? $projectIdsList[$project_id] : '';
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

    }

    /**
     * @access member
     */
    public function add()
    {
        if (! $this->is_member) {
            $this->redirect('admin/error/deny');
        }

        $project_id = I('get.project_id', 0, 'intval');
        if ($project_id === 0) {
            alert_back('参数错误！');
        }

        // if summary record exists, redirect to edit
        $summaryModel = M('summary');
        $summaryWhere = array(
            'member_uid' => array('EQ', $this->uid),
            'project_id' => array('EQ', $project_id)
        );
        $summaryTotal = $summaryModel->where($where)->count();
        if ($summaryTotal > 0) {
            $summary_id = $summaryModel->where($where)->getField('id');
            $this->redirect('admin/summary/edit', array('id' => $summary_id));
        }

        $projectModel = M('project');
        $projectArr = $projectModel
            ->where(array('id' => $project_id))
            ->find();

        $this->assign('project',    $projectArr);
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
        $project_id = I('project_id', 0, 'intval');
        $content    = I('content', false, 'text_store');
        $c_time     = time();

        if ($member_uid === 0 || $project_id === 0 || $content === false) {
            alert_back('表单数据错误！');
        }

        $add_data = array(
            'member_uid' => $member_uid,
            'project_id' => $project_id,
            'content'    => $content,
            'c_time'     => $c_time
        );

        $summaryModel = M('summary');
        $res = $summaryModel->add($add_data);
        if ($res === false) {
            alert_back('添加总结失败！');
        }

        alert_go('添加总结成功！', 'admin/project/home');
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

        $project_id = $summaryArr['project_id'];

        $projectModel = M('project');
        $projectArr = $projectModel
            ->where(array('id' => $project_id))
            ->find();

        $this->assign('project',    $projectArr);
        $this->assign('summary',    $summaryArr);
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

        $project_id = $summaryArr['project_id'];

        $projectModel = M('project');
        $projectArr = $projectModel
            ->where(array('id' => $project_id))
            ->find();

        $this->assign('project',    $projectArr);
        $this->assign('summary',    $summaryArr);
        $this->display();
    }
}
