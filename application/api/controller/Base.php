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
    public $projectInfo = '';
    public $gameInfo = '';
    public $brandInfo = '';
    public $brandWaresInfo = '';
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

        //获取项目
        $projectModel = new Project();
        $where = array();
        $where[] = array('partner_id' ,'=' ,$this->userInfo['partner_id']);
        $where[] = array('status','=',1);
        $projectData = $projectModel->where($where)->find();
        if(empty($projectData) || $projectData == null || $projectData['status'] != 1){
            ajaxJsonReturn(-1,'该合作商还没有开启项目，不能进入游戏',array());
        }
        $this->projectInfo = $projectData;

        //游戏
        $gameModel = new Game();
        $gameData = $gameModel->find($projectData['game_id']);
        if(empty($gameData) || $gameData == null || $gameData['status'] != 1){
            ajaxJsonReturn(-2,'该游戏不存在或还没有启动，不能进入游戏',array());
        }
        $this->gameInfo = $gameData;

        //获取品牌商
        $brandModel = new Brand();
        $brandWareModel = new BrandWares();
        $wareData = $brandWareModel->find($projectData['wares_id']);
        $brandData = $brandModel->find($wareData['brand_id']);
        if(empty($brandData) || $brandData == null || $brandData['status'] != 1){
            ajaxJsonReturn(-2,'该游戏奖品的品牌商不存在或还没有启动，不能进入游戏',array());
        }
        $this->brandWaresInfo = $brandWareModel;
        $this->brandInfo = $brandData;
        $this->userId = $user_id;
        $this->userInfo = $user_info;
        $this->partnerId = $partner_id;
        $this->partnerInfo = $partner_info;
    }
}
