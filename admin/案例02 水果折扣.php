<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
</head>

<body>
    <?php
	
	$fruite1 = "香蕉";
	$fruite2 = "苹果";
	$fruite3 = "橘子";
	
	$num1 = 2;
	$num2 = 3;
	$num3 = 4;
	
    $price1 = 1;
	$price2 = 2;
	$price3 = 3;
	
	define('sale', 0.8);
	
	?>
	
<table width="600" border="1">
  <tr>
    <td width="200" align="center">商品名称</td>
    <td width="200" align="center">购买数量（斤）</td>
    <td width="200" align="center">商品价格（元/斤）</td>
  </tr>
  <tr>
    <td align="center"><?php echo $fruite1 ?></td>
    <td align="center"><?php echo $num1 ?></td>
    <td align="center"><?php echo $price1 ?></td>
  </tr>
  <tr>
    <td align="center"><?php echo $fruite2 ?></td>
    <td align="center"><?php echo $num2 ?></td>
    <td align="center"><?php echo $price2 ?></td>
  </tr>
  <tr>
    <td align="center"><?php echo $fruite3 ?></td>
    <td align="center"><?php echo $num3 ?></td>
    <td align="center"><?php echo $price3 ?></td>
  </tr>
  <tr>
    <td colspan="3" align="right">商品折扣：<?php echo sale ?>
    </td>
  </tr>
  <tr>
    <td colspan="3" align="right">打折后购买商品总价格：<?php echo ($num1*$price1 + $num2*$price2 + $num3*$price3)*sale ?>
    </td>
  </tr>
</table>
</body>
</html>