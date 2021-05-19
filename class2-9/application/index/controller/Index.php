<?php
namespace app\index\controller;
use think\Controller;
use think\facade\Session;
use think\Db;
use think\facade\Request;

class Index extends Controller
{
    public function index()
    {
/*      $username=Session::get('username');
        $this->assign('username',$username);
 */
        return $this->fetch();
    }



    public function login()
    {
        // if (request()->isPost()) {
            $user=Request::param('user');
            $password=Request::param('password');
            if($user&&$password){
            $result=Db::name('admin')
             ->where('user',$user)
             ->where('password',$password)
             ->find();
            if ($result) {
                Session::set('user',$user);
                Session::set('password',$password);
                if ($result['identity']=='student') {
                    $s=Db::name('student')->where('s_user',$user)->find();
                    Session::set('sid',$s['sid']);                    
                    $this->success('欢迎同学登陆',url('student_index'));
                }elseif ($result['identity']=='teacher') {
                    $t=Db::name('teacher')->where('t_user',$user)->find();
                    Session::set('tid',$t['tid']);
                    $this->success('欢迎老师登陆',url('teacher_index'));
                }elseif ($result['identity']== '1' ) {
                    $this->success('欢迎管理员',url('admin_index'));
                }else{
                    $this->error('未知身份');
                }
            }else
                 {
                    $this->error('账号或者密码输入错误');
                 }
            }else{
                $this->error('请输入帐号密码');
            }
    }




    public function register()
    {
        $user=Request::param('user');
    	$username=Request::param('username');
    	$password=Request::param('password');
        $password1=Request::param('password1');
        $sex=Request::param('sex');
        $sf=Request::param('type');
        $file = request()->file();
        if(isset($file['image'])){
            $info = $file['image']->move( 'static/index/pot');
            if($info){
               $pic="/index/pot/".$info->getSaveName(); 
           }else{
            echo $file->getError();
        }
        }else{
        $pic="/index/pot/默认.png";
        }
        if($password==$password1){
        if($user&&$password&&$username&&$sf&&$sex){
    	$result=Db::name('admin')
    	->where('user',$user)->find();
    	        if ($result) {
    		      return $this->error('用户名以存在,请重新注册！',url('register'));
    	        }
    	        elseif ($sf=='student') {
                        Db::name('admin')->insert(['uid'=>'','user'=>$user,'username'=>$username,'password'=>$password,'pic'=>$pic,'identity'=>$sf]);
                        Db::name('student')->insert(['sid'=>'','s_user'=>$user,'student'=>$username,'s_password'=>$password,'pic'=>$pic,'s_sex'=>$sex]);
                        $this->success('注册成功!',url('index'));
                }elseif ($sf=='teacher') {
                        Db::name('admin')->insert(['uid'=>'','user'=>$user,'username'=>$username,'password'=>$password,'pic'=>$pic,'identity'=>$sf]);
                        Db::name('teacher')->insert(['tid'=>'','t_user'=>$user,'teacher'=>$username,'t_password'=>$password,'pic'=>$pic,'t_sex'=>$sex]);
                        $this->success('注册成功!',url('index'));

                }
            }
            else{
                $this->error( '注册失败,请填写完整信息！');
            }
        }else{
          $this->error( '注册失败,密码不一致！');
        }

    }



    public function register_index()
    {
        return $this->fetch('reg/register');
    }



        public function admin_index()
    {
        $tea=Db::name('teacher')->paginate(10);
        $stu=Db::name('student')->paginate(10);
        $cour=Db::name('course')->paginate(10);
        $sj=Db::name('shijuan')->paginate(10);
        $re=Db::field('r.results,s.student,r.shijuan,r.kid')->where('r.sid=s.sid')->table('results r,student s')->paginate(10);
        $this->assign([
            'tea'=>$tea,
            'stu'=>$stu,
            'cour'=>$cour,
            'sj'=>$sj,
            're'=>$re
            ]);
        return $this->fetch('adm/admin');

    }

    public function teacher_index()
    {
        $tid=Session::get('tid');
        $n=Db::name('teacher')->where('tid',$tid)->find();
        $cour=Db::name('course')->where('tid',$tid)->paginate(10);
        $sj=Db::name('shijuan')->where('tid',$tid)->paginate(10);
        $this->assign([
            'cour'=>$cour,
            'sj'=>$sj,
            'tid'=>$tid,
            'n'=>$n
            ]);
        return $this->fetch('tea/teacher');
    }


    public function student_index()
    {
        $sid=Session::get('sid');
        $n=Db::name('student')->where('sid',$sid)->find();
        $c=Db::name('course')->where('sid','like','%'.$sid.'%')->find();
        $cour=Db::name('course')->where('sid','like','%'.$sid.'%')->paginate(10);
        $re=Db::field('r.results,s.shijuan,r.kid')->where('r.kid=s.kid')->table('results r,shijuan s')->paginate(10);
        $sj=Db::name('shijuan')->where('cid',$c['cid'])->paginate(10);
        $this->assign([
            'cour'=>$cour,
            'sid'=>$sid,
            'sj'=>$sj,
            're'=>$re,
            'n'=>$n
            ]);
        return $this->fetch('stu/student');
    }


    public  function out_login_index()
    {
        return $this->fetch('index');
    }

}
