<?php
namespace app\api\controller;
use think\Controller;


class Base extends Controller
{
    public $userId = '';
    public $userInfo = '';
    public function initialize() {
        parent::initialize();
        //判断用户登录
        $UserModel = new User();
        $user_id = decode(input('user_id'));
        $user_info = $UserModel->where('id','=',$user_id)->find();
        if (empty($user_info)) {
            ajaxJsonReturn(-1, '用户不存在！');
        }
        $this->userId = $user_id;
        $this->userInfo = $user_info;
    }
}
