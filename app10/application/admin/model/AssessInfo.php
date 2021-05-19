<?php
namespace app\admin\model;
use think\Model;
use app\admin\validate\NodeValidates;

class AssessInfo extends Model {
    protected function initialize()
    {
        parent::initialize();
    }
    protected $pk = 'id';
    /*
     * author:莫博航
     * 连表查询
     */
    public function applylist($where,$order){
        $assessInfo = model('assessInfo');
        $info = $this
            ->alias('a')
            ->join('Area b','a.city = b.id')
            ->where($where)
            ->field('a.createtime, a.deletetime, a.status, b.name, a.id')
            ->order($order)
            ->paginate(config('list_rows'));
        return $info;
    }

    /*
     *连用户表查询
     */
    public function getWhereJoinList($where = [], $order = '') {
        if (!isset($where['a.status'])) {
            $where['a.status'] = 0;
        }
        $result = $this->alias('a')->where($where)->join('__USER__ u', 'u.id = a.uid')->field('a.*, u.realname')->order($order)->paginate(config('paginate.list_rows'));
        if ($result) {
            return $result;
        } else {
            return false;
        }
    }

    /*
     * 时间获取器
     */
    public function getCreatetimeAttr($value)
    {
        return date('Y-m-d H:i:s', $value);
    }

    public function getDeletetimeAttr($value)
    {
        return date('Y-m-d H:i:s', $value);
    }

    






}