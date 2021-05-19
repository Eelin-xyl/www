<?php
namespace app\admin\controller;

class Role extends Common {
    public function __construct() {
        parent::__construct();
    }
    /*
     * 角色列表
     */
    public function index() {
        $role = model('Role');
        $info = $role->getAllRole();
        $this->assign('data', []);
        if ($info['state']) {
            $this->assign('data', $info['data']);
        }
        $this->assign('num', 1);
        return $this->fetch('', ['title' => '角色管理']);
    }
    /*
     * 添加角色
     */
    public function add() {
        $data = $this->receive_data;
        if (request()->post()) {
            $role = model('Role');
            $result = $role->addRole($data);
            if ($result['state']) {
                $this->success('添加成功');
            } else {
                $his->error($result['data']);
            }
            exit;
        }
        return $this->fetch('', ['title' => '角色添加']);
    }
    /*
     * 编辑角色信息
     */
    public function edit() {
        $data = $this->receive_data;
        $role = model('Role');
        if (request()->post()) {
         //   echo "<pre>";pe($data);
            $result = $role->upRole($data);
            if ($result['state']) {
                $this->success('编辑成功');
            } else {
                $this->error($result['data']);
            }
            exit;
        }
        $role_info = $role->getInfoFId($data['id']);
        $this->assign('data', []);
        if ($role_info['state']) {
            $this->assign('data', $role_info['data']);
        }
        return $this->fetch('', ['title' => '角色编辑']);
    }
    /*
     * 
     */

}