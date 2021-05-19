<?php
namespace app\admin\model;

use think\Model;
use app\admin\validate\Validates;

class Organization extends Model{
    protected function initialize()
    {
        parent::initialize();
    }
    protected $pk = 'id';
    protected $auto = ['id'];
    protected function setIdAttr($val) {
        return $val ? $val : createGuid();
    }

    /*
     * 机构添加
     *
     *@param varchar area         保存前端传入的机构所属地区
     *@param char    pid          保存前端传入的父路径ID
     *@param char    org_code     保存前端传入的编号
     *@param tinyint haschild     记录是否存在下一级
     *@param int     name         保存前端传入的机构名称
     *@param text    address      保存前端传入的详细地址
     *@param varchar type         保存前端传入的机构类型
     *@param text    summary      保存前端传入的机构简介
     */
    public function addOrg($data) {
        $validate = new Validates('org_add');
        if(!$validate->check($data)){
            return return_data(false, $validate->getError());
        }
        $this_parentstr = config('parentstr_prefix');
        if (!empty($data['pid'])) {
            $parentstr = $this->getOrgInfo($data['pid'], 'parentstr');
            if ($parentstr['state']) {
                $this_parentstr = $parentstr['data']['parentstr'] . $data['pid'] . ',';
            } else {
                return return_data(false, $parentstr['data']);
            }
        }
        $data['parentstr'] = $this_parentstr;
        $data['status'] = '0';
        $data['timecreated'] = time();
        $data['econdevelopmentpic'] = json_encode(img_data_encode($data['econdevelopmentpic']));
        $data['resosuperioritypic'] = json_encode(img_data_encode($data['resosuperioritypic']));
        $data['summarypic'] = json_encode(img_data_encode($data['summarypic']));
        $res = $this->allowField(true)->data($data)->save();
        $this->where('id', $data['pid'])->update(['haschild' => 1]);
        return $res ? return_data(true, '') : return_data(false, '保存失败！');
    }
    /*
     * 机构删除
     * @param string $str   机构id，可以批量删除，以逗号连接
     */
    public function delOrg($id) {
        $id_arr = explode(',', $id);
        if ($id_arr) {
            $true = true;
            foreach ($id_arr as $val) {
                $result = $this->delOrgAndSon($val);
                if (!$result['state']) {
                    $true = false;
                    return return_data(false, $result['data']);
                }
            }
            if ($true) {
                return return_data(true, '');
            } else {
                return return_data(false, '删除机构出错！');
            }
        } else {
            return return_data(false, '传入数据错误');
        }
    }
    /*
     机构修改
     ['name' => '', 'area' => '', 'address' => '', 'summary' => '', 'type' => '']
     */
    public function upOrg($data) {
        $validate = new Validates('org_up');
        if(!$validate->check($data)){
            return return_data(false, $validate->getError());
        }
        $data['econdevelopmentpic'] = json_encode(img_data_decode($data['econdevelopmentpic']));
        $data['resosuperioritypic'] = json_encode(img_data_decode($data['resosuperioritypic']));
        $data['summarypic'] = json_encode(img_data_decode($data['summarypic']));
        $data['timemodified'] = time();
        $this_org_info = $this->getOrgInfo($data['id'], 'id');
        if (!$this_org_info['state'] || empty($this_org_info['data']['id'])) {
            return return_data(false, '无权限操作此机构');
        }
        $res = $this->allowField(true)->save($data, ['id'=>$data['id']]);
        if (!$res) {
            return return_data(false, '更新失败');
        } else {
            return return_data(true, '');
        }
    }
    /*
     获取机构信息
     @param string $id 查询信息
     @param string $str 查询字段，默认为所有
     */
    public function getOrgInfo($id = '', $str = "*") {
        $info = [];
        //判断该机构是否在用户所在机构及子机构中
        $user_org_parent = $this->where(['status' => 0, 'id' => session('user.orgid')])->value('parentstr');
        $where['status'] = 0;
        $where['id'] = $id;
        //$where['pid'] = $pid;
        $info = $this->allowField(true)->where($where)->where(function ($q) use ($user_org_parent) {
            $q->where('parentstr', 'like', $user_org_parent . session('user.orgid') . '%')->whereor('id', session('user.orgid'));
        })->field($str)->find();

        if (!$info) {
            return return_data(false, '空数据');
        } else {
            $info = $info->toArray();
        }
        return return_data(true, $info);
    }

    /*
     获取机构列表
     @param string $str 查询字段，默认为所有
     @param string $pid 根据父id查询信息
     */
    public function getOrgList($pid = 0, $str = "*") {
        $info = [];
        $user_org_parent = $this->where(['status' => 0, 'id' => session('user.orgid')])->value('parentstr');
        $where['status'] = 0;
        $where['pid'] = $pid;
        $info = $this->where($where)->where(function ($q) use ($user_org_parent) {
            $q->where('parentstr', 'like', $user_org_parent . session('user.orgid') . '%')->whereor('id', session('user.orgid'));
        })->field($str)->select();

        if (!$info) {
            return return_data(false, '空数据');
        } else {
            $info = collection($info)->toArray();
        }
        return return_data(true, $info);
    }


    /*
     * 删除机构，并删除该机构下的所有子机构
     */
    public function delOrgAndSon($id) {
        if (!is_string($id) || empty($id)) {
            return return_data(false, '传入数据错误');
        }
        //获取该机构下的parentstr
        $orgInfo = $this->getOrgInfo($id,  'parentstr, status');
        if (!$orgInfo['state']) {
            return $orgInfo;
        }
        //用户所在的机构信息
        $user_org_parent = $this->getOrgInfo(session('user.orgid'), 'parentstr');
        if (!$user_org_parent['state']) {
            return $user_org_parent;
        }
        //检查所要操作的机构是否属于用户所在机构之内
        if (strpos($orgInfo['data']['parentstr'], $user_org_parent['data']['parentstr']) === false) {
            return return_data(false, '该用户无权限操作该机构');
        }
        //修改本机构status
        $istrue = $this->where('id', $id)->update(['status' => 1, 'timemodified' => time()]);
        if ($orgInfo['data']['status'] === 0) {
            if ($istrue) {
                //删除子机构
                $this->where('parentstr', 'like', $orgInfo['data']['parentstr'] . $id . '%')->update(['status' => 1, 'timemodified' => time()]);
                return return_data(true, '');
            } else {
                return return_data(false, '删除失败');
            }
        } else {
            return return_data(false, '该机构已经被删除');
        }
    }
    /*
     * 根据机构id得到该机构所有子机构id,返回一维数组
     */
    public function getAllSonId($id) {
        if (!$id) {
            return return_data(false, '传入数据错误');
        }
        $this_org_parentstr = $this->where(['status' => 0, 'id' => $id])->value('parentstr');
        if (!$this_org_parentstr) {
            return return_data(false, '该用户机构无效');
        }
        $all_son_org = collection($this->where(['parentstr' => ['like', $this_org_parentstr . $id . '%'], 'status' => 0])->field('id')->select())->toArray();
        array_walk($all_son_org, function(&$val, $k) {
            $val = $val['id'];
        });
        return return_data(true, $all_son_org);
    }
    /*
     * 根据机构id得到该机构的下一子机构信息
     */
    public function nextOrg($id, $str = 'id, name') {
        if (!isset($id)) {
            return return_data(false, '传入参数错误');
        }
        //该登录用户所能查看的所有子机构
        $user_org_parent = $this->where(['status' => 0, 'id' => session('user.orgid')])->value('parentstr');
        $info = collection($this->where(['status' => 0, 'pid' => $id, 'parentstr' => ['like', $user_org_parent . session('user.orgid') . '%']])->field($str)->select())->toArray();
        if ($info) {
            return return_data(true, $info);
        } else {
            return return_data(false, '暂无数据');
        }
    }
    /*
     * 根据机构id获取该管理的最高机构到该机构之间的所有机构，包含该机构
     */
    public function getPOrg($id) {
        $parentstr = $this->where(['status' => 0, 'id' => session('user.orgid')])->value('parentstr');
        $num = mb_strpos($parentstr, $id);
        if (is_numeric($num) && $num >= 0 ) {
            $id = session('user.orgid');
        }
        $this_org = $this->where(['status' => 0, 'id' => $id])->find();
        $in = str_replace($parentstr, "", $this_org['parentstr'], $count);
        $info = $this->where(['status' => 0, 'id' => ['in', $in]])->field('id, name, pid')->select();
        if ($info) {
            $all = collection($info)->toArray();
        }
        $all[] = ['id' => $this_org['id'], 'name' => $this_org['name'], 'pid' => $this_org['pid']];
        return return_data(true, $all);
    }
    /*
     * 获取俩机构之间的信息树，默认首尾登录用户机构
     */
    public function getOrgTree($end, $head = false, $str = '*') {
        !$head && $head = session('user.orgid');
        $tree = $this->where(['status' => 0, 'id' => $end])->value('parentstr');
        if (!$tree) {
            return return_data(false, '传入数据错误');
        }
        $tree = $end . ',' . strstr($tree, $head);
        $all = collection($this->where(['status' => 0, 'id' => ['in', $tree]])->field($str)->order('type')->select())->toArray();
        if (!$all) {
            return return_data(false, '暂无数据');
        }
        return return_data(true, $all);
    }

    public function getInfoByWhere($where = []) {
        if (!isset($where['status'])) {
            $where['status'] = 0;
        }
        $info = $this->where($where)->select();
        if ($info) {
            return return_data(true, collection($info)->toArray());
        } else {
            return return_data(false, false);
        }
    }

}
