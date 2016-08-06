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
        $this->display();
    }
}
