<?php
namespace app\api\controller;
use app\model\Partner;
use think\Controller;
use app\model\User;


class Base extends Controller
{
    public $userId = '';
    public $userInfo = '';

    public $partnerId = '';
    public $partnerInfo = '';
    public function initialize() {
        parent::initialize();
        //判断用户登录
        $UserModel = new User();
        $user_id = decode(input('user_id'));
        $user_info = $UserModel->where('id','=',$user_id)->find();
        if (empty($user_info)) {
            ajaxJsonReturn(-1, '用户不存在！');
        }
        if ($user_info['status'] == 2) {
            ajaxJsonReturn(-2, '您已经被加入黑名单，不能进行游戏！');
        }

        $partnerModel = new Partner();
        $partner_id = input('partner_id');
        $partner_id = decode($partner_id);
        $partner_info = $partnerModel->where('id','=',$partner_id)->find();

        if (empty($partner_info)) {
            ajaxJsonReturn(-1, '合作商不存在！');
        }
        if ($user_info['status'] == 0) {
            ajaxJsonReturn(-2, '该合作商以被禁用，不能进行游戏！');
        }

        $this->userId = $user_id;
        $this->userInfo = $user_info;
        $this->partnerId = $partner_id;
        $this->partnerInfo = $partner_info;
    }
}
