<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
</head>

<body>
<form method="post">	
<table width="330" border="1">
  <tr>
    <td width="330" align="center"><strong>闰年的判断</strong></td>
  </tr>
  <tr>
    <td>输入的年份：
      <input type="text" name="year" id="year" >          
    </td>
  </tr>
  <tr>
    <td>判断的结果：    
	<?php 
	while(!empty($_POST['year']))
	{
	     if($_POST['year']%4==0)
		 {
		   echo $_POST['year']."年是闰年";
		 }
	       else 
		   {
		   echo $_POST['year']."年不是闰年";
		   }
		 break;
	}    
	?>
    </td>
  </tr>
</table>
</form>
</body>
</html>