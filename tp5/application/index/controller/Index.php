<?php
namespace app\index\controller;
use think\Controller;
use think\request;
use think\Session;
use think\Db;

class Index  extends Controller
{
    public function index()
    {
        return $this->fetch();
    }
}

?>