<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

return [
    // +----------------------------------------------------------------------
    // | 应用设置
    // +----------------------------------------------------------------------
    //权限相关数据表设置
    'db_prefix' => 'netmre_',
    'rbac_role_table' => 'role',
    'rbac_user_table' => 'role_user',
    'rbac_access_table' => 'access',
    'rbac_node_table' => 'node',
    //权限相关配置项
    'user_auth_type' =>1, //认证类型
    'user_auth_key'  => 'uid',//认证识别号
    //require_auth_module // 需要认证模块
    //不需要认证的控制器及方法
    //[ 'controller1' => ['action1', 'action2'], 'controller2' => ['action1', 'action2']]
    'not_auth' => [
        'INDEX' => [],
        'LOGIN' => ['LOGIN', 'PROCESS', 'LOGINOUT', 'GETBACKPWD']
    ],
    'not_auth_module' => 'LOGIN',//无需认证的控制器
    'not_auth_action' => 'LOGIN, PROCESS, LOGINOUT, GETBACKPWD',//无需认证方法
    //user_auth_gateway //认证网关
    'rbac_superadmin' =>'admin',
    'admin_auth_key' => 'superadmin',
    'user_auth_on' => true,//是否认证
    //rbac_db_dsn // 数据库连接DSN
    //权限数据库配置示例
    /*'rbac_db_dsn' => [
        // 数据库类型
        'type' => 'mysql',
        // 服务器地址
        'hostname' => '127.0.0.1',
        // 数据库名
        'database' => 'energy123',
        // 数据库用户名
        'username' => 'root',
        // 数据库密码
        'password' => '',
        // 数据库编码默认采用utf8
        'charset' => 'utf8',
        // 数据库表前缀
        'prefix' => 'chuandge_',
    ],*/
    'view_replace_str' => array(
            '__CSS__' => str_replace(array("//", '\/'), "/", dirname($_SERVER['SCRIPT_NAME']) . '/public/static/admin/css/'),
            '__JS__' =>  str_replace(array("//", '\/'), "/", dirname($_SERVER['SCRIPT_NAME']) . '/public/static/admin/js/'),
            '__IMAGES__' =>  str_replace(array("//", '\/'), "/", dirname($_SERVER['SCRIPT_NAME']) . '/public/static/admin/images/'),
            '__STATIC__' => str_replace(array("//", '\/'), "/", dirname($_SERVER['SCRIPT_NAME']) . '/public/static'),
            '__UPLOAD__' => str_replace(array("//", '\/'), '/', dirname($_SERVER['SCRIPT_NAME']).'/public/uploads'),
    ),
    'parentstr_prefix' => '0,',
    'list_rows'=>15,

];
