<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
</head>

<body>
<p>
  <?php
    $num = 5;
    $i = 1;
	while($i <= $num)
	{
		$j = 1;
        while($j <= $num-$i)
		{
			echo "&nbsp;";
			$j++;
		}
		$j = 1;
		while($j <= 2*$i-1)
		{
			echo "*";
			$j++;
		}
	    $j = 1;
		while($j <= $num-$i)
		{
			echo "&nbsp;";
			$j++;
		}
		echo "<br />";
		$i++;
	}
	echo "<br />";
?>
  <?php
    $i = 1;
	while($i <= $num)
	{
		$j = 1;
		while($j <= $num-$i)
		{
			echo "&nbsp;";
			$j++;
		}
		$j = 1;
		while($j < $i)
		{
			echo $j;
			$j++;
		}
		while($j >= 1)
		{
			echo $j;
			$j--;
		}
	    $j = 1;
		while($j <= $num-$i)
		{
			echo "&nbsp;";
			$j++;
		}
		echo "<br />";
		$i++;
	}
	echo "<br />";
?>
  <?php
	for($i = 1; $i <= 9; $i++)
	{
		for($j = 1; $j <= $i; $j++)
		{
		    echo $j."*".$i."=".$i*$i;
			echo "&nbsp;&nbsp;";
		}
		echo "<br />";
	}
	echo "<br />";
?>
  
</p>
</body>
</html>