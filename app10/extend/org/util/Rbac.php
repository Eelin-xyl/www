<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
namespace org\util;

use think\Db;
use think\Config;
use think\Request;

/**
 +------------------------------------------------------------------------------
 * 基于角色的数据库方式验证类
 +------------------------------------------------------------------------------
 */
// 配置文件增加设置
// user_auth_model 用户认证模块
// user_auth_key 认证识别号
// user_auth_type 认证类型
// admin_auth_key 管理员认证识别号
// user_auth_on 是否需要认证
// require_auth_module 需要认证的控制器
// not_auth_module 无需认证的控制器
// require_auth_action 需要认证方法
// not_auth_action 不需要认证的方法
// not_auth 不需要认证的控制器的下面的方法，二维数组
// guest_auth_on 游客认证是否需要
// guest_auth_id 游客认证id
// user_auth_gateway 认证网关
// rbac_db_dsn 数据库连接dsn
// 
// rbac_role_table 角色表名称
// rbac_user_table 角色用户表名称
// rbac_access_table 权限表名称
// rbac_node_table 节点表名称
/*
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `think_access` (
  `role_id` smallint(6) unsigned NOT NULL,
  `node_id` smallint(6) unsigned NOT NULL,
  `level` tinyint(1) NOT NULL,
  `module` varchar(50) DEFAULT NULL,
  KEY `groupId` (`role_id`),
  KEY `nodeId` (`node_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `think_node` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `title` varchar(50) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0',
  `remark` varchar(255) DEFAULT NULL,
  `sort` smallint(6) unsigned DEFAULT NULL,
  `pid` smallint(6) unsigned NOT NULL,
  `level` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `level` (`level`),
  KEY `pid` (`pid`),
  KEY `status` (`status`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `think_role` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `pid` smallint(6) DEFAULT NULL,
  `status` tinyint(1) unsigned DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `status` (`status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS `think_role_user` (
  `role_id` mediumint(9) unsigned DEFAULT NULL,
  `user_id` char(32) DEFAULT NULL,
  KEY `group_id` (`role_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
*/
class Rbac {
    static private $authId = '';
    function __construct($authId = false) {
       self::$authId = $authId ? $authId : '';
    }

    // 认证方法
    static public function authenticate($map, $model = '') {
        if(empty($model)) $model =  Config::get('user_auth_model');
        //使用给定的Map进行认证
        return Db::name($model)->where($map)->find();
    //    return M($model)->where($map)->find();
    }

    //用于检测用户权限的方法,并保存到Session中
    static function saveAccessList($authId = null) {
        if ($authId === null) $authId = self::$authId;
        $getAccessList = self::getAccessList($authId);
        if (Config::get('user_auth_type') != 2 && !session(Config::get('admin_auth_key'))) {
        //    if (!session('_ACCESS_LIST')) {
                session('_ACCESS_LIST', $getAccessList );
        //    }
        }
        return $getAccessList;
/*        if(null===$authId)   $authId = session(Config::get('user_auth_key'));
        // 如果使用普通权限模式，保存当前用户的访问权限列表
        // 对管理员开发所有权限
        if(Config::get('user_auth_type') !=2 && !session(Config::get('admin_auth_key')) )
            session('_ACCESS_LIST', self::getAccessList($authId));
        return self::getAccessList($authId);*/
    }

    // 取得模块的所属记录访问权限列表 返回有权限的记录ID数组
    static function getRecordAccessList($authId = null, $controller = '') {
        if ($authId === null)  $authId = self::$authId;
        if (empty($controller))  $controller = Request::instance()->controller();
        return self::getModuleAccessList($authId, $controller);
/*        if(null===$authId)   $authId = session(Config::get('user_auth_key'));
        if(empty($module))  $module = Request::instance()->controller();
    //    if(empty($module))  $module =   CONTROLLER_NAME;
        //获取权限访问列表
        $accessList = self::getModuleAccessList($authId,$module);
        return $accessList;*/
    }

    //检查当前操作是否需要认证
    static function checkAccess() {
        //如果项目要求认证，并且当前模块需要认证，则进行权限认证
        if( Config::get('user_auth_on') ){
            $_module    =   array();
            $_action    =   array();
            if("" != Config::get('require_auth_module')) {
                //需要认证的模块
                $_module['yes'] = explode(',', strtoupper(Config::get('require_auth_module')));
            }else {
                //无需认证的模块
            /*    if (!$not_auth_arr = Config::get('not_auth')) $not_auth_arr = [];
                $_module['no'] = array_keys($not_auth_arr);*/
                $_module['no'] = explode(',', strtoupper(Config::get('not_auth_module')));
            }
            //检查当前模块是否需要认证
            if((empty($_module['no']) || !in_array(strtoupper(Request::instance()->controller()), $_module['no'])) || (!empty($_module['yes']) && in_array(strtoupper(Request::instance()->controller()), $_module['yes']))) {
         //   if((!empty($_module['no']) && !in_array(strtoupper(CONTROLLER_NAME),$_module['no'])) || (!empty($_module['yes']) && in_array(strtoupper(CONTROLLER_NAME),$_module['yes']))) {
                if("" != Config::get('require_auth_action')) {
                    //需要认证的操作
                    $_action['yes'] = explode(',', strtoupper(Config::get('require_auth_action')));
                }else {
                    //无需认证的操作
                    if (!$_action['no'] = Config::get('not_auth.' . mb_strtoupper(Request::instance()->controller()))) $_action['no'] = [];
                //    $_action['no'] = explode(',', strtoupper(Config::get('not_auth_action')));
                }
                //检查当前操作是否需要认证
                if((empty($_action['no']) || !in_array(strtoupper(Request::instance()->action()), $_action['no'])) || (!empty($_action['yes']) && in_array(strtoupper(Request::instance()->action()), $_action['yes']))) {
            //    if((!empty($_action['no']) && !in_array(strtoupper(ACTION_NAME),$_action['no'])) || (!empty($_action['yes']) && in_array(strtoupper(ACTION_NAME),$_action['yes']))) {
                    return true;
                }else {
                    return false;
                }
            }else {
                return false;
            }
        }
        return false;
    }

    // 登录检查
    static public function checkLogin($authId) {
        //检查当前操作是否需要认证
        if(self::checkAccess()) {
            //检查认证识别号
            if(!session(Config::get('user_auth_key'))) {
                if(Config::get('guest_auth_on')) {
                    // 开启游客授权访问
                    $value = session('_ACCESS_LIST');
                    if(empty($value))
                        // 保存游客权限
                        self::saveAccessList(Config::get('guest_auth_id'));
                }else{
                    // 禁止游客访问跳转到认证网关      正常直接跳转
                //    redirect(Request::instance()->baseFile() . Config::get('user_auth_gateway'));
                    //service服务端直接返回认证失败
                    return false;
                //    redirect(PHP_FILE.C('USER_AUTH_GATEWAY'));
                }
            }
        }
        return true;
    }

    //权限认证的过滤器方法
    static public function AccessDecision($authId = null, $appName = null) {
        if ($authId === null) $authId = self::$authId;
        $appName = $appName != null ? strtoupper($appName) : strtoupper(Request::instance()->module());
        if (self::checkAccess()) {
            $accessList = self::getAccessList($authId);
            if (!isset($accessList[$appName][strtoupper(Request::instance()->controller())][strtoupper(Request::instance()->action())])) {
                return false;
            } else {
                return true;
            }
        } else {
            return true;
        }
    }

    /**
     +----------------------------------------------------------
     * 取得当前认证号的所有权限列表
     +----------------------------------------------------------
     * @param integer $authId 用户ID
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     */
    static public function getAccessList($authId) {
    //连接数据库
      /*  Db::connect($config)->query('select * from think_user where id=:id',['id'=>8]);
        $config是一个单独的数据库配置，支持数组和字符串，也可以是一个数据库连接的配置参数名。*/

        // Db方式权限数据
        $db    =   Db::connect(Config::get('rbac_db_dsn'));//可以自己配置数据库设置，rbac_db_dsn则采用，无则默认
    //    $db     =   Db::getInstance(C('RBAC_DB_DSN'));
        $table = array('role' => Config::get('db_prefix') . Config::get('rbac_role_table'),'user' => Config::get('db_prefix') . Config::get('rbac_user_table'),'access' => Config::get('db_prefix') . Config::get('rbac_access_table'),'node' => Config::get('db_prefix') . Config::get('rbac_node_table'));
        $sql    =   "select node.id,node.name from ".
                    $table['role']." as role,".
                    $table['user']." as user,".
                    $table['access']." as access ,".
                    $table['node']." as node ".
                    "where user.user_id=:authId and user.role_id=role.id and ( access.role_id=role.id or (access.role_id=role.pid and role.pid!=:pid ) ) and role.status=:role_status and access.node_id=node.id and node.level=:level and node.status=:node_status";
    //    $apps =   $db->query($sql);
        $param_apps = ['authId' => $authId, 'pid' => 0, 'role_status' => 1, 'level' => 1, 'node_status' => 1];
        $apps = $db->query($sql, $param_apps);
        $access =  array();
        foreach($apps as $key => $app) {
            $appId  =   $app['id'];
            $appName     =   $app['name'];
            // 读取项目的模块权限
            $access[strtoupper($appName)]   =  array();
            $sql    =   "select node.id,node.name from ".
                    $table['role']." as role,".
                    $table['user']." as user,".
                    $table['access']." as access ,".
                    $table['node']." as node ".
                    "where user.user_id=:authId and user.role_id=role.id and ( access.role_id=role.id  or (access.role_id=role.pid and role.pid!=:role_pid ) ) and role.status=:role_status and access.node_id=node.id and node.level=:level and node.pid=:appId and node.status=:node_status";
        //    $modules =   $db->query($sql);
            $param_modules = ['authId' => $authId, 'appId' => $appId, 'role_pid' => 0, 'role_status' => 1, 'level'=> 2, 'node_status' => 1];
            $modules = $db->query($sql, $param_modules);
            // 判断是否存在公共模块的权限
            $publicAction  = array();
            foreach($modules as $key => $module) {
                $moduleId    =   $module['id'];
                $moduleName = $module['name'];
                if('PUBLIC'== strtoupper($moduleName)) {
                $sql    =   "select node.id,node.name from ".
                    $table['role']." as role,".
                    $table['user']." as user,".
                    $table['access']." as access ,".
                    $table['node']." as node ".
                    "where user.user_id=:authId and user.role_id=role.id and ( access.role_id=role.id  or (access.role_id=role.pid and role.pid!=:role_pid ) ) and role.status=:role_status and access.node_id=node.id and node.level=:level and node.pid=:moduleId and node.status=:node_status";
                //    $rs =   $db->query($sql);
                    $param_rs = ['authId' => $authId, 'moduleId' => $moduleId, 'role_pid' => 0, 'role_status' => 1, 'level' => 3, 'node_status' => 1];
                    $rs = $da->query($sql, $param_rs);
                    foreach ($rs as $a){
                        $publicAction[$a['name']]    =   $a['id'];
                    }
                    unset($modules[$key]);
                    break;
                }
            }
            // 依次读取模块的操作权限
            foreach($modules as $key => $module) {
                $moduleId    =   $module['id'];
                $moduleName = $module['name'];
                $sql    =   "select node.id,node.name from ".
                    $table['role']." as role,".
                    $table['user']." as user,".
                    $table['access']." as access ,".
                    $table['node']." as node ".
                    "where user.user_id=:authId and user.role_id=role.id and ( access.role_id=role.id  or (access.role_id=role.pid and role.pid!=:pid ) ) and role.status=:role_status and access.node_id=node.id and node.level=:level and node.pid=:moduleId and node.status=:node_status";
            //    $rs =   $db->query($sql);
                $param_rs = ['authId' => $authId, 'moduleId' => $moduleId, 'pid' => 0, 'role_status' => 1, 'level' => 3, 'node_status' => 1];
                $rs = $db->query($sql, $param_rs);
                $action = array();
                foreach ($rs as $a){
                    $action[$a['name']]  =   $a['id'];
                }
                // 和公共模块的操作权限合并
                $action += $publicAction;
                $access[strtoupper($appName)][strtoupper($moduleName)]   =  array_change_key_case($action, CASE_UPPER);
            }
        }
        return $access;
    }

    // 读取模块所属的记录访问权限
    static public function getModuleAccessList($authId, $module) {
        // Db方式
        $db    =   Db::connect(Config::get('rbac_db_dsn'));
    //    $db     =   Db::getInstance(C('RBAC_DB_DSN'));
        $table = array('role' => Config::get('db_prefix') . Config::get('rbac_role_table'), 'user' => Config::get('db_prefix') . Config::get('rbac_user_table'), 'access' => Config::get('db_prefix') . Config::get('rbac_access_table'));
        $sql    =   "select access.node_id from ".
                    $table['role']." as role,".
                    $table['user']." as user,".
                    $table['access']." as access ".
                    "where user.user_id=:authId and user.role_id=role.id and ( access.role_id=role.id  or (access.role_id=role.pid and role.pid!=:pid ) ) and role.status=:role_status and  access.module=:module";
    //    $rs =   $db->query($sql);
        $param_ra = ['authId' => $authId, 'module' => $module, 'pid' => 0, 'role_status' => 1];
        $rs = $db->query($sql, $param_ra);
        $access =   array();
        foreach ($rs as $node){
            $access[]   =   $node['node_id'];
        }
        return $access;
    }
}