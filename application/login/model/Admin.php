<?php

namespace app\login\model;

use think\Model;
use think\Db;

class Admin extends Model {

    public function checkLogin() {

        /*  $code = input('post.code');
          if(!WSTVerifyCheck($code)){
          return WSTReturn('验证码错误!');
          } */
        $user = session('user');  
        $username = input('post.username');
        $pass = input('post.password');
        $password = password($pass, $username);

        if(empty($user)){
            $rs = Db::name('admin')->where(['username' => $username, 'password' => $password])->find();
        }else{
            $rs = Db::name('admin')->where(['aid' => $user['aid']])->find();
        }
        if (!empty($rs)) {
            if($rs['status' ] != 1){
                return WSTReturn("此用户已被禁用", 2);
            }
            session('user', $rs);
            if ($rs['role_id'] == 1) {
                return WSTReturn("登陆成功", 1, ['username'=>$username,'flag'=>0]);
            }
            return WSTReturn("登陆成功", 1, ['username'=>$username,'flag'=>1]);
        }
        return WSTReturn("此用户不存在或密码错误", 3);
    }

}
