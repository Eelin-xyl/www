<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
</head>

<body>
<?php
$pro = array(
            array('name' => '主板', 'price' => '100', 'place' => '广东', 'num' => '1'),
            array('name' => '内存', 'price' => '200', 'place' => '上海', 'num' => '2'),
			array('name' => '硬盘', 'price' => '300', 'place' => '北京', 'num' => '3'),
			);
?>
<p align="center"><strong>商品订货单 </strong></p>
<table width="100%" border="1">
  <tr>
    <td width="20%" bgcolor="#66FFFF"><div align="center">商品名称</div></td>
    <td width="20%" bgcolor="#66FFFF"><div align="center">单价（元）</div></td>
    <td width="20%" bgcolor="#66FFFF"><div align="center">产地</div></td>
    <td width="20%" bgcolor="#66FFFF"><div align="center">数量（个）</div></td>
    <td width="20%" bgcolor="#66FFFF"><div align="center">总价（元）</div></td>
  </tr>
  <?php foreach($pro as $key => $val){ ?>
  <tr>
    <td><div align="center"><?php echo $val['name'] ?></div></td>
    <td><div align="center"><?php echo $val['price'] ?></div></td>
    <td><div align="center"><?php echo $val['place'] ?></div></td>
    <td><div align="center"><?php echo $val['num'] ?></div></td>
    <td><div align="center"><?php echo $val['price']*$val['num'] ?></div></td>
  </tr>
  <?php } ?>
  <tr>
    <td colspan="5"><div align="right">小计：
	<?php 
	    $sum = 0;
	    foreach($pro as $key => $val)  
       {  
          $sum += $val['price']*$val['num'];
       }
	   echo $sum;
	?>
    </div>      
    </td>
  </tr>
</table>
</body>
</html>