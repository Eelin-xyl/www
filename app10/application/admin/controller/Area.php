<?php
namespace app\admin\controller;


class Area extends Common {
    public function __construct() {
        parent::__construct();
    }
    /*
     * 根据id获取下一级信息
     */
    public function jsnextArea() {
        $data = $this->receive_data;
        $area = model('Area');
        $result = $area->search_part($data['code']);
        if ($result['state']) {
            return ['state' => 'success', 'data' => $result['data']];
        } else {
            return ['state' => 'false'];
        }
    }
    /*
     *
     */
    public function top() {
        $area = model('Area');//最高级行政区域
        $area_info = $area->search_Top();
        $this->assign('area', $area_info['data']);
    }
    

}
