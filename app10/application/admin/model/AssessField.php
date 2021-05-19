<?php
namespace app\admin\model;
use think\Model;
use app\admin\validate\NodeValidates;
class AssessField extends Model {
    protected function initialize()
    {
        parent::initialize();
    }
    protected $pk = 'id';
  
  
    /**@author  冉孟圆
     * 查询所有节点数据，并体现层级
     * @return array  节点树形数组
     */
    public function getReportManaInfo(){
        $reportmanaInfoAll = collection($this->where('status',0)->field('id,pid,level,name')->order('id')->select())->toArray();
        $return_data = $this->recursion($reportmanaInfoAll);
        return return_data(true, $return_data);
    }

    /*
    *@author  冉孟圆
    * 实现assessField节点的层级关系
     */
    public function recursion($data, $pid = '0') {

        $arr = array();
        foreach ($data as $v) {
            if($v['pid'] == $pid) {
                $v['_children'] = $this->recursion($data, $v['id']);
                $arr[] = $v;

            }
        }
        return $arr;
    }


    /*
    *@author  冉孟圆
     * 查询assessField表获取所有节点，调用getChildrenid 方法，通过节点id查询子类节点
     * param $id   要查询子类节点的父级节点id
     */
    public function getChildren($assessFieldid) {
        $assessField = new AssessField;
        $assessFieldres = $assessFieldres->select();
        return $this->getChildrenid($assessFieldres, 
            $assessField);
    }

    /*
    *@author  冉孟圆
     * 递归查询子类id，保存在数组$arr中
     * param $assessFieldres  所有节点数组
     * param $assessField   父级id
     * return array    父级节点的子类id数组
     */
    public function  getChildrenid($reportManares, 
        $assessField) {
        static $arr = array();
        foreach ($assessFieldres as $k=>$v) {
            if ( $assessField == $v['pid'] ) {
                $arr[] = $v['id'];
                $this->getChildrenid($assessFieldres, $v['id']);
            }
        }
        return $arr;
    }    

    /**@author  冉孟圆
     * 通过申请id来删除指定父节点
     * @param $id 父id
     */

    public function delReportMana($id){
        if (!$id) {
            return return_data(false, '传入参数为空');
        }
        $info = $this->where('id', $id)->find();
        if (!$info) {
            return return_data(false, '该评估不存在');
        } else if ($info['status'] == 1) {
            return return_data(false, '该评估已被删除');
        }
        $result = $this->update(['status' => 1], ['id' => $id]);
        if ($result) {
            //删除所有子节点
            $re = $this->delSonReportMana($id);
            return return_data(true, '删除成功');
        } else {
            return return_data(false, '删除失败');
        }
    }

    /*
    *@author  冉孟圆
     * 删除所有子节点
     */
    public function delSonReportMana($id) {
        if (!$id) {
            return return_data(false, '传入参数为空');
        }
        $son_assessField = $this->where(['status' => 0, 'pid' => $id])->select();
        $son_str = '';
        foreach ($son_assessField as $k => $v) {
            $son_str .= $v['id'] . ',';
        }
        if ($son_str != '') {
            $this->where('id', 'in', $son_str)->update(['status' => 1]);
            $son_son_assessField = $this->where(['status' => 0, 'pid' => ['in', $son_str]])->select();
            $son_son_str = '';
            foreach ($son_son_assessField as $key => $value) {
                $son_son_str .= $value['id'] . ',';
            }
            if ($son_son_str != '') {
                $this->where('id', 'in', $son_son_str)->update(['status' => 1]);
            }
        }
    }

    /*判断添加指标的序号是否存在
     *@param int level
     *@param int thisNum
     *@param char thisLevel
     */
    public function isExist($level, $thisNum, $thisLevel){
        $isExist = $this->where('level',$level)->where('status','0')->where($thisLevel,$thisNum)->find();
        if ($isExist){
            return 1;//存在
        }
    }
    /*根据指标序号返回其id,pstring
     *@author 李桐
     *@param int thisNum
     *@param int level
     *@param char thisLevel
     */
    public function pInformation($level, $thisNum, $thisLevel){
        $pInformation = $this->where('level',$level)->where('status','0')->where($thisLevel,$thisNum)->field('id,pstring')->find();
        $res['id'] = $pInformation['id'];
        $res['pstring'] = $pInformation['pstring'];
        return $res;
    }
}