<?php
namespace Admin\Controller;
use Think\Controller;

class TaskController extends CommonController
{
    public function _initialize()
	{
		parent::_initialize();
		if (! $this->is_member) {
			$this->redirect('admin/error/deny');
		}
    }

    public function sign()
    {
    	$time = time();
    	
    	$time_str = date('Y-m-d ', $time);
    	$m_time = strtotime($time_str . '00:00:00');
    	
    	$is_morning = true;
    	if ($time >= $m_time) {
    		$is_morning = false;
    	}

    	if ($is_morning) {
    		$this->morning();
    	} else {
    		$this->afternoon();
    	}
    }

    public function morning()
    {
    	// code
    }

    public function afternoon()
    {
    	// code
    }

    public function morningHandle()
    {
    	$time = time();
        if (! is_sign_time($time)) {
        	alert_back('现在还不是打卡时间！');
        }

        // code
    }

    public function afternoonHandle()
    {
    	$time = time();
        if (! is_sign_time($time)) {
        	alert_back('现在还不是打卡时间！');
        }

    	// code
    }
}