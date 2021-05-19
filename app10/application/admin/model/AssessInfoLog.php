<?php
namespace app\admin\model;
use think\Model;
use app\admin\validate\NodeValidates;
class AssessInfoLog extends Model {
    protected function initialize()
    {
        parent::initialize();
    }
    protected $pk = 'id';
    protected $auto = ['id'];
    protected function setIdAttr($val) {
        return $val ? $val : createGuid();
    }
}