<?php

namespace app\admin\controller;

use app\model\Partner;
use think\facade\Request;
use app\model\User;
use app\model\GradeType;
use app\model\UserBank;

class Users extends Base {

    public $left_menu_active = 'admin_users_index';
    public $top_menu_active = 'users';

    public function initialize() {
        parent::initialize();
    }

    /*
     *  列表
     */

    public function index(){
        //获取数据  分页
        $userModel = new User();
        $limit = input('limit') == null || empty(input('limit')) ? getLimit() : input('limit');
        $where[] = array('status','=',1);
        $list = $userModel->where($where)->order('id desc')->paginate($limit);
        $this->assign('list', $list);
        return $this->fetch();
    }

    /*
     * 黑名单列表
     */
    public function blacklist(){
        //获取数据  分页
        $userModel = new User();
        $limit = input('limit') == null || empty(input('limit')) ? getLimit() : input('limit');
        $where[] = array('status','=',2);
        $list = $userModel->where($where)->order('id desc')->paginate($limit);
        $this->assign('list', $list);
        $this->assign('left_menu_active', 'admin_users_blacklist');
        return $this->fetch();
    }
    /*
     * 用户详情
     */
    public function detail(){
        $user_id = input('user_id');
        $userModel = new User();
        $data = $userModel->find($user_id);
        $this->assign('userData',$data);
        $this->assign('partnerData',getPartnerInfo($data['partner_id']));
        return $this->fetch();
    }

    /*
     * 加入黑名单
     */
    public function ajaxAddBlackLIst(){
        $user_id = input('user_id');
        $userModel = new User();
        $data = $userModel->find($user_id);
        if(empty($data)){
            $this->error('会员不存在');
        }

        $update = array(
            'status' => 2
        );
        $info = $userModel->where('id','=',$user_id)->update($update);
        if(empty($info)){
            ajaxJsonReturn(-1,'设置失败');
        }else{
            ajaxJsonReturn(1,'设置成功');
        }
    }

    /*
     * 加入白名单
     */
    public function ajaxAddWhiteLIst(){
        $user_id = input('user_id');
        $userModel = new User();
        $data = $userModel->find($user_id);
        if(empty($data)){
            $this->error('会员不存在');
        }

        $update = array(
            'status' => 1
        );
        $info = $userModel->where('id','=',$user_id)->update($update);
        if(empty($info)){
            ajaxJsonReturn(-1,'设置失败');
        }else{
            ajaxJsonReturn(1,'设置成功');
        }
    }





    public function game(){
        //获取所有激活的合作商
        $partnerModel = new Partner();
        $where = array();
        $where[] = array('status','=',1);
        $partnerList = $partnerModel->where($where)->select();
        $this->assign('partnerList',$partnerList);
        $this->assign('left_menu_active', 'admin_users_game');
        return $this->fetch();
    }
    public function lucky(){
        $this->assign('left_menu_active', 'admin_users_lucky');
        return $this->fetch();
    }

}
