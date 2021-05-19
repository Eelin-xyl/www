<?php
namespace app\index\controller;
use think\Controller;
use think\facade\Session;
use think\Db;
use think\facade\Request;

class Admin extends Controller
{

    // 关于老师模块
    public function teacher_delete()
    {
          $user=Request::param('user');
           if ($user) {
            $t=Db::name('teacher')->where('t_user',$user)->find();
            Db::name('teacher')->where('t_user',$user)->delete();
            Db::name('admin')->where('user',$user)->delete();
            Db::name('course')->where('tid',$t)->update(['tid'=>'','teacher'=>$t]);
            $this->success('删除成功',url('index/index/admin_index'));               
           }else{
            $this->error('删除失败');
           }
    }




    public function teacher_add()
    {
        $user=Request::param('user');
        $username=Request::param('username');
        $password=Request::param('password');
        $sex=Request::param('sex');
        $sf='teacher';
        $file = request()->file('image');
        if($file){
            $info = $file->move( 'static/index/pot');
            if($info){
               $pic="/index/pot/".$info->getSaveName(); 
           }else{
            echo $file->getError();
        }
        }else{
            $pic="/index/pot/默认.png";
        }
        if($user&&$password&&$username&&$sf&&$sex){
        $result=Db::name('admin')
        ->where('user',$user)->find();
                if ($result) {
                  return $this->error('用户名以存在,请重新注册！');
                }else{
                        Db::name('admin')->insert(['uid'=>'','user'=>$user,'username'=>$username,'password'=>$password,'pic'=>$pic,'identity'=>$sf]);
                        Db::name('teacher')->insert(['tid'=>'','t_user'=>$user,'teacher'=>$username,'t_password'=>$password,'pic'=>$pic,'t_sex'=>$sex]);
                        $this->success('注册成功!',url('index/index/admin_index'));

                }
            }
        else{
             $this->error( '注册失败,请填写完整信息！');
        }

    }
    

    public function teacher_update()
    {
        // $data=input('post.');
        // var_dump($data);die;
        $user=Session::get('user');
        $username=Request::param('username');
        $sex=Request::param('sex');
        $course=Request::param('course');
        $file=request()->file('image');  
        var_dump($file);die;         
        if ($file) {
            $info = $file->move('static/index/pot');
            if ($info) {
               $pic="/index/pot/".$info->getSaveName(); 
            } else {
                echo $file->getError();
            }
        } else {
            $pic="/index/pot/默认.png";
        }
        if ($username&&$pic&&$sex) {
            Db::name('teacher')->where('t_user',$user)->data(['teacher'=>$username,'t_sex'=>$sex,'cname'=>$course,'pic'=>$pic])->update();
            $this->success('修改成功!',url('index/admin_index'));
        } else {
            $this->error('修改出错!');
        }

    }


    public function teacher_add_index()
    {
        return $this->fetch('adm/teacheradd');
    }
    
    public function teacher_update_index()
    {
        $user=Request::param('user');
        Session::set('user',$user);
        $data=Db::name('teacher')->where('t_user',$user)->find();
        $this->assign('data',$data);
        return $this->fetch('adm/teacherupdata');

    }


    
    // 关于学生的模块
        public function student_delete()
    {
          $user=Request::param('user');
           if ($user) {
            Db::name('student')->where('s_user',$user)->delete();
            Db::name('admin')->where('user',$user)->delete();
            $this->success('删除成功',url('index/index/admin_index'));               
           }else{
            $this->error('删除失败');
           }
    }




    public function student_add()
    {
        $user=Request::param('user');
        $username=Request::param('username');
        $password=Request::param('password');
        $sex=Request::param('sex');
        $sf='student';
        $file = request()->file('image');
        if($file){
            $info = $file->move( 'static/index/pot');
            if($info){
               $pic="/index/pot/".$info->getSaveName(); 
           }else{
            echo $file->getError();
        }
        }else{
        $pic="/index/pot/默认.png";
        }
        if($user&&$password&&$username&&$sf&&$sex){
        $result=Db::name('admin')
        ->where('user',$user)->find();
                if ($result) {
                  return $this->error('用户名以存在,请重新添加！');
                }else{
                        Db::name('admin')->insert(['uid'=>'','user'=>$user,'username'=>$username,'password'=>$password,'pic'=>$pic,'identity'=>$sf]);
                        Db::name('student')->insert(['sid'=>'','s_user'=>$user,'student'=>$username,'s_password'=>$password,'pic'=>$pic,'s_sex'=>$sex]);
                        $this->success('添加成功!',url('index/index/admin_index'));

                }
            }
        else{
             $this->error( '添加失败,请填写完整信息！');
        }

    }
    

    public function student_update()
    {
        // $data=input('post.');
        // var_dump($data);die;
        $user=Session::get('user');
        $username=Request::param('username');
        $sex=Request::param('sex');
        $course=Request::param('course');
        $file=request()->file('image');  
        var_dump($file);die  ;      
        if ($file) {
            $info = $file->move('static/index/pot');
            if ($info) {
               $pic="/index/pot/".$info->getSaveName(); 
            } else {
                echo $file->getError();
            }
        } else {
            $pic="/index/pot/默认.png";
        }
        if ($username&&$pic&&$sex) {
            Db::name('student')->where('s_user',$user)->data(['student'=>$username,'s_sex'=>$sex,'cname'=>$course,'pic'=>$pic])->update();
            $this->success('修改成功!',url('index/admin_index'));
        } else {
            $this->error('修改出错!');
        }

    }


    
    public function student_update_index()
    {
        $user=Request::param('user');
        Session::set('user',$user);
        $data=Db::name('student')->where('s_user',$user)->find();
        $this->assign('data',$data);
        return $this->fetch('adm/studentupdata');
    }


    public function student_add_index()
    {
        return $this->fetch('adm/studentadd');
    }


     // 课程的增删改查
    public function course_add()
    {
        $cname=Request::param('cname');
        $tid=Request::param('tid');
        if ($cname&&$tid) {
            $d=Db::name('course')->where('cname',$cname)->find();
            if ($d) {
                $this->error('课程以存在，请重新输入!');
            }else{
                $t=Db::name('teacher')->where('tid',$tid)->find();
                Db::name('course')
                ->insert(['cid'=> '' ,'cname'=>$cname,'tid'=>$tid,'teacher'=>$t['teacher']]);
                $c=Db::name('course')->where('cname',$cname)->find();
                $id=$t['cid'].$c['cid'].',';
                Db::name('teacher')->where('tid',$tid)->data(['cid'=>$id,'cname'=>$cname])->update();
                $this->success('课程添加成功!',url('index/index/admin_index'));
            }
        }else{
            $this->error('请输入所有信息!');
        }
    }


    public function course_delete()
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
            $this->success('删除成功',url('index/index/admin_index'));               
        }else{
            $this->error('删除失败');
        }
    }



    public function course_s_add()
    {
        $id=Session::get('cid');
        $sid=Request::param('sid');
        if($id){
            $c=Db::name('course')->where('cid',$id)->find();
            $s=$c['sid'].$sid.',';
            $n=$c['number']+1;
            Db::name('course')->where('cid',$id)->data(['sid'=>$s,'number'=>$n])->update();
            $stu=Db::name('student')->where('sid',$sid)->find();
            $x=$stu['cid'].$id.',';
            Db::name('student')->where('sid',$sid)->update(['cid'=>$x]);
            $this->success('添加成功!',url('admin/course_update_index'));
        }else{
            $this->error('找不到参数!');
        }


    }


    public function course_s_delete()
    {
        $id=Session::param('id');
        if($id){
            $c=Db::name('course')->where('sid', 'like', '%' . $id . '%')->find();
            if($c){
                $n=$c['number']-1;
                $cour=explode(',',$c['sid']);
                $k=array_search($id,$cour);
                array_splice($cour,$k,1);
                $sid=implode(',',$cour);
                Db::name('course')->where('cid',$c['cid'])->data(['sid'=>$sid,'number'=>$n])->update();
                $s=Db::name('student')->where('sid',$id)->find();
                $stu=explode(',',$s['cid']);
                $k1=array_search($c['cid'],$stu);
                array_splice($stu,$k1,1);
                $cid=implode(',',$stu);
                Db::name('student')->where('sid',$id)->update(['cid'=>$cid]);
                $this->error('成功删除！');
            }else
            {
                $this->error('数据库查询错误!');
            }
        }else{
            $this->error('未获取参数!');
        }

    }


    public function course_t_update()
    {
        $cname=Request::param('cname');
        $teacher=Request::param('teacher');
        $cid=Session::get('cid');
        $tid=Session::get('tid');
        if ($cname&&$teacher&&$cid&&$tid) {
            $u=Db::name('teacher')->where('tid',$tid)->find();
            $i=explode(',',$u['cid']);
            $i2=array_search($cid,$i);
            array_splice($i,$i2,1);
            $id=implode(',',$i);
            Db::name('teacher')->where('tid',$tid)->update(['cid'=>$id]);
            $t=Db::name('teacher')->where('teacher',$teacher)->find();
            $nid=$t['cid'].$cid.',';
            Db::name('course')->where('cid',$cid)->data(['cname'=>$cname,'teacher'=>$teacher])->update();
            Db::name('teacher')->where('cid', 'like', '%' . $cid . '%')->data(['cname'=>$cname])->update();
            Db::name('teacher')->where('teacher',$teacher)->update(['cid'=>$nid]);
            Db::name('student')->where('cid', 'like', '%' . $cid . '%')->update(['cname'=>$cname]);
            $this->success('修改成功!',url('admin/course_update_index'));
        }else{
            $this->error('未知错误!');
        }
    }



    public function course_add_index()
    {
        return $this->fetch('adm/courseadd');
    }

    public function course_update_index()
    {
        $cid=Request::param('cid');
        Session::set('cid',$cid);
        $tea=Db::name('teacher')->where('cid',$cid)->find();
        $stu=Db::name('student')->where('cid', 'like', '%' . $cid . '%')->paginate(10);
        Session::set('tid',$tea['tid']);
        $this->assign([
            'tea'=>$tea,
            'stu'=>$stu,
            ]);
        return $this->fetch('adm/courseupdate');
    }

    public function course_s_add_index()
    {
        return $this->fetch('adm/coursesadd');
    }

     //成绩管理

    public function shijuan_cj_xg()
    {
        $aid=Session::get('aid');
        $fs=Request::param('fs');
        if ($aid&&$fs) {
            Db::name('results')->where('aid',$aid)->update('results',$fs);
            Db::name('answer')->where('aid',$aid)->update('fs',$fs);
            $this->success('修改成功',url('shijuan/shijuan_a_xscj_index'));
        }else{
            $this->error('参数获取失败，请输入修改成绩!');
        }
    }

    public function shijuan_cj_xg_index()
    {
        $aid=Request::param('aid');
        Session::set('aid',$aid);
        $a=Db::name('results')->where('aid',$aid)->find();  
        $this->assign([
            'a'=>$a,
            'aid'=>$aid
            ]);
        return $this->fetch('adm/cjxg');
    }

    public function shijuan_dj_ck_index()
    {
        $aid=Request::param('aid');
        $a=Db::name('answer')->where('aid',$aid)->find();
        $x=explode(',',$a['answer']);
        $x2=array_search('',$x);
        array_splice($x,$x2,1);
        $this->assign([
            'aid'=>$aid,
            'daan'=>$x,
            ]); 
        return $this->fetch('adm/djck');               
    }
}