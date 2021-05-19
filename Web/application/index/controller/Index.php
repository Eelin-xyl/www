<?php
namespace app\index\controller;
use think\Controller;
use think\request;
use think\Session;
use think\Db;

class Index  extends Controller
{
    public function index()
    {
        $ginfo = Db::table('goods')->select();
        $this->assign('ginfo', $ginfo);
        if(Session::has('uid'))
        {     	
        	$this->assign('user', '个人信息');
        	return $this->fetch('/common/homepage');
        }
        else
        {
        	$this->assign('user', '登陆');
        	return $this->fetch('/common/homepage');
        }
    }
}

?>