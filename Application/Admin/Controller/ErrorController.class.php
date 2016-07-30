<?php
namespace Admin\Controller;
use Think\Controller;

class ErrorController extends Controller
{
	public function deny()
	{
		$this->display();
	}

	public function lost()
	{
		$this->display();
	}
}