<?php
/**
 * Created by PhpStorm.
 * User: jyj
 * Date: 2018/6/24
 * Time: 17:15
 */
namespace app\admin\validate;
use think\Validate;
class User  extends  Validate
{
    protected $rule = [       //规则
        'username' => 'require|max:1024|unique:user',
        'realname' => 'require|max:1024',
        'password' => 'require|length:6,20',
        'password2' => 'require|confirm:password',
        'email' => 'email',
        'phone' => '/^1[34578]\d{9}$/',
        'address' => 'max:1024',
        'description' => 'max:2048',
        'area' => 'require'
    ];
    protected $message = [     //错误信息
        'phone./^1[34578]\d{9}$/' => '请填写正确的电话号码',
        'username.require' => '用户名不能为空',
        'password.require' => '密码不能为空',
        'password2.require' => '请再次输入密码不能为空',
        'password2.confirm' => '两次输入密码不相同',
        'email.email' => '请输入正确的email',
        'description.max' => '个人描述过长',
        'address.max' => '地址过长',
        'area.require' =>'请选择地区',
    ];
}
