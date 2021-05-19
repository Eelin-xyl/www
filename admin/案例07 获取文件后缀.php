<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
</head>

<body>
<form method="post">
<div align="center">
  <h1><strong>获取文件后缀
  </strong></h1>
</div>
<p>请输入文件名及后缀&nbsp;&nbsp;<input name="file" type="text" /></p>
<p>文件后缀：
<?php
    while(!empty($_POST['file'])){
	     $file = $_POST['file'];
         echo end(explode('.', $file));
	     break;
	}
?>
</p>
</form>
</body>
</html>