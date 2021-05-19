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
        	$this->error('请先登录！', url('/index/account/gologin'));
        }
	}

	public function order()
	{
		if(Session::has('uid'))
        {
            $uid = Session::get('uid');
            $oinfo = Db::table('order')->where('uid', $uid)->order('time desc')->select();
            foreach($oinfo as &$i)
            {
                if($i['status'] == '待付款')
                {
                    $i['status'] = '<input name="buy" type="submit" value="待付款" />';
                }
                else
                {
                    $i['status'] = '已付款';
                }
            }
            foreach($oinfo as &$j)
            {
                if($j['comment'] == '')
                {
                    $j['comment'] = '<input name="comment" type="submit" value="现在评论" />';
                }
                else
                {
                    $j['comment'] = '已评论';                
                }
            }        
            $this->assign('oinfo', $oinfo);
        	return $this->fetch('/common/order');
        }
        else
        {
        	$this->error('请先登录！', url('/index/account/gologin'));
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
        	return $this->fetch('/account/login');
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
        	$this->error('请先登录！', url('/index/account/gologin'));
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
        	$this->error('请先登录！', url('/index/account/gologin'));
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
            $this->error('请先登录！', url('/index/account/gologin'));
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
        if($ginfo['amount'] < $sinfo['number'])
        $this->error('店家库存不足！', url('shopcart'));
        
        if(($money - $sinfo['total']) >= 0)
        {
            $status = '已付款';
        }
        else
        {
            $status = '待付款';
        }

        Db::table('order')->insert(['uid' => $uid,
                                    'gid' => $gid,
                                    'name' => $ginfo['name'],
                                    'number' => $sinfo['number'],
                                    'total' => $sinfo['total'],
                                    'image' => $ginfo['image'],
                                    'time' => time(),
                                    'status' => $status,
                                    'comment' => '',
                                    'ctime' => 0]);
        
        Db::table('goods')->where('gid', $gid)->update(['amount' => $ginfo['amount'] - $sinfo['number']]);
        Db::table('shopcart')->where(['uid' => $uid, 'gid' => $gid])->delete();

        if($status = '已付款')
        {
            Db::table('goods')->where('gid', $gid)->update(['sales' => $ginfo['sales'] + $sinfo['number']]);         
            $this->success('购买成功！', url('order'));
        }
        else
        {
            $this->error('余额不足！(请尽快充值付款！）', url('charge'));
        }
    }

    public function rebuy()
    {
        $uid = Session::get('uid');
        $oid = Request::instance()->param('oid');
        $oinfo = Db::table('order')->where('oid', $oid)->find();
        $money = Db::table('userlist')->where('uid', $uid)->value('money');
        $sales = Db::table('goods')->where('gid', $oinfo['gid'])->value('sales');
        if($money >= $oinfo['total'])
        {
            Db::table('goods')->where('gid', $oinfo['gid'])->update(['sales' => $sales + $oinfo['number']]);         
            Db::table('order')->where('oid', $oid)->update(['status' => '已付款']);
            $this->success('付款成功！', url('order'));
        }
        else
        {
            $this->error('余额不足！(请尽快充值付款！）', url('charge'));
        }
    }

    public function gocomment()
    {     
        $oid = Request::instance()->param('oid');
        $cinfo = Db::table('order')->where('oid', $oid)->select();
        $this->assign('cinfo', $cinfo);
        return $this->fetch('/shop/comment');
    }

    public function comment()
    {
        $oid = Request::instance()->param('oid');
        $comment = Request::instance()->post('comment');
        Db::table('order')->where('oid', $oid)
                          ->update(['comment' => $comment, 'ctime' => time()]);                         
        $this->success('评论成功！', url('order'));
    }
}

?>