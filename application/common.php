<?php

/**
 * ============================================================================
 * WSTMart多用户商城
 * 版权所有 2016-2066 广州商淘信息科技有限公司，并保留所有权利。
 * 官网地址:http://www.wstmart.net
 * 交流社区:http://bbs.shangtao.net
 * 联系QQ:153289970
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！未经本公司授权您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 */
use think\Db;

/**
     * 模拟提交数据函数
     * @param unknown $url
     * @param unknown $data
     * @return mixed
     */
function vpost($url,$data){
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE); // 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)'); // 模拟用户使用的浏览器
        // curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        // curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
        $tmpInfo = curl_exec($curl); // 执行操作
        if (curl_errno($curl)) {
            echo 'Errno'.curl_error($curl);//捕抓异常
        }
        curl_close($curl); // 关闭CURL会话
        return $tmpInfo; // 返回数据
    }
/**
 * 获取CURL请求
 * @param $url // 请求地址
 * @param string $type // 请求方式
 * @param string $res // 发送请求资源
 * @param string $header // 设置POST头部
 * @return mixed|string
 * @author jihaichuan
 */
function http_curl($url, $type = 'get', $res = '', $header = '') {
    // 1.初始化 curl
    $ch = curl_init();
    // 2.设置curl参数
    curl_setopt($ch, CURLOPT_URL, $url);
    if ($header) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    }
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // 判断发送类型
    switch ($type) {
        case 'post':
            curl_setopt($ch, CURLOPT_POST, 3);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $res);
            break;
        case 'put':
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $res);
            break;
    }
    // 3.采集
    $outopt = curl_exec($ch);
    // 4.判断是否错误
    if (curl_errno($ch)) {
        return curl_error($ch);
    }
    // 4.关闭
    curl_close($ch);
    // 返回
    return json_decode($outopt, true);
}

/*
 * gf 返回 数据统一格式
 */

function ajaxJsonReturn($code = 0, $msg = '', $data = array()) {
    $data = array(
        'status' => 1,
        'code' => $code,
        'msg' => $msg,
        'data' => empty($data) ? array() : $data,
    );
    echo json_encode($data);
    exit();
}

function vget($url) {
    exec("curl -sS --connect-timeout 10 -m 60 '" . $url . "'");
    return true;
    
//        $curl = curl_init(); // 启动一个CURL会话
//    curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
//    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); // 对认证证书来源的检查
//    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE); // 从证书中检查SSL加密算法是否存在
//    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)'); // 模拟用户使用的浏览器
//    // curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
//    // curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
//    curl_setopt($curl, CURLOPT_TIMEOUT, 1); // 设置超时限制防止死循环
//    curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
//    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
//    $tmpInfo = curl_exec($curl); // 执行操作
//    if (curl_errno($curl)) {
//        //echo 'Errno'.curl_error($curl);//捕抓异常
//    }
//    curl_close($curl); // 关闭CURL会话
//    return $tmpInfo; // 返回数据
    
}

function urlsafe_b64decode($string) {
    $data = str_replace(array('-', '_'), array('+', '/'), $string);
    $mod4 = strlen($data) % 4;
    if ($mod4) {
        $data .= substr('====', $mod4);
    }
    return base64_decode($data);
}

/**
 * 生成验证码
 */
function WSTVerify() {
    ob_clean();
    $Verify = new \verify\Verify();
    $Verify->length = 4;
    $Verify->entry();
}

/**
 * 核对验证码
 */
function WSTVerifyCheck($code) {
    $verify = new \verify\Verify();
    return $verify->check($code);
}

/**
 * 生成数据返回值
 */
function WSTReturn($msg, $status = -1, $data = []) {
    $rs = ['status' => $status, 'msg' => $msg];
    if (!empty($data))
        $rs['data'] = $data;
    return $rs;
}

/**
 *
 * 加密密码
 *
 * @author zhaoming
 * @mail 468143282@qq.com
 */
function password($password, $username) {
    $rs = MD5($username . $password . config('app.password'));
    return $rs;
}

/**
 * 检测字符串不否包含
 * @param $srcword 被检测的字符串
 * @param $filterWords 禁用使用的字符串列表
 * @return boolean true-检测到,false-未检测到
 */
function WSTCheckFilterWords($srcword, $filterWords) {
    $flag = true;
    if ($filterWords != "") {
        $filterWords = str_replace("，", ",", $filterWords);
        $words = explode(",", $filterWords);
        for ($i = 0; $i < count($words); $i++) {
            if (strpos($srcword, $words[$i]) !== false) {
                $flag = false;
                break;
            }
        }
    }
    return $flag;
}

/**
 * 获取指定的全局配置
 */
function WSTConf($key, $v = '') {
    if (is_null($v)) {
        if (array_key_exists('WSTMARTCONF', $GLOBALS) && array_key_exists($key, $GLOBALS['WSTMARTCONF'])) {
            unset($GLOBALS['WSTMARTCONF'][$key]);
        }
    } else if ($v === '') {
        if (array_key_exists('WSTMARTCONF', $GLOBALS)) {
            $conf = $GLOBALS['WSTMARTCONF'];
            $ks = explode(".", $key);
            for ($i = 0, $k = count($ks); $i < $k; $i++) {
                if (array_key_exists($ks[$i], $conf)) {
                    $conf = $conf[$ks[$i]];
                } else {
                    return null;
                }
            }
            return $conf;
        }
    } else {
        return $GLOBALS['WSTMARTCONF'][$key] = $v;
    }
    return null;
}

//php获取中文字符拼音首字母
function WSTGetFirstCharter($str) {
    if (empty($str)) {
        return '';
    }
    $fchar = ord($str{0});
    if ($fchar >= ord('A') && $fchar <= ord('z'))
        return strtoupper($str{0});
    $s1 = iconv('UTF-8', 'gb2312', $str);
    $s2 = iconv('gb2312', 'UTF-8', $s1);
    $s = $s2 == $str ? $s1 : $str;
    if (empty($s{1})) {
        return '';
    }
    $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
    if ($asc >= -20319 && $asc <= -20284)
        return 'A';
    if ($asc >= -20283 && $asc <= -19776)
        return 'B';
    if ($asc >= -19775 && $asc <= -19219)
        return 'C';
    if ($asc >= -19218 && $asc <= -18711)
        return 'D';
    if ($asc >= -18710 && $asc <= -18527)
        return 'E';
    if ($asc >= -18526 && $asc <= -18240)
        return 'F';
    if ($asc >= -18239 && $asc <= -17923)
        return 'G';
    if ($asc >= -17922 && $asc <= -17418)
        return 'H';
    if ($asc >= -17417 && $asc <= -16475)
        return 'J';
    if ($asc >= -16474 && $asc <= -16213)
        return 'K';
    if ($asc >= -16212 && $asc <= -15641)
        return 'L';
    if ($asc >= -15640 && $asc <= -15166)
        return 'M';
    if ($asc >= -15165 && $asc <= -14923)
        return 'N';
    if ($asc >= -14922 && $asc <= -14915)
        return 'O';
    if ($asc >= -14914 && $asc <= -14631)
        return 'P';
    if ($asc >= -14630 && $asc <= -14150)
        return 'Q';
    if ($asc >= -14149 && $asc <= -14091)
        return 'R';
    if ($asc >= -14090 && $asc <= -13319)
        return 'S';
    if ($asc >= -13318 && $asc <= -12839)
        return 'T';
    if ($asc >= -12838 && $asc <= -12557)
        return 'W';
    if ($asc >= -12556 && $asc <= -11848)
        return 'X';
    if ($asc >= -11847 && $asc <= -11056)
        return 'Y';
    if ($asc >= -11055 && $asc <= -10247)
        return 'Z';
    return null;
}

/**
 * 设置当前页面对象
 * @param int 0-用户  1-商家
 */
function WSTLoginTarget($target = 0) {
    $WST_USER = session('WST_USER');
    $WST_USER['loginTarget'] = $target;
    session('WST_USER', $WST_USER);
}

/**
 * 邮件发送函数
 * @param string to      要发送的邮箱地址
 * @param string subject 邮件标题
 * @param string content 邮件内容
 * @return array
 */
function WSTSendMail($to, $subject, $content) {
    $mail = new \phpmailer\phpmailer();
    // 装配邮件服务器
    $mail->IsSMTP();
    $mail->SMTPDebug = 0;
    $mail->Host = WSTConf("CONF.mailSmtp");
    $mail->SMTPAuth = WSTConf("CONF.mailAuth");
    $mail->Username = WSTConf("CONF.mailUserName");
    $mail->Password = WSTConf("CONF.mailPassword");
    $mail->CharSet = 'utf-8';
    $mail->Port = WSTConf("CONF.mailPort");
    // 装配邮件头信息
    $mail->From = WSTConf("CONF.mailAddress");
    $mail->AddAddress($to);
    $mail->FromName = WSTConf("CONF.mailSendTitle");
    $mail->IsHTML(true);
    // 装配邮件正文信息
    $mail->Subject = $subject;
    $mail->Body = $content;
    // 发送邮件
    $rs = array();
    if (!$mail->Send()) {
        $rs['status'] = 0;
        $rs['msg'] = $mail->ErrorInfo;
        return $rs;
    } else {
        $rs['status'] = 1;
        return $rs;
    }
}

/**
 * 获取系统配置数据
 */
function WSTConfig() {
    $rs = cache('WST_CONF');
    if (!$rs) {
        $rv = Db::name('sys_configs')->field('fieldCode,fieldValue')->select();
        $rs = [];
        foreach ($rv as $v) {
            $rs[$v['fieldCode']] = $v['fieldValue'];
        }
        //获取风格
        $styles = Db::name('styles')->where(['isUse' => 1])->field('styleSys,stylePath,id')->select();
        if (!empty($styles)) {
            foreach ($styles as $key => $v) {
                $rs['wst' . $v['styleSys'] . 'Style'] = $v['stylePath'];
                $rs['wst' . $v['styleSys'] . 'StyleId'] = $v['id'];
            }
        }
        //获取上传文件目录配置
        $data = Db::name('datas')->where('catId', 3)->column('dataVal');
        foreach ($data as $key => $v) {
            $data[$key] = str_replace('_', '', $v);
        }
        $rs['wstUploads'] = $data;
        if (WSTConf('CONF.mallLicense') == '')
            $rs['mallSlogan'] = $rs['mallSlogan'] . "  " . base64_decode('UG93ZXJlZCBCeSBXU1RNYXJ0');
        cache('WST_CONF', $rs, 31536000);
    }
    return $rs;
}

/**
 * 判断手机号格式是否正确
 */
function WSTIsPhone($phoneNo) {
    $reg = "/^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$/";
    $rs = \think\Validate::regex($phoneNo, $reg);
    return $rs;
}

/**
 * 检测登录账号是否可用
 * @param $key 要检测的内容
 */
function WSTCheckLoginKey($val, $userId = 0) {
    if ($val == '')
        return WSTReturn("登录账号不能为空");
    if (!WSTCheckFilterWords($val, WSTConf("CONF.registerLimitWords"))) {
        return WSTReturn("登录账号包含非法字符");
    }
    $dbo = Db::name('users')->where(["loginName|userEmail|userPhone" => ['=', $val], 'dataFlag' => 1]);
    if ($userId > 0) {
        $dbo->where("userId", "<>", $userId);
    }
    $rs = $dbo->count();
    if ($rs == 0) {
        return WSTReturn("该登录账号可用", 1);
    }
    return WSTReturn("对不起，登录账号已存在");
}

/**
 * 生成随机数账号
 */
function WSTRandomLoginName($loginName) {
    $chars = array("a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z");
    //简单的派字母
    foreach ($chars as $key => $c) {
        $crs = WSTCheckLoginKey($loginName . "_" . $c);
        if ($crs['status'] == 1)
            return $loginName . "_" . $c;
    }
    //随机派三位数值
    for ($i = 0; $i < 1000; $i++) {
        $crs = $this->WSTCheckLoginKey($loginName . "_" . $i);
        if ($crs['status'] == 1)
            return $loginName . "_" . $i;
    }
    return '';
}

/**
 * 删除一维数组里的多个key
 */
function WSTUnset(&$data, $keys) {
    if ($keys != '' && is_array($data)) {
        $key = explode(',', $keys);
        foreach ($key as $v)
            unset($data[$v]);
    }
}

/**
 * 只允许一维数组里的某些key通过
 */
function WSTAllow(&$data, $keys) {
    if ($keys != '' && is_array($data)) {
        $key = explode(',', $keys);
        foreach ($data as $vkeys => $v)
            if (!in_array($vkeys, $key))
                unset($data[$vkeys]);
    }
}

/**
 * 字符串替换
 * @param string $str     要替换的字符串
 * @param string $repStr  即将被替换的字符串
 * @param int $start      要替换的起始位置,从0开始
 * @param string $splilt  遇到这个指定的字符串就停止替换
 */
function WSTStrReplace($str, $repStr, $start, $splilt = '') {
    $newStr = substr($str, 0, $start);
    $breakNum = -1;
    for ($i = $start; $i < strlen($str); $i++) {
        $char = substr($str, $i, 1);
        if ($char == $splilt) {
            $breakNum = $i;
            break;
        }
        $newStr .= $repStr;
    }
    if ($splilt != '' && $breakNum > -1) {
        for ($i = $breakNum; $i < strlen($str); $i++) {
            $char = substr($str, $i, 1);
            $newStr .= $char;
        }
    }
    return $newStr;
}

/**
 * 上传图片
 * 需要生成缩略图： isThumb=1
 * 需要加水印：isWatermark=1
 * pc版缩略图： width height
 * 手机版原图：mWidth mHeight
 * 缩略图：mTWidth mTHeight
 * 判断图片来源：fromType 0：商家/用户   1：平台管理员
 */
function WSTUploadPic($fromType = 0) {
    $fileKey = key($_FILES);
    $dir = Input('param.dir');
    if ($dir == '')
        return json_encode(['msg' => '没有指定文件目录！', 'status' => -1]);
    $dirs = WSTConf("CONF.wstUploads");
    if (!in_array($dir, $dirs)) {
        return json_encode(['msg' => '非法文件目录！', 'status' => -1]);
    }
    // 上传文件
    $file = request()->file($fileKey);
    if ($file === null) {
        return json_encode(['msg' => '上传文件不存在或超过服务器限制', 'status' => -1]);
    }
    $validate = new \think\Validate([
        ['fileMime', 'fileMime:image/png,image/gif,image/jpeg,image/x-ms-bmp', '只允许上传jpg,gif,png,bmp类型的文件'],
        ['fileExt', 'fileExt:jpg,jpeg,gif,png,bmp', '只允许上传后缀为jpg,gif,png,bmp的文件'],
        ['fileSize', 'fileSize:2097152', '文件大小超出限制'], //最大2M
    ]);
    $data = ['fileMime' => $file,
        'fileSize' => $file,
        'fileExt' => $file
    ];
    if (!$validate->check($data)) {
        return json_encode(['msg' => $validate->getError(), 'status' => -1]);
    }
    $info = $file->rule('uniqid')->move(ROOT_PATH . '/upload/' . $dir . "/" . date('Y-m'));
    if ($info) {
        $filePath = $info->getPathname();
        $filePath = str_replace(ROOT_PATH, '', $filePath);
        $filePath = str_replace('\\', '/', $filePath);
        $name = $info->getFilename();
        $filePath = str_replace($name, '', $filePath);
        //原图路径
        $imageSrc = trim($filePath . $name, '/');
        //图片记录
        WSTRecordImages($imageSrc, (int) $fromType);
        //打开原图
        $image = \image\Image::open($imageSrc);
        //缩略图路径 手机版原图路径 手机版缩略图路径
        $thumbSrc = $mSrc = $mThumb = null;
        //手机版原图宽高
        $mWidth = min($image->width(), (int) input('mWidth', 700));
        $mHeight = min($image->height(), (int) input('mHeight', 700));
        //手机版缩略图宽高
        $mTWidth = min($image->width(), (int) input('mTWidth', 250));
        $mTHeight = min($image->height(), (int) input('mTHeight', 250));

        /*         * **************************** 生成缩略图 ******************************** */
        $isThumb = (int) input('isThumb');
        if ($isThumb == 1) {
            //缩略图路径
            $thumbSrc = str_replace('.', '_thumb.', $imageSrc);
            $image->thumb((int) input('width', min(300, $image->width())), (int) input('height', min(300, $image->height())), 2)->save($thumbSrc, $image->type(), 90);
            //是否需要生成移动版的缩略图
            $suffix = WSTConf("CONF.wstMobileImgSuffix");
            if (!empty($suffix)) {
                $image = \image\Image::open($imageSrc);
                $mSrc = str_replace('.', "$suffix.", $imageSrc);
                $mThumb = str_replace('.', '_thumb.', $mSrc);
                $image->thumb($mWidth, $mHeight)->save($mSrc, $image->type(), 90);
                $image->thumb($mTWidth, $mTHeight, 2)->save($mThumb, $image->type(), 90);
            }
        }
        /*         * *************************** 添加水印 ********************************** */
        $isWatermark = (int) input('isWatermark');
        if ($isWatermark == 1 && (int) WSTConf('CONF.watermarkPosition') !== 0) {
            //取出水印配置
            $wmWord = WSTConf('CONF.watermarkWord'); //文字
            $wmFile = trim(WSTConf('CONF.watermarkFile'), '/'); //水印文件
            $wmPosition = (int) WSTConf('CONF.watermarkPosition'); //水印位置
            $wmSize = ((int) WSTConf('CONF.watermarkSize') != 0) ? WSTConf('CONF.watermarkSize') : '20'; //大小
            $wmColor = (WSTConf('CONF.watermarkColor') != '') ? WSTConf('CONF.watermarkColor') : '#000000'; //颜色必须是16进制的
            $wmOpacity = ((int) WSTConf('CONF.watermarkOpacity') != 0) ? WSTConf('CONF.watermarkOpacity') : '100'; //水印透明度
            //是否有自定义字体文件
            $customTtf = $_SERVER['DOCUMENT_ROOT'] . WSTConf('CONF.watermarkTtf');
            $ttf = is_file($customTtf) ? $customTtf : EXTEND_PATH . '/verify/verify/ttfs/3.ttf';
            $image = \image\Image::open($imageSrc);
            if (!empty($wmWord)) {//当设置了文字水印 就一定会执行文字水印,不管是否设置了文件水印
                //执行文字水印
                $image->text($wmWord, $ttf, $wmSize, $wmColor, $wmPosition)->save($imageSrc);
                if ($thumbSrc !== null) {
                    $image->thumb((int) input('width', min(300, $image->width())), (int) input('height', min(300, $image->height())), 2)->save($thumbSrc, $image->type(), 90);
                }
                //如果有生成手机版原图
                if (!empty($mSrc)) {
                    $image = \image\Image::open($imageSrc);
                    $image->thumb($mWidth, $mHeight)->save($mSrc, $image->type(), 90);
                    $image->thumb($mTWidth, $mTHeight, 2)->save($mThumb, $image->type(), 90);
                }
            } elseif (!empty($wmFile)) {//设置了文件水印,并且没有设置文字水印
                //执行图片水印
                $image->water($wmFile, $wmPosition, $wmOpacity)->save($imageSrc);
                if ($thumbSrc !== null) {
                    $image->thumb((int) input('width', min(300, $image->width())), (int) input('height', min(300, $image->height())), 2)->save($thumbSrc, $image->type(), 90);
                }
                //如果有生成手机版原图
                if ($mSrc !== null) {
                    $image = \image\Image::open($imageSrc);
                    $image->thumb($mWidth, $mHeight)->save($mSrc, $image->type(), 90);
                    $image->thumb($mTWidth, $mTHeight, 2)->save($mThumb, $image->type(), 90);
                }
            }
        }
        //判断是否有生成缩略图
        $thumbSrc = ($thumbSrc == null) ? $info->getFilename() : str_replace('.', '_thumb.', $info->getFilename());
        $filePath = ltrim($filePath, '/');
        // 用户头像上传宽高限制
        $isCut = (int) input('isCut');
        if ($isCut) {
            $imgSrc = $filePath . $info->getFilename();
            $image = \image\Image::open($imgSrc);
            $size = $image->size(); //原图宽高
            $w = $size[0];
            $h = $size[1];
            $rate = $w / $h;
            if ($w > $h && $w > 500) {
                $newH = 500 / $rate;
                $image->thumb(500, $newH)->save($imgSrc, $image->type(), 90);
            } elseif ($h > $w && $h > 500) {
                $newW = 500 * $rate;
                $image->thumb($newW, 500)->save($imgSrc, $image->type(), 90);
            }
        }
        return json_encode(['status' => 1, 'savePath' => $filePath, 'name' => $info->getFilename(), 'thumb' => $thumbSrc]);
    } else {
        //上传失败获取错误信息
        return $file->getError();
    }
}

/**
 * 上传文件
 */
function WSTUploadFile() {
    $fileKey = key($_FILES);
    $dir = Input('post.dir');
    if ($dir == '')
        return json_encode(['msg' => '没有指定文件目录！', 'status' => -1]);
    $dirs = WSTConf("CONF.wstUploads");
    if (!in_array($dir, $dirs)) {
        return json_encode(['msg' => '非法文件目录！', 'status' => -1]);
    }
    //上传文件
    $file = request()->file($fileKey);
    if ($file === null) {
        return json_encode(['msg' => '上传文件不存在或超过服务器限制', 'status' => -1]);
    }
    $validate = new \think\Validate([
        ['fileExt', 'fileExt:xls,xlsx,xlsm', '只允许上传后缀为xls,xlsx,xlsm的文件']
    ]);
    $data = ['fileExt' => $file];
    if (!$validate->check($data)) {
        return json_encode(['msg' => $validate->getError(), 'status' => -1]);
    }
    $info = $file->rule('uniqid')->move(ROOT_PATH . '/upload/' . $dir . "/" . date('Y-m'));
    //保存路径
    $filePath = $info->getPathname();
    $filePath = str_replace(ROOT_PATH, '', $filePath);
    $filePath = str_replace('\\', '/', $filePath);
    $name = $info->getFilename();
    $filePath = str_replace($name, '', $filePath);
    if ($info) {
        return json_encode(['status' => 1, 'name' => $info->getFilename(), 'route' => $filePath]);
    } else {
        //上传失败获取错误信息
        return $file->getError();
    }
}
/**
 * 图片管理
 * @param $imgPath    图片路径
 * @param $fromType   0：用户/商家 1：平台管理员
 *
 */
function WSTRecordImages($imgPath, $fromType) {
    $data = [];
    $data['imgPath'] = $imgPath;
    if (file_exists($imgPath)) {
        $data['imgSize'] = filesize($imgPath); //返回字节数 imgsize/1024 k  	imgsize/1024/1024 m
    }
    //获取表名
    $table = explode('/', $imgPath);
    $data['fromTable'] = $table[1];
    $data['fromType'] = (int) $fromType;
    //根据类型判断所有者
    $data['ownId'] = ((int) $fromType == 0) ? (int) session('WST_USER.userId') : (int) session('WST_STAFF.staffId');
    $data['isUse'] = 0; //默认不使用
    $data['createTime'] = date('Y-m-d H:i:s');

    //保存记录
    Db::name('images')->insert($data);
}

/**
 * 启用图片
 * @param $fromType 0：  用户/商家 1：平台管理员
 * @param $dataId        来源记录id
 * @param $imgPath       图片路径,要处理多张图片时请传入一位数组,或用","连接图片路径
 * @param $fromTable     该记录来自哪张表
 * @param $imgFieldName  表中的图片字段名称
 */
function WSTUseImages($fromType, $dataId, $imgPath, $fromTable = '', $imgFieldName = '') {
    if (empty($imgPath))
        return;

    $image['fromType'] = (int) $fromType;
    //根据类型判断所有者
    $image['ownId'] = ((int) $fromType == 0) ? (int) session('WST_USER.userId') : (int) session('WST_STAFF.staffId');
    $image['dataId'] = (int) $dataId;

    $image['isUse'] = 1; //标记为启用
    if ($fromTable != '') {
        $tmp = ['', ''];
        if (strpos($fromTable, '-') !== false) {
            $tmp = explode('-', $fromTable);
            $fromTable = str_replace('-' . $tmp[1], '', $fromTable);
        }
        $image['fromTable'] = str_replace('_', '', $fromTable . $tmp[1]);
    }

    $imgPath = is_array($imgPath) ? $imgPath : explode(',', $imgPath); //转数组
    //用于与旧图比较
    $newImage = $imgPath;

    // 不为空说明执行修改
    if ($imgFieldName != '') {
        //要操作的表名  $fromTable;
        // 获取`$fromTable`表的主键
        $prefix = config('database.prefix');
        $tableName = $prefix . $fromTable;
        $pk = Db::getTableInfo("$tableName", 'pk');
        // 取出旧图
        $oldImgPath = model("$fromTable")->where("$pk", $dataId)->value("$imgFieldName");
        // 转数组
        $oldImgPath = explode(',', $oldImgPath);

        // 1.要设置为启用的文件
        $newImage = array_diff($imgPath, $oldImgPath);
        // 2.要标记为删除的文件
        $oldImgPath = array_diff($oldImgPath, $imgPath);
        //旧图数组跟新图数组相同则不需要继续执行
        if ($newImage != $oldImgPath)
            WSTUnuseImage($oldImgPath);
    }
    if (!empty($newImage)) {
        Db::name('images')->where(['imgPath' => ['in', $newImage]])->update($image);
    }
}

/**
 * 编辑器图片记录
 * @param $fromType 0：  用户/商家 1：平台管理员
 * @param $dataId        来源记录id
 * @param $oldDesc       旧商品描述
 * @param $newDesc       新商品描述
 * @param $fromTable     该记录来自哪张表
 */
function WSTEditorImageRocord($fromTable, $dataId, $oldDesc, $newDesc) {
    //编辑器里的图片
    $rule = '/src="\/(upload.*?)"/';
    // 获取旧的src数组
    preg_match_all($rule, $oldDesc, $images);
    $oldImgPath = $images[1];

    preg_match_all($rule, $newDesc, $images);
    // 获取新的src数组
    $imgPath = $images[1];
    // 1.要设置为启用的文件
    $newImage = array_diff($imgPath, $oldImgPath);
    // 2.要标记为删除的文件
    $oldImgPath = array_diff($oldImgPath, $imgPath);
    //旧图数组跟新图数组相同则不需要继续执行
    if ($newImage != $oldImgPath) {
        //标记新图启用
        WSTUseImages($fromTable, $dataId, $newImage);
        //标记旧图删除
        WSTUnuseImage($oldImgPath);
    }
}

/**
 * 标记删除图片
 */
function WSTUnuseImage($fromTable, $field = '', $dataId = 0) {
    if ($fromTable == '')
        return;
    $imgPath = $fromTable;
    if ($field != '') {
        $prefix = config('database.prefix');
        $tableName = $prefix . $fromTable;
        $pk = Db::getTableInfo("$tableName", 'pk');
        // 取出旧图
        $imgPath = model("$fromTable")->where("$pk", $dataId)->value("$field");
    }
    if (!empty($imgPath)) {
        $imgPath = is_array($imgPath) ? $imgPath : explode(',', $imgPath); //转数组
        Db::name('images')->where(['imgPath' => ['in', $imgPath]])->setField('isUse', 0);
    }
}

/**
 * 获取系统根目录
 */
function WSTRootPath() {
    return dirname(dirname(dirname(dirname(__File__))));
}

/**
 * 切换图片
 * @param $imgurl 图片路径
 * @param $imgType 图片类型    0:PC版大图   1:PC版缩略图       2:移动版大图    3:移动版缩略图
 * 图片规则
 * PC版版大图 :201635459344.jpg
 * PC版版缩略图 :201635459344_thumb.jpg
 * 移动版大图 :201635459344_m.jpg
 * 移动版缩略图 :201635459344_m_thumb.jpg
 */
function WSTImg($imgurl, $imgType = 1) {
    $m = WSTConf('CONF.wstMobileImgSuffix');
    $imgurl = str_replace($m . '.', '.', $imgurl);
    $imgurl = str_replace($m . '_thumb.', '.', $imgurl);
    $imgurl = str_replace('_thumb.', '.', $imgurl);
    $img = '';
    switch ($imgType) {
        case 0:$img = $imgurl;
            break;
        case 1:$img = str_replace('.', '_thumb.', $imgurl);
            break;
        case 2:$img = str_replace('.', $m . '.', $imgurl);
            break;
        case 3:$img = str_replace('.', $m . '_thumb.', $imgurl);
            break;
    }
    return ((file_exists(WSTRootPath() . "/" . $img)) ? $img : $imgurl);
}


/**
 * 获取业务数据内容
 */
function WSTDatas($catId, $id = 0) {
    $rs = Db::name('datas')->order('catId asc,dataSort asc,id asc')->cache(31536000)->select();
    $data = [];
    foreach ($rs as $key => $v) {
        $data[$v['catId']][$v['dataVal']] = $v;
    }
    if (isset($data[$catId])) {
        if ($id == 0)
            return $data[$catId];
        return isset($data[$catId][$id]) ? $data[$catId][$id] : '';
    }
    return [];
}

/**
 * 截取字符串
 */
function WSTMSubstr($str, $start = 0, $length, $charset = "utf-8", $suffix = false) {
    $newStr = '';
    if (function_exists("mb_substr")) {
        if ($suffix) {
            $newStr = mb_substr($str, $start, $length, $charset) . "...";
        } else {
            $newStr = mb_substr($str, $start, $length, $charset);
        }
    } elseif (function_exists('iconv_substr')) {
        if ($suffix) {
            $newStr = iconv_substr($str, $start, $length, $charset) . "...";
        } else {
            $newStr = iconv_substr($str, $start, $length, $charset);
        }
    }
    if ($newStr == '') {
        $re ['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re ['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re ['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re ['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re [$charset], $str, $match);
        $slice = join("", array_slice($match [0], $start, $length));
        if ($suffix)
            $newStr = $slice;
    }
    return $newStr;
}

function WSTScore($score, $users, $type = 5, $len = 0, $total = 1) {
    if ((int) $score == 0)
        return $type;
    switch ($type) {
        case 5:return round($score / $total / $users, 0);
        case 10:return round($score / $total * 2 / $users, $len);
        case 100:return round($score / $total * 2 / $users, $len);
    }
}

function WSTShopEncrypt($shopId) {
    return md5(base64_encode("wstmart" . date("Y-m-d") . $shopId));
}



/**
 * 提供原生分页处理
 */
function WSTPager($total, $rs, $page, $size = 0) {
    $pageSize = ($size > 0) ? $size : config('paginate.list_rows');
    $totalPage = ($total % $pageSize == 0) ? ($total / $pageSize) : (intval($total / $pageSize) + 1);
    return ['Total' => $total, 'PerPage' => $pageSize, 'CurrentPage' => $page, 'TotalPage' => $totalPage, 'Rows' => $rs];
}

/**
 * 编辑器上传图片
 */
function WSTEditUpload($fromType) {
    //PHP上传失败
    if (!empty($_FILES['imgFile']['error'])) {
        switch ($_FILES['imgFile']['error']) {
            case '1':
                $error = '超过php.ini允许的大小。';
                break;
            case '2':
                $error = '超过表单允许的大小。';
                break;
            case '3':
                $error = '图片只有部分被上传。';
                break;
            case '4':
                $error = '请选择图片。';
                break;
            case '6':
                $error = '找不到临时目录。';
                break;
            case '7':
                $error = '写文件到硬盘出错。';
                break;
            case '8':
                $error = 'File upload stopped by extension。';
                break;
            case '999':
            default:
                $error = '未知错误。';
        }
        return WSTReturn(1, $error);
    }

    $fileKey = key($_FILES);
    $dir = 'image'; // 编辑器上传图片目录
    $dirs = WSTConf("CONF.wstUploads");
    if (!in_array($dir, $dirs)) {
        return json_encode(['error' => 1, 'message' => '非法文件目录！']);
    }
    // 上传文件
    $file = request()->file($fileKey);
    if ($file === null) {
        return json_encode(["error" => 1, "message" => '上传文件不存在或超过服务器限制']);
    }
    $validate = new \think\Validate([
        ['fileMime', 'fileMime:image/png,image/gif,image/jpeg,image/x-ms-bmp', '只允许上传jpg,gif,png,bmp类型的文件'],
        ['fileExt', 'fileExt:jpg,jpeg,gif,png,bmp', '只允许上传后缀为jpg,gif,png,bmp的文件'],
        ['fileSize', 'fileSize:2097152', '文件大小超出限制'], //最大2M
    ]);
    $data = ['fileMime' => $file,
        'fileSize' => $file,
        'fileExt' => $file
    ];
    if (!$validate->check($data)) {
        return json_encode(['message' => $validate->getError(), 'error' => 1]);
    }
    $info = $file->rule('uniqid')->move(ROOT_PATH . '/upload/' . $dir . "/" . date('Y-m'));
    if ($info) {
        $filePath = $info->getPathname();
        $filePath = str_replace(ROOT_PATH, '', $filePath);
        $filePath = str_replace('\\', '/', $filePath);
        $name = $info->getFilename();
        $imageSrc = trim($filePath, '/');
        //图片记录
        WSTRecordImages($imageSrc, (int) $fromType);
        return json_encode(array('error' => 0, 'url' => $filePath));
    }
}

/**
 * 转义单引号
 */
function WSTHtmlspecialchars($v) {
    return htmlspecialchars($v, ENT_QUOTES);
}





function WSTFormatIn($split, $str) {
    $strdatas = explode($split, $str);
    $data = array();
    for ($i = 0; $i < count($strdatas); $i++) {
        $data[] = (int) $strdatas[$i];
    }
    $data = array_unique($data);
    return implode($split, $data);
}

/**
 * 简单对称加密算法之加密
 * @param String $string 需要加密的字串
 * @param String $skey 加密EKY
 * @author Anyon Zou <zoujingli@qq.com>
 * @date 2013-08-13 19:30
 * @update 2014-10-10 10:10
 * @return String
 */
function encode($string = '', $skey = 'paixiu.sy') {
    $strArr = str_split(base64_encode($string));
    $strCount = count($strArr);
    foreach (str_split($skey) as $key => $value)
        $key < $strCount && $strArr[$key] .= $value;
    return str_replace(array('=', '+', '/'), array('O0O0O', 'o000o', 'oo00o'), join('', $strArr));
}

/**
 * 简单对称加密算法之解密
 * @param String $string 需要解密的字串
 * @param String $skey 解密KEY
 * @author Anyon Zou <zoujingli@qq.com>
 * @date 2013-08-13 19:30
 * @update 2014-10-10 10:10
 * @return String
 */
function decode($string = '', $skey = 'paixiu.sy') {

    $strArr = str_split(str_replace(array('O0O0O', 'o000o', 'oo00o'), array('=', '+', '/'), $string), 2);
    $strCount = count($strArr);
    foreach (str_split($skey) as $key => $value)
        $key <= $strCount && $strArr[$key][1] === $value && $strArr[$key] = $strArr[$key][0];
    return base64_decode(join('', $strArr));
}
/**
 * 获取客户端IP地址
 * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
 * @param boolean $adv 是否进行高级模式获取（有可能被伪装） 
 * @return mixed
 */
function get_client_ip($type = 0,$adv=false) {
    $type       =  $type ? 1 : 0;
    static $ip  =   NULL;
    if ($ip !== NULL) return $ip[$type];
    if($adv){
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos    =   array_search('unknown',$arr);
            if(false !== $pos) unset($arr[$pos]);
            $ip     =   trim($arr[0]);
        }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip     =   $_SERVER['HTTP_CLIENT_IP'];
        }elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip     =   $_SERVER['REMOTE_ADDR'];
        }
    }elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip     =   $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $long = sprintf("%u",ip2long($ip));
    $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
    return $ip[$type];
}
function getLimit(){
    return 10;
}

function getPartnerInfo($partner_id,$colum=''){
    $partnerModel = new \app\model\Partner();
    $info = $partnerModel->find($partner_id);
    if(empty($colum)){
        return $info;
    }
    return $info[$colum];
}

function getUrl(){
    $http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
    return  $http_type . $_SERVER['HTTP_HOST'];
}

/*
*功能：php完美实现下载远程图片保存到本地
*参数：文件url,保存文件目录,保存文件名称，使用的下载方式
*当保存文件名称为空时则使用远程文件原来的名称
*/
function getImage($url,$save_dir='',$filename='',$type=0){
    if(trim($url)==''){
        return array('file_name'=>'','save_path'=>'','error'=>1);
    }
    if(trim($save_dir)==''){
        $save_dir='./';
    }
    if(trim($filename)==''){//保存文件名
        $ext=strrchr($url,'.');
        if($ext!='.gif'&&$ext!='.jpg'){
            return array('file_name'=>'','save_path'=>'','error'=>3);
        }
        $filename=time().$ext;
    }
    if(0!==strrpos($save_dir,'/')){
        $save_dir.='/';
    }
    //创建保存目录
    if(!file_exists($save_dir)&&!mkdir($save_dir,0777,true)){
        return array('file_name'=>'','save_path'=>'','error'=>5);
    }
    //获取远程文件所采用的方法
    if($type){
        $ch=curl_init();
        $timeout=5;
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
        $img=curl_exec($ch);
        curl_close($ch);
    }else{
        ob_start();
        readfile($url);
        $img=ob_get_contents();
        ob_end_clean();
    }
    //$size=strlen($img);
    //文件大小
    $fp2=@fopen($save_dir.$filename,'a');
    fwrite($fp2,$img);
    fclose($fp2);
    unset($img,$url);
    return array('file_name'=>$filename,'save_path'=>$save_dir.$filename,'error'=>0);
}