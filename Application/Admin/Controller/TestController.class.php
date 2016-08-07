<?php
namespace Admin\Controller;
use Think\Controller;

class TestController extends CommonController
{
    public function _initialize()
	{
		parent::_initialize();
    }

    public function index()
    {
        echo THINK_PATH . '../config.php';
        // $this->display();
    }
}
