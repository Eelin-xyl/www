<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
</head>

<body>
<?php
namespace app\index\controller;

class Guestbook extends \think\Controller
{
	public function index(){
		$guestbook = model('Guestbook');
		$info = $guestbook->alias('g')->join('__USERLIST__u', 'u.uid = g.uid')->order('g.addtime desc')->select();
		$this->assign('list', $info);
		return $this->fetch();
	}
	public function edit(){
		$id = input('id');
		$guestbook = model('Gestbook');
		$info = $guestbook->fin($id);
		$this->assign('info',$info);
		return $this->fetch();
	}
}
?>
</body>
</html>