<?php
namespace app\index\controller;
use think\Controller;
use think\request;
use think\Session;
use think\Db;

class Category extends Controller
{
	public function bags()
	{
        $ginfo = Db::table('goods')->where('category', '箱包户外')->select();
        $this->assign('ginfo', $ginfo);
        if(Session::has('uid'))
        {     	
        	$this->assign('user', '个人信息');
        	return $this->fetch('/shop/category');
        }
        else
        {
        	$this->assign('user', '登陆');
        	return $this->fetch('/shop/category');
        }
	}
	
    public function books()
	{
		$ginfo = Db::table('goods')->where('category', '图书音像')->select();       
        $this->assign('ginfo', $ginfo);
        if(Session::has('uid'))
        {     	
        	$this->assign('user', '个人信息');
        	return $this->fetch('/shop/category');
        }
        else
        {
        	$this->assign('user', '登陆');
        	return $this->fetch('/shop/category');
        }	
	}
	
	public function clothes()
	{
        $ginfo = Db::table('goods')->where('category', '男装女装')->select();       
        $this->assign('ginfo', $ginfo);
		if(Session::has('uid'))
        {     	
        	$this->assign('user', '个人信息');
        	return $this->fetch('/shop/category');
        }
        else
        {
        	$this->assign('user', '登陆');
        	return $this->fetch('/shop/category');
        }	
	}
	
	public function computers()
	{
        $ginfo = Db::table('goods')->where('category', '手机电脑')->select();       
        $this->assign('ginfo', $ginfo);
		if(Session::has('uid'))
        {     	
        	$this->assign('user', '个人信息');
        	return $this->fetch('/shop/category');
        }
        else
        {
        	$this->assign('user', '登陆');
        	return $this->fetch('/shop/category');
        }
	}
}

?>