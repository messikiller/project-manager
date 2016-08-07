<?php
/**
 * backup mysql database
 */
class Backup
{
    private $host     = NULL;
    private $username = NULL;
    private $password = NULL;
    private $database = NULL;

    public $connect = false;

    private $error_info = NULL;

    private $default_path = './';

    public function __construct($host, $username, $password, $database)
    {
        $mysqlcon = mysqli_connect($host, $username, $password, $database);
        if (! $mysqlcon) {
            $error_info = 'ERROR: unable to connect mysql';
        } else {
            $this->connect  = $mysqlcon;

            $this->host     = $host;
            $this->username = $username;
            $this->password = $password;
            $this->database = $database;
        }
    }

    public function getTablesArray()
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
            return $arr;
        } catch (Exception $e) {
            $this->error_info = mysqli_error();
            return false;
        }
    }

    public function clear()
    {
        if (! $this->connect) {
            return false;
        }

        $tables = $this->getTablesArray();
        $tables_str = implode(',', $tables);
        $sql = 'DROP TABLE IF EXISTS ' . $tables_str;
        try {
            $res = mysqli_query($this->connect, $sql);
            return $this;
        } catch (Exception $e) {
            $this->error_info = mysqli_error();
            return false;
        }
    }

    public function import($srcfile)
    {
        if (! $this->connect) {
            return false;
        }

        if (! file_exists($srcfile)) {
            $this->error_info = 'ERROR: src sql file not exist';
            return false;
        }

        $username = $this->username;
        $password = $this->password;
        $database = $this->database;

        $cmd = "mysql -u{$username} -p{$password} {$database} < " . $srcfile;

        try {
            exec($cmd);
            return $this;
        } catch (Exception $e) {
            $this->error_info = 'ERROR: execute mysql import command error';
            return false;
        }
    }

    public function export($filename = NULL, $path = NULL)
    {
        if (! $this->connect) {
            return false;
        }

        if (! $path) {
            $path = $this->default_path;
        }

        if (! $filename) {
            $filename = time() . '.sql';
        }

        $destfile = $path . $filename; 

        $username = $this->username;
        $password = $this->password;
        $database = $this->database;

        $cmd = "mysqldump -u{$username} -p{$password} --default-character-set=utf8 {$database} > " . $destfile;
return $cmd;        
        try {
            exec($cmd);
            return $this;
        } catch (Exception $e) {
            $this->error_info = 'ERROR: execute mysqldump command error';
            return false;
        }
    }

    public function getLastErrorInfo()
    {
        return $this->error_info;
    }
}
