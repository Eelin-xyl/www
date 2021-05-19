<?php
namespace app\index\controller;
use think\Controller;
use think\request;
use think\Session;
use think\Db;

class Common extends Controller
{	
	public function homepage()
	{
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

	public function login()
	{
		return $this->fetch('/common/login');
	}

	public function register()
	{
		return $this->fetch('/common/register');   
	}

	public function shopcart()
	{
		if(Session::has('uid'))
        {
            $uid = Session::get('uid');
            $sinfo = Db::table('shopcart')->where('uid', $uid)->select();
            $this->assign('sinfo', $sinfo);
        	return $this->fetch('/common/shopcart');
        }
        else
        {
        	$this->error('请先登录！', url('/index/common/login'));
        }
	}

	public function order()
	{
		if(Session::has('uid'))
        {
            $uid = Session::get('uid');
            $nickname = Db::table('userlist')->where('uid', $uid)->value('nickname');
            $oinfo = Db::table('order')->where('uid', $uid)->select();
            $this->assign(['oinfo' => $oinfo, 'nickname' => $nickname]);
        	return $this->fetch('/common/order');
        }
        else
        {
        	$this->error('请先登录！', url('/index/common/login'));
        }
	}

	public function pi()
	{

        if(Session::has('uid'))
        {
        	$uid = Session::get('uid');
            $info = Db::table('userlist')->where('uid', $uid)->select();
            $ginfo = Db::table('goods')->where('uid', $uid)->select(); 
            $this->assign([
            	'uid' => $uid,
                'info' => $info,
                'ginfo' => $ginfo
            	]);
        	return  $this->fetch('/common/pi');
        }
        else
        {
        	return $this->fetch('/common/login');
        }
	}

	public function addgoods()
	{
		if(Session::has('uid'))
        {
            return $this->fetch('/common/addgoods');
        }
        else
        {
        	$this->error('请先登录！', url('/index/common/login'));
        }
	}

	public function charge()
	{
		if(Session::has('uid'))
        {
        	$uid = Session::get('uid');
            $money = Db::table('userlist')->where('uid', $uid)->value('money');
            $this->assign('money', $money);
            return $this->fetch('/common/charge');
        }
        else
        {
        	$this->error('请先登录！', url('/index/common/login'));
        }
	}

    public function addcart()
    {
        if(Session::has('uid'))
        {
            $uid = Session::get('uid');
            $gid = Request::instance()->param('gid');
            $readd= Db::table('shopcart')
                    ->where(['uid' => $uid, 'gid' => $gid])
                    ->find();

            if($readd)
            {
                $this->error('该商品已在购物车中！', url('shopcart'));
            }
            else
            {
                $ginfo = Db::table('goods')->where('gid', $gid)->find();
                $name = $ginfo['name'];
                $price = $ginfo['price'];
                $image = $ginfo['image'];
                Db::table('shopcart')->insert(['uid' => $uid, 
                                               'gid' => $gid,
                                               'name' => $name,
                                               'price' => $price,
                                               'number' => 1,
                                               'total' => $price,
                                               'image' => $image]);

                $this->success('添加成功！', url('shopcart'));
            }
        }
        else
        {
            $this->error('请先登录！', url('/index/common/login'));
        }
    }

    public function minuscart()
    {
        $uid = Session::get('uid');
        $gid = Request::instance()->param('gid');
        $number = Db::table('shopcart')->where('uid', $uid)->where('gid', $gid)->value('number');
        Db::table('shopcart')->where(['uid' => $uid, 'gid' => $gid])
                             ->update(['number' => $number - 1]);
        $this->redirect('shopcart');
    }

    public function pluscart()
    {
        $uid = Session::get('uid');
        $gid = Request::instance()->param('gid');
        $number = Db::table('shopcart')->where('uid', $uid)->where('gid', $gid)->value('number');
        Db::table('shopcart')->where(['uid' => $uid, 'gid' => $gid])
                             ->update(['number' => $number + 1]);
        $this->redirect('shopcart');
    }

    public function delcart()
    {
        $uid = Session::get('uid');
        $gid = Request::instance()->param('gid');
        Db::table('shopcart')->where(['uid' => $uid, 'gid' => $gid])->delete();
        $this->redirect('shopcart');
    }

    public function buy()
    {
        $uid = Session::get('uid');
        $gid = Request::instance()->param('gid');
        $ginfo = Db::table('goods')->where('gid', $gid)->find();
        $money = Db::table('userlist')->where('uid', $uid)->value('money');
        $sinfo = Db::table('shopcart')->where(['uid' => $uid, 'gid' => $gid])->find();
        if(($money - $sinfo['total']) >= 0)
        {
            if($ginfo['amount'] >= $sinfo['number'])
            {               
                Db::table('order')->insert(['uid' => $uid,
                                            'gid' => $gid,
                                            'name' => $ginfo['name'],
                                            'number' => $sinfo['number'],
                                            'total' => $sinfo['total'],
                                            'image' => $ginfo['image'],
                                            'time' => time(),
                                            'status' => '已付款']);
                Db::table('goods')->where('gid', $gid)->update(['amount' => $ginfo['amount'] - $sinfo['number']]);
                Db::table('shopcart')->where(['uid' => $uid, 'gid' => $gid])->delete();

                $this->success('购买成功！', url('order'));
           }
           else
           {
                $this->error('店家库存不足！', url('shopcart'));
           }
        }
        else
        {
            $this->error('余额不足！', url('charge'));
        }
    }

}

?>