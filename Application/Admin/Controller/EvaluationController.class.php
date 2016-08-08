<?php
namespace Admin\Controller;
use Think\Controller;

class EvaluationController extends CommonController
{
	public function _initialize()
	{
		parent::_initialize();

		if (! $this->is_member) {
			$this->redirect('admin/error/deny');
		}
	}

	public function schedule()
	{
		$uid = $this->uid;

		$pageno   = I('get.p', 1, 'intval');
        $pagesize = C('pagesize');
        $limit    = $pageno . ',' . $pagesize;

        $where = array('member_uid' => $uid);

		$evalModel = M('evaluation_records');
		$evalArr   = $evalModel
			->where($where)
			->page($limit)
			->order('c_time desc, id asc')
			->select();

		$total = $evalModel->where($where)->count();
        $Page  = new \Think\Page($total, $pagesize);
        $Page->setConfig('prev', '&laquo;上一页');
        $Page->setConfig('next', '下一页&raquo;');
        $show  = $Page->show();

        $userModel = M('user');
        $leader_uids = makeImplode($evalArr, 'leader_uid');
        $leaderIdsList = $userModel
        	->where(array('id' => array('IN', "$leader_uids")))
        	->getField('id, truename', true);

        $projectModel = M('project');
        $project_ids = makeImplode($evalArr, 'project_id');
        $projectIdsList = $projectModel
        	->where(array('id' => array('IN', "$project_ids")))
        	->getField('id, project_name', true);

		$data = array();
		foreach ($evalArr as $eval) {
			$arr = array();

			$leader_uid = $eval['leader_uid'];
			$project_id = $eval['project_id'];

			$arr = $eval;

			$arr['leader_truename'] = '';
			if (isset($leaderIdsList[$leader_uid])) {
				$arr['leader_truename'] = $leaderIdsList[$leader_uid];
			}

			$arr['project_name'] = '';
			if (isset($projectIdsList[$project_id])) {
				$arr['project_name'] = $projectIdsList[$project_id];
			}

			$p_a = intval(C('accuracy_percentage')) / 100;
			$p_s = intval(C('sampling_percentage')) / 100;
			$p_u = intval(C('summary_percentage')) / 100;

			$mark = $p_a * $arr['overall_accuracy_mark'] + $p_s * $arr['sampling_inspection_mark'] + $p_u * $arr['summary_mark'];
			$arr['mark'] = round($mark, 2);

			$data[] = $arr;
		}

		$this->assign('data',    $data);
		$this->assign('show',    $show);
        $this->assign('pagenum', $Page->totalPages);
        $this->assign('index',   $Page->firstRow+1);
        $this->display();
	}
}
