<?php
namespace app\admin\controller;

class Node extends Common {
    public function __construct() {
        parent::__construct();
    }
    /*
     * 节点管理----显示各节点
     */
    public function index() {
        $node = model('Node');
        $node_info = $node->getNodeInfo();
        $this->assign('data', []);
        if ($node_info['state']) {
            $this->assign('data', $node_info['data']);
        }
        return $this->fetch('', ['title' => '权限管理']);
    }
    /*
     * 添加节点跳转
     */
    public function add() {
        $data = $this->receive_data;
        $this->assign('pid', $data['pid']);
        $this->assign('level', $data['level']);
        if (request()->post()) {
            $node = model('Node');
            $result = $node->addNode($data);
            if ($result['state']) {
                $this->success('添加成功');
            } else {
                $this->error('添加失败，' . $result['data']);
            }
        }
        else{
            $node = model('Node');
            $result = $node->sort();
            $this->assign('sort',$result);
        }
        return $this->fetch('', ['title' => '添加节点']);
    }
    /*
     * 编辑节点
     */
    public function edit() {
        $data = $this->receive_data;
        $node = model('Node');
        if (request()->post()) {
            $result = $node->edNode($data);
            if ($result['state']) {
                $this->success('更新成功');
            } else {
                $this->error('更新失败，' . $result['data']);
            }
            exit;
        }
        $info = $node->search($data['id']);
        $this->assign('data', []);
        if ($info['state']) {
            $this->assign('data', $info['data']);
        }
    //    echo "<pre>";pe($info['data']);
        return $this->fetch('', ['title' => '编辑节点']);
    }
    /*
     * 删除节点
     */
    public function delete() {
        $data = $this->receive_data;
        $node = model('Node');
        $result = $node->delNode($data['id']);
        if ($result['state']) {
            $this->success('成功删除');
        } else {
            $this->error('删除失败');
        }
    }

}
