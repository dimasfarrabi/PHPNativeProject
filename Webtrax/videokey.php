<?php
session_start();
if(!session_is_registered("UIDWebTrax"))
{
	header('HTTP/1.0 403 Forbidden');
    exit();
}
header('Content-Type: binary/octet-stream');
header('Pragma: no-cache');
readfile('enc.key');
?>
