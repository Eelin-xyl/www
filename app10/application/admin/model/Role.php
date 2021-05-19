<?php
namespace app\admin\model;
use think\Model;
use app\admin\validate\RoleValidates;
class Role extends Model {
    protected function initialize()
    {
        parent::initialize();
    }
    protected $pk = 'id';
    protected $auto = ['id'];
    protected function setIdAttr($val) {
        return $val ? $val : createGuid();
    }
    /**
     * 关联role,,access,node表查询数据
     * @param $id 角色id
     * @return false|mixed|\PDOStatement|string|\think\Collection  关联查询结果
     */
    public function searchPermission($id) {
       $res = db('role')
            ->alias('role')
            ->join('__ACCESS__ access','role.id = access.role_id')
            ->join('__NODE__ node','access.node_id = node.id')
            ->field('node_id')
            ->select($id);
       return $res;
    }
    /**
     * 处理表单数据，将nodeid和level组合的字符串分隔成数组并与
     * 角色id组合构造成可以直接存入数据库的键值对数组形式 $arr为最终数组
     * 配置权限之前先删除角色原有权限
     * @param $id 是角色id
     * @return int 1 配置权限成功  0 配置权限失败
     */
    public  function setPermissions($id) {
        $access = new Access;
        if ( !isset(input('post.')['access']) ) {
            $res = $access->del($id);
            if( $res !== false ) {
               return 1;
            } else {
                return 0;
            }
        } else {
            $access->del($id);
            $dat = input('post.')['access'];
            foreach ($dat as $k=>$v) {
                $arr[$k]['role_id'] = $id;
                $tt = explode(',',$v);
                $arr[$k]['node_id'] = $tt[0];
                $arr[$k]['level'] = (int)$tt[1];
            }
            $del = $access->insertAll($arr);
            if( $del !== false ) {
                return 1;
            } else {
                return 2;
            }
        }
   }

    /**
     * 删除角色时同时删除用户角色表数据、角色节点表数据
     * @param $id 角色id
     * @return int 1 删除成功 0 删除失败
     */
   public function delRoleAccess($id) {
       $roleuser = new RoleUser;
       $roleuser->del($id);
       $access = new Access;
       $access->del($id);
       $role = Role::get($id);
       $res = $role->delete();
       if ($res) {
           return 1;
       } else {
           return 0;
       }
   }
   public function giveRole($userid,$roleids) {
       $user = new User;
       $res = $user->where('id',$userid)->find();
       if($res) {
            $user =new User;
       }
   }
    /*
     * 根据id获取单个角色信息
     */
    public function getInfoFId($id) {
        if (!$id) {
            return return_data(false, '传入参数错误');
        }
        $data = $this->where('id', $id)->find()->toArray();
        if ($data) {
            return return_data(true, $data);
        } else {
            return return_data(false, '暂无数据');
        }
    }
    /*
     * 获取所有有效角色信息
     * $type   false:获取所有角色，true:获取所有有效角色
     */
    public function getAllRole($type = false) {
        if ($type) {
            $data = collection($this->where('status', 1)->select())->toArray();
        } else {
          $data = collection($this->select())->toArray();
        }
        if ($data) {
            return return_data(true, $data);
        } else {
            return return_data(false, '暂无数据');
        }
    }
    /*
     * 添加角色
     */
    public function addRole($data) {
        $validate = new RoleValidates('addRole');
        if (!$validate->check($data)) {
          return return_data(false, $validate->getError());
        }
        $result = $this->allowField(true)->save($data);
        if ($result) {
          return return_data(true, '');
        } else {
          return return_data(false, '添加失败');
        }
    }
    /*
     * 编辑角色
     */
    public function upRole($data) {
        $validate = new RoleValidates('upRole');
        if (!$validate->check($data)) {
            return return_data(false, $validate->getError());
        }
        $result = $this->allowField(true)->save($data, ['id' => $data['id']]);
        if ($result >= 0) {
            return return_data(true, '');
        } else {
            return return_data(false, '编辑失败');
        }
    }
}