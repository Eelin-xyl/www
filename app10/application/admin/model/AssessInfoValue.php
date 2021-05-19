<?php
namespace app\admin\model;
use think\Model;
use app\admin\validate\NodeValidates;

class AssessInfoValue extends Model {
    protected function initialize()
    {
        parent::initialize();
    }
    protected $pk = 'id';
    /*
     *  获取评估的详情信息
     *  @宋玉琪
     *  $id:评估主体id
     */
    public function getInfo($id){
        $field = "aiv.id, aiv.fieldid, pid, name, aiv.score, aiv.content, aiv.attachments, aiv.value, big_no, second_no, third_no";
        $join = [
            ['AssessField af', 'af.id = aiv.fieldid', 'LEFT']
        ];
        $where = [
            'infoid' => $id,
            'aiv.status' => '0'
        ];
        $info = collection($this->alias('aiv')->field($field)->join($join)->where($where)->select())->toArray();
        return $info;
    }
}