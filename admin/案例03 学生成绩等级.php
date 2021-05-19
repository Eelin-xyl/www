<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
</head>

<body>
    <?php
	
	$name = "小明";
	$score = 80;
	if($score<=100&&$score>=90){
		$grade = "A";}
		elseif($score<=89&&$score>=80){
			$grade = "B";}
			elseif($score<=79&&$score>=70){
				$grade = "C";}
				elseif($score<=69&&$score>=60){
					$grade = "D";}
					elseif($score<=59){
						$grade = "D";}
						
	?>
    
<table width="200" border="1">
  <tr>
    <td align="center"><strong>学生成绩等级</strong></td>
  </tr>
  <tr>
    <td align="center">学生姓名：<?php echo $name ?></td>
  </tr>
  <tr>
    <td align="center">学生分数：<?php echo $score ?></td>
  </tr>
  <tr>
    <td align="center">成绩等级：<?php echo $grade ?></td>
  </tr>
</table>
    <?php $base = '1800';
	$salary = $base + 3600;
	var_dump($salary);
    ?>
</body>
</html>