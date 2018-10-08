<?php
namespace app\admin\behavior;

/**
 * Class ListenLoginStatus 监听用户是否登录
 * @package app\index\behavior
 */
class ListenLoginStatus
{
    public static function run($params){
        $user = session('user');
        $allowUrl = [
            'login/index/checklogin',
            'login/index/logout',
        ];
        $request = request();
        $visit = strtolower($request->module()."/".$request->controller()."/".$request->action());
        if(empty($user) && !in_array($visit,$allowUrl)){
//            if($request->isAjax()){
//                
//            }else{
//                header("Location:".url('/login/index/index'));
//            }
            echo json_encode(['status'=>-999,'msg'=>'对不起，您还没有登录，请先登录']);
            exit();
        }
    }
}