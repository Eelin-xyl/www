<?php
namespace app\index\controller;
use think\Controller;
use think\Session;
use think\request;

class Index extends Controller
{
    public function index()
    {
        for ($i = 1; $i <= 81; $i++)
        {
        	$num[$i] = '*';
        }
        Session::set('num', $num);
        return $this->fetch();
    }
}
