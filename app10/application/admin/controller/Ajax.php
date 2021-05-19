<?php
namespace app\admin\controller;
use think\Request;

class Ajax extends Common {
    public function __construct() {
        parent::__construct();
    }
    //自定义属性列表
    public function uploadFiles(){
        $files = request()->file();
        $i = 0;
        foreach($files['file'] as $k => $file){
            $info = $file->rule('md5')->validate(['size'=> 5242880,'ext'=>'jpg,png,jpeg,zip'])->move(ROOT_PATH . 'public' . DS . 'uploads');
            if($info){
                $piclist[$i]['name'] =$info->getInfo('name');
                $piclist[$i]['url'] = $info->getSaveName();
                $i++;
            } else {
                // 上传失败获取错误信息
                return json(return_data(0, $file->getInfo('name') . '图片错误：' . $file->getError()), 200, array('Content-Type' => 'text/html; charset=utf-8'));
            }
        }
        if (isset($piclist)) {
            return json(return_data(true, $piclist), 200, array('Content-Type' => 'text/html; charset=utf-8'));
        } else {
            return json(return_data(0, '上传失败'), 200, array('Content-Type' => 'text/html; charset=utf-8'));
        }
    }
}
