<?php

namespace app\admin\controller;

use think\Controller;
use think\Session;

class Base extends Controller {

    public $aid = '';
    public $user = '';
    public $top_menu_active = 'datas';
    public $left_menu_active;

    public function initialize() {
        //dump($this->top_menu_active);
        $user = session('user');
        if (empty($user)) {
            $data = array(
                'status' => -1,
                'code' => -1,
                'msg' => '请重新登录',
                'data' => array(),
            );

            $this->redirect('Login/Index/index');
        }
        $this->aid = $user['aid'];
        $this->user = $user;
        $this->assign('top_menu_active', $this->top_menu_active);
        $this->assign('left_menu_active', $this->left_menu_active);
    }

    public function setListPageUrl(){
        session('listurl',$_SERVER['REQUEST_URI']);
    }

}
