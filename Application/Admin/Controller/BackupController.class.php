<?php
namespace Admin\Controller;
use Think\Controller;

class BackupController extends CommonController
{
	public function _initialize()
	{
		parent::_initialize();

        if (! $this->is_admin) {
            $this->redirect('admin/error/deny');
        }
    }

    public function index()
    {
    	$backup_dir = C('backup_path');

    	$sqls = array();
    	$dir  = @ dir($backup_dir);
    	if ($dir) {
    		while (($file = $dir->read()) != false) {
	    		if (preg_match('/\.sql$/', $file)) {
	    			$sqls = $file;
	    		}
    		}
    		$dir->close();
    	}

    	p($sqls);
    }

    public function backup()
    {

    }

    public function recover()
    {

    }

    private function clear()
    {
    	
    }
}