<?php
namespace app\index\controller;
use think\Controller;
use think\request;
use think\Session;
use think\Db;

class Shop extends Controller
{
	public function shop()
	{
        $sid=Request::instance()->param('sid');
        $sname = Db::table('goods')->where('uid', $sid)->value('sname');
        $origin = Db::table('goods')->where('uid', $sid)->value('origin');
        $ginfo = Db::table('goods')->where('uid', $sid)->select();
        $this->assign(['sname' => $sname, 'origin' => $origin, 'ginfo' => $ginfo]);

		if(Session::has('uid'))
        {     	
        	$this->assign('user', '个人信息');
        	return $this->fetch('/shop/shop');
        }
        else
        {
        	$this->assign('user', '登陆');
        	return $this->fetch('/shop/shop');
        }			
	}

        public function goods()
        {
            $gid = Request::instance()->param('gid');
            $ginfo = Db::table('goods')->where('gid', $gid)->select();
            $this->assign('ginfo', $ginfo);
            
            if(Session::has('uid'))
            {       
                $this->assign('user', '个人信息');
                return $this->fetch('/shop/goods');
            }
            else
            {
                $this->assign('user', '登陆');
                return $this->fetch('/shop/goods');
            }
        }
}

?>