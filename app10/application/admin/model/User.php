<?php
namespace app\admin\model;

use think\Model;
use app\admin\validate\Validates;

class User extends Model {
    protected function initialize()
    {
        parent::initialize();
    }
    protected $pk = 'id';
    protected $auto = ['id'];
    protected function setIdAttr($val) {
        return $val ? $val : createGuid();
    }
    /*
     * 用户表多对多关联角色
     */
    public function roles() {
        return $this->belongsToMany('role','role_user', 'role_id', 'user_id');
    }
    /*
     * 用户登录
     */
    public function login($data) {
        $vali = new Validates('login');
        if(!$vali->check($data)){
            return return_data(false, $vali->getError());
        }
        if (!$user_info = $this->where('username', $data['username'])->find()) {
            return return_data(false, '该用户不存在');
        }
        if ($user_info['status'] == 1) {
            return return_data(false, '该用户已停止使用');
        }
        if ($this->workPass($data['password'], $user_info['password'], $user_info['salt'])) {
            $org = new Organization();
            if (!$org_info = $org->where('id', $user_info['pid'])->find()) {
                return return_data(false, '该用户不属于任何机构');
            }
            if (1 == $org_info['status']) {
                return return_data(false, '该机构已停用');
            }
            $save_data['ip'] = $data['request_ip'];
            $save_data['logintime'] = time();
            session('user.id', $user_info['id']);
            session('user.name', $user_info['username']);
            session('user.orgid', $user_info['pid']);
            session('user.last_login_time', $user_info['logintime']);
            session('user.last_login_ip', $user_info['logintime']);
            //修改用户登录信息
            $update_num = $this->where('id', $user_info['id'])->update($save_data);
            if ($update_num) {
                return return_data(true, '');
            } else {
                return return_data(false, '登录失败');
            }
        } else {
            return return_data(false, '密码错误');
        }
    }
    /**
     * 获取所有用户列表,根据登陆用户所在机构获取
     * @page ['list_rows' => 每页行数, config('paginate.var_page') => 页数]
     */
    public function userList($org_id = false, $page = false) {
        if (!$org_id) {
            $org_id = session('user.orgid');
        }
        $page['list_rows'] = config('list_rows');
        $org = model("Organization");
        $this_org_parentstr = $org->where(['status' => 0, 'id' => $org_id])->value('parentstr');
        if (!$this_org_parentstr) {
            return return_data(false, '该机构有误');
        }
        if (empty($page['list_rows']) || $page['list_rows'] < 0) {
            $page['list_rows'] = 1;
        }
        if (empty($page[config('paginate.var_page')]) || $page[config('paginate.var_page')] < 0) {
            $page[config('paginate.var_page')] = 1;
        }
        $org_info['parentstrs'] = $this_org_parentstr . $org_id . '%';
        $org_info['org_id'] = $org_id;
        $data = $this->alias('user')->join('__ORGANIZATION__ org', 'org.id = user.pid')->where('org.status', 0)->where(function($q) use ($org_info){
            $q->where('org.id', $org_info['org_id'])->whereor('org.parentstr', 'like', $org_info['parentstrs']);
        })->field('user.id, user.username, user.realname, user.idnumber, user.email, user.status, user.logintime, user.ip, user.phone, org.id as orgid, org.name as orgname')->order('user.status, user.timecreated desc')->paginate($page['list_rows']);
        if (!$data) {
            return return_data(false, '暂无信息');
        }
        $list=$data->all();
        foreach ($list as $k=>$v)
        {
            $v['logintime']=date('Y-m-d H:i:s', $v['logintime']);
            $data[$k]=$v;
        }
        return return_data(true, $data);
    }
    /**
     * 获取用户所有角色列表
     * @return false|mixed|\PDOStatement|string|\think\Collection  用户所有角色数组
     */
    public function userRole() {
        $res = $this->select();
        if ($res) {
            foreach ($res as $k => $v) {
                $arr[$k]['userid'] = $v['id'];
            }
            foreach ($arr as $v) {
                $data[] = $v['userid'];
            }
            $roles = db('user')
                ->alias('user')
                ->join('__ROLE_USER__ roleuser', 'user.id=roleuser.user_id')
                ->join('__ROLE__ role', 'roleuser.role_id=role.id')
                ->field('user.id,name')
                ->select();
            return $roles;
        }
    }
    /**
     * 删除用户原有角色，并从新添加角色
     * @param $userid   用户id
     * @return int   0 删除原有角色，添加角色失败 1 删除原有角色，添加角色成功 3 删除原有角色
     */
    public function giveRole($userid) {
        $roleuser = new RoleUser;
        if ( !isset(input('post.')['role_id']) ) {
            $res = $roleuser->where('user_id','in',$userid)->delete();
            if ($res !==false) {
                return 3;
            }else{
                return 0;
            }
        } else {
            $roles = input('post.')['role_id'];
            $roleuser->where('user_id','in',$userid)->delete();
            foreach ($userid as $k1 => $v1) {
                foreach ($roles as $k2 => $v2) {
                    $arr[$k1][$k2]['user_id'] = $v1;
                    $arr[$k1][$k2]['role_id'] = $v2;
                }
            }
            foreach ($arr as $v){
                foreach ($v as $v2){
                    $trr[] =$v2;
                }
            }
            $res = $roleuser->insertAll($trr);
            if ($res) {
               return 1;
            } else {
                return 0;
            }
        }
    }
    /*
     * 单个添加用户
     */
    public function addUser($data) {
        $validates = new Validates('user_add');
        if(!$validates->check($data)){
            return return_data(false, $validates->getError());
        }
        $encrypt_pass = $this->workPass($data['password']);
        $data['password'] = $encrypt_pass['password'];
        $data['pid'] = $data['orgid'];
        $data['salt'] = $encrypt_pass['salt'];
        $data['status'] = 0;
        $data['timecreated'] = time();
        $data['timemodified'] = time();
        //添加用户
        if ($this->allowField(true)->data($data)->save()) {
            return return_data(true, '');
        } else {
            return return_data(false, '添加用户失败！');
        }
    }
    /*
     *删除单个用户
     *根据用户id修改status字段
     */
    public function delUser($id) {
        if (!$id) {
            return return_data(false, '传入参数错误');
        }
        //获取删除用户信息
        $this_user_info = $this->where('id', $id)->field('status, pid')->find();
        if ($this_user_info['status'] == 1) {
            return return_data(false, '该用户已被删除');
        }
        //判断当前登录人员是否能操作该用户
        $org = new Organization();
        $org_id_arr = $org->getAllSonId(session('user.orgid'));
        $org_id_arr[] = session('user.orgid');
        if (in_array($this_user_info['pid'], $org_id_arr)) {
            //删除用户
            if ($this->where('id', $id)->update(['status' => 1, 'timemodified' => time()])) {
                return return_data(true, '');
            } else {
                return return_data(false, '删除失败');
            }
        } else {
            return return_data(false, '无权限操作此用户');
        }
    }
    /*
     * 修改用户信息
     * ['resetpwd' => '1:重置密码，0:不重置', 'username' => '用户名', 'realname' => '用户真实名', 'password' => '密码', 'repassword' => '重复密码', 'email' => '', 'phone' => '', 'address' => '', 'description' => '用户名', 'id' => '', ]
     * $status   true：修改用户status|false：修改字段信息       $data = ['id' => '', 'status' => ?]
     */
    public function upUser($data, $status = false) {
        $validate = new Validates('user_up');
        if (!$validate->check($data)) {
            return return_data(false, $validate->getError());
        }
        $this_user_info = $this->where('id', $data['id'])->field('status, pid')->find();
        if (!$status) {
            if ($this_user_info['status'] == 1) {
                return return_data(false, '该用户已被删除');
            }
        }
        $org = new Organization();
        $org_id_arr = $org->getAllSonId(session('user.orgid'));
        $org_id_arr[] = session('user.orgid');
        if (in_array($this_user_info['pid'], $org_id_arr)) {
            if (!$status) {
                if ($data['resetpwd'] == 1) {
                    $encrypt_pass = $this->workPass($data['password']);
                    $data['password'] = $encrypt_pass['password'];
                    $data['salt'] = $encrypt_pass['salt'];
                } else {
                    unset($data['password']);
                }
                $data['timemodified'] = time();
                $data['orgid'] && $data['pid'] = $data['orgid'];
            }
            if ($this->allowField(true)->save($data, ['id' => $data['id']])) {
                return return_data(true, '');
            } else {
                return return_data(false, '更新失败');
            }
        } else {
            return return_data(false, '无权限操作此用户');
        }
    }
    /*
     * 根据用户id获取用户信息
     */
    public function getUserInfoId($id) {
        if (!$id) {
            return return_data(false, '传入参数错误');
        }
        $org = new Organization();
        $org_parentstr = $org->where(['status' => 0, 'id' => session('user.orgid')])->value('parentstr');
        if (!$org_parentstr) {
            return return_data(false, '当前登录用户错误');
        }
        /*$str_arr = explode(',', $str);
        array_walk($str_arr, function(&$val, $k){
            $val = 'user.' . $val;
        });
        $str = implode(',', $str_arr);*/
        $str = 'user.id, user.username, user.realname, user.idnumber, user.email, user.phone, user.address, user.picture, user.description, user.timemodified, org.id as orgid, org.name as orgname, user.pid, user.status';
        $pstr = $org_parentstr . session('user.orgid') . '%';
        $user_info = $this->alias('user')->join('__ORGANIZATION__ org', 'org.id = user.pid')->where('user.id', $id)->where(function($q) use ($pstr){
            $q->where('org.id', session('user.orgid'))->whereor('org.parentstr', 'like', $pstr);
        })->field($str)->find();
        if (!$user_info) {
            return return_data(false, '暂无数据');
        }
        $user_info['timemodified'] = date('Y-m-d H:i:s', $user_info['timemodified']);
        return return_data(true, $user_info->toArray());
    }
    /*
     * 根据用户id查询用户信息
     * $type 根据id, username, realname, phone, idnumber查询,默认为id
     * $str 需要返回的字段
     */
    public function getUserInfo($id, $type = 'id', $str = '*') {
        if (!$id) {
            return return_data(false, '传入数据错误');
        }
        $info = [];
        $org = new Organization();
        $org_son = $org->getAllSonId(session('user.orgid'));
        $org_son[] = session('user.orgid');
        switch ($type) {
            case 'username':
                $info = collection($this->where(['username' => ['like', '%' . $id . '%'], 'status' => 0, 'pid' => ['in', $org_son]])->field($str)->select())->toArray();
                break;
            case 'realname':
                $info = collection($this->where(['realname' => ['like', '%' . $id . '%'], 'status' => 0, 'pid' => ['in', $org_son]])->field($str)->select())->toArray();
                break;
            case 'phone':
                $info = collection($this->where(['phone' => $id, 'status' => 0, 'pid' => ['in', $org_son]])->field($str)->select())->toArray();
                break;
            case 'idnumber':
                $info = collection($this->where(['idnumber' => $id, 'status' => 0, 'pid' => ['in', $org_son]])->field($str)->select())->toArray();
                break;
            default:
                $info = $this->where(['id' => $id, 'status' => 0, 'pid' => ['in', $org_son]])->field($str)->find()->toArray();
                break;
        }
        return return_data(true, $info);
    }
    /*
     *生成随机数
     */
    public function createRandomNumber($num = 6){
        $str = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randnum = '';
        for($i = 0; $i < $num; $i++){
            $randnum .= $str{mt_rand(0, 35)};
        }
        $re_num = md5($randnum);
        return substr($re_num, mt_rand(0, 9), $num);
    }
    /*
     *用户密码处理
     *$pass 输入密码
     *$db_pass 数据库存放密码
     *$salt 盐
     */
    public function workPass($pass, $db_pass = false, $salt = false) {
        if ($salt == false) {
            $salt = $this->createRandomNumber();
        }
        $new_pass = md5(md5($pass) . $salt);
        if ($db_pass == false) {
            return ['password' => $new_pass, 'salt' => $salt];
        } else {
            if ($db_pass == $new_pass) {
                return true;
            } else {
                return false;
            }
        }
    }
  public function reg($user){
        $user['check'] = 0;
        $user['timecreated'] = time();
        $user['timemodified'] = time();
        if (isset($user['areaQ'])) {
            $user['area'] = $user['areaQ'];
        } else {
            if (isset($user['areaS'])){
                $user['area'] = $user['areaS'];
                unset($user['AreaS']);
                unset($user['AreaQ']);
            }
        }

        return $user;
  }


}
