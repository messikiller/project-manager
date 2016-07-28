<?php

function p($str)
{
	echo '<pre>';
	print_r($str);
	echo '</pre>';
	die();
}

function alert_go($info, $url)
{
	header("Content-type: text/html; charset=utf-8");
	$href = U($url);
	echo '<script>window.alert("'.$info.'");window.location.href="'.$href.'"</script>';
	exit();
}

function alert_back($info)
{
	header("Content-type: text/html; charset=utf-8");
	echo '<script>window.alert("'.$info.'");window.history.go(-1);</script>';
	exit();
}

function makeIndex(&$arr, $key)
{
	if ($arr == false) return array();

	$index 	= array();
	foreach ($arr as $val) {
		$_m_key = $val["{$key}"];
		$index["{$_m_key}"] = $val;
	}

	return $index;
}

function makeImplode(&$array, $key, $glue=',')
{
	if ( $array == false || empty($array) ) return NULL;

	$value	= array();
	$idx	= array();
	
	foreach ( $array as $val ) {
		$v	= $val["{$key}"];
		if (! isset($idx["{$v}"])) {
			$value[] = "{$v}";
		}

		$idx["{$v}"] = true;
	}

	unset($idx);

	return implode($glue, $value);
}
