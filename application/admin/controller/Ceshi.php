<?php

namespace app\admin\controller;

use app\model\Brand;
use app\model\UserGameArData;
use think\Db;

class Ceshi extends Base
{
    public $left_menu_active = 'admin_ceshi_index';
    public $top_menu_active = 'ceshi';
    public function index(){

    }

    public function resetpage(){
        $ceshi = Db::table('cn_ceshi');
        $ceshiData = $ceshi->where('id','<=',2)->select();
        $this->assign('ceshiData',$ceshiData);
        $this->assign('left_menu_active', 'admin_ceshi_resetpage');
        return $this->fetch();
    }

    public function reset(){
        $user_id = input('user_id');
        $user_id = str_replace('，',',',$user_id);
        $userGameArModel = new UserGameArData();
        $update = array(
            'is_complete' => 0,
            'part1_num' => 0,
            'part2_num' => 0,
            'part3_num' => 0,
            'part4_num' => 0,
            'part5_num' => 0
        );
        $info = $userGameArModel->where('id','in',explode(',',$user_id))->update($update);
        if($info === false){
            ajaxJsonReturn(-1,'重置失败');
        }else{
            $ceshi = Db::table('cn_ceshi');
            $ceshi->where('id','=',1)->update(array('id' => 1,'value' => $user_id));
            ajaxJsonReturn(1,'重置成功');
        }
    }

    public function resetGame(){
        $user_id = input('user_id');
        $user_id = str_replace('，',',',$user_id);
        $userGameArModel = new UserGameArData();
        $data = $userGameArModel->where('id','in',explode(',',$user_id))->select();
        foreach ($data as $value){
            $playGameDataModel = Db::table('cn_play_game_ar_data');
            $where = array();
            $where[] = array('user_id','=',$value['user_id']);
            $where[] = array('project','=',$value['project_id']);
            $where[] = array('create_time','>',date('Y-m-d 00:00:00'));
            $where[] = array('create_time','<',date('Y-m-d 23:59:59'));
            $where[] = array('project','=',$value['project_id']);
            $playGameDataModel->where($where)->delete();
        }
        $ceshi = Db::table('cn_ceshi');
        $ceshi->where('id','=',2)->update(array('id' => 2,'value' => $user_id));
        ajaxJsonReturn(1,'重置成功');

    }
}