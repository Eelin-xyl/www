<?php
namespace app\index\controller;
use think\Controller;
use think\request;
use think\Session;
use think\Db;

class Account extends Controller
{
    public function gologin()
	{
		return $this->fetch('/account/login');
	}

	public function goregister()
	{
		return $this->fetch('/account/register');
	}

	public function register()
    {
        $uid = Request::instance()->post('uid');
        $password = Request::instance()->post('password');
        $repassword = Request::instance()->post('repassword');
        $nickname = Request::instance()->post('nickname');
        $origin = Request::instance()->post('origin');

        if($uid && $password && $nickname && $origin && $password == $repassword)
		{
			
			$reuid = Db::table('userlist')->where('uid', $uid)->find();
			
			if($reuid)
			{    
				$this->error( '账号已存在，请重新注册！',url('/index/common/register'));
			}
			else
			{
				Db::table('userlist')
				->insert(['uid' => $uid, 
                          'password' => $password, 
                          'nickname' => $nickname, 
					      'origin' => $origin, 
                          'money' => 0,
                          'regtime' => time()]);
				$this->success('注册成功！', url('/index/common/login'));
			}
		}
		else
		{
			$this->error('注册失败，请正确填写相关信息！', url('/index/common/register'));
		}
    }

    public function login()
    {
        $uid = Request::instance()->post('uid');
        $password = Request::instance()->post('password');
        $match = Db::table('userlist')
                 ->where(['uid' => $uid, 'password' => $password])
                 ->find();

        if($match)
        {
            Session::set('uid', $uid);           
         	$this->success('登陆成功！', url('/index/common/homepage'));
        }
        else
        {
         	$this->error('账号或密码错误！', url('/index/common/login'));
        }
    }
    
    public function charge()
    {
    	$uid = Session::get('uid');
    	$money = Request::instance()->post('money');
    	if(empty($money))
    	{
    		$this->error('请输入充值金额！', url('/index/common/charge'));
    	}
    	else
    	{
    		$old = Db::table('userlist')->where('uid', $uid)->value('money');
    	    $charge = Db::table('userlist')->where('uid', $uid)->update(['money' => $old + $money]);
    	    if($charge)
    	    {
    		    $this->success('充值成功！', url('/index/common/charge'));
    	    }
        }
    }

    public function addgoods()
    {
    	$uid = Session::get('uid');
    	$file = request()->file('img');
        $name = Request::instance()->post('name');
        $price = Request::instance()->post('price');
        $amount = Request::instance()->post('amount');
        $category = Request::instance()->post('category');
        $origin = Db::table('userlist')->where('uid', $uid)->value('origin');
        $nickname = Db::table('userlist')->where('uid', $uid)->value('nickname');

        if($file)
        {
            $info = $file->move(ROOT_PATH . 'public' . DS . 'static/shop');
            if($info)
            {     
                $image="/shop/".$info->getSaveName();
            }
            else
            {
               echo $file->getError();
            }
        }
        else
        {
        	$this->error('图片上传失败！', url('/index/common/addgoods'));
        }

        if($name && $price && $amount && $category && $image)
		{		
			Db::table('goods')
			->insert(['category' => $category, 
                      'name' => $name, 
                      'price' => $price,
                      'sales' => 0,
					  'amount' => $amount, 
                      'origin' => $origin, 
                      'uid' => $uid, 
                      'image' => $image, 
                      'sname' => $nickname.'的店铺']);
			$this->success('添加成功！', url('/index/common/pi'));
			
		}
		else
		{
			$this->error('添加失败，请正确填写相关信息！', url('/index/common/addgoods'));
		}
    }
    	
    public function logout()
    {
        Session::clear();
        return $this->success('退出成功！', url('/index/common/homepage'));
    }
}

?>