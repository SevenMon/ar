<?php

// 命名空间

namespace app\api\controller;

use app\model\Show;
use app\model\UserGameArData;
use think\Db;

class Planning{
    //恢复分享给其他人的部件
    public function resetShowPart(){
        $showModel = new Show();
        $where = array();
        $where[] = array('status','=',0);
        $where[] = array('type','=',1);
        $where[] = array('create_time','<',date('Y-m-d H-i-s',time()-60*60*24*3));
        $list = $showModel->where($where)->limit(10)->select();
        $userGameArDataModel = new UserGameArData();
        foreach ($list as $value){
            $where = array();
            $where[] = array('project_id','=',$value['project_id']);
            $where[] = array('user_id','=',$value['start_user_id']);
            Db::startTrans();
            $userGameArDataModel->where($where)->setInc('part'.$value['part_num'].'_num');
            $showModel->where($where)->update(array('status' => 1));
        }
    }
}