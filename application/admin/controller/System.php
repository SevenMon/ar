<?php

namespace app\admin\controller;




class System extends Base
{

    public $left_menu_active = 'admin_system_index';
    public $top_menu_active = 'system';

    public function initialize()
    {
        parent::initialize();
    }
    public function adminList(){
        $this->assign('left_menu_active', 'admin_system_adminList');
        return $this->fetch();
    }
    public function parameter(){
        $this->assign('left_menu_active', 'admin_system_parameter');
        return $this->fetch();
    }
}