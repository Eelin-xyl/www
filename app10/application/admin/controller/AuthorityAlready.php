<?php
namespace app\admin\controller;

class AuthorityAlready extends Common {
    public function __construct() {
        parent::__construct();
    }

    /*
        查看报告
     */
    public function show() {
        return $this->fetch('', ['title' => '查看报告']);
    }

    /*
        待评估
     */
    public function appliing() {
        $info = db('access')->paginate(2);
        $this->assign('info', $info);
        return $this->fetch('', ['title' => '待评估列表']);
    }

    /*
        已评估
     */
    public function applyed() {
         $info = db('access')->paginate(2);
        $this->assign('info', $info);
        return $this->fetch('', ['title' => '申请评估列表']);
    }

    /*
     * 评估申请详情
    */
    public function startapp() {
        return $this->fetch('', ['title' => '评估申请详情']);
    }


}
