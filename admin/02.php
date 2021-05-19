<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
</head>

<body>
<?php
namespace app\index\validate;
class Userlist extends \think\Validate{
	protected $rule = array(
	'username' => 'require|unique:userlist',
	'password' => 'require|confirm',
	);
	
	protected $message = array(
	'username.require' => '请填写用户名',
	'username.unique' => '请填写用户密码',
	//'username.' => ' ',
	);
	public function reg(){
		return $this->fetch();
	}
	public function regDo(){
		$data = input();
		$user = model('Userlist');
		if ($user->validate(true)->allowField(true)->save($data)){
			$this->success('注册成功',
			url('/index/Index/index'));
		} else {
			$this->error('注册失败:' . $user->getError());
		}
	}
}
?>
<?
namespace app\index\controller;

class Guestbook extends \think\Controller{
	public function index(){
		//$guestbook = new Guestbook;
		$guestbook = model('Guestbook');
		$info = $guestbook->select();
		print_r($info);
		return(1);
	}
}
?>
</body>
</html>