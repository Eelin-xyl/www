<?php
namespace app\admin\controller;

class AssessManage extends Common {
    public function __construct() {
        parent::__construct();
    }


   /*
   *@author 冉孟圆
    *评估管理----显示所有评估数据
    */
    public function index() {
        $reportMana = model('AssessField');
        $reportMana_info = $reportMana->getReportManaInfo();
        $this->assign('data',[]);
        if ($reportMana_info['data']){
            $this->assign('data',$reportMana_info['data']);
        }
        return $this->fetch('',['title' => '评估管理']);
    }
   /*
   *@author 冉孟圆
    *删除评估管理
    */
    public function delete() {
        $data = $this->receive_data;
        $reportMana = model('assessField');
        $result = $reportMana->delReportMana($data['id']);
        if ($result['state']) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }

    /*添加指标页面
     *@author 李桐
     **@param
     */
    public  function add(){
        $data = $this->receive_data;
        if (request()->post()){
            $af = model('AssessField');
            $pMessage = $af->where('id',$data['pid'])->where('status','0')->find();
            if ($pMessage){
                $data['bigNum'] = $pMessage['big_no'];
                $data['secondNum'] = $pMessage['second_no'];
            }else{

            }
            switch ($data['level']){
                case '1':
                    //判断添加的序号是否存在
                    $res = $af->isExist($data['level'], $data['thisNum'], 'big_no');
                    if ($res){
                        $this->error('添加失败,序号已存在！');
                    }
                    $list = [
                            'pid' => '0', 
                            'pstring' => '0,', 
                            'level' => $data['level'], 
                            'big_no' => $data['thisNum'], 
                            'name' => $data['name'], 
                            'status' => '0', 
                            'createtime' => time()
                        ];
                    break;
                case '2':
                    $res = $af->isExist($data['level'], $data['thisNum'], 'second_no');
                    if ($res){
                        $this->error('添加失败,序号已存在！');
                    }
                    $pInformation = $af->pInformation($data['level']-1, $data['bigNum'], 'big_no');
                    $pstring = $pInformation['pstring'].$pInformation['id'].",";
                    $list = [
                            'pid' => $pInformation['id'], 
                            'pstring' => $pstring, 
                            'level' => $data['level'], 
                            'big_no' => $data['bigNum'], 
                            'second_no' => $data['thisNum'], 
                            'name' => $data['name'], 
                            'status' => '0', 
                            'createtime' => time()
                        ];
                    break;
                case '3':
                    $res = $af->isExist($data['level'], $data['thisNum'], 'third_no');
                    if ($res){
                        $this->error('添加失败,序号已存在！');
                    }
                    $pInformation = $af->pInformation($data['level']-1, $data['secondNum'], 'second_no');
                    $pstring = $pInformation['pstring'].$pInformation['id'].",";
                    $temp = [
                            'type' => $data['type'], 
                            'defaultName' => $data['defaultName'], 
                            'default' => $data['default'], 
                            'grade' => $data['grade'], 
                            'hint' => $data['hint']
                        ];
                    $option = json_encode($temp);
                    $list = [
                            'pid' => $pInformation['id'], 
                            'pstring' => $pstring, 
                            'level' => $data['level'], 
                            'big_no' => $data['bigNum'], 
                            'second_no' => $data['secondNum'], 
                            'third_no' => $data['thisNum'], 
                            'name' => $data['name'], 
                            'option' => $option, 
                            'calculate_function' => $data['calculate_function'], 
                            'calculate_type' => $data['calculate_type'], 
                            'score' => $data['score'], 
                            'status' => '0', 
                            'indicator_hints' => $data['indicator_hints'], 
                            'createtime' => time()
                        ];
                    break;
            }
            $result = $af->save($list);
            if ($result){
                $this->success('添加成功', 'admin\Assess_Manage\index');
            }else{
                $this->error('添加失败');
            }
        }else{
            $this->assign('thisLevel', $data['level']);
            $this->assign('pid', $data['pid']);
            return $this->fetch('', ['title' => '添加指标']);
        }
    }
    /*编辑指标
     *@author 李桐
     *@param int   level 确定指标等级
     *@param int   id    
     *@param array $data 用户修改的信息
     *说明：一二级指标只能修改指标名，三级指标可以修改其他信息
     */
    public function edit(){
        $af = model('AssessField');
        if (request()->post()){
            $data = $this->receive_data;
            if ($data['level'] == 3){
                $isExist = $af->where('level',$data['level'])->where('third_no',$data['thisNum'])->where('status','0')->find();
                if ($isExist){
                    $this->error('修改失败，该序号已存在！');
                }else{
                    $temp = [
                            'type' => $data['type'], 
                            'defaultName' => $data['defaultName'], 
                            'default' => $data['default'], 
                            'grade' => $data['grade'], 
                            'hint' => $data['hint']
                        ];
                    $option = json_encode($temp);
                    $list = [
                            'third_no' => $data['thisNum'], 
                            'name' => $data['name'], 
                            'option' => $option, 
                            'calculate_function' => $data['calculate_function'], 
                            'calculate_type' => $data['calculate_type'], 
                            'score' => $data['score'], 
                            'indicator_hints' => $data['indicator_hints']
                        ];
                }
            }else{
                $list = ['name' => $data['name']];
            }
            $result = $af->save($list,['id' => $data['id']]);
            if ($result){
                $this->success('修改成功', 'admin\Assess_Manage\index');
            }else{
                $this->error('修改失败');
            }
        }else{
            $id = $this->receive_data;
            $data = $af->where('id',$id['id'])->where('status','0')->find();
            $data['option']= json_decode($data['option']);
            $this->assign('data', $data);
            return $this->fetch('',['title' => '编辑指标']);
        }
    }

}
