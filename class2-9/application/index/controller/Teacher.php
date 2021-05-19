<?php
namespace app\index\controller;
use think\Controller;
use think\facade\Session;
use think\Db;
use think\facade\Request;

class Teacher extends Controller
{

    public function teacher_update()
    {
        $tid=Session::get('tid');
        $username=Request::param('username');
        $file = request()->file();
        if(isset($file['image'])){
            $info = $file['image']->move( 'static/index/pot');
            if($info){
               $pic="/index/pot/".$info->getSaveName(); 
           }else{
            echo $file->getError();
        }
        }else{
        $p=Db::name('teacher')->where('tid',$tid)->find();
        $pic=$p['pic'];
        }
        if($pic){
            Db::name('teacher')->where('tid',$tid)->update(['teacher'=>$username,'pic'=>$pic]);
            $this->success('修改成功!',url('teacher_update_index'));
        }else{
            $this->error('图片获取失败');
        }   
    }


    
    public function  teacher_course_add()
    {
    	$tid=Session::get('tid');
    	$cname=Request::param('cname');
    	if ($tid&&$cname) {
    		$d=Db::name('course')->where('cname',$cname)->find();
    		if ($d) {
    			$this->error('课程以存在，请重新输入!');
    		}else{
        		$t=Db::name('teacher')->where('tid',$tid)->find();
	    		Db::name('course')->insert(['cid'=>'','cname'=>$cname,'tid'=>$tid,'teacher'=>$t['teacher']]);
	    		$c=Db::name('course')->where('cname',$cname)->find();
	    		$cid=$t['cid'].$c['cid'].',';
	    		Db::name('teacher')->where('tid',$tid)->update(['cid'=>$cid]);
	    		$this->success('添加成功',url('index/teacher_index'));
    		}
    	}
    }

    public function teacher_course_add_index()
    {
    	$tid=Request::param('tid');
    	Session::set('tid',$tid);
    	$this->assign('tid',$tid);
    	return $this->fetch('tea/cadd');
    }

    public function teacher_course_delete()
    {
    	$id=Request::param('cid');
    	if ($id) {
    		Db::name('course')->where('cid',$id)->delete();
            $t1=Db::name('teacher')->where('cid','like','%'. $id.'%')->find();
            $c=explode(',', $t1['cid']);
            $c2=array_search($id ,$c);
            array_splice($c,$c2,1);
            $cid=implode(",",$c);
            Db::name('teacher')->where('tid',$t1['tid'])->data(['cid'=>$cid])->update();
            $c1=Db::name('student')->where('cid','like','%'. $id.'%')->find();
            //字符串转化为数组
            $s=explode(',', $c1['cid']);
            //删除数组中的cid  
            $s2=array_search($id ,$s);
            array_splice($s,$s2,1);
            //数组重新转化为字符串
            $sid=implode(",",$s);
            Db::name('student')->where('sid',$c1['sid'])->data(['cid'=>$sid])->update();
            $this->success('删除成功',url('index/index/teacher_index'));
    	}else{
    		$this->error('参数获取失败!');
    	}
    }


    public function shijuan_fs_add()
    {
        $fs=Request::param('fs');
        $aid=Session::get('aid');
        if ($fs&&$aid) {
            Db::name('answer')->where('aid',$aid)->update(['fs'=>$fs,'gj'=>'2']);
            $r=Db::name('answer')->where('aid',$aid)->find();
            $s=Db::name('shijuan')->where('kid',$r['kid'])->find();
            Db::name('results')->insert(['kid'=>$r['kid'],'sid'=>$r['sid'],'results'=>$fs,'cid'=>$s['cid'],'aid'=>$aid]);
            $this->success('改卷成功',url('teacher_shijuan_show_index1'));
        }else{
            $this->error('参数获取失败!');
        }
    }



    public function teacher_shijuan_show_index()
    {
        $kid=Request::param('kid');
        Session::set('k',$kid);
        $sj=Db::name('answer')->where(['kid'=>$kid,'gj'=>'1'])->paginate(10);
        $this->assign('sj',$sj);
        return $this->fetch('tea/yjsj');
    }

    public function teacher_shijuan_show_index1()
    {
        $kid=Session::get('k');
        $sj=Db::name('answer')->where(['kid'=>$kid,'gj'=>'1'])->paginate(10);
        $this->assign('sj',$sj);
        return $this->fetch('tea/yjsj');
    }

    public function teacher_shijuan_pg_index()
    {
        $aid=Request::param('aid');
        Session::set('aid',$aid);
        $a=Db::name('answer')->where('aid',$aid)->find();
        $x=explode(',',$a['answer']);
        $x2=array_search('',$x);
        array_splice($x,$x2,1);
        $this->assign([
            'aid'=>$aid,
            'daan'=>$x,
            ]); 
        return $this->fetch('tea/gaijuan');               
    }

    public function teacher_course_content_index()
    {
    	$cid=Request::param('cid');
    	Session::set('cid',$cid);
    	if ($cid) {
    		$s=Db::name('student')->where('cid','like','%'.$cid.'%')->paginate(10);
    		$this->assign('stu',$s);
    		return $this->fetch('tea/content');
    	}else{
            $this->error('参数获取失败!');
        }
    }


    public function kaoshi_cj_index()
    {
        $kid=Request::param('kid');
        $re=Db::name('results')->where('kid',$kid)->paginate(10);
        $this->assign('re',$re);
        return $this->fetch('tea/kscj');
    }



    public function teacher_update_index()
    {
        $tid=Session::get('tid');
        $t=Db::name('teacher')->where('tid',$tid)->find();
        $this->assign(['t'=>$t]);
        return $this->fetch('tea/shezhi');
    }
    

}


