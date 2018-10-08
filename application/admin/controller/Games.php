<?php

namespace app\admin\controller;




use app\model\Game;
use app\model\GameType;
use think\Db;

class Games extends Base
{

    public $left_menu_active = 'admin_games_index';
    public $top_menu_active = 'games';

    public function initialize()
    {
        parent::initialize();
    }
    public function index(){
        //获取数据  分页
        $gameModel = new Game();
        $limit = input('limit') == null || empty(input('limit')) ? getLimit() : input('limit');
        $where[] = array('status','>',0);
        $list = $gameModel->where($where)->order('sort desc')->paginate($limit);
        foreach ($list as &$value){
            $materialModel = Db::table('cn_game_'.$value['type'].'_material');
            $materialData = $materialModel->find($value['material_id']);
            $value['materialStatus'] = $materialData['status'];
        }
        $this->assign('list', $list);
        return $this->fetch();
    }

    public function addPage(){
        return $this->fetch();
    }

    public function editPage(){
        $gamesId = input('games_id');
        $gameModel = new Game();
        $gameData = $gameModel->find($gamesId);
        if(empty($gameData)){
            $this->error('游戏不存在！');
        }elseif($gameData['status'] == 0){
            $this->error('游戏已删除！');
        }
        $this->assign('gameData',$gameData);
        return $this->fetch();
    }

    public function add(){
        $name = input('post.title');
        $gameType = input('post.gameType');

        if(empty($name)){
            $this->error('名称不可为空！');
        }
        /*if($status == 'on'){
            $status = 1;
        }else{
            $status = 2;
        }*/
        $gameModel = new Game();

        //获取最大sort
        $maxSort = $gameModel->field('max(sort) maxSort')->find();
        $maxSort = $maxSort['maxSort'];

        $gameMaterialModel = Db::table('cn_game_'.$gameType.'_material');
        //添加素材
        $materialsData = array(
            'status' => 0
        );
        $materialsId = $gameMaterialModel->insertGetId($materialsData);

        //添加商品
        $data = array(
            'title' => $name,
            'type' => $gameType,
            'sort' => ++$maxSort,
            'status' => 2,
            'material_id' => $materialsId
        );
        $id = $gameModel->insertGetId($data);



        if($id >= 1){
            $this->redirect('admin/Games/index');
        }else{
            $this->error('添加失败！');
        }
    }

    public function edit(){
        $gamesId = input('games_id');
        $name = input('post.title');

        $gameModel = new Game();
        $gameData = $gameModel->find($gamesId);
        if(empty($gameData)){
            $this->error('游戏不存在！');
        }elseif($gameData['status'] == 0){
            $this->error('游戏已删除！');
        }
        $status = input('post.status');
        if($status == 'on'){
            $status = 1;
        }else{
            $status = 2;
        }
        $updataData = array(
            'title' => $name,
            'status' => $status,
        );
        $id = $gameModel->where('id','=',$gamesId)->update($updataData);
        if($id >= 0){
            $this->redirect('admin/Games/index');
        }else{
            $this->error('修改失败！');
        }
    }

    public function delete(){
        $gamesId = input('games_id');
        $gameModel = new Game();
        $gameData = $gameModel->find($gamesId);
        if(empty($gameData)){
            $this->error('游戏不存在！');
        }elseif($gameData['status'] == 0){
            $this->error('游戏已删除！');
        }
        $updateData['status'] = 0;
        $info = $gameData->where('id','=',$gamesId)->update($updateData);
        if(empty($info)){
            $this->error('删除失败！');
        }else{
            $this->redirect('admin/Games/index');
        }
    }
}