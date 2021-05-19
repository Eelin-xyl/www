<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
</head>

<body>
     <?php
         $db = mysqli_connect('127.0.0.1','root','123456','guestbook');
		 $sql = "insert into guestbook1(`username`, password, nickname,         regtime)
		 values('jeky', '111111', 'jeky', " . time() . ")";
		 $query = mysqli_query($db, $sql);
		 print_r($query);
		 echo mysqli_error($db);
     ?>
</body>
</html>