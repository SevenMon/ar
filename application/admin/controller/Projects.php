<?php

namespace app\admin\controller;




use app\model\Brand;
use app\model\Game;
use app\model\Project;
use app\model\Property;
use app\model\Partner;
use think\Db;

class Projects extends Base
{

    public $left_menu_active = 'admin_projects_index';
    public $top_menu_active = 'projects';

    public function initialize()
    {
        parent::initialize();
    }
    public function index(){
        //获取数据  分页
        $projectModel = new Project();
        $limit = input('limit') == null || empty(input('limit')) ? getLimit() : input('limit');
        $where[] = array('cn_project.status','>',0);
        $list = $projectModel
            ->field('cn_project.id id,cn_project.name name,cn_project.status status,cn_game.title gameName,cn_game.title gameName,cn_brand_wares.name waresName,cn_partner.name partnerName,cn_partner.id partner_id')
            ->leftJoin('cn_game','cn_project.game_id=cn_game.id')
            ->leftJoin('cn_partner','cn_project.partner_id=cn_partner.id')
            ->leftJoin('cn_brand_wares','cn_project.wares_id=cn_brand_wares.id')
            ->where($where)
            ->order('cn_project.id desc')
            ->paginate($limit);
        $this->assign('list', $list);
        $this->setListPageUrl();
        return $this->fetch();
    }

    public function addPage(){

        //合作商
        $partnerModel = new Partner();
        $where = array();
        $where[] = array('status','=',1);
        $partnerList = $partnerModel->where($where)->order('sort desc')->select();
        //游戏列表
        $gameModel = new Game();
        $where = array();
        $where[] = array('status','=',1);
        $gameList = $gameModel->where($where)->order('sort desc')->select();
        //品牌商
        $brandModel = new Brand();
        $where = array();
        $where[] = array('status','=',1);
        $brandList = $brandModel->where($where)->order('sort desc')->select();
        $waresslist = array();
        if(!empty($brandList->toArray())){
            //商品
            $brandWaresModel = Db::table('cn_brand_wares');
            $where = array();
            $where[] = array('status','=',1);
            $where[] = array('brand_id','=',$brandList[0]['id']);
            $waresslist = $brandWaresModel->where($where)->order('sort desc')->select();
        }
        $this->assign('partnerList',$partnerList);
        $this->assign('gameList',$gameList);
        $this->assign('brandList',$brandList);
        $this->assign('waresslist',$waresslist);
        return $this->fetch();
    }

    public function editPage(){
        $project_id = input('project_id');
        $projectModel = new Project();
        $projectData = $projectModel->find($project_id);
        if(empty($projectData)){
            $this->error('项目不存在！');
        }elseif($projectData['status'] == 0){
            $this->error('项目已删除！');
        }
        //合作商
        $partnerModel = new Partner();
        $where = array();
        $where[] = array('status','=',1);
        $partnerList = $partnerModel->where($where)->order('sort desc')->select();
        //游戏列表
        $gameModel = new Game();
        $where = array();
        $where[] = array('status','=',1);
        $gameList = $gameModel->where($where)->order('sort desc')->select();
        //品牌商
        $brandModel = new Brand();
        $where = array();
        $where[] = array('status','=',1);
        $brandList = $brandModel->where($where)->order('sort desc')->select();

        //商品
        $brandWaresModel = Db::table('cn_brand_wares');
        $where = array();
        $where[] = array('status','=',1);
        $where[] = array('id','=',$projectData['wares_id']);
        $waresData = $brandWaresModel->where($where)->find();
        $where = array();
        $where[] = array('status','=',1);
        $where[] = array('brand_id','=',$waresData['brand_id']);
        $waresslist = $brandWaresModel->where($where)->order('sort desc')->select();

        $this->assign('projectData',$projectData);
        $this->assign('partnerList',$partnerList);
        $this->assign('gameList',$gameList);
        $this->assign('brandList',$brandList);
        $this->assign('waresslist',$waresslist);
        $this->assign('waresData',$waresData);
        return $this->fetch();
    }

    public function add(){
        $name = input('post.title');
        $content = input('post.content');
        $gameId = input('post.gameId');
        $partnerId = input('post.partnerId');
        $brandId = input('post.brandId');
        $waresId = input('post.waresId');

        if(empty($name) || empty($content) || empty($gameId) || empty($partnerId) || empty($brandId) || empty($waresId)){
            $this->error('参数不全！');
        }

        $projectModel = new Project();
        $where = array();
        $where[] = array('partner_id','=',$partnerId);
        $where[] = array('status','=',1);
        $count = $projectModel->where($where)->count();
        if($count > 0 ){
            $status = 2;
        }else{
            $status = 1;
        }
        $data = array(
            'name' => $name,
            'content' => $content,
            'partner_id' => $partnerId,
            'game_id' => $gameId,
            'wares_id' => $waresId,
            'status' => $status
        );
        $info = $projectModel->insert($data);
        if(!empty($info)){
            $this->redirect('admin/Projects/index');
        }else{
            $this->error('添加失败！');
        }
    }

    public function edit(){
        $project_id = input('project_id');
        $projectModel = new Project();
        $projectData = $projectModel->find($project_id);
        if(empty($projectData)){
            $this->error('项目不存在！');
        }elseif($projectData['status'] == 0){
            $this->error('项目已删除！');
        }
        $name = input('post.title');
        $content = input('post.content');
        $brandId = input('post.brandId');
        $waresId = input('post.waresId');

        if(empty($name) || empty($content) || empty($brandId) || empty($waresId)){
            $this->error('参数不全！');
        }
        $data = array(
            'name' => $name,
            'content' => $content,
            'wares_id' => $waresId,
        );
        $info = $projectModel->where('id','=',$project_id)->update($data);
        if(!empty($info)){
            $this->redirect('admin/Projects/index');
        }else{
            $this->error('修改失败！');
        }

    }

    public function delete(){
        $projectId = input('project_id');
        $projectModel = new Project();
        $projectData = $projectModel->find($projectId);
        if(empty($projectData)){
            $this->error('项目不存在！');
        }elseif($projectData['status'] == 0){
            $this->error('项目已删除！');
        }
        $updateData['status'] = 0;
        $info = $projectModel->where('id','=',$projectId)->update($updateData);
        if(empty($info)){
            $this->error('删除失败！');
        }else{
            $this->redirect(session('listurl'));
        }
    }

    public function updateStatus(){
        $projectId = input('project_id');
        $partnerId = input('partner_id');
        $projectModel = new Project();
        $projectData = $projectModel->find($projectId);
        if(empty($projectData)){
            $this->error('项目不存在！');
        }elseif($projectData['status'] == 0){
            $this->error('项目已删除！');
        }elseif($projectData['status'] == 1){
            exit();
            //$this->error('项目已处于启动中！');
        }
        Db::startTrans();
        $updateData['status'] = 2;
        $where = array();
        $where[] = array('status','=',1);
        $where[] = array('partner_id','=',$partnerId);
        $info = $projectModel->where($where)->update($updateData);
        if(empty($info)){
            Db::rollback();
            $this->error('设置失败！');
        }
        $updateData['status'] = 1;
        $info = $projectModel->where('id','=',$projectId)->update($updateData);
        if(empty($info)){
            Db::rollback();
            $this->error('设置失败！');
        }else{
            Db::commit();
            $this->redirect(session('listurl'));
        }
    }
}