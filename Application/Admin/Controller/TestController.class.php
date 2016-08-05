<?php
namespace Admin\Controller;
use Think\Controller;

class TestController extends CommonController
{
    public function _initialize()
	{
		parent::_initialize();
		if (! $this->is_member) {
			$this->redirect('admin/error/deny');
		}
    }

    public function index()
    {
        $taskModel = M('task');
        $workModel = M('work');
        $task_id = 8;
        $project_id = $taskModel->where(array('id' => $task_id))->getField('project_id');
    	$statusArr = $workModel
    		->where(array('project_id' => $project_id))
    		->getField('status', true);

        p($statusArr);
    }
}
