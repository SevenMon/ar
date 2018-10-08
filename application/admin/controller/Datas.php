<?php

namespace app\admin\controller;


use think\Request;
use think\Db;

use app\model\Card;
use app\model\AppConfig;
use app\model\UserScene;
use app\model\TicketApiConf;



class Datas extends Base {

    public $left_menu_active = 'admin_datas_index';
    public $top_menu_active = 'datas';

    public function initialize() {
        parent::initialize();
    }

    public function index(){
        return $this->fetch();
    }

    public function project(){
        $this->assign('left_menu_active', 'admin_datas_project');
        return $this->fetch();
    }
    public function game(){
        $this->assign('left_menu_active', 'admin_datas_game');
        return $this->fetch();
    }
    public function wares(){
        $this->assign('left_menu_active', 'admin_datas_wares');
        return $this->fetch();
    }
    public function partner(){
        $this->assign('left_menu_active', 'admin_datas_partner');
        return $this->fetch();
    }
    /*public function ceshi(){
        $this->assign('left_menu_active', 'admin_datas_partner');
        return $this->fetch('wares');
    }*/
}
