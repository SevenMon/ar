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
        $source_address_url = input('source_address_url');
        $addData = array(
            'user_id' => $user_id,
            'open_id' => $open_id,
            'partner_id' => $partner_id,
            'project_id' => $project_id,
            'address_url' => $address_url,
            'source_address_url' => $source_address_url
        );
        $visitDataModel = new VisitData();
        $visitDataModel->insertGetId($addData);
    }

    public function recordFirstEnd(){

    }
}
