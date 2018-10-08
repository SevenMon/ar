<?php

namespace app\model;

use think\Model;
use think\Db;

class UserGameArData extends Model {

    //获取用户游戏信息
    public function getUserData($userInfo){
        //获取项目
        $projectModel = new Project();
        $where = array();
        $where[] = array('partner_id' ,'=' ,$userInfo['partner_id']);
        $where[] = array('status','=',1);
        $projectData = $projectModel->where($where)->find();
        if(empty($projectData) || $projectData == null || $projectData['status'] != 1){
            return array();
        }

        //获取游戏获取部件的情况
        $where = array();
        $where[] = array('user_id','=',$this->userId);
        $where[] = array('project_id','=',$projectData['id']);
        $userGameModel = Db::table('cn_user_game_ar_data');
        $userGameData = $userGameModel->where($where)->find();
        if(empty($userGameData)){
            $userGameData = array();
        }
        return $userGameData;
    }

    //获取用户游戏信息（包括游戏素材）
    public function getMateria($game_id){
        //获取游戏
        $gameModel = new Game();
        $gameData = $gameModel->find($game_id);

        //获取游戏素材
        $gameMaterialModel = Db::table('cn_game_1_material');
        $gameMaterialData = $gameMaterialModel->find($gameData['material_id']);
        return array(
            'gameMaterialData' => $gameMaterialData,
            'gameData' => $gameData
        );
    }

}
