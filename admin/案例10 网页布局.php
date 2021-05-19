<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
</head>

<body>
<?php
    $info = array(
	             array('name'=>'Tom','age'=>12),
	             array('name'=>'King','age'=>11),
	             array('name'=>'Davis','age'=>15)
                 );
?>
<table> <tr><td>姓名</td><td>&nbsp;&nbsp;年龄</td></tr>
<?php 
	foreach($info as $k):
    if($k['age'] >11):	
?>
<tr><td>
<?php 
echo $k['name'];
?>
</td>
<td>
<?php 
    echo "&nbsp;&nbsp;&nbsp;".$k['age'];
    endif;
	endforeach;
?>
</td></tr>
</table>

</body>
</html>