<?php
namespace app\admin\controller;

use app\admin\model\User;

class Login extends Common { 
    public function __construct() {
        parent::__construct();
    }

    public function login() {
        return $this->fetch('', ['title'=>'用户登录']);
    }
    public function register() {
        return $this->fetch('reg', ['title'=>'用户注册']);
    }
    public function test() {
        return $this->fetch('test', ['title'=>'用户注册']);
    }
    //用户登录
    public function process() {
        $data = $this->receive_data;
        //用户登录验证
        $user = new User();
        $is_true = $user->login($data);
        if (!$is_true['state']) {
            //清除session
            session(null);
            $this->error($is_true['data']);
        }
        $this->success('登录成功', url('Index/index'));
    }
    //用户退出
    public function loginOut() {
        session(null);
        $this->success('退出成功',url('admin/Login/login'));
    }
    //忘记密码
    public function getBackPwd() {
        echo "忘记";
    }
    //重置密码
    public function resetPwd() {

    }
    public function reg() {
        if (input('post.')) {
            $data = $_POST;
            $user = new User;
            $register = $user->reg($data);
            if ($user->allowField(true)->validate(true)->save($register)) {
                return $this->success('注册成功请等待审核', url('Login/login'));

            } else {
                return $this->error($user->getError());
            }
        }
    }
    public function getAreaJ(){  //省级
        $where['pid'] = 1;
        $area=Model('Area')->where($where)->select();
        if($area) {
            $temp="<option selected='shiqu'>省级</option>";
        }else{
            $temp=" ";
        }
        foreach ($area as $key=>$value)
        {
            $temp.="<option value='".$area[$key]['id']."'>".$area[$key]['name']."</option>";
        }//dump($temp);exit();
       return json($temp);
    }

    public function getAreaS(){  //市级


            $where['pid'] = $_POST['pid'];

        $area=Model('Area')->where($where)->select();
        if($area) {
            $temp="<option selected='shiqu'>请选择市县</option>";
        }else{
            $temp=" ";
        }
        foreach ($area as $key=>$value)
        {
            $temp.="<option value='".$area[$key]['id']."'>".$area[$key]['name']."</option>";

        }
        return json($temp);
    }
    public function getAreaQ(){  // 区级

            $where['pid'] = $_POST['pid'];

        $area=Model('Area')->where($where)->select();
        if($area) {
            $temp = "<select name='areaQ' id='AreaQ'>";
            $temp.="<option selected='qu'>请选择区</option>";
            foreach ($area as $key=>$value)
            {
                $temp.="<option value='".$area[$key]['id']."'>".$area[$key]['name']."</option>";
            }
            $temp.="</select>";
        }else{
            $temp=" ";
        }

       return json($temp);
    }

}