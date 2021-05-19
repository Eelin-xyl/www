<?php
namespace app\admin\validate;
use think\Validate;
class NodeValidates  extends  Validate
{
    protected $rule = [];//规则
    protected $message = [];//错误信息
    public function __construct($name) {
        parent::__construct();
        switch ($name) {
            case 'addNode':
                $this->rule = [
                    'pid'     => 'require|alphaNum',
                    'level'   => 'require|/^[123]$/',
                    'name'    => 'require|alphaDash',
                    'title'   => 'require',
                    'sort'    => 'require|number',
                    'status'  => 'require|/^[01]$/',
                ];
                $this->message = [
                    'pid.require'      => '上传数据丢失',
                    'level.require'    => '上传数据丢失',
                    'name.require'     => '名称不能为空',
                    'title.require'    => '描述不能为空',
                    'sort.require'     => '排序不能为空',
                    'status.require'   => '上传数据丢失',
                    'pid.alphaNum'     => '上传数据错误',
                    'level./^[123]$/'  => '上传数据错误',
                    'name.alphaDash'   => '名称为字母和数字，下划线 _ 及破折号 - ',
                    'sort.number'      => '排序只能为数字',
                    'status./^[01]&/'  => '上传数据错误'
                ];
                break;
            case 'upNode':
                $this->rule = [
                    'id'      => 'require|alphaNum',
                    'name'    => 'require|alphaDash',
                    'title'   => 'require',
                    'sort'    => 'require|number',
                    'status'  => 'require|/^[01]$/',
                ];
                $this->message = [
                    'id.require'       => '上传数据丢失',
                    'name.require'     => '名称不能为空',
                    'title.require'    => '描述不能为空',
                    'sort.require'     => '排序不能为空',
                    'status.require'   => '上传数据丢失',
                    'id.alphaNum'      => '上传数据错误',
                    'name.alphaDash'   => '名称为字母和数字，下划线 _ 及破折号 - ',
                    'sort.number'      => '排序只能为数字',
                    'status./^[01]&/'  => '上传数据错误'
                ];
                break;
            case 'setAccess':
                $this->rule = [
                    'id'        => 'require|alphaNum',
                    'access'    => 'require|array'
                ];
                $this->message = [
                    'id.require'         => '上传数据丢失',
                    'access.require'     => '上传数据丢失',
                    'access.array'       => '上传数据错误',
                    'id.alphaNum'        => '上传数据错误'
                ];
                break;
            default:
                # code...
                break;
            
        }
    }
}