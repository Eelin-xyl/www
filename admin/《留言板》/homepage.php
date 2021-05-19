<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
</head>

<body>
<h1 align="center">留言板</h1>
<form method="post" >
  <p>欢迎&nbsp;<font color="red"><strong>
  <?php session_start(); echo $_SESSION['nickname']; ?>
  </strong></font>！&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <input type="submit" name="update" value="修改资料" />
  <input type="submit" name="logout" value="退出登录" />
  <?php 
      if($_POST['logout'])
	  {
		  unset($_SESSION['account']);
		  unset($_SESSION['nickname']);
		  exit('<script>alert("您已退出登录！");
		  location.href = "login.php";</script>');
	  }
	  if($_POST['update'])
      exit('<script>location.href = "pi.php";</script>');
  ?>
  </p>
  <p>您的留言：</p>
  <p>
    <textarea name="message" cols="80" rows="5"></textarea>
  </p>
  <input type="submit" name="submit" value="发表" />
<?php
    $message = $_POST['message'];
	while($_POST['submit'])
	{
		if (!$message)
		{
			exit('<script>alert("请输入留言");history.back();</script>');
		}
		else
		{
			$time = time();
			$db=mysqli_connect('localhost','root','123456','guestbook');
            $sql="insert into messagebase(account,nickname,message,time)
                  values('" .$_SESSION['account']. "',                  
				  '" .$_SESSION['nickname']. "', 
				  '" .$message. "', '" .$time. "')";
            $query = mysqli_query($db, $sql);		
		    exit('<script>alert("留言成功!");history.back();</script>');
		}
		break;
	}
	$db2 = mysqli_connect('localhost','root','123456','guestbook');
    $select = "select * from messagebase";
	$query2 = mysqli_query($db2, $select);
	echo 
	'<table>';
	while ($row = mysqli_fetch_assoc($query2))
	{
     	echo'<tr><td><font color="red"><h2>'
		.$row['nickname'].'&nbsp;</h2></font></td><td><tt>'
		.date('Y年m月d日H:i:s', $row['time']). '</tt></td></tr>';
		echo '<tr><td>' .$row['message']. '</td>';
		if ($_SESSION['account'] == $row['account'])
		{
			echo '<td>&nbsp;<input type="submit" name="edit" value="编辑">
			&nbsp;&nbsp;<input type="submit" name="delete" value="删除">
			</td>';
		}
		echo '</tr>';
		if ($_POST['delete'])
		{
			$sql2 = "delete from messagebase where account = 
			'".$_SESSION['account']."' and time = '".$time."' ";
			$query3 = mysqli_query($db2, $sql2);			
		}
	}
	echo '</table>';
    /*$sql2 = "select count(1) as num from guestbook g left join userlist u on u.account = g.account";
	$query3 = mysqli_query($db2, $sql2);
	$countinfo = mysqli_fetch_array($query3);
	$count = $countinfo['num'];
	$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
	$pagesize = 10;
	$page = show_page($page, $pagesize, $count);
	function show_page($page, $pagesize = 10, $count = 0)
	{
		$page = ($page > 0) ? $page : 1;
		$pagecount = ceil($count / $pagesize);
		$page = ($page <= $pagecount) ? $page : $pagecount;
		$limit = ($page - 1) * $pagesize;
		$page_str = '';
	    $url =$_SERVER['SCRIPT_NAME'];
		$data = $_GET;
		$request = '';
		if ($data) 
		{
			if (isset($data['page']))
			unset($data['page']);
			foreach($data as $k => $v)
			{
				$request .= $k . '=' . $v . '&';
			}
		}
		$request .= 'page=';
		$url .= '?' . $request;
		$page_str .= "<span><a href='" . $url . "1'>首页</a></span>";
		if ($page > 1)
		{
			$page_str .= "<span><a href='" . $url . ($page - 1) . "'>上一页</a></span>";
		}
		$prevepage = $page - 1;
		$prevepage = ($prevepage < 1) ? 1 : $prevepage;
		$nextpage = $page + 3;
		$nextpage = ($nextpage > $pagecount) ? $pagecount : $nextpage;
		for ($i = $prevepage; $i <= $nextpage; $i++)
		{
			if ($i == $page)
			{
				$page_str .= "<span>[" . $i . "]</span>";
			}
			else 
			{
				$page_str .= "<span><a href='" . $url . $i . "'>[" . $i . "]</a></span>";
			}
		}
		if ($page < $pagecount)
		{
			$page_str .= "<span><a href='" . $url . ($page + 1) . "'>下一页</a></span>";
		}
		$page_str .= "<span><a href='" . $url . $pagecount ."'>尾页</a></span>";
		return array('limit' => $limit, 'page' => $page_str);
	}*/
?>
</form>
</body>
</html>