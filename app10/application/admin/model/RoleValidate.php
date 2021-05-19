<?php
namespace app\admin\model;
use think\Validate;

class RoleValidate extends Validate
{
    /*
     *验证规则(require必须要，没有require，当没有输入的时候，默认验证通过)
     */
    protected $rule = [
        'name' => 'require|checkInput|checkStrLen:255',
        'remark' => 'require|checkInput|checkStrLen:255',
        'status' => 'require|checkInput|number|max:1'
    ];
    /*
     * 提示信息
     */
    protected $message = [
        'name.require' => '角色不能为空',
        'name.checkInput' => '角色不能为空',
        'name.checkStrLen' => '角色超过最大字数',
        'status.require' => '请选中开启角色',
        'status.max' => '请规范传输',
        'status.number' => '请输入数字',
        'remark.checkInput' => '角色名称不能为空',
        'remark.require' => '角色名称不能为空',
        'remark.checkStrLen' => '角色名称超过最大字数'
    ];
    /*
     * 自定义输入长度验证规则
     */
    protected function checkStrLen($val, $rule, $data) {
        if (iconv_strlen($val) > $rule) {
            return false;
        } else {
            return true;
        }
    }
    /*
     * 自定义输入不为空验证规则
     */
    protected function checkInput($val, $rule, $data) {
        $val = trim($val);
        if (!empty($val)) {
            return true;
        } else {
            return false;
        }
    }

}