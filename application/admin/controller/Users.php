<?php

namespace app\admin\controller;

use app\model\GameType;
use app\model\Partner;
use app\model\UserGameArData;
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
        $game_type_id = input('game_type_id');
        if(empty($game_type_id) || $game_type_id == null){
            $game_type_id = 1;
        }
        switch ($game_type_id)
        {
            case 1:
                return $this->argame();
                break;
            default:
                ajaxJsonReturn(-1,'请输入正确的gametypeid',array());
                break;
        }
    }

    public function argame(){
        $partner_id = input('partner_id');
        $is_complete = input('is_complete');
        //获取游戏类型
        $gameList = GameType::TYPE;
        $this->assign('gameList',$gameList);
        //获取所有激活的合作商
        $partnerModel = new Partner();
        $where = array();
        $where[] = array('status','=',1);
        $partnerList = $partnerModel->where($where)->select();
        $this->assign('partnerList',$partnerList);

        //获取用户及其游戏信息
        $userModel = new User();
        $query = array();
        $limit = input('limit') == null || empty(input('limit')) ? getLimit() : input('limit');
        $where = array();
        if(!(empty($partner_id) || $partner_id == null)){
            $where[] = array('cn_user.partner_id','=',$partner_id);
            $this->assign('partner_id',$partner_id);
            $query['partner_id'] = $partner_id;
        }
        if(!(empty($is_complete) || $is_complete == null) || $is_complete === '0'){
            $where[] = array('cn_user_game_ar_data.is_complete','=',$is_complete);
            $this->assign('is_complete',$is_complete);
            $query['is_complete'] = $is_complete;
        }
        $list = $userModel->field('cn_user.*,cn_partner.*,cn_user_game_ar_data.*,cn_user.id user_id,cn_project.*,cn_project.name project_name,cn_partner.name partner_name')
            ->join('cn_user_game_ar_data','cn_user.id=cn_user_game_ar_data.user_id')
            ->join('cn_project','cn_project.id=cn_user_game_ar_data.project_id')
            ->leftJoin('cn_partner','cn_user.partner_id=cn_partner.id')
            ->where($where)->paginate($limit,false,array('query'=>$query));
        //获取部件信息
        $userGameModel = new UserGameArData();
        foreach ($list as &$value){
            $tempData = $userGameModel->getMateria($value['game_id']);
            $partStr = '';
            for($i=1;$i <= $tempData['gameMaterialData']['material_num'];$i++){
                $partStr .= '<p>部件'.$i.':'.$value['part'.$i.'_num'].'个</p>';
            }

            $value['partStr'] = $partStr;
            switch ($value['is_complete']){
                case 0:
                    $value['is_complete_str'] = '未集齐';
                    break;
                case 1:
                    $value['is_complete_str'] = '已集齐';
                    break;
                case 2:
                    $value['is_complete_str'] = '已合成';
                    break;
                case 3:
                    $value['is_complete_str'] = '已领奖';
                    break;
            }
            $value['project_name'] = $value['project_name'];
        }
        $this->assign('list', $list);
        $this->assign('left_menu_active', 'admin_users_game');
        return $this->fetch('argame');
    }

    /*
     * 中奖信息
     */
    public function lucky(){
        $partner_id = input('partner_id');
        $is_complete = input('is_complete');
        //获取游戏类型
        $gameList = GameType::TYPE;
        $this->assign('gameList',$gameList);
        //获取所有激活的合作商
        $partnerModel = new Partner();
        $where = array();
        $where[] = array('status','=',1);
        $partnerList = $partnerModel->where($where)->select();
        $this->assign('partnerList',$partnerList);
        
        $this->assign('left_menu_active', 'admin_users_lucky');
        return $this->fetch();
    }

}
