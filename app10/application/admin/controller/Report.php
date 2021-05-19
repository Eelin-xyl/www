<?php
namespace app\admin\controller;

class Report extends Common {
    public function __construct() {
        parent::__construct();
    }

    /*
        查看报告
     */
    public function show() {
        return $this->fetch('', ['title' => '报告']);
    }



}
