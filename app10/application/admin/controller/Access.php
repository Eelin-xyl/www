<?php
namespace app\admin\controller;

class Access extends Common {
    public function __construct() {
        parent::__construct();
    }
    /*
     * 根据$id显示角色权限
     * 
     */
    public function index() {
        $data = $this->receive_data;
        $access = model('Access');
        if (request()->post()) {
            $result = $access->setAccess($data);
            if ($result['state']) {
                $this->success('操作成功');
            } else {
                $this->error($result['data']);
            }
        }
        $result = $access->getUserAccess($data['id']);
        $this->assign('id', $data['id']);
        $this->assign('data', []);
        if ($result['state']) {
            $this->assign('data', $result['data']);
        }
        return $this->fetch('', ['title' => '角色权限配置']);
    }

}
