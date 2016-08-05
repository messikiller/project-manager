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
    {p(C('CLASS_PATH'));
		header("Content-type: text/html; charset=utf-8");

    	$backup_dir = C('backup_path');

		if (! file_exists($backup_dir)) {
			echo '指定的备份文件存储目录不存在！';
			exit();
		}

		if(! is_writable($backup_dir)) {
			echo '备份文件存储目录不可写！';
			exit();
		}

    	$sqls = array();
    	$dir  = @ dir($backup_dir);
    	if ($dir) {
    		while (($file = $dir->read()) != false) {
	    		if (is_file($backup_dir . $file) && preg_match('/(\d+)\.sql$/', $file, $match)) {
					$size = filesize($backup_dir . $file);
	    			$sqls[] = array(
						'filename'  => $file,
						'timestamp' => $match[1],
						'filesize'  => $size
					);
	    		}
    		}
    		$dir->close();
    	}

		$pageno   = I('get.p', 1, 'intval');
        $pagesize = C('pagesize');
		$total 	  = count($files_arr);

		$Page  = new \Think\Page($total, $pagesize);
		$start = $Page->firstRow;
		$files_arr = array_slice($sqls, $start, $pagesize);

        $Page->setConfig('prev', '&laquo;上一页');
        $Page->setConfig('next', '下一页&raquo;');
        $show  = $Page->show();

    	$this->assign('files',   $files_arr);
		$this->assign('show',    $show);
        $this->assign('pagenum', $Page->totalPages);
        $this->assign('index',   $Page->firstRow+1);
		$this->display();
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
