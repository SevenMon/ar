<?php

namespace app\admin\controller;

use app\model\Brand;


class Ceshi extends Base
{
    public $left_menu_active = 'admin_ceshi_index';
    public $top_menu_active = 'ceshi';
    public function index(){

    }

    public function reset(){
        $ceshi = Db::table('cn_ceshi');
        $ceshiData = $ceshi->find(1);
        $this->assign('ceshiData',$ceshiData);
        $this->assign('left_menu_active', 'admin_ceshi_reset');
        return $this->fetch();
    }
}