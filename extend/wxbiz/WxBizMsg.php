<?php
/**
 * 微信小程序授权类
 *
 * @copyright Copyright (c) FDD 20170824
 */
namespace wxbiz;

include_once "PHPwx/WXBizMsgCrypt.php";

use think\Db;
use OSS\OssClient;
use OSS\Core\OssException;
class WxBizMsg
{
    private $appId='wx128aa16a467de0e3';  
    private $appSecret='d1c2711fe0834e59ccaf03faffc9d8b1'; 
    /**
     * 获得appid
     * @author  fdd
     */
    public function getAppId(){
            return $this->appId;
    }
    /**
     * 获得$appSecret
     * @author  fdd
     */
    public function getAppSecret(){
            return $this->appSecret;
    }
}
