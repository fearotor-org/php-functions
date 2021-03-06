<?php
    /**
     * 判断是否SSL协议
     * @return boolean
     */
    function is_ssl()
    {
        if(isset($_SERVER['HTTPS']) && ('1' == $_SERVER['HTTPS'] || 'on' == strtolower($_SERVER['HTTPS']))){
            return true;
        }elseif(isset($_SERVER['SERVER_PORT']) && ('443' == $_SERVER['SERVER_PORT'] )) {
            return true;
        }
        return false;
    }

    //代理ip验证
    /**
     * @param string $ip    代理ip
     * @param string $port  端口
     * @param string $type  类型 http https socks4 socks5
     * @return boolean
     */
    function checkProxy($ip='',$port='',$type='http')
    {
        header("Content-type:text/html;charset=utf-8");

        if(empty($ip)){
            return false;
        }
        if(empty($port)){
            $port='80';
        }

        $type_map=array(
            'http'=>'CURLPROXY_HTTP',
            'https'=>'CURLPROXY_HTTPS',
            'socks4'=>'CURLPROXY_SOCKS4',
            'socks5'=>'CURLPROXY_SOCKS5',
        );

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'http://baidu.com/');
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        if(isset($type_map[$type])){
            curl_setopt($curl, CURLOPT_PROXYTYPE, $type_map[$type]);
        }
        curl_setopt($curl, CURLOPT_PROXY, $ip);
        curl_setopt($curl, CURLOPT_PROXYPORT, $port);
        curl_setopt($curl, CURLOPT_TIMEOUT, 15);
        $response = curl_exec( $curl );
        curl_close($curl);

        if(empty($response)){
            return false;
        }

        if(strpos($response,"url=http://www.baidu.com/")!==false){
            return true;
        }
        return false;
    }

    /**
     * 字符串截取，支持中文和其他编码  本函数依赖 str_to_array 函数
     * @param  string       $str     需要转换的字符串
     * @param  integer      $start   开始位置  该参数详细用法同substr
     * @param  integer|null $len     截取长度 null表示直到字符串结尾 该参数详细用法同substr
     * @param  string       $charset 编码格式
     * @param  string       $suffix  截断显示字符
     * @return string
     */
    function msubstr($str, $start=0, $len=null, $charset="utf-8", $suffix='')
    {
        if(!(is_scalar($str) && $str!==true && $str!==false)) {
            return '';
        }
        if(!preg_match("/^[-]?0|([1-9][0-9]*)$/",$start)){
            return '';
        }
        if(!(preg_match("/^[-]?[1-9][0-9]*$/",$len) || is_null($len))){
            return '';
        }
        if(!in_array($charset,array('utf-8','gb2312','gbk','big5'))){
            return '';
        }
        if(!(is_scalar($suffix) && $suffix!==true && $suffix!==false)) {
            return '';
        }
        if($str===''){
            return '';
        }
        $str=strval($str);

        $str_arr=str_to_array($str,$charset);

        if(is_null($len)){
            $slice = join("",array_slice($str_arr, $start));
        }else{
            $slice = join("",array_slice($str_arr, $start, $len));
        }

        return $suffix!=='' ? $slice.$suffix : $slice;
    }

    /**
     * 产生随机字串 默认长度6位 字母和数字混合
     * @param  integer  $len      长度
     * @param  integer  $type     字串类型 ， 0 字母 1 数字 2 大写字母 3 小写字母 4 汉字 默认数字字母混合
     * @param  string   $addChars 额外字符
     * @return string
     */
    function rand_string($len=6,$type=-1,$addChars='') 
    {
        if(!preg_match("/^[1-9][0-9]*$/",$len)){
            return '';
        }
        if(!in_array($type,array(0,1,2,3,4))){
            $type==-1;
        }
        if(!(is_scalar($addChars) && $addChars!==true && $addChars!==false)) {
            return '';
        }

        $str ='';
        switch($type) {
            case 0:
                $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'.$addChars;
                break;
            case 1:
                $chars= str_repeat('0123456789',3);
                break;
            case 2:
                $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZ'.$addChars;
                break;
            case 3:
                $chars='abcdefghijklmnopqrstuvwxyz'.$addChars;
                break;
            case 4:
                $chars = "们以我到他会作时要动国产的一是工就年阶义发成部民可出能方进在了不和有大这主中人上为来分生对于学下级地个用同行面说种过命度革而多子后自社加小机也经力线本电高量长党得实家定深法表着水理化争现所二起政三好十战无农使性前等反体合斗路图把结第里正新开论之物从当两些还天资事队批点育重其思与间内去因件日利相由压员气业代全组数果期导平各基或月毛然如应形想制心样干都向变关问比展那它最及外没看治提五解系林者米群头意只明四道马认次文通但条较克又公孔领军流入接席位情运器并飞原油放立题质指建区验活众很教决特此常石强极土少已根共直团统式转别造切九你取西持总料连任志观调七么山程百报更见必真保热委手改管处己将修支识病象几先老光专什六型具示复安带每东增则完风回南广劳轮科北打积车计给节做务被整联步类集号列温装即毫知轴研单色坚据速防史拉世设达尔场织历花受求传口断况采精金界品判参层止边清至万确究书术状厂须离再目海交权且儿青才证低越际八试规斯近注办布门铁需走议县兵固除般引齿千胜细影济白格效置推空配刀叶率述今选养德话查差半敌始片施响收华觉备名红续均药标记难存测士身紧液派准斤角降维板许破述技消底床田势端感往神便贺村构照容非搞亚磨族火段算适讲按值美态黄易彪服早班麦削信排台声该击素张密害侯草何树肥继右属市严径螺检左页抗苏显苦英快称坏移约巴材省黑武培著河帝仅针怎植京助升王眼她抓含苗副杂普谈围食射源例致酸旧却充足短划剂宣环落首尺波承粉践府鱼随考刻靠够满夫失包住促枝局菌杆周护岩师举曲春元超负砂封换太模贫减阳扬江析亩木言球朝医校古呢稻宋听唯输滑站另卫字鼓刚写刘微略范供阿块某功套友限项余倒卷创律雨让骨远帮初皮播优占死毒圈伟季训控激找叫云互跟裂粮粒母练塞钢顶策双留误础吸阻故寸盾晚丝女散焊功株亲院冷彻弹错散商视艺灭版烈零室轻血倍缺厘泵察绝富城冲喷壤简否柱李望盘磁雄似困巩益洲脱投送奴侧润盖挥距触星松送获兴独官混纪依未突架宽冬章湿偏纹吃执阀矿寨责熟稳夺硬价努翻奇甲预职评读背协损棉侵灰虽矛厚罗泥辟告卵箱掌氧恩爱停曾溶营终纲孟钱待尽俄缩沙退陈讨奋械载胞幼哪剥迫旋征槽倒握担仍呀鲜吧卡粗介钻逐弱脚怕盐末阴丰雾冠丙街莱贝辐肠付吉渗瑞惊顿挤秒悬姆烂森糖圣凹陶词迟蚕亿矩康遵牧遭幅园腔订香肉弟屋敏恢忘编印蜂急拿扩伤飞露核缘游振操央伍域甚迅辉异序免纸夜乡久隶缸夹念兰映沟乙吗儒杀汽磷艰晶插埃燃欢铁补咱芽永瓦倾阵碳演威附牙芽永瓦斜灌欧献顺猪洋腐请透司危括脉宜笑若尾束壮暴企菜穗楚汉愈绿拖牛份染既秋遍锻玉夏疗尖殖井费州访吹荣铜沿替滚客召旱悟刺脑措贯藏敢令隙炉壳硫煤迎铸粘探临薄旬善福纵择礼愿伏残雷延烟句纯渐耕跑泽慢栽鲁赤繁境潮横掉锥希池败船假亮谓托伙哲怀割摆贡呈劲财仪沉炼麻罪祖息车穿货销齐鼠抽画饲龙库守筑房歌寒喜哥洗蚀废纳腹乎录镜妇恶脂庄擦险赞钟摇典柄辩竹谷卖乱虚桥奥伯赶垂途额壁网截野遗静谋弄挂课镇妄盛耐援扎虑键归符庆聚绕摩忙舞遇索顾胶羊湖钉仁音迹碎伸灯避泛亡答勇频皇柳哈揭甘诺概宪浓岛袭谁洪谢炮浇斑讯懂灵蛋闭孩释乳巨徒私银伊景坦累匀霉杜乐勒隔弯绩招绍胡呼痛峰零柴簧午跳居尚丁秦稍追梁折耗碱殊岗挖氏刃剧堆赫荷胸衡勤膜篇登驻案刊秧缓凸役剪川雪链渔啦脸户洛孢勃盟买杨宗焦赛旗滤硅炭股坐蒸凝竟陷枪黎救冒暗洞犯筒您宋弧爆谬涂味津臂障褐陆啊健尊豆拔莫抵桑坡缝警挑污冰柬嘴啥饭塑寄赵喊垫丹渡耳刨虎笔稀昆浪萨茶滴浅拥穴覆伦娘吨浸袖珠雌妈紫戏塔锤震岁貌洁剖牢锋疑霸闪埔猛诉刷狠忽灾闹乔唐漏闻沈熔氯荒茎男凡抢像浆旁玻亦忠唱蒙予纷捕锁尤乘乌智淡允叛畜俘摸锈扫毕璃宝芯爷鉴秘净蒋钙肩腾枯抛轨堂拌爸循诱祝励肯酒绳穷塘燥泡袋朗喂铝软渠颗惯贸粪综墙趋彼届墨碍启逆卸航衣孙龄岭骗休借".$addChars;
                break;
            default :
                // 默认去掉了容易混淆的字符oOLl和数字01，要添加请使用addChars参数
                $chars='ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789'.$addChars;
                break;
        }
        if($len>10 ) {//位数过长重复字符串一定次数
            $chars= $type==1? str_repeat($chars,$len) : str_repeat($chars,5);
        }
        if($type!=4) {
            $chars   =   str_shuffle($chars);
            $str     =   substr($chars,0,$len);
        }else{
            // 中文随机字
            for($i=0;$i<$len;$i++){
              $str.= msubstr($chars, floor(mt_rand(0,mb_strlen($chars,'utf-8')-1)),1);
            }
        }
        return $str;
    }

    /**
     * 将一个字符串部分字符用*替代隐藏 支持中文  本函数依赖 str_to_array 函数
     * @param  string       $str      待转换的字符串
     * @param  integer      $start    起始位置 该参数详细用法同substr
     * @param  integer|null $len      截取长度 null表示直到字符串结尾 该参数详细用法同substr
     * @param  string       $hide_str 替代字符
     * @param  string       $charset  编码格式
     * @return string
     */
    function hide_str($str, $start = 0, $len = null,$hide_str='*',$charset="utf-8")
    {
        if(!(is_scalar($str) && $str!==true && $str!==false)) {
            return '';
        }
        if(!preg_match("/^[-]?0|([1-9][0-9]*)$/",$start)){
            return '';
        }
        if(!(preg_match("/^[-]?[1-9][0-9]*$/",$len) || is_null($len))){
            return '';
        }
        if(!(is_scalar($hide_str) && $hide_str!==true && $hide_str!==false)) {
            return '';
        }
        if($str===''){
            return '';
        }
        $str=strval($str);

        $str_arr=str_to_array($str,$charset);

        if(is_null($len)){
            $len=count($str_arr);
        }

        $slice=array_slice($str_arr, $start, $len);
        if(empty($slice)){
            return $str;
        }

        $hide_str_arr=array_fill(0,count($slice),$hide_str);

        array_splice($str_arr, $start, $len, $hide_str_arr);

        return join("",$str_arr);
    }

    /**
     * 加密解密字符串
     * @param  string   $str       明文 或 密文
     * @param  string   $operation decode表示解密,encode表示加密
     * @param  string   $key       密匙
     * @param  integer  $expiry    密文有效期 秒 0 永不过期
     * @return string
     */
    function authcode($str, $operation = 'decode', $key = '', $expiry = 0)
    {
        if(!(is_scalar($str) && $str!==true && $str!==false)){
            return '';
        }
        if(!in_array($operation,array('decode','encode'))){
            return '';
        }
        if(!(is_scalar($key) && $key!==true && $key!==false)){
            return '';
        }
        if(!preg_match("/^0|([1-9][0-9]*)$/", $expiry)){
            return '';
        }

        // 动态密匙长度，相同的明文会生成不同密文就是依靠动态密匙
        $ckey_length = 4;

        // 密匙
        $key = $key?md5($key):'';

        // 密匙a会参与加解密
        $keya = md5(substr($key, 0, 16));
        // 密匙b会用来做数据完整性验证
        $keyb = md5(substr($key, 16, 16));
        // 密匙c用于变化生成的密文
        $keyc = $ckey_length ? ($operation == 'decode' ? substr($str, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';
        // 参与运算的密匙
        $cryptkey = $keya.md5($keya.$keyc);
        $key_length = strlen($cryptkey);
        // 明文，前10位用来保存时间戳，解密时验证数据有效性，10到26位用来保存$keyb(密匙b)，解密时会通过这个密匙验证数据完整性
        // 如果是解码的话，会从第$ckey_length位开始，因为密文前$ckey_length位保存 动态密匙，以保证解密正确
        $str = $operation == 'decode' ? base64_decode(substr($str, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($str.$keyb), 0, 16).$str;
        $string_length = strlen($str);
        $result = '';
        $box = range(0, 255);
        $rndkey = array();
        // 产生密匙簿
        for($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);
        }
        // 用固定的算法，打乱密匙簿，增加随机性，好像很复杂，实际上对并不会增加密文的强度
        for($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }
        // 核心加解密部分
        for($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            // 从密匙簿得出密匙进行异或，再转成字符
            $result .= chr(ord($str[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }
        if($operation == 'decode') {
            // substr($result, 0, 10) == 0 验证数据有效性
            // substr($result, 0, 10) - time() > 0 验证数据有效性
            // substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16) 验证数据完整性
            // 验证数据有效性，请看未加密明文的格式
            if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
                return substr($result, 26);
            } else {
                return '';
            }
        } else {
            // 把动态密匙保存在密文里，这也是为什么同样的明文，生产不同密文后能解密的原因
            // 因为加密后的密文可能是一些特殊字符，复制过程可能会丢失，所以用base64编码
            return $keyc.str_replace('=', '', base64_encode($result));
        }
    }

    /**
     * escape编码  同JS的escape
     * @param  string $str 待编码字符串
     * @return string
     */
    function escape($str)
    {
        if(!(is_scalar($str) && $str!==true && $str!==false)){
            return '';
        }
        if($str===''){
            return '';
        }

        preg_match_all("/[\xc2-\xdf][\x80-\xbf]+|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}|[\x01-\x7f]+/e",$str,$r);
        //匹配utf-8字符，
        $str = $r[0];
        $l = count($str);
        for($i=0; $i <$l; $i++){
            $value = ord($str[$i][0]);
            if($value < 223){
                $str[$i] = rawurlencode(utf8_decode($str[$i]));
                //先将utf8编码转换为ISO-8859-1编码的单字节字符，urlencode单字节字符.
                //utf8_decode()的作用相当于iconv("UTF-8","CP1252",$v)。
            }else{
                $str[$i] = "%u".strtoupper(bin2hex(iconv("UTF-8","UCS-2",$str[$i])));
            }
        }
        return join("",$str);
    }

    /**
     * unescape编码  同JS的unescape
     * @param  string $str 待解码字符串
     * @return string
     */
    function unescape($str)
    {
        if(!(is_scalar($str) && $str!==true && $str!==false)){
            return '';
        }
        if($str===''){
            return '';
        }

        $ret = '';
        $len = strlen($str);
        for ($i = 0; $i < $len; $i++){
            if ($str[$i] == '%' && $str[$i+1] == 'u'){
                $val = hexdec(substr($str, $i+2, 4));
                if ($val < 0x7f) $ret .= chr($val);
                else if($val < 0x800) $ret .= chr(0xc0|($val>>6)).chr(0x80|($val&0x3f));
                else $ret .= chr(0xe0|($val>>12)).chr(0x80|(($val>>6)&0x3f)).chr(0x80|($val&0x3f));

                $i += 5;
            }else if ($str[$i] == '%'){
                $ret .= urldecode(substr($str, $i, 3));
                $i += 2;
            }
            else $ret .= $str[$i];
        }
        //$ret=iconv('utf-8', 'gb2312', $ret);
        return $ret;
    }

    /**
     * 将一个字符串转换成数组，支持中文
     * @param  string $str 待转换成数组的字符串
     * @param  string       $charset 编码格式
     * @return array
     */
    function str_to_array($str,$charset='utf-8')
    {
        if(!(is_scalar($str) && $str!==true && $str!==false)) {
            return array();
        }
        if(!in_array($charset,array('utf-8','gb2312','gbk','big5'))){
            return array();
        }
        if($str===''){
            return array();
        }
        $str=strval($str);

        $array=[];
        if(function_exists("mb_substr")) {
            $strlen = mb_strlen($str);
            while ($strlen) {
                $array[] = mb_substr($str, 0, 1, $charset);
                $str = mb_substr($str, 1, $strlen, $charset);
                $strlen = mb_strlen($str);
            }
        }elseif(function_exists('iconv_substr')) {
            $strlen = iconv_strlen($str);
            while ($strlen) {
                $array[] = iconv_substr($str, 0, 1, $charset);
                $str = iconv_substr($str, 1, $strlen, $charset);
                $strlen = iconv_strlen($str);
            }
        }else{
            $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
            $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
            $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
            $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
            preg_match_all($re[$charset], $str, $match);
            $array=$match[0];
        }

        return $array;
    }

    /**
     * 判断是否为索引数组
     * @param  array  $array 
     * @return boolean        
     */
    function is_index_array($array)
    {
        if(is_array($array)) {
            $keys = array_keys($array);
            return $keys === array_keys($keys);
        }
        return false;
    }

    /**
     * 判断是否为关联数组数组
     * @param  array  $array 
     * @return boolean        
     */
    function is_assoc_array($array)
    {
        if(is_array($array)) {
            $keys = array_keys($array);
            return $keys !== array_keys($keys);
        }
        return false;
    }

    /**
     * 获取数组最大深度
     * @param  array  $array
     * @return int
     */
    function get_array_deep($array)
    {
        
    }

    /**
     * 获取数组维度
     * @param  array  $array
     * @return int
     */
    function get_array_dimension($array)
    {
        
    }

    /**
     * 删除数组的值，不会重建索引，支持删除多维数组中的值
     * @param array &$arr 目标数组
     * @param array $del_arr 需删除的值数组
     * @param boolean $strict 是否严格模式，严格模式将比较类型
     * @return void
     */
    function array_remove_value(&$arr, $del_arr,$strict=false){
        foreach ($arr as $key => $value) {
            if (is_array($value)) {
                array_remove_value($arr[$key], $var);
            } else {
                if (in_array($value,$del_arr,$strict)) {
                    unset($arr[$key]); } else { $arr[$key] = $value;
                }
            }
        }
    }

    /**
     * 获取无限分类的树形结构数据
     * @param array  $list        待处理数组
     * @param string $id_field    id标记字段
     * @param string $pid_field   父级id标记字段
     * @param string $child_field 子级数据标记字段
     * @param mix    $root_id     根id
     * @param bool   $self        是否返回根id对应的数据
     * @return array
     */
    /*
    输入数据
    [
        ['id'=>1,'pid'=>0,'name'=>'aaa'],
        ['id'=>2,'pid'=>1,'name'=>'bb'],
        ['id'=>4,'pid'=>0,'name'=>'aaa1'],
        ['id'=>5,'pid'=>4,'name'=>'bb1'],
    ]
     */
    /*
    返回结构
    Array
    (
        [0] => Array
            (
                [id] => 1
                [pid] => 0
                [name] => aaa
                [children] => Array
                    (
                        [0] => Array
                            (
                                [id] => 2
                                [pid] => 1
                                [name] => bb
                                [children] => Array
                                    (
                                    )

                            )

                    )

            )

        [1] => Array
            (
                [id] => 4
                [pid] => 0
                [name] => aaa1
                [children] => Array
                    (
                        [0] => Array
                            (
                                [id] => 5
                                [pid] => 4
                                [name] => bb1
                                [children] => Array
                                    (
                                    )

                            )

                    )

            )

    )
     */
    function get_tree($list=array(), $id_field='id',$pid_field = 'pid',$child_field = '_child',$root_id=0,$self=true)
    {
        if(!is_array($list) || empty($list)){
            return [];
        }
        if(!(is_string($id_field) && is_string($pid_field) && is_string($child_field) )){
            return [];
        }
        if(!(is_scalar($root_id) && $root_id!==true && $root_id!==false)) {
            return [];
        }
        if(!is_bool($self)){
            return [];
        }

        $root_id=strval($root_id);

        $items=array();
        $tree = array();
        $not_root_items=array();
        foreach($list as $data){
            if(!(isset($data[$id_field]) && isset($data[$pid_field]))){
                continue;
            }

            $item=new stdClass();
            foreach($data as $k=>$v){
                $item->$k=$v;
            }
            $item->$child_field = array();

            $id = $data[$id_field];
            $pid = strval($data[$pid_field]);
            $items[$id]=$item;

            if($pid===$root_id){
                $tree[] = $item;
            }else{
                $not_root_items[]=$item;
            }
        }
        foreach($not_root_items as $item){
            $level=$item->$pid_field;
            if(isset($items[$level])){
                array_push($items[$level]->$child_field, $item);
            }
        }

        $tree_data=json_decode( json_encode($tree),true);
        if($self && isset($items[$root_id])){
            $res=$items[$root_id];
            $res->$child_field=$tree_data;
        }else{
            $res=$tree_data;
        }

        return json_decode( json_encode($res),true);
    }


    /**
     * 获取无限分类的树形结构文本数据
     * @param array  $list        待处理数组
     * @param string $id_field    id标记字段
     * @param string $pid_field   父级id标记字段
     * @param string $name_field  名称标记字段
     * @param mix    $root_id     根id
     * @param bool   $self        是否返回根id对应的数据
     * @return array
     *
     * $data 格式：
     * array(
     *  array('id'=>1,'pid'=>0,'name'=>'aaa',...),
     *  array('id'=>2,'pid'=>1,'name'=>'bbb',...)
     *  ...
     * )
     * 返回的数据格式
     * array(
     *  array('id'=>1,'pid'=>0,'name'=>'aaa','fullname'=>'aaa',...),
     *  array('id'=>2,'pid'=>1,'name'=>'bbb','fullname'=>'└ bbb'...)
     *  ...
     * )
     */
    function get_tree_text($list=array(),$id_field='id',$pid_field = 'pid',$name_field='name',$root_id=0,$self=true)
    {
        if(!is_array($list) || empty($list)){
            return array(1);
        }
        if(!(is_string($id_field) && is_string($pid_field) && is_string($name_field) )){
            return array(2);
        }
        if(!(is_scalar($root_id) && $root_id!==true && $root_id!==false)) {
            return array(3);
        }
        if(!is_bool($self)){
            return array(4);
        }

        $root_id=strval($root_id);

        static $res=array();

        $arg_num=func_num_args();
        if($arg_num!=7){
            $root_item=array();
            foreach($list as $v){
                if(strval($v[$id_field])===$root_id){
                    $root_item=$v;
                    break;
                }
            }
            if(empty($root_item)){
                $level=0;
            }else{
                if($self){
                    $level=1;
                    $root_item['level']=1;
                    $res[]=$root_item;
                }else{
                    $level=0;
                }

            }
        }else{
            $level=func_get_arg(6);
        }
        $level++;

        $filter_datas=array();
        foreach($list as $v){
            if(isset($v[$id_field]) && isset($v[$pid_field]) && isset($v[$name_field])){
                if(strval($v[$pid_field])===$root_id){
                    $filter_datas[]=$v;
                }
            }
        }

        foreach($filter_datas as $v){
            $v['level']=$level;
            $res[]=$v;
            get_tree_text($list,$id_field,$pid_field,$name_field,$v[$id_field],$self,$level);
        }

        $resx=array();
        if(!empty($res)){
            foreach($res as $k=>$v){
                if(isset($res[$k+1]['level'])){
                    if($v['level']>=2){
                        $separate2=$v['level']>$res[$k+1]['level']?"&nbsp;&nbsp;&nbsp;&nbsp;└":"&nbsp;&nbsp;&nbsp;&nbsp;├";
                    }else{
                        $separate2='';
                    }
                    if($v['level']>=3){
                        if($v['level']>$res[$k+1]['level']){
                            $separate1=str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;│", $res[$k+1]['level']-1).str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;└", $v['level']-$res[$k+1]['level']-1);
                        }else{
                            $separate1=str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;│", $v['level']-2);
                        }
                    }else{
                        $separate1='';
                    }
                }else{
                    $separate1='';
                    $separate2=str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;└", $v['level']-1);
                }
                $v['fullname']=html_entity_decode($separate1.$separate2).' '.$v[$name_field];
                $resx[]=$v;
            }
        }

        unset($res);
        return $resx;
    }

    /**
     * 获取无限分类的完整父级路径
     * @param array  $list        待处理数组
     * @param string $id_field    id标记字段
     * @param string $pid_field   父级id标记字段
     * @return array
     */
    /*
    输入数据
    [
        ['id'=>1,'pid'=>0,...]
        ['id'=>2,'pid'=>1,...],
        ['id'=>3,'pid'=>2,...],
        ['id'=>4,'pid'=>0,...],
        ['id'=>5,'pid'=>4,...],
    ]
     */
    /*
    输出数据
    [
        ['id'=>1,'pid'=>0,'full_pid'=>[0]],
        ['id'=>2,'pid'=>1,'full_pid'=>[0,1]],
        ['id'=>3,'pid'=>2,'full_pid'=>[0,1,2]],
        ['id'=>4,'pid'=>0,'full_pid'=>[0]],
        ['id'=>5,'pid'=>4,'full_pid'=>[0,4]],
    ]
     */
    function get_tree_path($list=array(),$id_field="id",$pid_field = 'pid')
    {
        if(!is_array($list) || empty($list)){
            return [];
        }
        if(!(is_string($id_field) && is_string($pid_field) )){
            return [];
        }

        $items=[];
        foreach($list as $k=>$v){
            if(isset($v[$id_field]) && isset($v[$pid_field])){
                $items[$v[$id_field]]=$v;

                $list[$k]['full_pid']=[$v[$pid_field]];
                $list[$k]['done']=false;
            }else{
                $list[$k]['full_pid']=[];
                $list[$k]['done']=true;
            }
        }

        while(true){
            $flag=true;
            foreach($list as $k=>$v){
                if(!$v['done']){
                    $flag=false;
                }else{
                    continue;
                }

                if(in_array($v[$id_field],$v['full_pid'])){
                    $list[$k]['full_pid']=[];
                    $list[$k]['done']=true;
                    continue;
                }

                if(isset($items[$v['full_pid'][0]])){
                    array_unshift($v['full_pid'],$items[$v['full_pid'][0]][$pid_field]);
                    $list[$k]['full_pid']=$v['full_pid'];
                }else{
                    $list[$k]['done']=true;
                }

            }

            if($flag){
                break;
            }
        }

        foreach($list as &$v){
            unset($v['done']);
        }
        return $list;
    }

    /**
     * 删除目录(包括子目录和文件)
     * @param  string $path 目录路径
     * @return boolean
     */
    function delete_dir($path)
    {
        if(!is_string($path)){
            return false;
        }

        if(file_exists($path) && is_dir($path)){
            $op = dir($path);
            while(false != ($item = $op->read())) {
                if($item == '.' || $item == '..') {
                    continue;
                }
                if(is_dir($op->path.'/'.$item)) {
                    $r1=delete_dir($op->path.'/'.$item);
                    if($r1===false){
                        return false;
                    }
                    $r2=rmdir($op->path.'/'.$item);
                    if($r2===false){
                        return false;
                    }
                } else {
                    $r3=unlink($op->path.'/'.$item);
                    if($r3===false){
                        return false;
                    }
                }

            }
            return true;
        }else{
            return false;
        }
    }

    /**
     * 获取当前时间毫秒
     * @return string
     */
    function get_microtime(){
        list($usec, $sec) = explode(' ', microtime());
        $usec2msec = $usec * 1000;    //计算微秒部分的毫秒数(微秒部分并不是微秒,这部分的单位是秒)
        $sec2msec = $sec * 1000;    //计算秒部分的毫秒数
        $usec2msec2float = (float)$usec2msec;
        $sec2msec2float = (float)$sec2msec;
        $msec = $usec2msec2float + $sec2msec2float; //加起来就对了
        $arrMsc = explode('.', $msec);
        return $arrMsc[0];
    }

    /**
     * 字节格式化 把字节数格式为 B K M G T 描述的大小
     * @param  int $size 字节数
     * @param  int $dec  小数点后保留的位数
     * @return string
     */
    function byte_format($size, $dec=2)
    {
        if(!preg_match("/^0|([1-9][0-9]*)$/", $size)){
            return '';
        }
        if(!preg_match("/^0|([1-9][0-9]*)$/", $dec)){
            return '';
        }

        $size=intval($size);
        $dec=intval($dec);
        $a = array("B", "KB", "MB", "GB", "TB", "PB");
        $pos = 0;
        while ($size >= 1024) {
            $size /= 1024;
            $pos++;
        }
        return round($size,$dec)." ".$a[$pos];
    }

    /**
     * 根据PHP各种类型变量生成唯一标识号
     * @param  mixed  $mix 变量
     * @return string
     */
    function to_guid_string($mix)
    {
        if (is_object($mix) && function_exists('spl_object_hash')) {
            return spl_object_hash($mix);
        } elseif (is_resource($mix)) {
            $mix = get_resource_type($mix) . strval($mix);
        } else {
            $mix = serialize($mix);
        }
        return md5($mix);
    }

    /**
     * 优化的require_once
     * @param  string  $filename 文件地址
     * @return boolean
     */
    function require_cache($filename)
    {
        if(!is_string($filename)){
            return false;
        }

        static $_importFiles = array();
        if (!isset($_importFiles[$filename])) {
            if (file_exists_case($filename)) {
                require $filename;
                $_importFiles[$filename] = true;
            } else {
                $_importFiles[$filename] = false;
            }
        }
        return $_importFiles[$filename];
    }

    /**
     * 将数字转换为大写金额
     * @param  string|int $ns 数字金额
     * @return string     转换后的大写金额
     */
    function cny($ns)
    {
        if(!preg_match("/^(0|([1-9][0-9]*))([\.][0-9]{1,2})?$/", $ns)){
            return '';
        }
        static $cnums = array("零","壹","贰","叁","肆","伍","陆","柒","捌","玖"),
        $cnyunits = array("元","角","分"),
        $grees = array("拾","佰","仟","万","拾","佰","仟","亿");
        list($ns1,$ns2) = explode(".",$ns,2);
        $ns2 = array_filter(array($ns2[1],$ns2[0]));

        $arr=array(array(str_split($ns1),$grees),array('',$cnyunits));
        foreach ($arr as $k => $v_arr) {
            if($k==1)$v_arr[0]=$ret;
            $ul = count($v_arr[1]);
            $xs = array();
            foreach (array_reverse($v_arr[0]) as $x) {
                $l = count($xs);
                if($x!="0" || !($l%4)) {
                    $n=($x=='0'?'':$x).($v_arr[1][($l-1)%$ul]);
                }
                else{
                    $n=is_numeric($xs[0][0]) ? $x : '';
                }
                array_unshift($xs, $n);
            }
            if($k==0){
                $ret = array_merge($ns2,array(implode("", $xs), ""));
            }
        }
        $ret = implode("",array_reverse($xs));
        $r=str_replace(array_keys($cnums), $cnums,$ret);

        preg_match_all("/./u", $r, $r_arr);
        $rr='';
        $prev_letter='';
        foreach ($r_arr[0] as $k1 => $v1) {
            if(!($v1==$prev_letter && $prev_letter=='零')){
                $rr.=$v1;
            }
            $prev_letter=$v1;
        }
        return $rr;
    }

    /**
     * @param int $current_page     当前页码
     * @param int $pagesize         每页数据条数
     * @param int $total            数据总条数
     * @param int $pagebar_width    分页条显示的页码按钮数量
     * @return array
     */
    function paginator($current_page=1,$pagesize=10,$total=0,$pagebar_width=9)
    {
        $current_page=intval($current_page);
        $current_page=$current_page<=0?1:$current_page;
        $pagesize=intval($pagesize);
        if($pagesize<=0){
            return [];
        }
        $total=intval($total);
        $total=$total<0?0:$total;
        $pagebar_width=intval($pagebar_width);
        $pagebar_width=$pagebar_width<0?0:$pagebar_width;

        $pagebar=[];
        $total_pages=ceil($total/$pagesize);

        if($total_pages>1){
            //上一页
            if($current_page>1){
                array_push($pagebar,['page_text'=>'首页','page_value'=>1]);
                $prev_page=$current_page>=$total_pages?$total_pages-1:$current_page-1;
                array_push($pagebar,['page_text'=>'上一页','page_value'=>$prev_page]);
            }

            if($pagebar_width%2==0){
                $pagebar_width=$pagebar_width+1;
            }
            $n=floor($pagebar_width/2);
            if($total_pages-$current_page>=$n){
                $page_start=$current_page-$n<1?1:$current_page-$n;
                $page_end=2*$n+$page_start>$total_pages?$total_pages:2*$n+$page_start;
            }else{
                $page_end=$current_page+$n>$total_pages?$total_pages:$current_page+$n;
                $page_start=($page_end-2*$n)<1?1:$page_end-2*$n;
            }
            for($i=$page_start;$i<=$page_end;$i++){
                array_push($pagebar,['page_text'=>$i,'page_value'=>$i]);
            }

            //下一页
            if($current_page<$total_pages){
                array_push($pagebar,['page_text'=>'下一页','page_value'=>$current_page+1]);
                array_push($pagebar,['page_text'=>'末页','page_value'=>$total_pages]);
            }
        }
        return $pagebar;
    }

	/**
     * 车牌验证
     * @param string $plateno 车牌号
     * @return boolean
     */
    function platenoCheck($plateno = '')
	{
        if(!is_string($plateno) || empty($plateno)){
            return false;
        }

		$arr = str_to_array($plateno);

		if(!(count($arr) == 7 || count($arr) == 8)){
			return false;
		}

		$short_provinces = ['黑','吉','辽','蒙','新','甘','陕','宁','青','藏','云','贵','川','粤','桂','鄂','湘','豫','冀','鲁','晋','苏','浙','闽','皖','赣','京','津','沪','渝','琼'];
		$letters = range('A', 'Z');
        $chars = array_merge($letters, range(0, 9));

		if(!in_array($arr[0], $short_provinces)){
			return false;
		}

		if(!in_array($arr[1], $letters)){
			return false;
		}
		
		unset($arr[0], $arr[1]);

		if(!empty(array_diff($arr, $chars))){
			return false;
		}

        return true;
    }

    /**
     * 获取两个经纬度之间的距离
     * @param array $from_coords ['lng'=>'经度', 'lat'=>'纬度']
     * @param array $to_coords ['lng'=>'经度', 'lat'=>'纬度']
     * @return integer|null
     */
    function getDistanceByCoords($from_coords = [], $to_coords = [])
    {
        foreach(['lng','lat'] as $k){
            if(empty($from_coords[$k]) || empty($to_coords[$k])){
                return null;
            }
            if(!is_numeric($from_coords[$k]) || !is_numeric($to_coords[$k])){
                return null;
            }
        }

        $lng1=$from_coords['lng'];
        $lat1=$from_coords['lat'];

        $lng2=$to_coords['lng'];
        $lat2=$to_coords['lat'];

        // 将角度转为狐度
        $radLat1 = deg2rad($lat1); //deg2rad()函数将角度转换为弧度
        $radLat2 = deg2rad($lat2);
        $radLng1 = deg2rad($lng1);
        $radLng2 = deg2rad($lng2);
        $a = $radLat1 - $radLat2;
        $b = $radLng1 - $radLng2;
        $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2))) * 6378.137 * 1000;

        return round($s);
    }

    /**
     * 常用的正则验证
     * @param  string $rule 匹配规则
     * @param string $value 待验证值
     * @return boolean
     */
    function regexCheck($rule = '', $value = '')
    {
        $rules=array(
            'email'				=> "/^[0-9a-zA-Z]+(?:[\_\.\-][a-z0-9\-]+)*@[a-zA-Z0-9]+(?:[-.][a-zA-Z0-9]+)*\.[a-zA-Z]+$/",//邮箱
            'cellphone'			=> "/^(13[0-9]|14[579]|15[0-3,5-9]|16[6]|17[0135678]|18[0-9]|19[89])\d{8}$/",//手机号
			'username'			=> "/^[a-zA-Z][0-9a-zA-Z_]*$/", //用户名 字母开头由数字、字母、下划线组成
            'password'			=> "/^[0-9a-zA-Z_]+$/",//密码 由数字、字母、下划线组成
            'int'				=> "/^[-]?(0|[1-9]\d*)$/",//整数
            'int_without_zero'	=> "/^([-]?[1-9]\d*)$/",//非零整数
            'int_gt_zero'		=> "/^[1-9]\d*$/",//正整数
            'int_egt_zero'		=> "/^(0|[1-9]\d*)$/",//正整数和零
            'int_lt_zero'		=> "/^-[1-9]\d*$/",//负整数
            'int_elt_zero'		=> "/^(0|-[1-9]\d*)$/",//负整数和零
			'money'				=> "/^(0|[1-9]\d*)([\.]\d{1,2})?$/",//金额
            'config_item'		=> "/^([a-z_]+[\.]?)*[a-z_]+$/",//配置项键规则
        );

        if(is_string($rule) && is_string($value) && !empty($rule)){
            if(!in_array($rule, array_keys($rules))){
                return false;
            }
        }else{
            return false;
        }

        return !!preg_match($rules[$rule], $value);
    }