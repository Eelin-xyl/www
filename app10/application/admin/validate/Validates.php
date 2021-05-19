<?php
namespace app\admin\validate;
use think\Validate;
class Validates  extends  Validate
{
    protected $rule = [];//规则
    protected $message = [];//错误信息
    public function __construct($name) {
        parent::__construct();
        switch ($name) {
            case 'org_add'://机构
                $this->rule = [
                    'org_code'   => 'require|max:150|alphaDash|unique:organization',
                    'pid'        => 'length:32,32|alphaNum',
                    'haschild'   => 'require|max:1|/^[0-1]$/',
                    'name'       => 'require|max:150',
                    //'area'       => 'require|number|max:6',
                    //'address'    => 'require|max:1024',
                    'summary'    => 'max:1024',
                    'type'       => 'require|/[0-9]$/|max:1'
                ];
                $this->message = [
                    'org_code.require'   => '机构编码缺失',
                    'org_code.unique'    => '该机构编码已经存在',
                    'haschild.require'   => '是否有子机构？',
                    'name.require'       => '机构名称缺失',
                    //'area.require'       => '机构所属行政区域缺失',
                    //'address.require'    => '机构地址缺失',
                    'type.require'       => '机构类型缺失',
                    'org_code.max'       => '机构编码过长',
                    'pid.length'         => '上传数据错误',//机构父id长度超过32
                    'haschild.max'       => '上传数据错误',//长度为1
                    'haschild./^[0-1]$'  => '上传数据错误',//只能是0或1  0：男|1：女
                    'name.max'           => '机构名过长',
                    //'area.max'           => '上传数据错误',//该处为地理区域行政编码,6位
                    //'address.max'        => '机构地址过长',//该处为位置信息，不能过长
                    'summary.max'        => '机构描述过长',
                    'type./[0-9]$/'      => '机构类型错误',
                    'type.max'           => '机构类型上传数据错误'//该处长度为1
                ];
                break;
            case 'org_up'://机构编辑['name' => '', 'area' => '', 'address' => '', 'summary' => '', 'type' => '']
                $this->rule = [
                    'id'      => 'require|length:32,32|alphaNum',//机构id
                    'name'    => 'require|max:150',
                    //'area'    => 'require|number|max:6',
                    //'address' => 'require|max:1024',
                    'summary' => 'max:10240',
                    'type'    => 'require|/[0-9]$/|max:1',
                ];
                $this->message = [
                    'id.require'      => '机构id为空',
                    'name.require'    => '机构名为空',
                    'area.require'    => '机构所属行政区域缺失',
                    'address.require' => '机构地址缺失',
                    'type.require'    => '机构类型错误',
                    'name.max'        => '机构名过长',
                    //'area.max'        => '上传数据错误',//该处为地理区域行政编码,6位
                    //'address.max'     => '机构地址过长',//该处为位置信息，不能过长
                    'summary.max'     => '机构描述过长',
                    'type./[0-9]$/'   => '机构类型错误',
                    'type.max'        => '机构类型上传数据错误'//该处长度为1
                ];
                break;

            case 'user_add':
                $this->rule = [
                    'username'     => 'require|max:1024',
                    'realname'     => 'require|max:1024',
                    'password'     => 'require|length:6,20',
                    'repassword'   => 'require|confirm:password',
          //          'idnumber'     => 'require|alphaNum|unique:user',//编号为字母和数字
                    'email'        => 'email',
                    'phone'        => '/^1[34578]\d{9}$/',
                    'orgid'        => 'require|alphaNum|length:32,32',
                    'address'      => 'max:1024',
     //               'picture'      => 'image',
                    'description'  => 'max:2048'
                ];
                $this->message = [
                    'phone./^1[34578]\d{9}$/' => '请填写正确的电话号码',
                    'orgid.require'           => '机构id不能为空',
                    'username.require'        => '用户名不能为空',
                    'password.require'        => '密码不能为空',
                    'repassword.require'      => '请再次输入密码不能为空',
                    'repassword.confirm'      => '两次输入密码不相同',
        //            'idnumber.require'        => '用户编码不能为空',
       //             'idnumber.alphaNum'       => '用户编码只能为英文和数字',
       //             'idnumber.unique'         => '该用户编码已存在',
                    'email.email'             => '请输入正确的email',
                    'orgid.alphaNum'          => '机构id不正确',
        ///            'picture.image'           => '请上传正确图片',
                    'description.max'         => '个人描述过长',
                    'address.max'             => '地址过长',
                    'orgid.require'           => '请传入机构id',
                ];
                break;
                //['resetpwd' => '1:重置密码，0:不重置', 'username' => '用户名', 'realname' => '用户真实名', 'password' => '密码', 'repassword' => '重复密码', 'email' => '', 'phone' => '', 'address' => '', 'description' => '用户名', 'id' => '', ]
            case 'user_up':
                $this->rule = [
                    'resetpwd'     => 'max:1|number',
                    'username'     => 'max:1024',
                    'realname'     => 'max:1024',
                    'password'     => 'requireIf:resetpwd,1',
                    'repassword'   => 'requireIf:resetpwd,1|confirm:password',
                    'email'        => 'email',
                    'phone'        => '/^1[34578]\d{9}$/',
                    'address'      => 'max:1024',
                    'description'  => 'max:2048',
                    'id'           => 'require|alphaNum|length:32,32'
                ];
                $this->message = [
                    'resetpwd.max'                    => '上传数据错误',
                    'resetpwd.number'                 => '上传数据错误',
                    'username.max'                    => '用户名过长',
                    'realname.max'                    => '用户真实姓名错误',
                    'password.requireIf'              => '输入密码',
                    'repassword.requireIf'            => '重新输入密码',
                    'repassword.confirm:password'     => '两次密码输入不一致',
                    'phone./^1[34578]\d{9}$/'         => '电话号码错误',
                    'address.max'                     => '地址过长',
                    'description.max'                 => '个人描述过长',
                    'id.require'                      => '上传数据错误',
                    'id.alphaNum'                     => '上传数据错误',
                    'id.length:32,32'                 => '上传数据错误',
                ];
                break;
            case 'login':
                $this->rule = [
                    'username' => 'require',
                    'password' => 'require'
                ];
                $this->message = [
                    'username.require' => ' 用户名不能为空',
                    'password.require' => '密码不能为空'
                ];
                break;
            case 'addNode':
                $this->rule = [
                    'pid'     => 'require|alphaNum',
                    'level'   => 'require|/^[123]$/',
                    'name'    => 'require|alpha',
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
                    'name.alpha'       => '名称为英文',
                    'sort.number'      => '排序只能为数字',
                    'status./^[01]&/'  => '上传数据错误'
                ];
                break;
            default:
                # code...
                break;
            
        }
    }
}