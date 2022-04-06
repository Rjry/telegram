<?php

// +----------------------------------------------------------------------
// | 调试函数
// +----------------------------------------------------------------------

if ( !function_exists('p') )
{
    /**
     * [p打印]
     * @param  [type] $val [description]
     * @return [type]      [description]
     */
    function p($val)
    {
        header('Content-Type: text/html; charset=utf-8');
        echo '<hr />';
        if ( true === $val || empty($val) ) {
            var_export($val);
        } elseif ( is_array($val) || is_object($val) ) {
            echo '<pre>';
            print_r($val);
            echo '</pre>';
        } else {
            printf($val);
        }
    }
}




// +----------------------------------------------------------------------
// | 生产函数
// +----------------------------------------------------------------------

if ( !function_exists('mk_uuid') )
{
    /**
     * [生成UUID]
     * @return [type] [ad73915aa55f11ec94d7020017000b7b]
     */
    function mk_uuid()  
    {  
        $chars = md5( uniqid( mt_rand(),true ) );
        $uuid  = substr ( $chars, 0,  8 )
               . substr ( $chars, 8,  4 )
               . substr ( $chars, 12, 4 )
               . substr ( $chars, 16, 4 )
               . substr ( $chars, 20, 12 );
        return $uuid;
    }
}

if ( !function_exists('mk_uniqid') )
{
    /**
     * [生成32长度唯一值]
     * @return [type] [description]
     */
    function mk_uniqid()
    {
        return md5( uniqid( md5( microtime(true) ),true ) );
    }
}

if ( !function_exists('mk_6_uniqid') )
{
    function mk_6_uniqid()
    {
        return substr(uniqid(),7);
    }
}
 
if ( !function_exists('mk_str') )
{
    // 获取随机长度的字符串
    // @param  $length 长度 默认32
    // @param  $type   类型 0-大小写字母数字(默认)
    //                      1-大写字母数字
    //                      2-小写字母数字
    //                      3-大小写字母
    //                      4-大写字母
    //                      5-小写字母
    //                      6-数字
    function mk_str($length = 32, $type = 0)
    {
        $digit = '1234567890';
        $upper = 'QWERTYUIOPASDFGHJKLZXCVBNM';
        $lower = 'qwertyuiopasdfghjklzxcvbnm';
        switch ($type) {
            case 0:
                $strPol = $upper . $lower . $digit;
                break;
            case 1:
                $strPol = $upper . $digit;
                break;
            case 2:
                $strPol = $lower . $digit;
                break;
            case 3:
                $strPol = $upper . $lower;
                break;
            case 4:
                $strPol = $upper;
                break;
            case 5:
                $strPol = $lower;
                break;
            case 6:
                $strPol = $digit;
                break;
        }
        $max = strlen($strPol) - 1;
        $str = '';
        for ($i=0; $i<$length; $i++) {
            $str .= $strPol[rand(0,$max)];
        }
        return $str;
    }
}

if ( !function_exists('hide_str') )
{
    /**
     +-------------------------------------------------------------------------
     * 将一个字符串部分字符用*替代隐藏
     * 常规用法: hide_str($str, 3, 3, 4)
     +-------------------------------------------------------------------------
     * @param string $string 待转换的字符串
     * @param int    $bengin 起始位置,从0开始计数,当$type=4时,表示左侧保留长度
     * @param int    $len    需要转换成*的字符个数,当$type=4时,表示右侧保留长度
     * @param int    $type   转换类型,0-从左向右隐藏
     *                               1-从右向左隐藏
     *                               2-从指定字符位置分割前由右向左隐藏
     *                               3-从指定字符位置分割后由左向右隐藏
     *                               4-保留首末指定字符串
     * @param string $glue  分割符
     +-------------------------------------------------------------------------
     * @return string 处理后的字符串
     +-------------------------------------------------------------------------
     */
    function hide_str($string, $bengin = 0, $len = 4, $type = 0, $glue = '@')
    {
        if (empty($string))
            return false;
        $array = array();
        if ($type == 0 || $type == 1 || $type == 4) {
            $strlen = $length = mb_strlen($string);
            while ($strlen) {
                $array[] = mb_substr($string, 0, 1, "utf8");
                $string = mb_substr($string, 1, $strlen, "utf8");
                $strlen = mb_strlen($string);
            }
        }
        if ($type == 0) {
            for ($i = $bengin; $i < ($bengin + $len); $i++) {
                if (isset($array[$i]))
                    $array[$i] = "*";
            }
            $string = implode("", $array);
        } else if ($type == 1) {
            $array = array_reverse($array);
            for ($i = $bengin; $i < ($bengin + $len); $i++) {
                if (isset($array[$i]))
                    $array[$i] = "*";
            }
            $string = implode("", array_reverse($array));
        } else if ($type == 2) {
            $array = explode($glue, $string);
            $array[0] = hideStr($array[0], $bengin, $len, 1);
            $string = implode($glue, $array);
        } else if ($type == 3) {
            $array = explode($glue, $string);
            $array[1] = hideStr($array[1], $bengin, $len, 0);
            $string = implode($glue, $array);
        } else if ($type == 4) {
            $left = $bengin;
            $right = $len;
            $tem = array();
            for ($i = 0; $i < ($length - $right); $i++) {
                if (isset($array[$i]))
                    $tem[] = $i >= $left ? "*" : $array[$i];
            }
            $array = array_chunk(array_reverse($array), $right);
            $array = array_reverse($array[0]);
            for ($i = 0; $i < $right; $i++) {
                $tem[] = $array[$i];
            }
            $string = implode("", $tem);
        }
        return $string;
    }
}

if ( !function_exists('mk_mobile') )
{
    // 匹配手机号的正则表达式
    // #^(13[0-9]|14[47]|15[0-35-9]|17[6-8]|18[0-9])([0-9]{8})$#
    function mk_mobile()
    {
        $arr = [
            130,131,132,133,134,135,136,137,138,139,
            144,147,
            150,151,152,153,155,156,157,158,159,
            176,177,178,
            180,181,182,183,184,185,186,187,188,189,
        ];
        return $arr[array_rand($arr)] . rand(1000,9999) . rand(1000,9999);
    }
}

if ( !function_exists('mk_realname') )
{
    // 匹配手机号的正则表达式
    // #^(13[0-9]|14[47]|15[0-35-9]|17[6-8]|18[0-9])([0-9]{8})$#
    function mk_realname()
    {
        $firstNameArr = [
            '赵','钱','孙','李','周','吴','郑','王','冯','陈','褚','卫','蒋','沈','韩','杨','朱','秦','尤','许','何','吕','施','张','孔','曹','严','华','金','魏','陶','姜',
            '戚','谢','邹','喻','柏','水','窦','章','云','苏','潘','葛','奚','范','彭','郎','鲁','韦','昌','马','苗','凤','花','方','任','袁','柳','鲍','史','唐','费','薛',
            '雷','贺','倪','汤','滕','殷','罗','毕','郝','安','常','傅','卞','齐','元','顾','孟','平','黄','穆','萧','尹','姚','邵','湛','汪','祁','毛','狄','米','伏','成',
            '戴','谈','宋','茅','庞','熊','纪','舒','屈','项','祝','董','梁','杜','阮','蓝','闵','季','贾','路','娄','江','童','颜','郭','梅','盛','林','钟','徐','邱','骆',
            '高','夏','蔡','田','樊','胡','凌','霍','虞','万','支','柯','管','卢','莫','柯','房','裘','缪','解','应','宗','丁','宣','邓','单','杭','洪','包','诸','左','石',
            '崔','吉','龚','程','嵇','邢','裴','陆','荣','翁','荀','于','惠','甄','曲','封','储','仲','伊','宁','仇','甘','武','符','刘','景','詹','龙','叶','幸','司','黎',
            '溥','印','怀','蒲','邰','从','索','赖','卓','屠','池','乔','胥','闻','莘','党','翟','谭','贡','劳','逄','姬','申','扶','堵','冉','宰','雍','桑','寿','通','燕',
            '浦','尚','农','温','别','庄','晏','柴','瞿','阎','连','习','容','向','古','易','廖','庾','终','步','都','耿','满','弘','匡','国','文','寇','广','禄','阙','东',
            '欧','利','师','巩','聂','关','荆',
            // '司马','上官','欧阳','夏侯','诸葛','闻人','东方','赫连','皇甫','尉迟','公羊','澹台','公冶','宗政','濮阳','淳于','单于','太叔','申屠','公孙','仲孙','轩辕','令狐',
            // '徐离','宇文','长孙','慕容','司徒','司空'
        ];
       $lastNameArr   = [
            '伟','刚','勇','毅','俊','峰','强','军','平','保','东','文','辉','力','明','永','健','世','广','志','义','兴','良','海','山','仁','波','宁','贵','福','生','龙',
            '元','全','国','胜','学','祥','才','发','武','新','利','清','飞','彬','富','顺','信','子','杰','涛','昌','成','康','星','光','天','达','安','岩','中','茂','进',
            '林','有','坚','和','彪','博','诚','先','敬','震','振','壮','会','思','群','豪','心','邦','承','乐','绍','功','松','善','厚','庆','磊','民','友','裕','河','哲',
            '江','超','浩','亮','政','谦','亨','奇','固','之','轮','翰','朗','伯','宏','言','若','鸣','朋','斌','梁','栋','维','启','克','伦','翔','旭','鹏','泽','晨','辰',
            '士','以','建','家','致','树','炎','德','行','时','泰','盛','雄','琛','钧','冠','策','腾','楠','榕','风','航','弘','秀','娟','英','华','慧','巧','美','娜','静',
            '淑','惠','珠','翠','雅','芝','玉','萍','红','娥','玲','芬','芳','燕','彩','春','菊','兰','凤','洁','梅','琳','素','云','莲','真','环','雪','荣','爱','妹','霞',
            '香','月','莺','媛','艳','瑞','凡','佳','嘉','琼','勤','珍','贞','莉','桂','娣','叶','璧','璐','娅','琦','晶','妍','茜','秋','珊','莎','锦','黛','青','倩','婷',
            '姣','婉','娴','瑾','颖','露','瑶','怡','婵','雁','蓓','纨','仪','荷','丹','蓉','眉','君','琴','蕊','薇','菁','梦','岚','苑','婕','馨','瑗','琰','韵','融','园',
            '艺','咏','卿','聪','澜','纯','毓','悦','昭','冰','爽','琬','茗','羽','希','欣','飘','育','滢','馥','筠','柔','竹','霭','凝','晓','欢','霄','枫','芸','菲','寒',
            '伊','亚','宜','可','姬','舒','影','荔','枝','丽','阳','妮','宝','贝','初','程','梵','罡','恒','鸿','桦','骅','剑','娇','纪','宽','苛','灵','玛','媚','琪','晴',
            '容','睿','烁','堂','唯','威','韦','雯','苇','萱','阅','彦','宇','雨','洋','忠','宗','曼','紫','逸','贤','蝶','菡','绿','蓝','儿','翠','钱','孙','李','周','吴',
            '郑','王','冯','陈','褚','卫','蒋','沈','韩','杨','朱','秦','尤','许','何','吕','施','张','孔','曹','严','华','金','魏','陶','姜','戚','谢','邹','喻','柏','水',
            '窦','章','云','苏','潘','葛','奚','范','彭','郎','鲁','韦','昌','马','苗','凤','花','方','任','袁','柳','鲍','史','唐','费','薛','雷','贺','倪','汤','滕','殷',
            '罗','毕','郝','安','常','傅','卞','齐','元','顾','孟','平','黄','穆','萧','尹','姚','邵','湛','汪','祁','毛','狄','米','伏','成','戴','谈','宋','茅','庞','熊',
            '纪','舒','屈','项','祝','董','梁','杜','阮','蓝','闵','季','贾','路','娄','江','童','颜','郭','梅','盛','林','钟','徐','邱','骆','高','夏','蔡','田','樊','胡',
            '凌','霍','虞','万','支','柯','管','卢','莫','柯','房','裘','缪','解','应','宗','丁','宣','邓','单','杭','洪','包','诸','左','石','崔','吉','龚','程','嵇','邢',
            '裴','陆','荣','翁','荀','于','惠','甄','曲','封','储','仲','伊','宁','仇','甘','武','符','刘','景','詹','龙','叶','幸','司','黎','溥','印','怀','蒲','邰','从',
            '索','赖','卓','屠','池','乔','胥','闻','莘','党','翟','谭','贡','劳','逄','姬','申','扶','堵','冉','宰','雍','桑','寿','通','燕','浦','尚','农','温','别','庄',
            '晏','柴','瞿','阎','连','习','容','向','古','易','廖','庾','终','步','都','耿','满','弘','匡','国','文','寇','广','禄','阙','东','欧','利','师','巩','聂','关',
            '荆','烟'
        ];

        $firstNameArrRandKey = rand(0,count($firstNameArr) - 1);
        $firstName           = $firstNameArr[$firstNameArrRandKey]; 
        $nameLength          = rand(1,2);
        $lastName            = '';
        for($i = 1; $i <= $nameLength; $i++) {
            $lastNameArrRandKey = rand( 0,count( $lastNameArr )-1 );
            $lastName          .= $lastNameArr[$lastNameArrRandKey];
        }
        return $firstName . $lastName;
    }
}




// +----------------------------------------------------------------------
// | 加解密函数
// +----------------------------------------------------------------------
 
if ( !function_exists('encode') )
{
    // 加密
    function encode($txt, $key = '51dojob')
    {
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-=+";
        $nh = rand(0,64);
        $ch = $chars[$nh];
        $mdKey = md5($key.$ch);
        $mdKey = substr($mdKey,$nh%8, $nh%8+7);
        $txt = base64_encode($txt);
        $tmp = '';
        $i=0;$j=0;$k = 0;
        for ($i=0; $i<strlen($txt); $i++) {
            $k = $k == strlen($mdKey) ? 0 : $k;
            $j = ($nh + strpos($chars,$txt[$i]) + ord($mdKey[$k++]))%64;
            $tmp .= $chars[$j];
        }
        return urlencode($ch.$tmp);
    }
}
 
if ( !function_exists('decode') )
{
    // 解密
    function decode($txt, $key = '51dojob')
    {
        $txt = urldecode($txt);
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-=+";
        $ch  = $txt[0];
        $nh  = strpos($chars,$ch);
        $mdKey = md5($key.$ch);
        $mdKey = substr($mdKey,$nh%8, $nh%8+7);
        $txt = substr($txt,1);
        $tmp = '';
        $i=0;$j=0; $k = 0;
        for ($i=0; $i<strlen($txt); $i++) {
            $k = $k == strlen($mdKey) ? 0 : $k;
            $j = strpos($chars,$txt[$i])-$nh - ord($mdKey[$k++]);
            while ($j<0) $j+=64;
            $tmp .= $chars[$j];
        }
        return base64_decode($tmp);
    }
}


if ( !function_exists('arr_sort') )
{
    /**
     * [二维数组根据字段进行排序]
     * @params array    $arrays 需要排序的数组
     * @params string   $sort_key 排序的字段
     * @params int      $sort 排序顺序标志      [ SORT_DESC 降序 | SORT_ASC 升序 ]
     * @param  string   $sort_type 排序类型标志 [ SORT_NUMERIC : 数字排序 | SORT_STRING：字母排序 ]
     * @author Edmund
     */
    function arr_sort($arrays, $sort_key, $sort_order = SORT_ASC, $sort_type = SORT_NUMERIC )
    {
        if ( is_array($arrays) ) {
            foreach ($arrays as $array) {
                if ( is_array($array) ) {
                    $key_arrays[] = $array[$sort_key];
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
        array_multisort($key_arrays,$sort_order,$sort_type,$arrays);
        return $arrays;
    }
}


// +----------------------------------------------------------------------
// | 请求方法函数
// +----------------------------------------------------------------------

if ( !function_exists('curl') )
{
    /**
     * [curl 请求]
     * @param  [type] $url    [description]
     * @param  string $method [description]
     * @param  [type] $data   [description]
     * @return [type]         [description]
     */
    function curl($url, $method = 'GET', $data = null, $header = null)
    {
        return ( false === strpos($url,'https') ) ? 
                    http_curl($url,$method,$data,$header) : 
                    https_curl($url,$method,$data,$header);
    }
}
 
if ( !function_exists('http_curl') )
{
    /**
     * [curl http请求]
     * @param  [type] $url    [description]
     * @param  string $method [description]
     * @param  [type] $data   [description]
     * @return [type]         [description]
     */
    function http_curl($url, $method = 'GET', $data = null, $header = null)
    {
        $ch = curl_init();
        if ( 'GET' == $method && !empty($data) ) {
            $url .= '?' . http_build_query($data);
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $header = is_null($header) ? 
        ['User-Agent:Mozilla/5.0 (Macintosh; Intel Mac OS X 10.9; rv:33.0) Gecko/20100101 Firefox/33.0'] : $header;
        curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        if ( in_array($method,['POST','PUT','DELETE']) ) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}
 
if ( !function_exists('https_curl') )
{
    /**
     * [curl https请求]
     * @param  [type] $url    [description]
     * @param  string $method [description]
     * @param  [type] $data   [description]
     * @return [type]         [description]
     */
    function https_curl($url, $method = 'GET', $data = null, $header = null)
    {
        $ch = curl_init();
        if ( 'GET' == $method && !empty($data) ) {
            $url .= '?' . http_build_query($data);
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $header = is_null($header) ? 
        ['User-Agent:Mozilla/5.0 (Macintosh; Intel Mac OS X 10.9; rv:33.0) Gecko/20100101 Firefox/33.0'] : $header;
        curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        if ( in_array($method,['POST','PUT','DELETE']) ) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}




// +----------------------------------------------------------------------
// | 微信开发函数
// +----------------------------------------------------------------------
 
if ( !function_exists('filter_emoji') )
{
    // 将emoji的unicode置为空，其他不动
    function filter_emoji($str)
    {
        $str = json_encode($str);
        $str = preg_replace("#(\\\ud[0-9a-f]{3})|(\\\ue[0-9a-f]{3})#i","",$str);
        $str = json_decode($str,true);
        return $str;
    }
}
 
if ( !function_exists('xml_to_array') )
{
    /**
     * XML转换为数组
     * @param  [type] $xml [description]
     * @return [type]      [description]
     */
    function xml_to_array($xml)
    {
        return json_decode( json_encode( simplexml_load_string($xml,'SimpleXMLElement',LIBXML_NOCDATA) ), true );
    }
}

if ( !function_exists('wx_cert_curl') )
{
    /**
     * [微信证书crul请求]
     * @param  [type] $url    [description]
     * @param  string $method [description]
     * @param  [type] $data   [description]
     * @return [type]         [description]
     */
    function wx_cert_curl($url, $method = 'GET', $data = null, $header = null)
    {
        $ch = curl_init();
        if ( 'GET' == $method && !empty($data) ) {
            $url .= '?' . http_build_query($data);
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $header = is_null($header) ? 
        ['User-Agent:Mozilla/5.0 (Macintosh; Intel Mac OS X 10.9; rv:33.0) Gecko/20100101 Firefox/33.0'] : $header;
        curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        // ---------------------------------------------------------------------
        $cert_path = root_path() . 'public' . DS . 'static' . DS . 'cert';
        curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
        curl_setopt($ch,CURLOPT_SSLCERT,$cert_path . '/apiclient_cert.pem');
        curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
        curl_setopt($ch,CURLOPT_SSLKEY,$cert_path . '/apiclient_key.pem');
        // ---------------------------------------------------------------------
        if ( in_array($method,['POST','PUT','DELETE']) ) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}


// +----------------------------------------------------------------------
// | 辅助函数
// +----------------------------------------------------------------------

if ( !function_exists('arr_del') )
{
    /**
     * [删除数组指定元素]
     * @param  [type] $number [description]
     * @return [type]         [description]
     */
    function arr_del($arr, $node)
    {
        $key = array_search($node,$arr);
        if (false === $key) return false;
        array_splice($arr,$key,1);
        return $arr;
    }
}

if ( !function_exists('number_point_format') )
{
    /**
     * [保留2位小数点格式化输出数字]
     * @param  [type] $number [description]
     * @return [type]         [description]
     */
    function number_point_format($number, $point = 3)
    {
        return number_format($number,$point,'.','');
    }
}

if ( !function_exists('timeago') )
{
    function timeago($timeStamp, $curTimeStamp = null)
    {
        $curTimeStamp = $curTimeStamp ?: time();
        $intTimeStamp = $curTimeStamp - $timeStamp;
        if ($intTimeStamp < 60) {
            return $intTimeStamp . '秒前';
        } else if ($intTimeStamp < 3600) {
            $minute = intval($intTimeStamp / 60);
            return $minute . '分钟前';
        } else if ($intTimeStamp < 86400) {
            $hour = intval($intTimeStamp / 3600);
            return $hour . '小时前';
        } else {
            $day = intval($intTimeStamp / 86400);
            return $day . '天前';
        }
    }
}

if ( !function_exists('is_mobile') )
{
    /**
     * [是否移动端]
     */
    function is_mobile()
    { 
        // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
        if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
        {
            return true;
        } 
        // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
        if (isset ($_SERVER['HTTP_VIA']))
        { 
            // 找不到为flase,否则为true
            return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
        } 
        // 脑残法，判断手机发送的客户端标志,兼容性有待提高
        if (isset ($_SERVER['HTTP_USER_AGENT']))
        {
            $clientkeywords = array ('nokia',
                'sony',
                'ericsson',
                'mot',
                'samsung',
                'htc',
                'sgh',
                'lg',
                'sharp',
                'sie-',
                'philips',
                'panasonic',
                'alcatel',
                'lenovo',
                'iphone',
                'ipod',
                'blackberry',
                'meizu',
                'android',
                'netfront',
                'symbian',
                'ucweb',
                'windowsce',
                'palm',
                'operamini',
                'operamobi',
                'openwave',
                'nexusone',
                'cldc',
                'midp',
                'wap',
                'mobile'
                ); 
            // 从HTTP_USER_AGENT中查找手机浏览器的关键字
            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT'])))
            {
                return true;
            } 
        } 
        // 协议法，因为有可能不准确，放到最后判断
        if (isset ($_SERVER['HTTP_ACCEPT']))
        { 
            // 如果只支持wml并且不支持html那一定是移动设备
            // 如果支持wml和html但是wml在html之前则是移动设备
            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html'))))
            {
                return true;
            } 
        } 
        return false;
    }
}

if ( !function_exists('millitime') )
{
    /**
     * [获取毫秒时间戳]
     * @return [type] [description]
     */
    function millitime()
    {
        date_default_timezone_set("Etc/GMT+0");
        $time = explode (" ", microtime () );
        $time = $time[1] . ($time [0] * 1000);
        $time = explode ( ".", $time );
        $time = $time[0];
        switch ( strlen($time) ) {
            case 11:
                $time .= '00';
                break;
            case 12:
                $time .= '0';
                break;
        }
        return intval($time);
    }
}


// +----------------------------------------------------------------------
// | 三方函数
// +----------------------------------------------------------------------

if ( !function_exists('ip_addr') )
{
    /**
     * [根据ip获取定位]
     * @param  string $ip [description]
     * @return [type]     [description]
     */
    function ip_addr($ip = '')
    {
        $tok = '939e9039d813279133bea1947f668186';
        $url = 'http://api.ip138.com/query'
             . '/?ip=' . $ip
             . '&datatype=jsonp'
             . '&callback=ipCallback'
             . '&token=' . $tok;
        $res = curl($url);
        $res = str_replace('ipCallback(','',$res);
        $res = str_replace(')','',$res);
        $res = json_decode($res,true);
        if ('ok' == $res['ret']) {
            $country  = !empty($res['data']['0']) ? ($res['data']['0'] . ' ') : '';
            $province = !empty($res['data']['1']) ? ($res['data']['1'] . ' ') : '';
            $city     = !empty($res['data']['2']) ? ($res['data']['2'] . ' ') : '';
            $operator = !empty($res['data']['3']) ? $res['data']['3']         : '';
            return $country . $province . $city . $operator;
        } else {
            return '';
        }
    }
}


// +----------------------------------------------------------------------
// | 其他函数
// +----------------------------------------------------------------------

if ( !function_exists('url_paraphrase') )
{
    /**
     * [URL转义]
     * @param  [type] $str   [description]
     * @param  [type] $repKv [description]
     * @return [type]        [description]
     */
    function url_paraphrase($url)
    {
        $pattern     = '/\\//';
        $replacement = '\\';
        return preg_replace($pattern,$replacement,$url);
    }
}

if ( !function_exists('tpl_to_replace') )
{
    /**
     * [模板替换]
     * @param  [type] $str   [description]
     * @param  [type] $repKv [description]
     * @return [type]        [description]
     */
    function tpl_to_replace($str, $repKv)
    {
        $patterns     = [];
        $replacements = [];
        foreach ($repKv as $key => $val) {
            $patterns[] = '/\\[#' . $key . '#\\]/';
            $replacements[] = $val;
        }
        return preg_replace($patterns,$replacements,$str);
    }
}

if ( !function_exists('miss') )
{
    /**
     * [MISS]
     * @return [type] [description]
     */
    function miss()
    {
        $html = '<!DOCTYPE html>
                 <html lang="en">
                    <head>
                        <meta charset="UTF-8">
                        <title>404 Page Not Found</title>
                        <meta name="viewport" content="width=device-width,initial-scale=1">
                        <style type="text/css">
                            html,body{margin:0;padding:0;color:#333;font-size:16px;font-family:"Century Gothic","Microsoft yahei"}
                            .warp{width:60%;margin:60px auto;text-align:center}
                            .warp h1{font-size:120px;color:#BD1717}
                            .warp h2{margin-top:-80px;font-size:38px;color:#BD1717}
                        </style>
                    </head>
                    <body>
                        <div class="warp"><h1>404</h1><h2>Page Not Found</h2></div>
                    </body>
                </html>';
        return $html;
    }
}


if ( !function_exists('apiReturn') )
{
    function apiReturn($code, $msg, $data = []) {
        return json([
            'code' => $code,
            'msg'  => $msg,
            'data' => $data
        ]);
    }
}



if ( !function_exists('calc_number_radix_point') )
{
    /**
     * [计算数字小数点长度]
     * @param  [type] $number [description]
     * @return [type]         [description]
     */
    function calc_number_radix_point($number) {
        $arr = explode('.', $number);
        $len = isset($arr[1]) ? strlen($arr[1]) : 0;
        return $len;       
    }
}

if ( !function_exists('calc_number_point_length') )
{
    /**
     * [截取数字小数点长度]
     * @param  [type] $number [description]
     * @return [type]         [description]
     */
    function calc_number_point_length($number, $length = 0) {
        $num = number_point_format($number, 12);
        $arr = explode('.', $num);
        $str = mb_substr($arr[1],0,$length,'UTF-8');
        $num = empty($str) ? $arr[0] : ($arr[0] . '.' . $str);
        $num = floatval($num);
        return $num;
    }
}
