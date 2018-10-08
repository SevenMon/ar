<?php

namespace app\login\controller;

use think\Request;
use app\login\model\Admin;

class Index extends Base {

    /**
     *
     * 登陆页面
     * @return mixed
     *
     * @author zhaoming
     * @mail 468143282@qq.com
     */
    public function index() {
        $msg = input('msg');
        $this->assign('erromsg',$msg);
        return $this->fetch();
    }

    /**
     * 注销登陆
     * @author zhengjingqiang
     * @email scenewood@163.com
     */
    function logout() {
        
        session(null);
        session_unset();
        $this->redirect('/');
        //$this->success('注销登录成功', url('/'), 3);
    }

    /**
     *
     * 验证登录
     * @param Request $request
     *
     * @author zhaoming
     * @mail 468143282@qq.com
     */
    public function checkLogin() {
        $m = new Admin();
        $info = $m->checkLogin();
        if($info['status'] == 1){
            $this->redirect('admin/Index/index');
        }else{
            $this->redirect('login/Index/index?msg='.$info['msg']);
        }
    }

    /**
     *
     * 获取验证码
     *
     * @author zhaoming
     * @mail 468143282@qq.com
     */
    public function getVerify() {
        WSTVerify();
    }

    public function json(Request $request, $name) {
        dump($name);
        dump($request->isGet());
        // 输出JSON
        //return ['status'=>1];
    }

    public function read() {
        $rs = db('test')->where('id', 2)->find();
        $res = db('test')->where('id', 2)->value('test');
        dump($rs);
        dump($res);
        // 渲染默认模板输出
        //return view();
    }

}
