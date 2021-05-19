<?php
namespace app\admin\controller;

class RoleUser extends Common{
    public function __construct() {
        parent::__construct();
    }
    /*
     * 用户角色列表
     */
    public function index() {
        $role_user = model('RoleUser');
        $result = $role_user->getURoleInfo();
        $this->assign('data', []);
        if ($result['state']) {
            $this->assign('data', $result['data']);
        }
        $this->assign('num', 1);
        return $this->fetch('', ['title' => '用户角色分配列表']);
    }
    /*
     * 分配角色
     */
    public function edit() {
        $data = $this->receive_data;
        $role_user = model('RoleUser');
        $role = model('Role');
        if (request()->post()) {
            //编辑用户角色
            $result = $role_user->upURole($data);
            if ($result['state']) {
                $this->success('操作成功');
            } else {
                $this->error($result['data']);
            }
        }
        if (empty($data['id'])) {
            $this->error('错误操作');
        }
        $result = $role_user->getURoleInfo($data['id']);//获取当前用户的角色
        $roleAll = $role->getAllRole(true);
        $this->assign('role', []);
        $this->assign('data', []);
        if ($result['state']) {
            $this->assign('data', $result['data']);
        }
        if ($roleAll['state']) {
            $this->assign('role', $roleAll['data']);
        }
        return $this->fetch('', ['title' => '分配用户角色']);
    }
}