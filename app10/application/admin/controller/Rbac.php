<?php
namespace app\admin\controller;
use app\admin\model\Access;
use app\admin\model\Role;
use app\admin\model\Node;
use app\admin\model\RoleUser;
use app\admin\model\User;
use think\Request;
class Rbac extends Common {
    protected $beforeActionList = [
        'delsonNode'  =>  ['only' => 'del'],
    ];
    public function __construct() {
        parent::__construct();
    }
    /*
     * 人员管理
     */
    public function  index() {
        $user = new User();
        $role = new RoleUser;
        $ids=array();
        $res = $role->field('user_id')->select();
        foreach ($res as $v) {
            $ids[] =$v['user_id'];
        }
        if(request()->param('flag') !=null) {
            $data = $user->where('id','not in',$ids)->paginate(30);
        }else {
            $data = $user->where('id','in',$ids)->paginate(30);
        }
        $userrole = $user->userRole();
        $this->assign('userrole', $userrole);
        $this->assign('result', $data);
        return $this->fetch();
    }
    /*
     * 分配角色
     */
    public function giveRole() {
        $role = new Role;
        $usermsg = request()->param();
        if(!empty($usermsg)) {
            if (isset($usermsg['username'])) {
                $id[] = $usermsg['id'];
                $name[] = $usermsg['username'];
            } else {
                $usermsg = $usermsg['id'];
                foreach ($usermsg as $k => $v) {
                    $num = stripos($v, '|');
                    $name[] = substr($v, $num + 1);
                    $id[] = substr($v, 0, $num);
                }
            }
            $rolelst = $role->select();
            $this->assign('userid', $id);
            $this->assign('username', $name);
            $this->assign('rolelst', $rolelst);
            return $this->fetch();
        }else {
            $this->error('请选择用户');
        }
    }
    /**
     * 根据用户编号或者名字查询用户
     */
    public function searchUser() {
        if(input('key') != null) {
            $key = input('key');
            $type = input('type');
            if($type == "id"){
                $user = new User;
                $usermsg =$user->where('idnumber',$key)->paginate(1,false,$config=['query'=>array('key'=>$key,'type'=>$type)]);
                if($usermsg) {
                    $userrole = $user->userRole();
                    $this->assign('userrole', $userrole);
                    $this->assign('result',$usermsg);
                    $this->assign('key',$key);
                    $this->assign('type',$type);
                    return $this->fetch('index');
                }
            }else{
                $user = new User;
                $usermsg =$user->where('username','like','%'.$key.'%')->paginate(1,false,$config=['query'=>array('key'=>$key,'type'=>$type)]);
                if($usermsg) {
                    $userrole = $user->userRole();
                    $this->assign('userrole', $userrole);
                    $this->assign('result',$usermsg);
                    $this->assign('key',$key);
                    $this->assign('type',$type);
                    return $this->fetch('index');
                }
            }
        }else {
            $this->error('请输入查询条件');
        }
    }
    /**
     * 给调用User模型giveRole方法用户添加角色
     */
    public function addUserRole() {
        if (request()->isPost()) {
            $userid = input('post.')['id'];
            $user = new User;
            $res = $user->giveRole($userid);
            if ( $res == 1 ) {
                $this->success('添加用户角色成功', url('index'));
            } elseif ( $res == 3 ) {
                $this->success('没有选择角色，原有角色已被删除', url('index'));
            } else {
                $this->error('添加角色失败，原有角色被删除', url('index'));
            }
        }
    }
    /*
     * 节点管理
     */
    public function add() {
        $node = new Node;
        if (request()->isPost()) {
            $title = input('post.title');
            $nodetitle = $node->where('title', $title)->find();
            if ($nodetitle) {
                $this->error('节点已经存在');
            } else {
                $data = input('post.');
                $res = $node->addNod($data);
                if ( $res == 1 ) {
                    $this->success('添加节点成功', url('nodeList'));
                } else {
                    $this->error('添加节点失败');
                }
            }
        }
        $request = Request::instance();
        $arr = $request->param();
        $this->assign('arr', $arr);
    }
    /*
     * 节点显示
     */
    public function nodeList() {
        $node = new Node;
        $num = request()->param('flog');
        if (empty($num)) {
            $num = 0;
        }
        $data = $node->getMenu();/*所有节点数组*/
        $arr = $node->nodeTree();/*模块数组*/
        if (empty($arr)) {
            $data = null;
            $arr = null;
            $this->assign('data', $data[$num]);
            $this->assign('arr', $arr);
        }
        if ($data) {
            $this->assign('data', $data[$num]);
            $this->assign('arr', $arr);
        }
        $this->assign('flog', $num);
        return $this->fetch();
    }
    /*
     * 添加模块
     */
    public  function addNode() {
        $this->add();
        return $this->fetch();
    }
    /*
     * 添加控制器
     */
    public function addController() {
        $this->add();
        $num = request()->param('pid');
        if (empty($num)) {
            $this->error('请先添加模块');
        } else {
            return $this->fetch();
        }
    }
    /*
     * 添加方法
     */
    public function addMethod() {
        $this->add();
        return $this->fetch();
    }
    /*
     * 修改节点
     */
    public  function modifyNode() {
        $node = new Node;
        if (request()->isPost()) {
            $postdata = input('post.');
            $res = $node->save($postdata, ['id'=>input('post.id')]);
            if ( $res !== false ) {
               $this->success('修改成功', url('nodeList'));
           } else {
               $this->error('修改失败');
           }
        }
        $res = request()->param('id');
        $data = $node->search($res);
        $this->assign('node', $data);
        return $this->fetch();
    }
    /*
     * 删除目标节点
     */
    public function del() {
        $node = new Node;
        $id = input('id');
        $del = $node->where('id', input('id'))->delete();
        Access::where('node_id', $id)->delete();
        if ($del) {
            $this->success('删除节点成功', url('nodeList'));
        } else {
            $this->error('删除节点失败');
        }
    }
    /*
     * 删除子节点
     */
    public function delsonNode() {
        $node = new Node;
        $node->delNodeAccess();
    }
    /*
     * 角色列表
     */
    public function roleList() {
       $role = new Role;
       $roleres = $role->paginate(3);
       $this->assign('roles', $roleres);
       return $this->fetch();
    }
    /*
     * 删除角色
     */
    public function delRole($id) {
        $role = new Role;
        $res = $role->delRoleAccess($id);
        if ( $res == 1 ) {
            $this->success('删除角色成功', url('roleList'));
        } else {
            $this->error('删除失败');
        }
    }
    /**
     * 添加角色
     */
    public function addRole() {
        $role = new Role;
        if (request()->isPost()) {
            $name = input('post.name');
            $rolename = $role->where('name', $name)->find();
            if ($rolename) {
                $this->error('角色已经存在');
            } else {
                $data = input('post.');
                $data['id'] = createGuid();
                $res = $role->save($data);
                if ($res) {
                    $this->success('添加角色成功', url('roleList'));
                } else {
                    $this->error('添加角色失败');
                }
            }
        }
        return $this->fetch();
    }
    /**
     * 修改角色
     */
    public function modifyRole() {
        $role = new Role;
        if (request()->isPost()) {
            $postdata = input('post.');
            $res = $role->save($postdata, ['id'=>input('post.id')]);
            if ( $res !== false ) {
                $this->success('修改成功', url('roleList'));
            } else {
                $this->error('修改失败');
            }
        }
        $id = request()->param('id');
        $data = $role->where('id',$id)->find();
        $this->assign('role',$data);
        return $this->fetch();
    }
    /**
     * 配置权限
     */
    public function setPermissions() {
        $role = new Role;
        $node = new Node;
        $id = input('post.role_id');
        if (request()->isPost()) {
            $res = $role->setPermissions($id);
            if ( $res == 1) {
                $this->success('配置权限成功', url('roleList'));
            } else {
                $this->error('配置权限失败');
            }
        }
        $roleid = input('id');
        $rolenoderes = $role->searchPermission($roleid);/*角色拥有的节点*/
        $nodes = $node->select();
        $nodedata = $node->roleFlog($nodes, $rolenoderes);
        $this->assign('node', $nodedata);
        $this->assign('role_id', $roleid);
        return $this->fetch();
    }
}
