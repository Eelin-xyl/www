<?php
namespace app\admin\controller;

class Index extends Common {
    public function __construct() {
        parent::__construct();
    }

    public function index() {
        if (!strstr($_SERVER['REQUEST_URI'], '/index.php')) {
            \think\Url::root('index.php');
        }
        return $this->fetch('', ['title' => '管理']);
    }
}
