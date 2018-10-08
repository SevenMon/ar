<?php
namespace app\admin\controller;
use think\Request;
use app\admin\model\Admin;

class Index  extends Base
{
    /**
     *
     *首页模板
     */
    public function index()
    {
        return $this->fetch();
    }
    
    /**
     * 注销登陆
     */
    function logout() {
        session(null);
        session_unset();
        $this->success('注销登录成功', url('index'), 3);
    }

    /**
     *
     * 验证登录
     */
    public function checkLogin() {
        $m = new Admin();
        //dump($m->checkLogin());exit;
        return $m->checkLogin();
    }
    /**
     *
     * 获取菜单
     */
    public function getMenu() {
        $menu = MenuAll();
        //echo json_encode($menu);exit;
        return json_encode($menu);
    }
    
    
    
}
