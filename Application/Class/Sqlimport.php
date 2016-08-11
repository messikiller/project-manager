<?php
	/* ********************************************
	*  HTML page setup
	*  *******************************************/
	$header = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
			<html xmlns="http://www.w3.org/1999/xhtml">
			<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<title>Import| Pitney Bowes Software</title>
			<meta name="author" content="Pitney Bowes Software Web Team" />
			<meta name="title" content="PB Import" />
			<meta name="description" content="PB Import" />
			</head>';

	$body = '<body>
				<div>
					<h3>Import Status</h3>';

	$footer = '</div></body></html>';
	define('SQLIMPORT',1);
	/* *******************************************
	*	Include the configuration file
	* *******************************************/

	require_once 'sqlconfig.php';
	//require_once 'sqlimportfunc.php';
	//Initialize variables
	$sqlfile = $config['sql_file'];					// SQL File
	$hostname = $config['host_name'];				// Server Name
	$db_user = $config['database_user'];			// User Name
	$db_password = $config['database_password'];	// User Password
	$database_name = $config['database_name'];		// DBName

	$sqldelimiter = ';';
	$description = '';
	$diagnostic_info = '';
	$blnDiagnostics = FALSE;
	$diagmode = (isset($_REQUEST['diagmode'])) ? $_REQUEST['diagmode'] : 0;

	if($diagmode == 1)
		$blnDiagnostics = TRUE;
	/* ************************************************
	*	Connect to the mysql database
	* ************************************************/
	$link = mysql_connect(mysql_escape_string($hostname), mysql_escape_string($db_user), mysql_escape_string($db_password));

	if (!$link)
	{
		die($header.$body."Unable to connect to the MySQL database".$footer);
	}
	else
	{
		// Select the mySQL DB
		mysql_select_db(mysql_escape_string($database_name), $link) or die("Wrong MySQL Database");

		$filename = $sqlfile;
		$sqlfile = fopen($sqlfile, 'r');
		if (is_resource($sqlfile) === true)
		{
			$query = array();

			while (feof($sqlfile) === false)
			{
				$query[] = fgets($sqlfile);

				if (preg_match('~' . preg_quote($sqldelimiter, '~') . '\s*$~iS', end($query)) === 1)
				{
					$query = trim(implode('', $query));
					if($blnDiagnostics === true)
						$diagnostic_info .= $query;
					$result = mysql_query($query)or die($header.$body.mysql_error().'. The import has been terminated and did not complete the process.'.$query.$footer);
					while (ob_get_level() > 0)
					{
						ob_end_flush();
					}
					flush();
				}
				if (is_string($query) === true)
				{
					$query = array();
				}
			}

			fclose($sqlfile);
		}
		$description = "File ".$filename." successfully imported into the ".$database_name." database.";

		if($blnDiagnostics === true)
			$description .= '<h3>Diagnostics</h3><div>'.$diagnostic_info.'</div>';

		mysql_close();

	}

	echo $header.$body.$description.$footer;
?>
