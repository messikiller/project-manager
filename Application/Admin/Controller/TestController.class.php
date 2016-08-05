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
        $work_id = 13;
        var_dump(is_work_finished(13));

        // $taskModel = M('task');
    	// $statusArr   = $taskModel
    	// 	->where(array('work_id' => $work_id))
    	// 	->getField('status', true);
        //
        // $is_finished = true;
    	// foreach ($statusArr as $status) {
    	// 	if ($status != 1) {
    	// 		$is_finished = false;
    	// 		break;
    	// 	}
    	// }
        //
        // var_dump($is_finished);
    }
}
