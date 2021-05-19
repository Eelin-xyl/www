<?php
namespace app\admin\model;
use think\Model;
use app\admin\validate\RoleValidates;
class RoleUser extends Model {
    public function  del($id) {
        $this->where('role_id', $id)->delete();
    }
    //关联查询
    public function getUserRoleInfo($id) {
        $info = $this->where(['user_id' => $id])->alias('ru')->join('__ROLE__ r ', 'r.id = ru.role_id')->find();
        return $info;
    }
    /*
     * 查询用户角色分配
     */
    public function getRoleUser() {
        $str = 'u.id as userid, u.username, u.realname, u.idnumber, r.remark';
        $join = [
            ["__ROLE__ r", "r.id = ru.role_id"], 
            ["__USER__ u", "u.id = ru.user_id"]
        ];
        $data = collection($this->alias('ru')->where('u.status', 0)->join($join)->field($str)->select())->toArray();
        $reData = false;
        foreach ($data as $val) {
            if (empty($reData[$val['userid']])) {
                $reData[$val['userid']] = $val;
            }
            $reData[$val['userid']]['role'][] = ['name' => $val['remark']];
        }
        if ($reData) {
            return return_data(true, $reData);
        } else {
            return return_data(false, '暂无数据');
        }
    }
    /*
     * 获取用户角色信息
     * $id  false:返回所有用户及其角色，有值：返回指定id的用户的信息及其角色信息
     */
    public function getURoleInfo($id = false) {
        /*if (!$id) {
            return return_data(false, '传入参数错误');
        }*/
        $str = 'u.id as userid, u.username, u.realname, u.idnumber, r.remark, r.id as roleid';
        $join = [
            ["__ROLE__ r", "r.id = ru.role_id"], 
            ["__USER__ u", "u.id = ru.user_id", 'RIGHT']
        ];
        $where = ['u.status' => 0];
        if ($id) {
            $where['u.id'] = $id;
        }
        $data = collection($this->alias('ru')->where($where)->join($join)->field($str)->select())->toArray();
        $reData = false;
        foreach ($data as $val) {
            if (empty($reData[$val['userid']])) {
                $reData[$val['userid']] = $val;
            }
            $arr = ['name' => $val['remark']];
            if ($id) {
                $arr['roleid'] = $val['roleid'];
            }
            $reData[$val['userid']]['role'][] = $arr;
        }
        if ($reData) {
            if ($id) {
                return return_data(true, $reData[$id]);
            } else {
                return return_data(true, $reData);
            }
            
        } else {
            return return_data(false, '暂无数据');
        }

    }
    /*
     * 编辑用户角色
     */
    public function upURole($data) {
        $validate = new RoleValidates('upURole');

        if (!$validate->check($data)) {
            return return_data(false, $validate->getError());
        }
        //删除该用户先前角色信息
        $this->where('user_id', $data['id'])->delete();
        //添加新角色
        $list = [];
        foreach ($data['role_id'] as $val) {
            $list[] = ['role_id' => $val, 'user_id' => $data['id']];
        }
        if ($list != []) {
            $re = $this->saveAll($list, false);
        }
        return return_data(true, '');
    }
}