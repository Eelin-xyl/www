<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<script src="../../../Dreamweaver CS6/JQuery-3.1.1框架/jquery-3.1.1.min.js"></script>
<script>
function get_userlist(){
	$.get('url', '', function(data){
		if (data.status == 1) {
    		html_add = '';
	    	console.log(data);
			for (i = 0; i < data.msg.length; i++)
			{
				html_add += '<div> + data.msg[i] + </div>';
			}
			$("#userlist").html(html_add);
		}
		else if (data.status == 0) {
			alert(ata.msg);
			location.href = "登录.php";
		}
		}, 'json');	
}
$(function(){
	get_userlist();
	setInterval('get_userlist',3);
})
</script>
</head>

<body>
<table border="1" align="center" width="600" >
  <tr>
    <td height="300" valign="top">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td height="300">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>

<?php
if (!$_SESSION['account'])
{
	$arr = array('status' => 0, 'msg' => "请登录后访问");
	echo json_encode($arr);
	exit;
}
$sql = "updata userlist set chattime = " .time(). " where uid = " .$_SESSION['uid']." ";
$query = mysqli_query($db, $sql);


$sql = "select * from userlist where chattime > " .(time()-10). " ";
$query = mysqli_query($db, $sql);
$userlist = array();
while ($userinfo = mysqli_fetch_array($query)){
		$userlist[] = $userinfo['nickname'];
}
echo json_encode(array('status' => 1, 'msg' => $userlist));
?>
</body>
</html>     