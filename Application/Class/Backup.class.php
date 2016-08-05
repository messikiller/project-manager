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

    private $connect = false;

    private $error_info = NULL;

    private $storage_path = NULL;

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

    public function export()
    {

    }

    public function reset()
    {

    }

    public function clear()
    {

    }

    public function return()
    {

    }

    public function download()
    {

    }

    public function getLastErrorInfo()
    {

    }
}
