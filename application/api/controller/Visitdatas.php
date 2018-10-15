<?php

// 命名空间

namespace app\api\controller;

use think\Request;
use think\Db;
use app\model\VisitData;

class Visitdatas extends Base {

    public function index(){

    }

    public function recordPage(){
        $user_id = $this->userId;
        $open_id = $this->userInfo['openid'];
        $partner_id = $this->userInfo['partner_id'];
        $project_id = $this->projectInfo['id'];
        $address_url = input('address_url');
        //1任意页面  2进入小程序页面
        $type = input('type');
        $source_address_url = input('source_address_url');
        $visitDataModel = new VisitData();
        $addData = array(
            'user_id' => $user_id,
            'open_id' => $open_id,
            'partner_id' => $partner_id,
            'project_id' => $project_id,
            'address_url' => $address_url,
            'address_name' => $visitDataModel->pageData[$address_url],
            'source_address_url' => $source_address_url,
            'source_address_name' => empty($visitDataModel->pageData[$source_address_url]) || $visitDataModel->pageData[$source_address_url] == null ?"":$visitDataModel->pageData[$source_address_url],
            'type' => $type
        );
        $visitDataModel->insertGetId($addData);
    }
}
