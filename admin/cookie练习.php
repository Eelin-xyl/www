<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php 
    setcookie('logintime', time());
	setcookie('loginIP', $_SERVER['REMOTE_ADDR'])	
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
</head>

<body>
<?php
    echo date('Y-m-d H:i:s', $_COOKIE['logintime'])."<br />";
    echo $_COOKIE['loginIP'];
?>
</body>
</html>