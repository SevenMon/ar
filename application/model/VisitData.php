<?php

namespace app\model;

use think\Model;

class VisitData extends Model {

    //页面数据字典
    public $pageData = array(
        "pages/index/index"=>  "首页",
        "pages/index/login"=>  "登录页",
        "pages/camera/index"=>  "照相机页",
        "pages/camera/get"=>  "领奖页"
    );
}
