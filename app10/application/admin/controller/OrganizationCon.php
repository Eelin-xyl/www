<?php
namespace app\admin\controller;

use app\admin\model\Area;
use app\admin\model\Organization;

class OrganizationCon extends Common {
    public function __construct() {
        parent::__construct();
    }
    /*
     * 添加机构跳转
     */
    public function addOrg() {
        $data = $this->receive_data;
        $org = new Organization();//当前用户所在的机构
        if (request()->ispost()) {
            if (isset($data['msgmodule'])) {
                for ($i=0; $i < count($data['msgmodule']); $i++) {
                    $data['msgmodule'][$i]['district_pic'] = img_data_decode($data['msgmodule'][$i]['district_pic']);
                }
                $data['msgmodule'] = json_encode($data['msgmodule']);
            }
            $result = $org->addOrg($data);
            if ($result['state']) {
                $this->success('添加成功', url('nextList', array('pid' => $data['pid'])));
            } else {
                $this->error('添加失败，'. $result['data']);
            }
        }
        $org_info = $org->getOrgInfo(session('user.orgid'), 'id, name');
        $this->assign('org_type_list', config('org_type_list'));
        if ($org_info['state']) {
            $this->assign('org', [$org_info['data']]);
        } else {
            $this->assign('org', 0);
        }
        return $this->fetch('org/add', ['title' => '添加机构']);
    }
    /*
     * 添加机构
     */
    public function addOrgHeadler() {
        $data = $this->receive_data;
        $org = new Organization();
        if (request()->ispost()) {
            if (isset($data['msgmodule'])) {
                for ($i=0; $i < count($data['msgmodule']); $i++) {
                    $data['msgmodule'][$i]['district_pic'] = img_data_decode($data['msgmodule'][$i]['district_pic']);
                }
                $data['msgmodule'] = json_encode($data['msgmodule']);
            }
            $result = $org->addOrg($data);
            if ($result['state']) {
                $this->success('添加成功', url('nextList', array('pid' => $data['pid'])));
            } else {
                $this->error('添加失败，'. $result['data']);
            }
        }
        $result = $org->upOrg($data);
        if ($result['state']) {
            $this->success('更新成功', url('nextList', array('pid' => $data['pid'])));
        } else {
            $this->error('更新失败，' . $result['data']);
        }
    }
    /*
     * 机构编辑
     */
    public function edit() {
        $data = $this->receive_data;
        $org = new Organization();//当前用户所在的机构
        if (request()->post()) {
            if (isset($data['msgmodule'])) {
                for ($i=0; $i < count($data['msgmodule']); $i++) {
                    if (isset($data['msgmodule'][$i]['district_pic'])) {
                        $data['msgmodule'][$i]['district_pic'] = img_data_decode($data['msgmodule'][$i]['district_pic']);
                    }
                }
                $data['msgmodule'] = json_encode($data['msgmodule']);
            }
            $resutl = $org->upOrg($data);
            if ($resutl['state']) {
                $this->success('编辑成功', url('nextList', array('pid' => $data['pid'])));
            } else {
                $this->error('编辑失败，' . $resutl['data']);
            }
        }
        $this->assign('orgdata', ['id' => '', 'org_code' => '', 'name' => '', 'area' => '', 'address' => '', 'pid' => '', 'haschild' => '', 'type' => '', 'summary' => '']);
        if (!empty($data['id'])) {
            $info = $org->getOrgInfo($data['id'], 'id, pid, org_code, name, area, address, pid, haschild, type, summary, resosuperioritypic, econdevelopmentpic, econdevelopment, resosuperiority, msgmodule, summarypic');
            if ($info['state']) {
                $info['data']['resosuperioritypic'] = img_data_encode(json_decode($info['data']['resosuperioritypic'], true));
                $info['data']['econdevelopmentpic'] = img_data_encode(json_decode($info['data']['econdevelopmentpic'], true));
                $info['data']['summarypic'] = img_data_encode(json_decode($info['data']['summarypic'], true));
                $msgmodulelist = json_decode($info['data']['msgmodule'], true);
                for ($i=0; $i < count($msgmodulelist); $i++) {
                    $msgmodulelist[$i]['district_pic'] = img_data_encode($msgmodulelist[$i]['district_pic']);
                }

                $this->assign('orgdata', $info['data']);
                $this->assign('pname', $info['data']['name']);
                $this->assign('msgmodulelist', $msgmodulelist);
                $porg = $org->getOrgInfo($info['data']['pid'], 'name');
                if ($porg['state']) {
                    $this->assign('pname', $porg['data']['name']);
                }
            } else {
                $this->error($info['data']);
            }
        } else {
            $this->error('错误');
        }
        $this->assign('org_type_list', config('org_type_list'));
/*        echo "<pre>";
       echo "orgdata**"; print_r($info);
      echo "pname**";  print_r($porg);
      echo "msgmodulelist**";   print_r($msgmodulelist);
     echo "org_type_list**";    pe(config('org_type_list'));*/
        return $this->fetch('org/edit', ['title' => '机构编辑']);
    }
    /*
     * 机构删除
     */
    public function delete() {
        $data = $this->receive_data;
        if (empty($data['id'])) {
            $this->error('参数错误');
        }
        $org = new Organization();
        $re = $org->delOrg($data['id']);
        if ($re['state']) {
            $this->success('删除成功', url('nextList', array('pid' => $data['pid'])));
        } else {
            $this->error('删除失败，' . $re['data']);
        }
    }
    /*
     * 根据机构编码获取下一级机构
     */
    public function jsnextOrg() {
        $data = $this->receive_data;

        $org = new Organization();
        $result = $org->nextOrg($data['code']);
        if ($result['state']) {
            return ['state' => 'success', 'data' => $result['data']];
        } else {
            return ['state' => 'false'];
        }
    }
    /*
     * 机构列表
     */
    public function orgList() {
        $org_id = session('user.orgid');
        $org = new Organization();
        $info = $org->getOrgInfo($org_id, 'id, pid, org_code, name, type, level, haschild');
        $this->assign('orgdata', []);
        $this->assign('ppid', 0);
        if ($info['data']) {
            $this->assign('orgdata', [$info['data']]);
            $this->assign('ppid', $info['data']['pid']);
        }
        $this->assign('navigation_bar', []);
        $this->assign('num', 1);
        $this->assign('pid', $org_id);
        $this->assign('org_type_list', config('org_type_list'));
        return $this->fetch('org/orgList', ['title' => '管理机构']);
    }
    /*
     * 根据机构id获取子机构列表
     */
    public function nextList(){
        $pid = input('pid');
        if ($pid == NULL) {
            $this->error('参数错误');
        }
        $org = new Organization();
        $this->assign('navigation_bar', []);
        $this->assign('ppid', 0);
        if ($pid != '0') {
            $p_org = $org->getPOrg($pid);
            if ($p_org['state']) {
                $this->assign('navigation_bar', $p_org['data']);
                $this->assign('ppid', $p_org['data'][0]['pid']);
            }
        }
        $info = $org->getOrgList($pid, 'id, pid, org_code, name, type, level, haschild');
        $this->assign('orgdata', []);
        if ($info['data']) {
            $this->assign('orgdata', $info['data']);
        }
        $this->assign('num', 1);
        $this->assign('pid', $pid);
        $this->assign('org_type_list', config('org_type_list'));
        return $this->fetch('org/orgList', ['title' => '管理机构']);
    }
    /*
        ppt 汇报
    */
    public function showppt(){
        $orgid = input('orgid');
        if (!$orgid) {
            $this->error('参数错误');
        }
        $org = model('Organization');
        $year = date('Y') - 1;
        $info = $org->getOrgInfo($orgid);
        if (!$info['state']) {
            $this->error('参数错误！', url('nextList'));
        }
        $info = $info['data'];
        $info['econdevelopmentpic'] = json_decode($info['econdevelopmentpic'], true);
        $info['resosuperioritypic'] = json_decode($info['resosuperioritypic'], true);
        $info['summarypic'] = json_decode($info['summarypic'], true);
        $this->assign('info', $info);

        if ($info['type'] == 7) {
            $orgInfo = model('OrganizationInfo');
            $orginfo_info = $orgInfo->getInfo(['orgid' => $orgid, 'year' => $year]);
            if (!$orginfo_info['state']) {
                $orginfo_info['data'] = [];
                $this->error('请提醒该行政村完善村基本信息！', url('nextList', array('pid' => $info['pid'])));
            }
            $orginfo_info['data']['content'] = json_decode($orginfo_info['data']['content'], true);

            //获取登陆用户所在机构及其所有子机构
            $org_son = $org->getAllSonId($orgid);
            if (!$org_son['state'] && $org_son['data']) {
                $this->error($org_son['data']);
            }
            $org_son['data'][] = $orgid;
            $Household = model('Household');
            $HouseholdInfo = model('HouseholdInfo');
            $HouseholdMember = model('HouseholdMember');
            $Household_id_arr = $Household->getInfoByWhere(['orgid' => ['in', $org_son['data']]], 'id as householdid');
            $info = [];
            if ($Household_id_arr['state']) {
                array_walk($Household_id_arr['data'], function(&$val, $key) use (&$info){
                    $val = $val['householdid'];
                    $info[$val] = [];
                });
                $HouseholdInfo_info = $HouseholdInfo->getList(['year' => $year, 'householdid' => ['in', $Household_id_arr['data']], 'status' => 0]);
                if ($HouseholdInfo_info['state']) {
                    $HouseholdInfo_info = $HouseholdInfo_info['data'];
                    array_walk($info, function(&$val, $key) use ($HouseholdInfo_info){
                        foreach ($HouseholdInfo_info as $v_i) {
                            if ($v_i['householdid'] == $key) {
                                $val = $v_i;
                            }
                        }
                    });
                }
                $HouseholdMember_info = $HouseholdMember->getfieldinfo(['pid' => ['in', $Household_id_arr['data']], 'year' => $year, 'status' => 0]);
                if ($HouseholdMember_info['state']) {
                    $HouseholdMember_info = $HouseholdMember_info['data'];
                    array_walk($info, function(&$val, $key) use ($HouseholdMember_info){
                        foreach ($HouseholdMember_info as $v_m) {
                            if ($v_m['pid'] == $key) {
                                $val['member_list'][] = $v_m;
                            }
                        }
                    });
                }
            }
            //组合页面数据格式
            //贫困人口分析
            $poor_num_list = ['name' => [], 'data' => []];
            foreach (config('poor_num_list') as $poor_num_k => $poor_num_v) {
                $poor_num_list['name'][$poor_num_k] = $poor_num_v;
                $poor_num_list['data'][$poor_num_k] = 0;
            }
            //贫困属性分析
            $poor_attr_list = ['name' => [], 'data' => []];
            foreach (config('poor_attr_list') as $poor_k => $poor_v) {
                $poor_attr_list['name'][$poor_k] = $poor_v;
                $poor_attr_list['data'][$poor_k]['value'] = 0;
                $poor_attr_list['data'][$poor_k]['name'] = $poor_v;
            }
            //脱贫指标分析
            $alleviation_attr = db('household_alleviation_attr')->where('status', 0)->select();
            $alleviation = ['name' => [], 'istrue' => [], 'isfalse' => []];
            foreach ($alleviation_attr as$attr_v) {
                $alleviation['name'][$attr_v['titleenname']] = $attr_v['titlename'];
                $alleviation['istrue'][$attr_v['titleenname']] = 0;
                $alleviation['isfalse'][$attr_v['titleenname']] = 0;
            }

            //人口信息
            foreach (config('poor_attr_list') as $key => $value) {
                $population_msg_1[] = ['name' => $value, 'type' => 'bar', 'stack' => '未脱贫户数', 'data' => [0, 0]];
            }
            foreach (config('poor_attr_list_renshu') as $key => $value) {
                $population_msg_2[] = ['name' => $value, 'type' => 'bar', 'stack' => '未脱贫人数', 'data' => [0, 0]];
            }
            //致贫原因
            foreach (config('zhipingyuanyinlist') as $key => $value) {
                $cause[] = ['value' => 0, 'name' => $value];
            }
            //贫困人口年龄
            foreach (config('nianlingduan') as $key => $value) {
                $poverty_population[$key] = 0;
            }
            foreach ($info as $k => $v) {
                if (!isset($v['id'])) {
                    continue;
                }
                if ($v['poor_status'] == 0) {
                    //贫困人口分析
                    switch ($v['poor_num']) {
                        case 1:
                            $poor_num_list['data'][1]++;
                            break;
                        case 2:
                            $poor_num_list['data'][2]++;
                            break;
                        case 3:
                            $poor_num_list['data'][3]++;
                            break;
                        default:
                            $poor_num_list['data'][4]++;
                            break;
                    }
                    //贫困属性分析
                    foreach ($poor_attr_list['name'] as $poor_attr_k => $poor_attr_v) {
                        if ((string)$poor_attr_v === (string)$v['poor_attr']) {
                            $poor_attr_list['data'][$poor_attr_k]['value']++;
                        }
                    }
                    //脱贫指标分析
                    $alleviation_data = json_decode($v['alleviation'], true);
                    if (is_array($alleviation_data)) {
                        foreach ($alleviation_data as $all_k => $all_v) {
                            if ($all_v['is_have_list'] == '1') {
                                $alleviation['istrue'][$all_k]++;
                            } else {
                                $alleviation['isfalse'][$all_k]++;
                            }
                        }
                    }
                }
                

                array_walk($population_msg_1, function(&$val, $key) use ($v) {
                    if ($v['poor_attr'] == $val['name']) {
                        if ($v['poor_status'] == 0) {
                            $val['data'][0] += 1;
                        } else {
                            $val['data'][1] += 1;
                        }
                    }
                });
                array_walk($population_msg_2, function(&$val, $key) use ($v) {
                    if (strpos($val['name'], $v['poor_attr']) === 0) {
                        if ($v['poor_status'] == 0) {
                            $val['data'][0] += $v['poor_num'];
                        } else {
                            $val['data'][1] += $v['poor_num'];
                        }
                    }
                });
                //致贫原因
                array_walk($cause, function(&$val, $key) use ($v) {
                    if ($val['name'] == $v['causes_of_poverty1']) {
                        $val['value']++;
                    }
                });
                //贫困人口年龄
                if (isset($v['member_list'])) {
                    foreach ($v['member_list'] as $key => $value) {
                        $year_age = substr($value['idcard'], 6, 4);
                        $age = date('Y') - $year_age;
                        foreach (config('nianlingduan') as $k_n => $v_n) {
                            $a_arr = explode('-', $v_n);
                            if (count($a_arr) > 1 && $a_arr[0] <= $age && $age < $a_arr[1]) {
                                $poverty_population[$k_n]++;
                            } elseif (count($a_arr) == 1 && substr($v_n, 0, 2) <= $age) {
                                $poverty_population[$k_n]++;
                            }
                        }
                    }
                }
            }
            //致贫原因分析
            $poor_num = 0;
            $max_k = 0;
            $max_val = 0;
            foreach ($cause as $key => $value) {
                $poor_num += $value['value'];
                if ($max_val < $value['value']) {
                    $max_val = $value['value'];
                    $max_k = $key;
                }
            }
            if ($poor_num == 0) {
                $analyze = '贫困户致贫原因中';
                foreach (config('zhipingyuanyinlist') as $key => $value) {
                    $analyze .= $value . '占比0%';
                    $zhipingyuanyinlist = config('zhipingyuanyinlist');
                    if (isset($zhipingyuanyinlist[$key + 1])) {
                        $analyze .= '，';
                    }
                }
            } else {
                $analyze = '贫困户主要致贫原因为' . config('zhipingyuanyinlist.' . $max_k) . '，占比' . number_format(($cause[$max_k]['value']/ $poor_num * 100), 2, '.', '') . '%' ;
                $analyze .= '，其余为';
                foreach (config('zhipingyuanyinlist') as $key => $value) {
                    if ($key == $max_k) {
                        continue;
                    } else {
                        $analyze .= $value . number_format(($cause[$key]['value']/ $poor_num * 100), 2, '.', '') . '%';
                        $zhipingyuanyinlist = config('zhipingyuanyinlist');
                        if (isset($zhipingyuanyinlist[$key + 1])) {
                            $analyze .= '，';
                        }
                    }
                }
            }
            //人口年龄结构分析
            $max_val = 0;
            $nianlingduan = '空';
            foreach ($poverty_population as $key => $value) {
                if ($max_val < $value) {
                    $max_val = $value;
                    $nianlingduan = config('nianlingduan.' . $key);
                }
            }
            $population_msg = array_merge($population_msg_1, $population_msg_2);
            //贫困人口分析
            $this->assign('poor_num_list', $poor_num_list);
            //贫困属性分析
            $this->assign('poor_attr_list_name', $poor_attr_list['name']);
            $this->assign('poor_attr_list_data', json_encode($poor_attr_list['data']));
            //脱贫指标分析
            $this->assign('alleviation', $alleviation);
            
            $this->assign('orginfo_info', $orginfo_info['data']);
            $this->assign('population_msg', json_encode($population_msg));
            $this->assign('analyze', $analyze);
            $this->assign('cause', json_encode($cause));
            $this->assign('nianlingduan', $nianlingduan);
            $this->assign('poverty_population', json_encode($poverty_population));
            return $this->fetch('ppt/show');
        } else {

            $msgmodule = json_decode($info['msgmodule'], true);
            $this->assign('msgmodule', $msgmodule);
            return $this->fetch('ppt/show_qu');
        }

    }
}
