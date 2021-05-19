<?php
namespace app\admin\model;
use think\Model;
use app\admin\validate\NodeValidates;
class Access extends Model {
    protected function initialize()
    {
        parent::initialize();
    }
    protected $pk = 'id';
    /**
     * @param $id 角色id
     */
    public function del($id) {
        $this->where('role_id', $id)->delete();
    }
    /*
     * 根据角色id获取角色权限
     */
    public function getUserAccess($id){
        if (!$id) {
            return return_data(false, '传入参数错误');
        }
        $role_access = collection($this->alias('a')->where(['n.status' => 1, 'a.role_id' => $id])->join('__NODE__ n', 'n.id = a.node_id')->select())->toArray();
        $node = new Node();
        $all_node = collection($node->where('status', 1)->select())->toArray();
        if (!$all_node) {
            return return_data(false, '暂无数据');
        }
        array_walk($all_node, function(&$val, $k) use ($role_access){
            $val['access'] = 0;
            foreach ($role_access as $value) {
                if ($value['id'] == $val['id']) {
                    $val['access'] = 1;
                }
            }
        });
        $re_data = $this->recursionArr($all_node, '0');
        return return_data(true, $re_data);
    }
    /*
     *  设置用户权限
     */
    public function setAccess($data) {
        $validate = new NodeValidates('setAccess');
        if (!$validate->check($data)) {
            return return_data(false, $validate->getError());
        }
        //删除原有权限
        $this->where('role_id', $data['id'])->delete();
        $list = [];
        foreach ($data['access'] as $v) {
            $arr = explode('_', $v);
            $list[] = ['role_id' => $data['id'], 'node_id' => $arr['0'], 'level' => $arr['1']];
        }
        $this->saveAll($list);
        return return_data(true, '');
    }
    /*
     * 递归
     */
    public function recursionArr($arr, $pid = '0') {
        $data = false;
        foreach ($arr as $v) {
            if ($v['pid'] == $pid) {
                $v[config('classify_name')] = $this->recursionArr($arr, $v['id']);
                $data[] = $v;
            }
        }
        return $data;
    }
}