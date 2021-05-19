<?php
namespace app\admin\model;
use think\Model;
use app\admin\validate\NodeValidates;

class AssessInform extends Model {
    protected function initialize()
    {
        parent::initialize();
    }
    protected $pk = 'id';
    

    /*
     * 时间获取器
     */
    public function getStarttimeAttr($value)
    {
        return date('Y-m-d H:i:s', $value);
    }

    public function getEndtimeAttr($value)
    {
        return date('Y-m-d H:i:s', $value);
    }
    public function getTimecreatedAttr($value)
    {
        return date('Y-m-d H:i:s', $value);
    }
    public function getTimemodifiedAttr($value)
    {
        return date('Y-m-d H:i:s', $value);
    }
}