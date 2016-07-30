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
	 * @access leader
	 */
	public function index()
	{
		if (! $this->is_leader) {
			$this->redirect('admin/error/deny');
		}
	}

	/**
	 * publish a new project
	 * 
	 * @access leader
	 */
	public function create()
	{
		if (! $this->is_leader) {
			$this->redirect('admin/error/deny');
		}

	}

	/**
	 * @access leader
	 */
	public function createHandle()
	{
		if (! $this->is_leader) {
			$this->redirect('admin/error/deny');
		}

	}

	/**
	 * start a project
	 * 
	 * @access member
	 */
	public function start()
	{
		if (! $this->is_member) {
			$this->redirect('admin/error/deny');
		}

	}

	/**
	 * @access member
	 */
	public function startHandle()
	{
		if (! $this->is_member) {
			$this->redirect('admin/error/deny');
		}

	}
}