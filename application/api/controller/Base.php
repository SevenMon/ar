<?php
namespace app\api\controller;
use app\model\Partner;
use think\Controller;
use app\model\User;
use app\model\Project;
use app\model\Game;
use app\model\Brand;
use app\model\BrandWares;
use think\Request;

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
        $action = request()->action();
        if('getGameMaterials' != $action && 'getRule' == $action){
            $this->getUserInfo();
        }
        $this->getGameInfo();


    }
    public function getUserInfo(){
        //判断用户登录
        $UserModel = new User();
        $user_id = decode(input('user_id'));
        $user_info = $UserModel->where('id','=',$user_id)->find();
        if (empty($user_info)) {
            ajaxJsonReturn(-100, '用户不存在！');
        }
        if ($user_info['status'] == 2) {
            ajaxJsonReturn(-101, '您已经被加入黑名单，不能进行游戏！');
        }
        $this->userId = $user_id;
        $this->userInfo = $user_info;
    }
    public function getGameInfo(){
        $partnerModel = new Partner();
        $partner_id = input('partner_id');
        $partner_id = decode($partner_id);
        $partner_info = $partnerModel->where('id','=',$partner_id)->find();
        $this->partnerId = $partner_id;
        $this->partnerInfo = $partner_info;
        if (empty($partner_info)) {
            ajaxJsonReturn(-102, '合作商不存在！');
        }
        if ($partner_info['status'] != 1) {
            ajaxJsonReturn(-103, '该合作商以被禁用，不能进行游戏！');
        }
        //获取项目
        $projectModel = new Project();
        $where = array();
        $where[] = array('partner_id' ,'=' ,$this->userInfo['partner_id']);
        $where[] = array('status','=',1);
        $projectData = $projectModel->where($where)->find();
        if(empty($projectData) || $projectData == null || $projectData['status'] != 1){
            ajaxJsonReturn(-104,'该合作商还没有开启项目，不能进入游戏',array());
        }
        $this->projectInfo = $projectData;

        //游戏
        $gameModel = new Game();
        $gameData = $gameModel->find($projectData['game_id']);
        if(empty($gameData) || $gameData == null || $gameData['status'] != 1){
            ajaxJsonReturn(-105,'该游戏不存在或还没有启动，不能进入游戏',array());
        }
        $this->gameInfo = $gameData;

        //获取品牌商
        $brandModel = new Brand();
        $brandWareModel = new BrandWares();
        $wareData = $brandWareModel->find($projectData['wares_id']);

        $brandData = $brandModel->find($wareData['brand_id']);
        if(empty($brandData) || $brandData == null || $brandData['status'] != 1){
            ajaxJsonReturn(-106,'该游戏奖品的品牌商不存在或还没有启动，不能进入游戏',array());
        }
        $this->brandWaresInfo = $wareData;
        $this->brandInfo = $brandData;
    }
}
