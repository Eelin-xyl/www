<?php
namespace app\admin\validate;
use think\Validate;
class RoleValidates  extends  Validate
{
    protected $rule = [];//规则
    protected $message = [];//错误信息
    public function __construct($name) {
        parent::__construct();
        switch ($name) {
            case 'addRole':
                $this->rule = [
                    'name'     => 'require|alphaNum',
                    'remark'   => 'require',
                    'status'  => 'require|/^[01]$/'
                ];
                $this->message = [
                    'remark.require'   => '上传数据丢失',
                    'name.require'     => '名称不能为空',
                    'status.require'   => '上传数据丢失',
                    'name.alphaNum'    => '名称为英文或数字',
                    'status./^[01]&/'  => '上传数据错误'
                ];
                break;
                case 'upRole':
                $this->rule = [
                    'id'      => 'require|alphaNum',
                    'name'    => 'require|alphaNum',
                    'remark'  => 'require',
                    'status'  => 'require|/^[01]$/'
                ];
                $this->message = [
                    'id.require'       => '上传数据丢失',
                    'name.require'     => '名称不能为空',
                    'remark.require'   => '描述不能为空',
                    'status.require'   => '上传数据丢失',
                    'id.alphaNum'      => '上传数据错误',
                    'name.alphaNum'    => '名称为英文或数字',
                    'status./^[01]&/'  => '上传数据错误'
                ];
                break;
            case 'upURole':
                $this->rule = [
                    'id'      => 'require|alphaNum',
                    'role_id' => 'array'
                ];
                $this->message = [
                    'id.require'          => '上传数据错误1',
                    'id.alphaNum'         => '上传数据错误2',
                    'role_id.array'       => '上传数据错误3'
                ];
                break;
            default:
                # code...
                break;
            
        }
    }
}