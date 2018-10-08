<?php

namespace app\admin\controller;


use think\Request;
use think\Db;

use app\model\Card;
use app\model\AppConfig;
use app\model\UserScene;
use app\model\TicketApiConf;



class Easyar extends Base {

    private $sdk;

    public function initialize(){
        if(empty($this->sdk)){
            $this->sdk = new \easyar\EasyARCloudSdk(config('easyar.appKey'),config('easyar.appSecret'),config('easyar.Server'));
        }
        $rs = $this->sdk->ping();
        if(!(!empty($rs) && $rs != null && $rs['statusCode'] == 0 && $rs['result']['message'] == 'pong')){
            return -1;
        }
        return $this->sdk;
    }

    public function targetAdd($name,$imgpath,$mate=''){
        $params = [
            'name' => $name,
            'size' => '20',
            'meta' => base64_encode($mate),
            'image' => base64_encode(file_get_contents($imgpath)),
        ];
        $rs = $this->sdk->targetAdd($params);
        return $rs;
    }

    public function targetDelete($targetIdArr){
        foreach ($targetIdArr as $value){
            $rs = $this->sdk->delete($value);
        }
    }

    public function checkSimilar($path){
        $image = base64_encode(file_get_contents($path));
        $rs = $this->sdk->similar($image);
        return $rs;
    }

}