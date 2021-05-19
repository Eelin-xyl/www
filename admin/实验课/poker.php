<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<style>
table{float:left;margin-right:10px}
</style>
</head>

<body>
<?php
    for($i = 1; $i < 14; $i++)
	{
		$j = $i;
		switch($j)
		{
	        case 1: $j = 'A'; break;
	        case 11: $j = 'J'; break;
			case 12: $j = 'Q'; break;
			case 13: $j = 'K'; break;
		}
		$heart[$i - 1] = "♥ $j";
	    $spade[$i - 1] = "♠ $j";
   	    $diamond[$i - 1] = "♦ $j";
		$club[$i - 1] = "♣ $j";
	}
	$Joker = array('joker', 'JOKER');
	$Poker = array_merge($heart, $spade, $diamond, $club, $Joker);
	shuffle($Poker);
	$m = 0;
	for($n = 0; $n < 54; $n+=3)
	{
		$Player1[$m] = $Poker[$n];
		$Player2[$m] = $Poker[$n + 1];
		$Player3[$m] = $Poker[$n + 2];
		$m++;
	}
	echo "玩家A牌组：<br />";
	foreach($Player1 as $value)
	{
		?>
        <table width="50" height="80" border="1">
        <tr>
        <td align="center">
        <?php
		if(
		   preg_match('/♥/', $value) || preg_match('/♦/', $value) ||           preg_match('/JOKER/', $value) == 1
		   )
		{?>
        <font color="red"><?php echo $value;?></font>
		<?php }
		else
		{
		    echo $value;
	    }
		?>
        </td>
        </tr>
        </table>   
        <?php 
	}
	?> 
    <p>&nbsp;</p> <p>&nbsp;</p> <p>&nbsp;</p>
	<?php
	echo "玩家B牌组：<br />";
	foreach($Player2 as $value)
	{
		?>
        <table width="50" height="80" border="1">
        <tr>
        <td align="center">
        <?php
		if(
		   preg_match('/♥/', $value) || preg_match('/♦/', $value) ||           preg_match('/JOKER/', $value) == 1
		   )
		{?>
        <font color="red"><?php echo $value;?></font>
		<?php }
		else
		{
		    echo $value;
	    }
		?>
        </td>
        </tr>
        </table>
        <?php 
	}
	?> 
    <p>&nbsp;</p> <p>&nbsp;</p> <p>&nbsp;</p>    
	<?php
	echo "玩家C牌组：<br />";	
	foreach($Player3 as $value)
	{
		?>
        <table width="50" height="80" border="1">
        <tr>
        <td align="center">
        <?php
		if(
		   preg_match('/♥/', $value) || preg_match('/♦/', $value) ||           preg_match('/JOKER/', $value) == 1
		   )
		{?>
        <font color="red"><?php echo $value;?></font>
		<?php }
		else
		{
		    echo $value;
	    }
		?>
        </td>
        </tr>
        </table>
        <?php 
	}
	
?>
</body>
</html>