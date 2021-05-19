<?php
namespace app\admin\model;

use think\Model;
use app\admin\validate\NodeValidates;

class Node extends Model {
    protected function initialize()
    {
        parent::initialize();
    }
    protected $pk = 'id';
    protected $auto = ['id'];
    protected function setIdAttr($val) {
        return $val ? $val : createGuid();
    }
    /**通过id查询node表数据
     * @param $id  nodeid
     * @return array|false|\PDOStatement|string|Model  查询出的节点
     */
    public function search($id) {
        if (!$id) {
            return return_data(false, '传入参数为空');
        }
        $data = $this->where(['status' => 1, 'id' => $id])->field('id, name, title, sort, level, status')->find()->toArray();
        if ($data) {
            return return_data(true, $data);
        } else {
            return return_data(false, '暂无数据');
        }
    }
    /**
     * 添加节点
     * @param $data 需要添加的节点数组
     * @return int  1 添加成功 2 添加失败
     */
    public function addNode($data) {
        $validate = new NodeValidates('addNode');
        if (!$validate->check($data)) {
            return return_data(false, $validate->getError());
        }
        //    $data['name'] = mb_strtoupper($data['name']);
        if ($this->allowField(true)->save($data)) {
            return return_data(true, '');
        } else {
            return return_data(false, '添加失败');
        }
    }
    /*
     *sort的默认值
     * */
    public function sort(){
        $pid=input('pid');
        $level=input('level');
        $sort=$this->field('sort')->where('pid',$pid)->where('level',$level)->order('sort desc')->limit(1)->find();
        $sort['sort'];
        $sort=$sort['sort']+1;
        return $sort;
    }
    /*
     * 编辑节点
     */
    public function edNode($data) {
        $validate = new NodeValidates('upNode');
        if (!$validate->check($data)) {
            return return_data(false, $validate->getError());
        }
    //    $data['name'] = strtoupper($data['name']);
   //     $result = $this->where('id', $data['id'])->setField('name', 'title', 'status', 'sort')->update($data);
        $result =  $this->allowField(['name', 'title', 'status', 'sort'])->save($data, ['id' => $data['id']]);
        if ($result >= 0) {
            return return_data(true, '');
        } else{
            return return_data(false, '更新失败');
        }
    }
    /*
     * 删除节点
     */
    public function delNode($id) {
        if (!$id) {
            return return_data(false, '传入参数为空');
        }
        $info = $this->where('id', $id)->find();
        if (!$info) {
            return return_data(false, '该节点不存在');
        } else if ($info['status'] == 0) {
            return return_data(false, '该节点已被删除');
        }
        $result = $this->save(['status' => 0], ['id' => $id]);
        if ($result) {
            //删除所有子节点
            $re = $this->delSonNode($id);
            return return_data(true, '删除成功');
        } else {
            return return_data(false, '删除失败');
        }
    }
    /*
     * 删除所有子节点
     */
    public function delSonNode($id) {
        if (!$id) {
            return return_data(false, '传入参数为空');
        }
        $son_node = $this->where(['status' => 1, 'pid' => $id])->select();
        $son_str = '';
        foreach ($son_node as $k => $v) {
            $son_str .= $v['id'] . ',';
        }
        if ($son_str != '') {
            $this->where('id', 'in', $son_str)->update(['status' => 0]);
            $son_son_node = $this->where(['status' => 1, 'pid' => ['in', $son_str]])->select();
            $son_son_str = '';
            foreach ($son_son_node as $key => $value) {
                $son_son_str .= $value['id'] . ',';
            }
            if ($son_son_str != '') {
                $this->where('id', 'in', $son_son_str)->update(['status' => 0]);
            }
        }
    }
    /**
     * 查询所有节点数据，并体现层级
     * @return array  节点树形数组
     */
    public function getNodeInfo() {
        $node_info_all = collection($this->where('status', 1)->field('id, name, title, level, pid')->order('sort')->select())->toArray();
        if (!$node_info_all) {
            return return_data(false, '暂无节点信息');
        }
        $return_data = $this->recursion($node_info_all);
         return return_data(true, $return_data);
    }
    /*
    * 实现node节点的层级关系
     */
    public function recursion($data, $pid = '0') {
        $recor = false;
        foreach ($data as $v) {
            if($v['pid'] == $pid) {
                $v[config('classify_name')] = $this->recursion($data, $v['id']);
                $recor[] = $v;
            }
        }
        return $recor;
    }


    /**
     * 查询node表获取所有节点，调用getChildrenid 方法，通过节点id查询子类节点
     * @param $nodeid   要查询子类节点的父级节点id
     * @return array 节点的子类id数组
     */
    public function getChildren($nodeid) {
        $node = new Node;
        $noderes = $node->select();
        return $this->getChildrenid($noderes, $nodeid);
    }
    /**
     * 递归查询子类id，保存在数组$arr中
     * @param $noderes  所有节点数组
     * @param $nodeid   父级id
     * @return array    父级节点的子类id数组
     */
    public function  getChildrenid($noderes, $nodeid) {
        static $arr = array();
        foreach ($noderes as $k=>$v) {
            if ( $nodeid == $v['pid'] ) {
                $arr[] = $v['id'];
                $this->getChildrenid($noderes, $v['id']);
            }
        }
        return $arr;
    }

    /**
     * 查询所有节点数据，调用topNode方法获取所有的最高级节点，并添加folg字段给每个节点编号
     * @return array  所有的最高级节点数组
     */
    public function nodeTree() {
        $node = new Node;
        $noderes = $node->select();
        return $node->topNode($noderes);
    }

    /**
     * 循环获取最高节点数组
     * @param $data  所有的节点数组
     * @param string $pid 节点pid
     * @param int $flog  最高级节点编号
     * @return array  所有的最高级节点数组
     */
    public function topNode($data, $pid = '0', $flog = 0) {
        static $trr = array();
        foreach ($data as $k => $v) {
            if ( $pid == $v['pid'] ) {
                $trr[] = array("title"=>$v['title'], "flog"=>$flog);
                $flog = $flog+1;
            }
        }
        return $trr;
    }
    
    /**
     * 判断所有节点中是否包含用户已经拥有的节点，如果拥有就在所有节点数组中添加flog=1来标志，否则flog为0
     * 用于自动勾选出当前角色已经有的节点
     * @param $nodes  所有的节点
     * @param $rolenoderes  角色拥有的节点
     * @return array  添加folg标志的所有节点树形数组
     */
    public function  roleFlog($nodes, $rolenoderes) {
        foreach ($nodes as $k=>$v){
            $v['flog'] = 0;
            foreach ($rolenoderes as $key=>$vo) {
                if ( $v['id'] == $vo['node_id'] ) {
                    $v['flog'] = 1;
                    break ;
                } else {
                    $v['flog'] = 0;
                }
            }
        }
        $arr = $this::recursion($nodes);
        return $arr;
    }
    /**
     * 删除子节点
     */
    public function delNodeAccess() {
        $nodeid = input('id');
        $sonids = $this->getChildren($nodeid);
        if ($sonids) {
           $this->where('id','in', $sonids)->delete();
            Access::where('node_id','in', $sonids)->delete();
        }
    }
}