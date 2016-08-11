<?php
namespace Admin\Controller;
use Think\Controller;

class BackupController extends CommonController
{
    private $dumper  = NULL;
    private $conf    = NULL;
    private $connect = NULL;

	public function _initialize()
	{
		parent::_initialize();
        header("Content-type: text/html; charset=utf-8");

        if (! $this->is_admin) {
            $this->redirect('admin/error/deny');
        }

        $host     = C('db_host');
        $username = C('db_user');
        $password = C('db_pwd');
        $database = C('db_name');

        $this->conf = array(
            'host'     => $host,
            'username' => $username,
            'password' => $password,
            'db_name'  => $database
        );

        $this->connect = mysqli_connect($host, $username, $password, $database);

        $class = C('class_path') . 'Dumper.class.php';
        require_once($class);

        $this->dumper = \Shuttle_Dumper::create($this->conf);
    }

    public function index()
    {
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
	    		if (is_file($backup_dir . $file)
                        && preg_match('/(\d+)\.sql$/', $file, $match))
                {
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
        $obj = $this->dumper;

        $filename = time() . '.sql';
        $path = C('backup_path');
        $file = $path . $filename;

        try {
            $obj->dump($file);
        } catch (Exception $e) {
            alert_back('错误！'.$e->getMessage());
        }

        alert_back('数据库导出成功！');
    }

    /**
     * recover database with specific sql
     * 1. clear all tables in old databases;
     * 2. import new sql
     */
    public function recover()
    {
        $timestamp = I('get.timestamp', 0, 'intval');
        if ($timestamp === 0) {
            alert_back('参数错误！');
        }

        $filename = $timestamp . '.sql';
        $path = C('backup_path');

        $file = $path . $filename;

        if (! file_exists($file)) {
			alert_back('指定的备份文件不存在！');
		}

        if(! is_writable($file)) {
			alert_back('指定的备份文件不可写！');
		}

        if (! $this->clearDatabase()) {
            alert_back('清空数据库失败！');
        }

        $sql = file_get_contents($file);
        $arr = explode(';', $sql);

        try {
            foreach ($arr as $line) {
                mysqli_query($this->connect, $line);
            }
        } catch (Exception $e) {
            alert_back('恢复数据库出错！');
        }

        alert_back('恢复数据库成功！');
    }

    public function delete()
    {
        $timestamp = I('timestamp', 0, 'intval');
        if ($timestamp === 0) {
            alert_back('参数错误！');
        }

        $filename = $timestamp . '.sql';
        $path = C('backup_path');
        $file = $path . $filename;

        if (! file_exists($file)) {
			alert_back('文件不存在！');
		}

        try {
            @ unlink($file);
        } catch (Exception $e) {
            alert_back('删除文件失败！');
        }
        alert_back('删除文件成功！');
    }

    /**
     * @access private for admin
     */
    private function clearDatabase()
    {
        if (! $this->connect) {
            return false;
        }

        try {
            $arr = array();
            $sql = 'SHOW TABLES';
            $res = mysqli_query($this->connect, $sql);
            while ($row = mysqli_fetch_array($res)) {
                $arr[] = $row[0];
            }
        } catch (Exception $e) {
            return false;
        }

        $tables = $arr;
        $tables_str = implode(',', $tables);
        $sql = 'DROP TABLE IF EXISTS ' . $tables_str;
        try {
            $res = mysqli_query($this->connect, $sql);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
