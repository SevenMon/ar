<?php

namespace app\admin\controller;




use app\model\Game;
use app\model\GameType;
use think\Db;

class Gamear extends Base
{

    public $left_menu_active = 'admin_gamear_index';
    public $top_menu_active = 'gamear';

    public function initialize()
    {
        parent::initialize();
    }
    public function index(){
        $list = Db::table('cn_game_ar_param')->order('id asc')->select();
        $ruleList = Db::table('cn_game_ar_rule')->order('id asc')->select();
        $this->assign('list',$list);
        $this->assign('ruleList',$ruleList);
        return $this->fetch();
    }
    public function ceshi(){
        return $this->fetch();
    }

    public function ajaxUpdateParam(){
        $data = input('data');
        Db::startTrans();
        $sql = 'delete from cn_game_ar_param';
        $info = Db::execute($sql);
        if($info === false){
            Db::rollback();
            ajaxJsonReturn(-1,'保存失败');
        }
        if(empty($data)){
            if($info !== false){
                Db::commit();
                ajaxJsonReturn(1,'保存成功');
            }
        }
        $temp = array();
        for($i = 0;$i < (count($data) / 3);$i++){
            $temp[] = array(
                'start_time' => $data[$i*3]['value'],
                'end_time' => $data[$i*3+1]['value'],
                'play_number' => $data[$i*3+2]['value'],
            );
        }
        $addInfo = Db::table('cn_game_ar_param')->insertAll($temp);
        if(empty($addInfo)){
            Db::rollback();
            ajaxJsonReturn(-1,'保存失败');
        }else{
            Db::commit();
            ajaxJsonReturn(1,'保存成功');
        }
    }

    public function ajaxUpdateRule(){
        $data = input('data');
        $temp = array();
        for($i = 0;$i < count($data);$i++){
            $temp[] = array(
                'content' => $data[$i]['value']
            );
        }
        Db::startTrans();
        $sql = 'delete from cn_game_ar_rule';
        $info = Db::execute($sql);
        $addInfo = Db::table('cn_game_ar_rule')->insertAll($temp);
        if(empty($addInfo)){
            Db::rollback();
            ajaxJsonReturn(-1,'保存失败');
        }else{
            Db::commit();
            ajaxJsonReturn(1,'保寸成功');
        }
    }

}