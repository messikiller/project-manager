<?php
namespace Admin\Controller;
use Think\Controller;

class WorkController extends CommonController
{
	public function _initialize()
	{
		parent::_initialize();
	}

	/**
	 * show available works for member, condition:
	 *
	 * 1. s_time <= today
	 * 2. member_uid = user_id
	 * 3. status != 3
	 *
	 * @access member
	 */
	public function schedule()
	{
		if (! $this->is_member) {
			$this->redirect('admin/error/deny');
		}

		$pageno   = I('get.p', 1, 'intval');
        $pagesize = C('pagesize');
        $limit    = $pageno . ',' . $pagesize;

		$uid 	  = $this->uid;
		$username = $this->username;
		$time     = time();

		$where = array();
		$where = array(
			'member_uid' => array('EQ', $uid),
			's_time'	 => array('ELT', $time),
			'status'     => array('NEQ', 3)
		);

		$workModel = M('work');
		$workArr   = $workModel
			->where($where)
			->order('status asc, s_time desc, id asc')
			->page($limit)
			->select();

		$total = $workModel->count();
        $Page  = new \Think\Page($total, $pagesize);
        $Page->setConfig('prev', '&laquo;上一页');
        $Page->setConfig('next', '下一页&raquo;');
        $show  = $Page->show();

        $startable_num = get_startable_works_num($uid);
        $finished_num  = get_finished_works_num($uid);

		$leaderIdsList = get_level_uids_list(array('truename'), 1);

		$projIds = makeImplode($workArr, 'project_id');
		$projectModel = M('project');
		$projectArr   = $projectModel
			->field('id, project_name')
			->where(array('id' => array('IN', "$projIds")))
			->select();
		$projectIdsList = makeIndex($projectArr, 'id');

		$data = array();
		foreach ($workArr as $work) {
			$leader_uid = $work['leader_uid'];
			$work['leader_truename'] = '';
			if (isset($leaderIdsList[$leader_uid])) {
				$work['leader_truename'] = $leaderIdsList[$leader_uid]['truename'];
			}

			$project_id = $work['project_id'];
			$work['project_name'] = '';
			if (isset($projectIdsList[$project_id])) {
				$work['project_name'] = $projectIdsList[$project_id]['project_name'];
			}

			$data[] = $work;
		}

        $this->assign('data',    $data);
        $this->assign('show',    $show);
        $this->assign('pagenum', $Page->totalPages);
        $this->assign('index',   $Page->firstRow+1);
        $this->assign('startable_num', $startable_num);
        $this->assign('finished_num', $finished_num);
		$this->display();
	}
}
