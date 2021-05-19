<?php
namespace app\admin\controller;

use app\admin\model\Organization;
//use app\admin\model\User;

class User extends Common {
    public function __construct() {
        parent::__construct();
    }
    /*
     * 用户管理
     */
    public function index() {
        $user = model('User');
        $Organization = model('Organization');
        $search_org = input('organization');

        $where_in = [session('user.orgid')];
        $orgid = session('user.orgid');
        if ($search_org) {
            $where_in[] = $search_org;
            $orgid = $search_org;
        }
        $org_info = $Organization->getInfoByWhere(['id' => ['in', $where_in]]);
        $org_list = [];
        $str_arr = [];
        if ($org_info['state']) {
            $this_org_parentstr = false;
            $user_org_parentstr = false;
            foreach ($org_info['data'] as $key => $val) {
                if (session('user.orgid') == $val['id']) {
                    $org_list = $val;
                    $user_org_parentstr = $val['parentstr'];
                } else {
                    $this_org_parentstr = $val['parentstr'];
                }
            }
            $parentstr = mb_substr($this_org_parentstr, mb_strlen($user_org_parentstr)) . $orgid;
            $str_arr = explode(',', $parentstr);
        } else {
            $this->error('用户所在机构信息有误', url('admin/index/loginOut'));
        }
        $this->assign('str_arr', json_encode($str_arr));
        $this->assign('org', [$org_list]);
        $this->assign('orgid', $orgid);

        $info_user = $user->userList($orgid);
        $data = [];
        if ($info_user['state']) {
            $data = $info_user['data'];
        }
        $this->assign('data', $data);
        $this->assign('num', 1);
        return $this->fetch('', ['title' => '用户列表']);
    }
    /*
     * 添加用户
     */
    public function add() {
        $data = $this->receive_data;
        $org = model('Organization');
        $user = model('User');
        if (request()->post()) {
            $result = $user->addUser($data);
            if ($result['state']) {
                $this->success('添加成功');
            } else {
                $this->error('添加失败，' . $result['data']);
            }
        }
        $orgid_in = [session('user.orgid')];
        $orgid = session('user.orgid');
        if (isset($data['orgid'])) {
            $orgid_in[] = $data['orgid'];
            $orgid = $data['orgid'];
        }
        $org_info = $org->getInfoByWhere(['id' => ['in', $orgid_in]]);
        $org_list = [];
        $str_arr = [];
        if ($org_info['state']) {
            $this_org_parentstr = false;
            $user_org_parentstr = false;
            foreach ($org_info['data'] as $key => $val) {
                if (session('user.orgid') == $val['id']) {
                    $org_list = $val;
                    $user_org_parentstr = $val['parentstr'];
                } else {
                    $this_org_parentstr = $val['parentstr'];
                }
            }
            $parentstr = mb_substr($this_org_parentstr, mb_strlen($user_org_parentstr)) . $orgid;
            $str_arr = explode(',', $parentstr);
        }
        $this->assign('str_arr', json_encode($str_arr));
        $this->assign('org', [$org_list]);
        $this->assign('orgid', $orgid);

        return $this->fetch('', ['title' => '添加用户']);
    }
    /*
     * 编辑用户信息
     */
    public function edit() {
        $data = $this->receive_data;
        $user = model('User');
        if (request()->post()) {
            if ($data['resetpwd'] == '0') {
                unset($data['password']);
                unset($data['repassword']);
            }
            $result = $user->upUser($data);
            if ($result['state']) {
                $this->success('操作成功', url('index'));
            } else {
                $this->error('更新失败，' . $result['data']);
            }
        } else {
            $user_info = $user->getUserInfoId($data['id']);
            $this->assign('data', []);
            if (!$user_info['state']) {
                $this->error('操作失败，' . $user_info['data']);
            }

             //默认选中
             $Organization = model('Organization');
            $str_arr = [];
            $this->assign('orglist', []);
     //     if ($user_info['data']['pid'] != session('user.orgid')) {
                //获取用户所在机构和选中机构的信息
                $parentstrs = $Organization->getInfoByWhere(['id' => ['in', [session('user.orgid'), $user_info['data']['pid']]]]);
                if ($parentstrs['state']) {
                    $this_org_parentstr = false;
                    $user_org_parentstr = false;
                    foreach ($parentstrs['data'] as $key => $value) {
                        if ($value['id'] == session('user.orgid')) {
                            $user_org_parentstr = $value['parentstr'];
                            $this->assign('orglist', [['id' => $value['id'], 'name' => $value['name']]]);
                        } else {
                            $this_org_parentstr = $value['parentstr'];
                        }
                    }
                    $parentstr = mb_substr($this_org_parentstr, mb_strlen($user_org_parentstr)) . $user_info['data']['pid'];
                    $str_arr = explode(',', $parentstr);
                }
     //     } else {
    //            pe('456');
      //      }
            $this->assign('str_arr', json_encode($str_arr));

            $this->assign('data', $user_info['data']);
        }
     //   pe($user_info['data']);
        return $this->fetch('', ['title' => '更新用户信息']);
    }
    /*
     * 启用用户
     */
    public function start(){
        $id = input('id');
        $user = model('User');
        $re = $user->upUser(['id' => $id, 'status' => 0], true);
        if ($re['state']) {
            $this->success('成功');
        } else {
            $this->error('操作失败，' . $re['data']);
        }
    }
    /*
     * 删除用户
     */
    public function delete() {
        $id = request()->param('id');
        $user = model('User');
        $result = $user->delUser($id);
        if ($result['state']) {
            $this->success('操作成功');
        } else {
            $this->error('操作失败，' . $result['data']);
        }
    }
    /*
     * 用户表单
     */
    public function form() {
        return $this->fetch('', ['title' => '添加用户']);
        echo "用户管理";
    }
    

}