<?php
namespace app\admin\model;
use think\Model;

class Area extends Model {
    protected function initialize()
    {
        parent::initialize();
    }
    protected $pk = 'id';

    public function searchTo($data) {
        if($data['type'] == 'top') {
            $top = $this->search_Top($data);
            return getBack(0,$top);
        }
        else if($data['type'] == 'id') {
           $child = $this->search_part($data);
           return getBack(0,$child);
       }
        else if($data['type'] == 'back') {
            $back = $this->search_back($data);
            return getBack(0,$back);
        }
    }
    public function search_Top() {
        $res = collection($this->where(['status' => 1, 'pid' => 0])->field('code, name')->select())->toArray();
        if ($res) {
            return return_data(true, $res);
        } else {
            return return_data(false, '暂无信息');
        }
    }
    public function search_part($code) {
        if ($code === false) {
            return return_data(false, '传入参数错误');
        }
        $id = $this->where(['status' => 1, 'code' => $code])->value('id');
        if ($id) {
            $info = collection($this->where(['status' => 1, 'pid' => $id])->field('code, name')->select())->toArray();
            if ($info) {
                return return_data(true, $info);
            } else {
                return return_data(false, '暂无数据');
            }
        } else {
            return return_data(false, '暂无数据');
        }
    }
    public function search_back($dataSelf = null, $pid = 0) {
            $dataSelf = $this->dataSelf();
            $array = array();
        foreach ($dataSelf as $v) {
            if ( $v['pid'] == $pid ) {
                $array[] = $v;
                $arr = array_merge($array,$this->search_back($dataSelf,$v['id']));
            }
        }
        return $array;
    }
    public function dataSelf() {
        $area = new Area();
        $dataSelf = $area->select()->toArray();
        return $dataSelf;
    }
    /*
     * 根据指定code获取包含该区域的所有上级区域
     */
    public function getparInfo($code) {
        if (!$code) {
            return return_data(false, '参数错误');
        }
        $this_info = $this->where(['status' => 1, 'code' => $code])->field('id, code, name, parentstr')->find();
        if (!$this_info) {
            return return_data(false, '暂无该信息');
        }
        $this_info = $this_info->toArray();
        $parentstr = explode(',', $this_info['parentstr']);
        unset($this_info['parentstr']);
        $parent_arr = collection($this->where(['status' => 1, 'id' => ['in', $parentstr]])->order('id')->field('id, code, name')->select())->toArray();
        if ($parent_arr) {
            $parent_arr[] = $this_info;
            return return_data(true, $parent_arr);
        } else {
            return return_data(true, [$this_info]);
        }
    }

}