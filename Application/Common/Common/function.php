<?php

function alert_go($info, $url)
{
	header("Content-type: text/html; charset=utf-8");
	$href = U($url);
	echo '<script>window.alert("'.$info.'");window.location.href="'.$href.'"</script>';
	exit();
}
