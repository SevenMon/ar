<?php

// 命名空间

namespace app\api\controller;

use app\model\Partner;
use think\Request;
use think\Db;
use app\model\TicketApiConf;
use app\model\AppConfig;
use app\model\User;


//登录接口
class Login {

    /**
     * 首页
     * @author  whh
     */
    public function index() {
        echo('OK');
    }

    public function login() {
        $UserModel = new User();
        $code = input('post.code');
        /*$encryptedData = input('post.encryptedData');
        $iv = input('post.iv');*/
        $tmpinfo = input('userInfo');
        $userinfo = json_decode($tmpinfo, true);

        $partner_id = input('partner_id');
        $partner_id = decode($partner_id);
        $app_id = input('app_id');
        $partnerModel = new Partner();
        $where = array();
        $where[] = array('id','=',$partner_id);
        $where[] = array('appid','=',$app_id);
        $partnerData = $partnerModel->where($where)->find();
        if(empty($partnerData)){
            ajaxJsonReturn(-1,'partnerId或appid错误');
        }
        if ($code) {
            $wxbizmsg = new \wxbiz\WxBizMsg();
            $url = "https://api.weixin.qq.com/sns/jscode2session?appid=".$partnerData['appid']."&secret=".$partnerData['appsecret']."&js_code=$code&grant_type=authorization_code";
            $info = http_curl($url, 'get');         
            if ($info['openid']) {
                $addData['session_key'] = $info['session_key'];
                $addData['openid'] = $info['openid'];
                $addData['nickname'] = base64_encode($userinfo['nickName']);
                $addData['avatar_url'] = $userinfo['avatarUrl'];
                $addData['city'] = $userinfo['city'];
                $addData['language'] = $userinfo['language'];
                $addData['province'] = $userinfo['province'];
                $addData['country'] = $userinfo['country'];
                $addData['gender'] = $userinfo['gender'];
                $addData['status'] = 1;
                $addData['timestamp'] = time();
                $addData['partner_id'] = $partner_id;
                $userInfo1 = $UserModel->where(['openid' => $addData['openid']])->find();
                if (empty($userInfo1)) {
                    //$users = $UserModel->insert($addData);
                    $userId = $UserModel->insertGetId($addData);
                } else {
                    $UserModel->save($addData, ['id' => $userInfo1['id']]);
                    $userId = $userInfo1['id'];
                }
                //插入商铺跟用户关联表
                ajaxJsonReturn(0, '获取成功', ['a' => encode($userId)]);
            } else {
                ajaxJsonReturn(-1, '获取session_key失败！');
            }
        } else {
            ajaxJsonReturn(-1, '获取code失败！');
        }
    }

    

}
