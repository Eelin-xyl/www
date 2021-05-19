<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php
    session_start();
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
</head>

<body>
<h1 align="center" >登录</h1>
<form method="post" >
<table align="center" >
  <tr>
    <td>账号</td>
  </tr>
  <tr>
    <td><input name="account" type="text" /></td>
  </tr>
  <tr>
    <td>密码</td>
  </tr>
  <tr>
    <td><input name="password" type="password" /></td>
  </tr>
  <tr>
    <td><input name="getmessage" type="submit" value="获取验证码" />
        <?php if ($_POST['getmessage']) echo "1234"; ?>
    </td>
  </tr>
  <tr>
    <td><input name="message" type="text" /></td>
  </tr>
  <tr>
    <td><input name="login" type="submit" value="登录" /></td>
  </tr>
  <tr>
    <td>
    <input name="register" type="submit" value="注册一个账号" /></td>
  </tr>
</table>
</form>

<?php
    while($_POST['login'])
	{
		$account = $_POST['account'];
		$password = $_POST['password'];
		$message = $_POST['message'];
		if (!$account)
		{
			exit('<script>alert("请填写账号");history.back();</script>');
		}
		elseif (!$password)
		{
			exit('<script>alert("请填写密码");history.back();</script>');
		}
		elseif (!$message)
		{
			exit('<script>alert("请填写验证码");history.back();</script>');
		}
		elseif ($message != '1234')
		{
			exit('<script>alert("验证码错误");history.back();</script>');
		}
		else
		{
		    $db = mysqli_connect('localhost','root','123456','guestbook'                  );
            $judge = "select * from userlist where account = '" .$account                     . "' ";
		    $query = mysqli_query($db, $judge);
		if (mysqli_fetch_array($query))
		{
			$sql = "select * from userlist where account = '" .$account.            "' and password = '" .$password."' ";
            $query2 = mysqli_query($db, $sql);
			if ($user = mysqli_fetch_array($query2))
			{
				$_SESSION['account'] = $user['account'];
				$_SESSION['nickname'] = $user['nickname'];
				$sql2 = "update userlist
				         set lastlogtime = '".time()."'
				         where account = '".$account."' ";
				$query3 = mysqli_query($db, $sql2);		 
				exit('<script>alert("登录成功！");
				location.href = "homepage.php";</script>');
			}
			else
			{
				exit('<script>alert("密码错误，请重新填写！");history.back();
				</script>');
			}
		}
		else
		{
			exit('<script>alert("账号不存在，请重新填写！");history.back();
			</script>');
		}
		}
		break;
	}
	if($_POST['register'])
	exit('<script>location.href = "register.php";</script>');
?>
</body>
</html>