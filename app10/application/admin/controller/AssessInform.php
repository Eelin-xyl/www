<?php
namespace app\admin\controller;

class AssessInform extends Common {
    public function __construct() {
        parent::__construct();
    }

    /*
        规则列表
     */
    public function index() {
        return $this->fetch('', ['title' => '评估规则列表']);
    }



}
