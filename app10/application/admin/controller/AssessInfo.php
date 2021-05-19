<?php
namespace app\admin\controller;


use app\admin\model\AssessInfoLog;
use think\Db;

class AssessInfo extends Common {
    public function __construct() {
        parent::__construct();
    }
    //申请
    public function apply() {
        

        return $this->index();
    }
    //审核
    public function audit() {
        

        return $this->index();
    }
    /*
        评估列表 0
     */
    public function index($where = []) {
        $list = Db::name('assess_info')->where('status', 0)->select();
        $this->assign('list', $list);
        return $this->fetch('index', ['title' => '评估列表']);
    }
    public function addAssess(){
        
    }

    /*
     * 未受理列表 1
     */
    public function noAcceptIndex() {
        return $this->index();
    }

    /*
     * 待评估列表 2
     */
    public function noAssessIndex() {
        return $this->index();
    }

    /*
     * 已评估列表 4
     */
    public function yetAssessIndex() {
        return $this->index();
    }

    /*
     * 新添加申请 
     */
    public function addApply() {

    }

    
    /*
     * 开始受理 
     */
    public function acceptStart() {
        
        return $this->fetch('doassess',['title' => '待评论']);

    }

    /*
     * 开始评估 
     */
    public function assessStart() {

    }

    /*
     * 查看报告 
     */
    public function show() {

    }

    
    /*
     *  评估申请列表
     */
    /*
     * author:莫博航
     */
    public function applyList() {
        return $this->index();
    }

    /*  删除申请  */
    /*
    * author:莫博航
    */
    public function del() {
        $data = $this->receive_data;
        $id = Db::name('assess_info')->where('id', $data['id'])->update(['status'=> 1]);
        return $this->success(url('index'));
    }


    /*
     *  评估中
     */
    public function beAssessIndex() {
        return $this->index();
    }


}
