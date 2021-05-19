<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
</head>

<body>
<h1 align="center">修改资料</h1>
<p>您的账号：
<?php session_start(); echo $_SESSION['account']; ?>
</p>
<form method="post">
<table align="center">
  <tr>
    <td>新的昵称：</td>
  </tr>
  <tr>
    <td><input type="text" name="nickname" /></td>
  </tr>
  <tr>
    <td>新的密码：</td>
  </tr>
  <tr>
    <td><input type="password" name="password" /></td>
  </tr>
  <tr>
    <td>确认密码：</td>
  </tr>
  <tr>
    <td><input type="password" name="repassword" /></td>
  </tr>
  <tr>
    <td><input type="submit" name="submit" value="确认修改" /></td>
  </tr>
</table>
</form>
<?php
    while ($_POST['submit']) 
	{
	 $nickname = $_POST['nickname'];
	 $password = $_POST['password'];
	 if (!$nickname)
	 {
		 exit('<script>alert("请填写昵称");history.back();</script>');
	 }
	 elseif (!$password)
	 {
		 exit('<script>alert("请填写密码");history.back();</script>');	 
	 }
	 elseif ($_POST['password'] != $_POST['repassword'])
	 {
		 exit('<script>alert("密码不一致");history.back();</script>');
	 }
	 else
	 {
        $db = mysqli_connect('localhost','root','123456','guestbook');
        $sql = "update userlist
		        set nickname = '".$nickname."',
				password = '".$password."'
				where account = '".$_SESSION['account']."' ";
        $query = mysqli_query($db, $sql);		
	    exit('<script>alert("修改成功！");
		location.href = "login.php";</script>');	
	 }
	 break;
	 }
?>
</body>
</html>