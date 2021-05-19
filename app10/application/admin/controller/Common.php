<?php
namespace app\admin\controller;
use think\Controller;

class Common extends \think\Controller {
    protected $receive_data;//接收前端上传的所有数据
    public function __construct() {
        parent::__construct();
        $Rbac = new \org\util\Rbac();
        //检验当前操作是否需要验证
        if ($Rbac::checkAccess()) {
            //判定是否登录
            $this->checkAdminLogin();
            //保存用户权限
            $Rbac::saveAccessList(session('user.id'));
            //判定用户权限
     /*       if (!$Rbac::AccessDecision(session('user.id'))) {
                session(null);
                $this->error("没有权限");
            }*/
        }
        //处理前端传过来的数据
        $this->dealwithData();
        $this->assign('list', []);
    }
    /*
     * 用户登录验证
     */
    public function checkAdminLogin(){
        if (!session('user.id')) {
            \think\Url::root($_SERVER['SCRIPT_NAME']);
            $this->redirect('admin/Login/login');
        }
    }
    /*
     * 前端数据处理
     */
    public function dealwithData() {
        $this->receive_data = request()->param();
        $this->receive_data['request_ip'] = request()->ip();
    }
    

}