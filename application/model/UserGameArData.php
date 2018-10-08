<?php

namespace app\model;

use think\Model;

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
            ajaxJsonReturn(-1,'该合作商还没有开启项目，不能进入游戏',array());
        }

        //获取游戏获取部件的情况
        $where = array();
        $where[] = array('user_id','=',$this->userId);
        $where[] = array('project','=',$projectData['id']);
        $userGameModel = Db::table('cn_user_game_ar_data');
        $userGameData = $userGameModel->where($where)->find();
        if(empty($userGameData)){
            $userGameData = array();
        }
        return $userGameData;
    }

}
