<?php

namespace app\model;

use think\Model;
use think\Db;

class User extends Model {

   //修改数据
    public function saveData($id, $data) {
        $info = $this->save($data, ['id' => $id]);
        if ($info === false) {
            return false;
        } else {
            return true;
        }
    }

    //获取用户信息
    public function getDetail($id){
        return $this->leftJoin('cn_grade','cn_user.grade_id = cn_grade.id')->where('cn_user.id','=',$id)->find();
    }

    //获取剩余次数
    public function getPlayTime($user_id,$project_id){
        $playDataModel = Db::table('cn_play_game_ar_data');
        $where = array();
        $where[] = array('user_id','=',$user_id);
        $where[] = array('project','=',$project_id);
        $where[] = array('create_time','>',date('Y-m-d 00:00:00',time()));
        $where[] = array('create_time','<',date('Y-m-d 23:59:59',time()));
        $todayPlayNum = $playDataModel->where($where)->count();
        $playNumParamModel = Db::table('cn_game_ar_param');
        $where = array();
        $where[] = array('start_time','<=',date('Y-m-d',time()));
        $where[] = array('end_time','>=',date('Y-m-d',time()));
        $paramData = $playNumParamModel->where($where)->order('play_number desc')->find();
        if(empty($paramData) || $paramData == null){
            $allPlayNum = 0;
        }else{
            //加上分享的次数
            $showTimeModel = Db::table('cn_show_time');
            $where = array();
            $where[] = array('user_id','=',$user_id);
            $where[] = array('project_id','=',$project_id);
            $where[] = array('create_time','>',date('Y-m-d 00:00:00',time()));
            $where[] = array('create_time','<',date('Y-m-d 23:59:59',time()));
            $count = $showTimeModel->where($where)->count();
            $allPlayNum = $paramData['play_number'] + $count;
        }
        return array(
            'todayPlayNum' => empty($todayPlayNum)?0:$todayPlayNum,
            'allPlayNum' => empty($allPlayNum)?0:$allPlayNum,
        );
    }
}
