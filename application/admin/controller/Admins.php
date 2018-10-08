<?php

namespace app\admin\controller;

use app\model\Admin;
use think\Request;

class Admins extends Base {

    public $left_menu_active = 'admin_admins_index';
    public $top_menu_active = 'system';

    public function initialize() {
        parent::initialize();
    }

    /*
     *  列表
     */

    public function index() {
        $Model = new Admin();
        $limit = input('limit') == null || empty(input('limit')) ? getLimit() : input('limit');
        $where[] = array('status','>',0);
        $list = $Model->where($where)->order('aid desc')->paginate($limit);
        // 模板变量赋值
        $this->assign('list', $list);
        return $this->fetch('index');
    }

    /*
     * 添加
     */

    public function add() {
        $Model = new Admin();
        if ($this->request->isPost()) {
            $postArr = input();
            $username = $postArr['username'];
            $password = $postArr['password'];
            $data = [];
            $data['username'] = $username;
            $data['password'] = password($password, $username);
            $info = $Model->addData($data);
            if ($info > 0) {
                $this->redirect('admin/Admins/index');
            } else {
                $this->error('新增管理员失败！');
            }
        } else {
            return $this->fetch();
        }
    }

    /*
     * 修改密码页面
     */
    public function updatePasswordPage() {
        $aid = input('aid');
        $adminModel = new Admin();
        $data = $adminModel->where('aid','=',$aid)->find();
        if(empty($data)){
            $this->error('该管理员账户不存在！');
        }
        $this->assign('aid',$aid);
        return $this->fetch('edit');
    }

    /*
     * 修改密码
     */
    public function updatePassword() {
        $aid = input('aid');
        $oldpassword = input('oldpassword');
        $password = input('password');
        $adminModel = new Admin();
        $data = $adminModel->where('aid','=',$aid)->find();
        if(empty($data)){
            $this->error('该管理员账户不存在！');
        }
        if(password($oldpassword,$data['username']) != $data['password']){
            $this->error('旧密码不正确！');
        }

        $info = $adminModel->where('aid','=',$aid)->update(array('password'=>password($password,$data['username'])));
        if($info === false){
            $this->error('修改失败！');
        }else{
            $this->redirect('admin/Admins/index');
        }
    }

    /*
     * 修改状态
     */
    public function updateStatus(){
        $aid = input('aid');
        $adminModel = new Admin();
        $data = $adminModel->where('aid','=',$aid)->find();
        if(empty($data)){
            $this->error('该管理员账户不存在！');
        }
        $status = '';
        if($data['status'] == 1){
            $status = 2;
        }else{
            $status = 1;
        }

        $adminModel->where('aid','=',$aid)->update(array('status' => $status));
        ajaxJsonReturn(1,'修改成功',array());
    }

    /*
     *  重置密码
     */
    public function resetPassword(){
        $aid = input('aid');
        $adminModel = new Admin();
        $data = $adminModel->where('aid','=',$aid)->find();
        if(empty($data)){
            $this->error('该管理员账户不存在！');
        }
        $adminModel->where('aid','=',$aid)->update(array('password' => password('888888',$data['username'])));
        ajaxJsonReturn(1,'修改成功',array());
    }
}
