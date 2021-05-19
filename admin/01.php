<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
</head>

<body>
<?php
    class person{
		public $name;
		public $stunum;
		public $major;
	    public $clas;
		public function __construct(){
			$this->name = "薛奕林";
			$this->stunum = 1641310625;
			$this->major = "云计算";
			$this->clas = 2;
		}
		public function show(){				
		    echo '姓名：' . $this->name.
			     '</ br>学号：' . $this->stunum. 
				 '</ br>方向：' . $this->major.
			     '</ br>班级：' . $this->clas;
		}
	}
	$p1 = new persion();
	$p1->show();
?>
</body>
</html>