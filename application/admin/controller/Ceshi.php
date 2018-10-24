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
        $ceshiData = $ceshi->find(1);
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
}