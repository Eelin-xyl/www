<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
</head>

<body>
<?php 
    echo strpos('itcastitcast','a');
	echo strpos('itcastitcast','c',5);
	echo strpos('itcastitcast','t',-4);
	echo '<br />';
	echo strrpos('itcastitcast','a');
	echo strrpos('itcastitcast','c',1);
	echo strrpos('itcastitcast','t',-4);
?>
<br />
<?php
    $info = array('id' => 1, 'name' => 'Tom');
	echo $info['name'];
	echo $info['id'];
	echo '<br />';
	print_r($info);
	echo '<br />';
	var_dump($info);
?>
</body>
</html>