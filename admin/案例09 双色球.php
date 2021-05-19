<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
</head>

<body>
<?php 
    $red = range(1, 33);
	$blue = range(1, 16);
	$pick1 = array_rand($red, 6);
	$pick2 = array_rand($blue, 1);
	for($i = 0; $i < 6; $i++){
	?>
<input name="<?php echo $i+1 ?>" type="button" value="<?php echo str_pad($red[$pick1[$i]], 2, "0", STR_PAD_LEFT); ?>" style="background-color: red; height: 50px; width: 50px;" />
<?php 
	echo "&nbsp;";
	}
?>
<input name="7" type="button" value="<?php echo str_pad($blue[$pick2], 2, "0", STR_PAD_LEFT); ?>" style="background-color: blue; height: 50px; width: 50px;" />
</body>
</html>