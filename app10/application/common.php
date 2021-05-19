<?php
function p($param){
    header("Content-type:text/html;charset=utf-8");
    print_r($param);
    echo '<br />';
}
function pe($param){
    echo "<pre>";
    header("Content-type:text/html;charset=utf-8");
    print_r($param);
    echo '<br />';
    exit;
}
function getrealip(){
    if ($HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"]){
        $ip = $HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"];
    }elseif ($HTTP_SERVER_VARS["HTTP_CLIENT_IP"]){
        $ip = $HTTP_SERVER_VARS["HTTP_CLIENT_IP"];
    }elseif ($HTTP_SERVER_VARS["REMOTE_ADDR"]){
        $ip = $HTTP_SERVER_VARS["REMOTE_ADDR"];
    } elseif (getenv("HTTP_X_FORWARDED_FOR")){
        $ip = getenv("HTTP_X_FORWARDED_FOR");
    } elseif (getenv("HTTP_CLIENT_IP")){
        $ip = getenv("HTTP_CLIENT_IP");
    } elseif (getenv("REMOTE_ADDR")){
        $ip = getenv("REMOTE_ADDR");
    }else{
        $ip = "Unknown";
    }
}

function createGuid($hyphen = '') {
    mt_srand((double)microtime() * 10000);//optional for php 4.2.0 and up.
    $charid = strtoupper(md5(uniqid(rand(), true)));
    $uuid = substr($charid, 0, 8) . $hyphen
            . substr($charid, 8, 4) . $hyphen
            . substr($charid,12, 4) . $hyphen
            . substr($charid,16, 4) . $hyphen
            . substr($charid,20,12);
    return $uuid;
}
/**
 * 加密数据
 * @param  string $data 加密数据
 * @param  string $key  加密key
 * @return [type]       [description]
 */
function createAuth($data = '', $key = 'encrypt') {
    $encrypt = md5(md5($data) . $key);
    return $encrypt;
}
/**
 * 数据返回方法
 * @param  [type]  $code     执行代码
 * @param  string  $data     返回数据
 * @param  tab $original     标记
 * @return [type]            [description]
 */
function getBack($code, $data = '', $tab = '') {
    $reData['status'] = $code;//1:错误;0:正确
    $reData['data'] = $data;
    if ($tab) {
        $reData['tab'] = $tab;
    }
    return json_encode($reData);
}

/**
 * 无限极分类
 * @param  array $data 传入需要分类的数组数据
 * @param  string $id 主键key值
 * @param  string $data_pid 父id的key值
 * @param  string $pid 指定父id值
 * @param  bool $is_hierarchy 是否分层级，还是返回一维id数组
 * @return  array 返回数组数据
 */
function  getClassify($data, $id, $data_pid, $pid, $is_hierarchy = false, &$array = null) {
    foreach ($data as $k => $v) {
        if ( (string)$pid == (string)$v[$data_pid] ) {
            if ($is_hierarchy) {
                $array[config('classify_name')][$v[$id]] = $v;
                getClassify($data, $id, $data_pid, $v[$id], $is_hierarchy, $array[config('classify_name')][$v[$id]]);
            } else {
                $array[] = $v[$id];
                getClassify($data, $id, $data_pid, $v[$id], $is_hierarchy, $array);
            }
        }
    }
    return $array;
}
/*
    自定义链接  按钮方法
    get_link_info(模块, 控制器, 方法,  参数= array(), 显示值， 附加属性 = array(), 类型="a/submit/button" )
*/
function get_link_info($module, $controller, $action, $data= array(), $value = '', $attr = array(), $type="" ){
    if (!$module) {
        $module = request()->module();
    }
    if (!$controller) {
        $controller = request()->controller();
    }
    //权限判断,无权限，直接return
    $access = session('_ACCESS_LIST');
    if (!$access || !array_key_exists(strtoupper($module), $access) || !array_key_exists(strtoupper($controller), $access[strtoupper($module)]) || !array_key_exists(strtoupper($action), $access[strtoupper($module)][strtoupper($controller)])) {
        //权限暂时屏蔽
        return '';
    }


    $returnstr = '';
    $attrstr = '';
    if ($attr && is_array($attr)) {
        foreach ($attr as $k => $v) {
            $attrstr .= ' ' . $k . '="' . $v . '" ';
        }
    }
    switch ($type) {
        case 'a':
            $returnstr = '<a ' . $attrstr . ' href="' . url($module . '/' . $controller . '/' . $action, $data) . '" >' . $value . '</a>';
            break;
        case "submit":
            $returnstr = '<input type="submit" ' . $attrstr . ' value="' . $value . '" />';
            break;
        case "button":
            $returnstr = '<input type="button" ' . $attrstr . ' value="' . $value . '" />';
            break;
        case "input":
            $returnstr = '<input ' . $attrstr . ' value="' . $value . '" />';
            break;
        default:
            $returnstr = url($module . '/' . $controller . '/' . $action, $data);
            break;
    }
    return $returnstr;
}
function select_year($choice = false, $end = 2017) {
    $year = date('Y');
    if ($choice && $year == $choice) {
        $tag = '<option value="' . $year . '" selected="selected">' . $year . '</option>';
    } else {
        $tag = '<option value="' . $year . '">' . $year . '</option>';
    }
    for ($i = ($year - 1); $i >= $end ; $i--) {
        if ($choice && $i == (int)$choice) {
            $tag .= '<option value="' . $i . '" selected="selected">' . $i . '</option>';
        } else {
            $tag .= '<option value="' . $i . '">' . $i . '</option>';
        }
    }

    return $tag;
}
function tablereturndata($data, $str, $re_str = '空', $encod = false, $decode = false) {
    $k_arr = explode('.', $str);
    foreach ($k_arr as $k => $v) {
        if (!isset($data[$v])) {
            return $re_str;
        } else {
        //    $str = $data[$v];
            $data = $data[$v];
        }
    }
    if ($encod) {
        if ($decode) {
            $data = img_data_encode(json_decode($data));
        } else {
            $data = img_data_encode($data);
        }
    }
    return $data;
}
function showLabel($data, $str1, $str2, $encod = false) {
    $str_name = tablereturndata($data, $str1);
    $str_rel = tablereturndata($data, $str2, false);
    if ($str_rel && !$encod) {
        $str_rel = img_data_encode($str_rel);
    } else if ($str_rel && $encod) {
        $str_rel = img_data_encode(json_decode($str_rel));
    }
    if (!$str_rel) {
        $str_rel = '';
    }
    return "<a title='点击查看图片' href='javascript:void(0)' rel='" . $str_rel . "'>" . $str_name . "</a>";
}
//图片编码
function img_data_encode ($data){
//json编码
    $data = json_encode($data);
//url编码
    $data = urlencode($data);
    return $data;
}
//图片解码
function img_data_decode($data){
//url解码
    $data = urldecode($data);
 //   $data = str_replace('\\', "\\\\", $data);
//json解码
    $data = json_decode($data, true);
    img_thumbnail($data);
    return $data;
}
//url解码
function js_urldecode($str) {
    $ret = '';
    $len = strlen($str);
    for ($i = 0; $i < $len; $i++) { 
        if ($str[$i] == '%' && $str[$i+1] == 'u') {
            $val = hexdec(substr($str, $i+2, 4));
            if ($val < 0x7f) {
                $ret .= chr($val);
            } elseif ($val < 0x800) {
                $ret .= chr(0xc0|($val>>6)).chr(0x80|($val&0x3f));
            } else {
                $ret .= chr(0xe0|($val>>12)).chr(0x80|(($val>>6)&0x3f)).chr(0x80|($val&0x3f));
                $i += 5;
            }
        } elseif ($str[$i] == '%') {
            $ret .= urldecode(substr($str, $i, 3));
            $i += 2;
        } else {
            $ret .= $str[$i];
        }
    }
    return $ret;
}
//图片缩略图
function img_thumbnail($data = [], $width = 800, $hight = 800) {
    $DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];
    $re_data = [];
    if (!is_array($data)) {
        return $data;
    }
    foreach ($data as $key => $value) {
        if (!empty($value['url']) && is_file($DOCUMENT_ROOT. config('view_replace_str.__UPLOAD__') . DS . $value['url']))  {
            $image = \think\Image::open($DOCUMENT_ROOT. config('view_replace_str.__UPLOAD__') . DS . $value['url']);
            $image->thumb($width, $hight)->save($DOCUMENT_ROOT. config('view_replace_str.__UPLOAD__') . DS . $value['url']);
            $re_data[] = $value;
        }
    }
    return $re_data;
}

/*
 * 模型返回方法
 */
function return_data($state, $data) {
    return ['state' => $state, 'data' => $data];
}

/*
 * 发送消息
 */
function sendMessage($name, $msg) {
    Vendor('phpmailer.PHPMailer');

    $mail = new PHPMailer(); //实例化

    $mail->IsSMTP(); // 启用SMTP
    $mail->Host = 'smtp.126.com';

}



