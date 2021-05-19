<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
</head>

<body>
<h1 align="center">注册</h1>
<form method="post" enctype="multipart/form-data">
<table align="center" >
      <tr><td>
             账号
      </td></tr>
      <tr><td>
             <input type="text" name="account" />
      </td></tr>
      <tr><td>
             密码
      </td></tr>
      <tr><td>
             <input type="password" name="password" />    
      </td></tr>
      <tr><td>
             确认密码
      </td></tr>
      <tr><td>
             <input type="password" name="repassword" /> 
      </td></tr>      
      <tr><td>
             昵称
      </td></tr>
      <tr><td>
             <input type="text" name="nickname" />
      </td></tr>
      <tr><td>
             上传头像
      </td></tr>
      <tr><td>
             <input type="file" name="headimg" />
      </td></tr>
      <tr><td>
             <input name="submit" type="submit" value="提交"/>
      </td></tr>
</table>
</form>
<?php
     while ($_POST['submit']) {
	 $account = $_POST['account'];
	 $password = $_POST['password'];
	 $nickname = $_POST['nickname'];
	 if (!$account)
	 {
		 exit('<script>alert("请填写账号");history.back();</script>');
	 }
	 elseif (!$password)
	 {
		 exit('<script>alert("请填写密码");history.back();</script>');	 
	 }
	 elseif ($_POST['password'] != $_POST['repassword'])
	 {
		 exit('<script>alert("密码不一致");history.back();</script>');
	 }
	 elseif (!$nickname)
	 {
		 exit('<script>alert("请填写昵称");history.back();</script>');
	 }
	 else
	 {
        $db = mysqli_connect('localhost','root','123456','guestbook');
        $select = "select * from userlist where account = '" .$account.                   "' ";
		$query = mysqli_query($db, $select);
		if (!mysqli_fetch_array($query))
		{
			$sql = "insert into userlist(account, password, nickname,                    regtime, lastlogtime)
            values('".$account."', '".$password."', '".$nickname."',                   '".time()."', '0000')";
            $query2 = mysqli_query($db, $sql);		
			exit('<script>alert("注册成功，请登录");
			location.href = "登录.php";</script>');
		}
		else
		{
			exit('<script>alert("用户名重复，请重新填写!");history.back();
			</script>');
		}
		//$filename = $headimg['name'];
		//$index = strrpos($filename, '.');
		//$ext = substr($filename, $index);
		//$newfilename = date('YmdHis').rand(1000, 9999);		
	 }
	 break;
	 }
?>
</body>
</html>