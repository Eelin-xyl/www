<?php
namespace app\index\controller;
use think\Controller;
use think\facade\Session;
use think\Db;
use think\facade\Request;

class Shijuan extends Controller
{
	public function shijuan_add()
	{
		$cid=Request::param('cid');
		$shijuan=Request::param('shijuan');
		$tid=Session::get('tid');
		$result=Db::name('shijuan')->where('shijuan',$shijuan)->find();
		if ($result) {
			$this->error('试卷以存在，请重新创建!');
		}else{
			if ($cid&&$shijuan) {
			    Db::name('shijuan')->insert(['cid'=>$cid,'shijuan'=>$shijuan,'tid'=>$tid]);
			    $s=Db::name('shijuan')->where('shijuan',$shijuan)->find();
			    $c=Db::name('course')->where('cid',$cid)->find();
			    $k=$c['cid'].$s['kid'].',';
			    Db::name('course')->where('cid',$cid)->update(['kid'=>$k]);
			    $this->success('创建成功!',url('index/index/teacher_index'));
		    }else{
		    	$this->error('请输入全部信息!');
		    }
		}
	}


        public function shijuan_a_add()
    {
        $cid=Request::param('cid');
        $shijuan=Request::param('shijuan');
        $tid=Request::param('tid');
        $result=Db::name('shijuan')->where('shijuan',$shijuan)->find();
        if ($result) {
            $this->error('试卷以存在，请重新创建!');
        }else{
            if ($cid&&$shijuan) {
                Db::name('shijuan')->insert(['cid'=>$cid,'shijuan'=>$shijuan,'tid'=>$tid]);
                $s=Db::name('shijuan')->where('shijuan',$shijuan)->find();
                $c=Db::name('course')->where('cid',$cid)->find();
                $k=$c['cid'].$s['kid'].',';
                Db::name('course')->where('cid',$cid)->update('kid',$k);
                $this->success('创建成功!',url('index/index/teacher_index'));
            }else{
                $this->error('请输入全部信息!');
            }
        }
    }



	public function shijuan_delete()
	{
		$kid=Request::param('kid');
		if ($kid) {
			$t=Db::name('shijuan')->where('kid',$kid)->find();
			Db::name('shijuan')->where('kid',$kid)->delete();
			Db::name('sjtm')->where('kid',$kid)->delete();
			$this->error('删除成功!');
		}else{
			$this->error('参数获取失败!');
		}
	}


    public function shijuan_tm_add()
    {
        $kid=Request::param('kid');
        $pid=Request::param('pid');
        if ($kid&&$pid) {
            $tm=Db::name('problem')->where('pid',$pid)->find();
            Db::name('sjtm')->insert(['kid'=>$kid,'kind'=>$tm['kind'],'timu'=>$tm['timu'],'x1'=>$tm['x1'],'x2'=>$tm['x2'],'x3'=>$tm['x3'],'x4'=>$tm['x4'],'daan'=>$tm['daan'],'fs'=>$tm['fs'],'nandu'=>$tm['nandu'],'yaodian'=>$tm['yaodian']]);
            $this->success('成功添加！',url('shijuan_tm_add_index1'));
        }
    }


    public function a_shijuan_tm_add()
    {
        $kid=Request::param('kid');
        $pid=Request::param('pid');
        if ($kid&&$pid) {
            $tm=Db::name('problem')->where('pid',$pid)->find();
            Db::name('sjtm')->insert(['kid'=>$kid,'kind'=>$tm['kind'],'timu'=>$tm['timu'],'x1'=>$tm['x1'],'x2'=>$tm['x2'],'x3'=>$tm['x3'],'x4'=>$tm['x4'],'daan'=>$tm['daan'],'fs'=>$tm['fs'],'nandu'=>$tm['nandu'],'yaodian'=>$tm['yaodian']]);
            $this->success('成功添加！',url('a_shijuan_tm_add_index1'));
        }
    }


    public function shijuan_tm_delete()
    {
        $id=Request::param('id');
        if ($id) {
            Db::name('sjtm')->where('id',$id)->delete();
            $this->error('删除成功!');
        }else{
            $this->error('参数获取失败!');
        }        
    }



    public function tk_dan_add()
    {
        $cid=Session::get('cid');
        $t=input('post.');
        if ($t) {
            Db::name('problem')->insert(['pid'=>'','timu'=>$t['timu'],'kind'=>'1','x1'=>$t['xa'],'x2'=>$t['xb'],'x3'=>$t['xc'],'x4'=>$t['xd'],'daan'=>$t['an'],'fs'=>$t['fs'],'cid'=>$cid,'nandu'=>$t['nan'],'yaodian'=>$t['yd'],'dy'=>$t['dy']]);
            $this->success('添加成功',url('tk_show_index1'));
        }else{
            $this->error('获取参数失败!');
        }
    }



    public function tk_duo_add()
    {
        $cid=Session::get('cid');
        $t=input('post.');
        if ($t) {
            Db::name('problem')->insert(['pid'=>'','timu'=>$t['timu'],'kind'=>'2','x1'=>$t['xa'],'x2'=>$t['xb'],'x3'=>$t['xc'],'x4'=>$t['xd'],'daan'=>$t['an'],'fs'=>$t['fs'],'cid'=>$cid,'nandu'=>$t['nan'],'yaodian'=>$t['yd'],'dy'=>$t['dy']]);
            $this->success('添加成功',url('tk_show_index1'));
        }else{
            $this->error('获取参数失败!');
        }
    }



    public function tk_pan_add()
    {
        $cid=Session::get('cid');
        $t=input('post.');
        if ($t) {
            Db::name('problem')->insert(['pid'=>'','timu'=>$t['timu'],'kind'=>'3','daan'=>$t['an'],'fs'=>$t['fs'],'cid'=>$cid,'nandu'=>$t['nan'],'yaodian'=>$t['yd'],'dy'=>$t['dy']]);
            $this->success('添加成功',url('tk_show_index1'));
        }else{
            $this->error('获取参数失败!');
        }
    }



    public function tk_tian_add()
    {
        $cid=Session::get('cid');
        $t=input('post.');
        if ($t) {
            Db::name('problem')->insert(['pid'=>'','timu'=>$t['timu'],'kind'=>'4','xuanxiang'=>'','daan'=>$t['an'],'fs'=>$t['fs'],'cid'=>$cid,'nandu'=>$t['nan'],'yaodian'=>$t['yd'],'dy'=>$t['dy']]);
            $this->success('添加成功',url('tk_show_index1'));
        }else{
            $this->error('获取参数失败!');
        }
    }



    public function tk_jian_add()
    {
        $cid=Session::get('cid');
        $t=input('post.');
        if ($t) {
            Db::name('problem')->insert(['pid'=>'','timu'=>$t['timu'],'kind'=>'5','daan'=>$t['an'],'fs'=>$t['fs'],'cid'=>$cid,'nandu'=>$t['nan'],'yaodian'=>$t['yd'],'dy'=>$t['dy']]);
            $this->success('添加成功',url('tk_show_index1'));
        }else{
            $this->error('获取参数失败!');
        }       
    }



    public function tk_t_delete()
    {
        $pid=Request::param('pid');
        if ($pid) {
            Db::name('problem')->where('pid',$pid)->delete();
            $this->error('删除成功!');
        }else{
            $this->error('参数获取失败!');
        }
    }



    public function tk_t_update()
    {
        $pid=Session::get('pid');
        $cid=Session::get('cid');
        $p=input('post.');
        if ($p) {
            Db::name('problem')->where('pid',$pid)->data(['timu'=>$p['timu'],'x1'=>$p['xa'],'x2'=>$p['xb'],'x3'=>$p['xc'],'x4'=>$p['xd'],'daan'=>$p['an'],'fs'=>$p['fs'],'nandu'=>$p['nan'],'dy'=>$p['dy'],'yaodian'=>$p['yd']])->update();
            $this->success('修改成功!',url('tk_show_index1'));
        }else{
            $this->error('参数获取失败!');
        }
    }

    public function a_tk_t_update()
    {
        $pid=Session::get('pid');
        $cid=Session::get('cid');
        $p=input('post.');
        if ($p) {
            Db::name('problem')->where('pid',$pid)->data(['timu'=>$p['timu'],'x1'=>$p['xa'],'x2'=>$p['xb'],'x3'=>$p['xc'],'x4'=>$p['xd'],'daan'=>$p['an'],'fs'=>$p['fs'],'nandu'=>$p['nan'],'dy'=>$p['dy'],'yaodian'=>$p['yd']])->update();
            $this->success('修改成功!',url('a_tk_show_index1'));
        }else{
            $this->error('参数获取失败!');
        }
    }


    public function a_tk_dan_add()
    {
        $cid=Session::get('cid');
        $t=input('post.');
        if ($t) {
            Db::name('problem')->insert(['pid'=>'','timu'=>$t['timu'],'kind'=>'1','x1'=>$t['xa'],'x2'=>$t['xb'],'x3'=>$t['xc'],'x4'=>$t['xd'],'daan'=>$t['an'],'fs'=>$t['fs'],'cid'=>$cid,'nandu'=>$t['nan'],'yaodian'=>$t['yd'],'dy'=>$t['dy']]);
            $this->success('添加成功',url('a_tk_show_index1'));
        }else{
            $this->error('获取参数失败!');
        }
    }



    public function a_tk_duo_add()
    {
        $cid=Session::get('cid');
        $t=input('post.');
        if ($t) {
            Db::name('problem')->insert(['pid'=>'','timu'=>$t['timu'],'kind'=>'2','x1'=>$t['xa'],'x2'=>$t['xb'],'x3'=>$t['xc'],'x4'=>$t['xd'],'daan'=>$t['an'],'fs'=>$t['fs'],'cid'=>$cid,'nandu'=>$t['nan'],'yaodian'=>$t['yd'],'dy'=>$t['dy']]);
            $this->success('添加成功',url('a_tk_show_index1'));
        }else{
            $this->error('获取参数失败!');
        }
    }



    public function a_tk_pan_add()
    {
        $cid=Session::get('cid');
        $t=input('post.');
        if ($t) {
            Db::name('problem')->insert(['pid'=>'','timu'=>$t['timu'],'kind'=>'3','daan'=>$t['an'],'fs'=>$t['fs'],'cid'=>$cid,'nandu'=>$t['nan'],'yaodian'=>$t['yd'],'dy'=>$t['dy']]);
            $this->success('添加成功',url('a_tk_show_index1'));
        }else{
            $this->error('获取参数失败!');
        }
    }



    public function a_tk_tian_add()
    {
        $cid=Session::get('cid');
        $t=input('post.');
        if ($t) {
            Db::name('problem')->insert(['pid'=>'','timu'=>$t['timu'],'kind'=>'4','xuanxiang'=>'','daan'=>$t['an'],'fs'=>$t['fs'],'cid'=>$cid,'nandu'=>$t['nan'],'yaodian'=>$t['yd'],'dy'=>$t['dy']]);
            $this->success('添加成功',url('a_tk_show_index1'));
        }else{
            $this->error('获取参数失败!');
        }
    }



    public function a_tk_jian_add()
    {
        $cid=Session::get('cid');
        $t=input('post.');
        if ($t) {
            Db::name('problem')->insert(['pid'=>'','timu'=>$t['timu'],'kind'=>'5','daan'=>$t['an'],'fs'=>$t['fs'],'cid'=>$cid,'nandu'=>$t['nan'],'yaodian'=>$t['yd'],'dy'=>$t['dy']]);
            $this->success('添加成功',url('a_tk_show_index1'));
        }else{
            $this->error('获取参数失败!');
        }       
    }





    public function shijuan_index()
    {
        $kid=Request::param('kid');
        $cid=Db::name('course')->where('kid','like','%'. $kid.'%')->find();
    	Session::set('kid',$kid);
    	$dan=Db::name('sjtm')->where(['kid'=>$kid,'kind'=>1])->select();
    	$duo=Db::name('sjtm')->where(['kid'=>$kid,'kind'=>2])->select();
    	$pan=Db::name('sjtm')->where(['kid'=>$kid,'kind'=>3])->select();
    	$tian=Db::name('sjtm')->where(['kid'=>$kid,'kind'=>4])->select();
    	$jian=Db::name('sjtm')->where(['kid'=>$kid,'kind'=>5])->select();
    	$this->assign([
    		'kid'=>$kid,
            'cid'=>$cid['cid'],
    		'dan'=>$dan,
    		'duo'=>$duo,
    		'pan'=>$pan,
    		'tian'=>$tian,
    		'jian'=>$jian
    		]);
    	return $this->fetch('shijuan/sjxq');
    }


    public function shijuan_index1()
    {
        $kid=Session::get('kid');
        $cid=Db::name('course')->where('kid','like','%'. $kid.'%')->find();
        $dan=Db::name('sjtm')->where(['kid'=>$kid,'kind'=>1])->select();
        $duo=Db::name('sjtm')->where(['kid'=>$kid,'kind'=>2])->select();
        $pan=Db::name('sjtm')->where(['kid'=>$kid,'kind'=>3])->select();
        $tian=Db::name('sjtm')->where(['kid'=>$kid,'kind'=>4])->select();
        $jian=Db::name('sjtm')->where(['kid'=>$kid,'kind'=>5])->select();
        $this->assign([
            'kid'=>$kid,
            'cid'=>$cid['cid'],
            'dan'=>$dan,
            'duo'=>$duo,
            'pan'=>$pan,
            'tian'=>$tian,
            'jian'=>$jian
            ]);
        return $this->fetch('shijuan/sjxq');
    }


    public function a_shijuan_index()
    {
        $kid=Request::param('kid');
        $cid=Db::name('course')->where('kid','like','%'. $kid.'%')->find();
        Session::set('akid',$kid);
        $dan=Db::name('sjtm')->where(['kid'=>$kid,'kind'=>1])->select();
        $duo=Db::name('sjtm')->where(['kid'=>$kid,'kind'=>2])->select();
        $pan=Db::name('sjtm')->where(['kid'=>$kid,'kind'=>3])->select();
        $tian=Db::name('sjtm')->where(['kid'=>$kid,'kind'=>4])->select();
        $jian=Db::name('sjtm')->where(['kid'=>$kid,'kind'=>5])->select();
        $this->assign([
            'kid'=>$kid,
            'cid'=>$cid['cid'],
            'dan'=>$dan,
            'duo'=>$duo,
            'pan'=>$pan,
            'tian'=>$tian,
            'jian'=>$jian
            ]);
        return $this->fetch('shijuan/asjxq');
    }

    public function a_shijuan_index1()
    {
        $kid=Session::get('akid');
        $cid=Db::name('course')->where('kid','like','%'. $kid.'%')->find();
        $dan=Db::name('sjtm')->where(['kid'=>$kid,'kind'=>1])->select();
        $duo=Db::name('sjtm')->where(['kid'=>$kid,'kind'=>2])->select();
        $pan=Db::name('sjtm')->where(['kid'=>$kid,'kind'=>3])->select();
        $tian=Db::name('sjtm')->where(['kid'=>$kid,'kind'=>4])->select();
        $jian=Db::name('sjtm')->where(['kid'=>$kid,'kind'=>5])->select();
        $this->assign([
            'kid'=>$kid,
            'cid'=>$cid['cid'],
            'dan'=>$dan,
            'duo'=>$duo,
            'pan'=>$pan,
            'tian'=>$tian,
            'jian'=>$jian
            ]);
        return $this->fetch('shijuan/asjxq');
    }


    
    public function shijuan_tm_add_index()
    {
        $kid=Request::param('kid');
        $cid=Request::param('cid');
        Session::set('kid1',$kid);
        Session::set('cid1',$cid);
        $dan=Db::name('problem')->where(['cid'=>$cid,'kind'=>1])->paginate(10);
        $duo=Db::name('problem')->where(['cid'=>$cid,'kind'=>2])->paginate(10);
        $pan=Db::name('problem')->where(['cid'=>$cid,'kind'=>3])->paginate(10);
        $tian=Db::name('problem')->where(['cid'=>$cid,'kind'=>4])->paginate(10);
        $jian=Db::name('problem')->where(['cid'=>$cid,'kind'=>5])->paginate(10);
        $this->assign([
            'kid'=>$kid,
            'dan'=>$dan,
            'duo'=>$duo,
            'pan'=>$pan,
            'tian'=>$tian,
            'jian'=>$jian
            ]);
        return $this->fetch('shijuan/sjtadd');
    }


        public function shijuan_tm_add_index1()
    {
        $kid=Session::get('kid1');
        $cid=Session::get('cid1');
        $dan=Db::name('problem')->where(['cid'=>$cid,'kind'=>1])->paginate(10);
        $duo=Db::name('problem')->where(['cid'=>$cid,'kind'=>2])->paginate(10);
        $pan=Db::name('problem')->where(['cid'=>$cid,'kind'=>3])->paginate(10);
        $tian=Db::name('problem')->where(['cid'=>$cid,'kind'=>4])->paginate(10);
        $jian=Db::name('problem')->where(['cid'=>$cid,'kind'=>5])->paginate(10);
        $this->assign([
            'kid'=>$kid,
            'dan'=>$dan,
            'duo'=>$duo,
            'pan'=>$pan,
            'tian'=>$tian,
            'jian'=>$jian
            ]);
        return $this->fetch('shijuan/sjtadd');
    }

    public function a_shijuan_tm_add_index()
    {
        $kid=Request::param('kid');
        $cid=Request::param('cid');
        Session::set('akid1',$kid);
        Session::set('acid1',$cid);
        $dan=Db::name('problem')->where(['cid'=>$cid,'kind'=>1])->paginate(10);
        $duo=Db::name('problem')->where(['cid'=>$cid,'kind'=>2])->paginate(10);
        $pan=Db::name('problem')->where(['cid'=>$cid,'kind'=>3])->paginate(10);
        $tian=Db::name('problem')->where(['cid'=>$cid,'kind'=>4])->paginate(10);
        $jian=Db::name('problem')->where(['cid'=>$cid,'kind'=>5])->paginate(10);
        $this->assign([
            'kid'=>$kid,
            'dan'=>$dan,
            'duo'=>$duo,
            'pan'=>$pan,
            'tian'=>$tian,
            'jian'=>$jian
            ]);
        return $this->fetch('shijuan/asjtadd');
    }


        public function a_shijuan_tm_add_index1()
    {
        $kid=Session::get('akid1');
        $cid=Session::get('acid1');
        $dan=Db::name('problem')->where(['cid'=>$cid,'kind'=>1])->paginate(10);
        $duo=Db::name('problem')->where(['cid'=>$cid,'kind'=>2])->paginate(10);
        $pan=Db::name('problem')->where(['cid'=>$cid,'kind'=>3])->paginate(10);
        $tian=Db::name('problem')->where(['cid'=>$cid,'kind'=>4])->paginate(10);
        $jian=Db::name('problem')->where(['cid'=>$cid,'kind'=>5])->paginate(10);
        $this->assign([
            'kid'=>$kid,
            'dan'=>$dan,
            'duo'=>$duo,
            'pan'=>$pan,
            'tian'=>$tian,
            'jian'=>$jian
            ]);
        return $this->fetch('shijuan/asjtadd');
    }




    public function shijuan_add_index()
    {
        $tid=Request::param('tid');
        Session::set('tid',$tid);
        return $this->fetch('shijuan/sjcj');
    }



    public function shijuan_a_add_index()
    {
        return $this->fetch('shijuan/asjcj');  
    }


    public function tk_add_index()
    {
    	return $this->fetch('shijuan/tadd');
    }

    public function a_tk_add_index()
    {
        return $this->fetch('shijuan/atadd');
    }



    public function tk_t_update_index()
    {
    	$pid=Request::param('pid');
    	Session::set('pid',$pid);
    	$p=Db::name('problem')->where('pid',$pid)->find();
    	$this->assign('p',$p);
    	return $this->fetch('shijuan/tmxg');
    }


    public function shijuan_a_xscj_index()
    {
        $kid=Request::param('kid');
        $re=Db::field('r.results,r.sid,s.student,r.kid,a.aid')->where('r.sid=s.sid=a.sid')->table('results r,student s,answer a')->paginate(10);
        $this->assign('re',$re);
        return $this->fetch('shijuan/axscj');
    }


    public function tk_show_index()
    {
        $cid=Request::param('cid');
        Session::set('cid',$cid);
        $dan=Db::name('problem')->where(['cid'=>$cid,'kind'=>1])->paginate(10);
        $duo=Db::name('problem')->where(['cid'=>$cid,'kind'=>2])->paginate(10);
        $pan=Db::name('problem')->where(['cid'=>$cid,'kind'=>3])->paginate(10);
        $tian=Db::name('problem')->where(['cid'=>$cid,'kind'=>4])->paginate(10);
        $jian=Db::name('problem')->where(['cid'=>$cid,'kind'=>5])->paginate(10);
        $this->assign([
            'cid'=>$cid,
            'dan'=>$dan,
            'duo'=>$duo,
            'pan'=>$pan,
            'tian'=>$tian,
            'jian'=>$jian
            ]);
        return $this->fetch('shijuan/tmshow');
    }

    public function a_tk_show_index()
    {
        $cid=Request::param('cid');
        Session::set('acid',$cid);
        $dan=Db::name('problem')->where(['cid'=>$cid,'kind'=>1])->paginate(10);
        $duo=Db::name('problem')->where(['cid'=>$cid,'kind'=>2])->paginate(10);
        $pan=Db::name('problem')->where(['cid'=>$cid,'kind'=>3])->paginate(10);
        $tian=Db::name('problem')->where(['cid'=>$cid,'kind'=>4])->paginate(10);
        $jian=Db::name('problem')->where(['cid'=>$cid,'kind'=>5])->paginate(10);
        $this->assign([
            'cid'=>$cid,
            'dan'=>$dan,
            'duo'=>$duo,
            'pan'=>$pan,
            'tian'=>$tian,
            'jian'=>$jian
            ]);
        return $this->fetch('shijuan/atmshow');
    }


    public function tk_show_index1()
    {
        $cid=Session::get('cid');
        $dan=Db::name('problem')->where(['cid'=>$cid,'kind'=>1])->paginate(10);
        $duo=Db::name('problem')->where(['cid'=>$cid,'kind'=>2])->paginate(10);
        $pan=Db::name('problem')->where(['cid'=>$cid,'kind'=>3])->paginate(10);
        $tian=Db::name('problem')->where(['cid'=>$cid,'kind'=>4])->paginate(10);
        $jian=Db::name('problem')->where(['cid'=>$cid,'kind'=>5])->paginate(10);
        $this->assign([
            'cid'=>$cid,
            'dan'=>$dan,
            'duo'=>$duo,
            'pan'=>$pan,
            'tian'=>$tian,
            'jian'=>$jian
            ]);
        return $this->fetch('shijuan/tmshow');        
    }

    public function a_tk_show_index1()
    {
        $cid=Session::get('acid');
        $dan=Db::name('problem')->where(['cid'=>$cid,'kind'=>1])->paginate(10);
        $duo=Db::name('problem')->where(['cid'=>$cid,'kind'=>2])->paginate(10);
        $pan=Db::name('problem')->where(['cid'=>$cid,'kind'=>3])->paginate(10);
        $tian=Db::name('problem')->where(['cid'=>$cid,'kind'=>4])->paginate(10);
        $jian=Db::name('problem')->where(['cid'=>$cid,'kind'=>5])->paginate(10);
        $this->assign([
            'cid'=>$cid,
            'dan'=>$dan,
            'duo'=>$duo,
            'pan'=>$pan,
            'tian'=>$tian,
            'jian'=>$jian
            ]);
        return $this->fetch('shijuan/atmshow');        
    }
}