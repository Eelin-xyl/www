<?php
namespace app\index\controller;
use think\Controller;
use think\facade\Session;
use think\Db;
use think\facade\Request;

class Student extends Controller
{
    public function student_course_add()
    {
    	$sid=Session::get('sid');
    	$cid=Request::param('cid');
    	if ($cid&&$sid) {
    		$c=Db::name('course')->where('cid',$cid)->find();
    		$s=$c['sid'].$sid.',';
    		Db::name('course')->where('cid',$cid)->update('sid',$s);
    		$this->success('选课成功!',url('student_course_add_index'));
       	}else{
       		$this->error('参数获取失败!');
       	}

    }

    public function student_update()
    {
        $sid=Session::get('sid');
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
            $p=Db::name('student')->where('sid',$sid)->find();
            $pic=$p['pic'];
        }
        if($pic){
            Db::name('student')->where('sid',$sid)->update(['student'=>$username,'pic'=>$pic]);
            $this->success('修改成功!',url('student_update_index'));
        }else{
            $this->error('图片获取失败');
        }   
    }


    public function shijuan_upload()
    {
    	$sid=Session::get('sid');
    	$kid=Session::get('kid');
    	$k=input('post.');
        $x='';
        foreach (array_reverse($k) as $value) {
            $x=$value.','.$x;
        }
        $x1=explode(',',$x);
        $x2=array_search('提交试卷',$x1);
        array_splice($x1,$x2,1);
        $s=implode(",",$x1);
        $r=Db::name('answer')->where(['kid'=>$kid,'sid'=>$sid])->find();
        if ($r) {
            $this->error('您已提交过该试卷，请勿再次提交!');
        }else{
            if ($k&&$sid&&$kid) {
                Db::name('answer')->insert(['kid'=>$kid,'sid'=>$sid,'answer'=>$s,'gj'=>1]);
                $this->success('试卷上传成功!',url('index/student_index'));
            }else{
                $this->error('参数获取失败！');
            
            }
        }
    }


    public function student_course_add_index()
    {
    	$cour=Db::name('course')->paginate(10);
    	$this->assign([
    		'cour'=>$cour
    		]);
    	return $this->fetch('stu/xuanke');
    }



    public function kaoshi_index()
    {
    	$sid=Session::get('sid');
    	$kid=Request::param('kid');
    	Session::set('kid',$kid);
    	$dan=Db::name('sjtm')->where(['kid'=>$kid,'kind'=>1])->select();
    	$duo=Db::name('sjtm')->where(['kid'=>$kid,'kind'=>2])->select();
    	$pan=Db::name('sjtm')->where(['kid'=>$kid,'kind'=>3])->select();
    	$tian=Db::name('sjtm')->where(['kid'=>$kid,'kind'=>4])->select();
    	$jian=Db::name('sjtm')->where(['kid'=>$kid,'kind'=>5])->select();
    	$this->assign([
    		'kid'=>$kid,
    		'dan'=>$dan,
    		'duo'=>$duo,
    		'pan'=>$pan,
    		'tian'=>$tian,
    		'jian'=>$jian
    		]);
    	return $this->fetch('kaoshi/kaoshi');
    }



    public function student_update_index()
    {
        $sid=Session::get('sid');
        $s=Db::name('student')->where('sid',$sid)->find();
        $this->assign(['s'=>$s]);
        return $this->fetch('stu/shezhi');
    }

}