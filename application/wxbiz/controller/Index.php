<?php

namespace app\wxbiz\controller;

use think\Request;
use think\Db;
class Index extends Base {

    public function initialize() {
        
    }

    /**
     *
     * 入口文件
     * @return mixed
     *
     * @author fdd
     */
    public function index() {
        $wxbizmsg = new \wxbiz\WxBizMsg();
        $wxbizmsg->getVerifyTicket();
        exit;        
    }
    /**
     *
     * 消息与事件接收URL
     * @return mixed
     *
     * @author fdd
     */
    public function Wxobject() {

        $encryptMsg = file_get_contents('php://input');
        //自动发布
        $wxbizmsg = new \wxbiz\WxBizMsg();
        $wxbizmsg->getVerifyTicket1(json_encode(request()->param()),$encryptMsg);
        ob_clean();
        echo "success";
        exit;
    }
    //获得授权url
    public function getUrl()
    {   
        $id = intval($_GET['id']);
        $wxbizmsg =  new \wxbiz\WxBizMsg();
        $str = $wxbizmsg->getRebackUrl($id);
        echo   json_encode(array('url'=>$str));
        exit;
    }
    //授权回调地址
    public function myback(){
        $id = $_GET['mid'];
        $auth_code = $_GET['auth_code'];
        $wxbizmsg = new \wxbiz\WxBizMsg();
        $flag = $wxbizmsg->setInfo($id,$auth_code);
        if($flag==0){
            $this->assign('flag',1);
        }else{
            $this->assign('flag',2);
        }
        return $this->fetch('index');
        exit;
        
    }
    /**
     * 发送模版消息
     * @param app_id
     */ 
    public function getWeixinImg()
    {   
        $id = intval($_GET['id']); 
        $wxbizmsg = new \wxbiz\WxBizMsg();
        $str = $wxbizmsg->getWeixinImg($id);
        echo   json_encode($str);
        exit;
    }
    //添加测试账号
    public function addmember(){

        $app_id = input('post.id');
        $member_num = input('post.member_num');
        $wxbizmsg = new \wxbiz\WxBizMsg();
        $back = $wxbizmsg->setMember($app_id,$member_num);
        echo   json_encode($back);
        exit;
    }
    //删除测试账号
    public function deletemember(){
        $app_id = input('post.id');        
        $wxbizmsg = new \wxbiz\WxBizMsg();
        $back = $wxbizmsg->deleteMember($app_id);
        echo   json_encode($back);
        exit;
    }
    //上传支付信息
    public function updatepay(){
        $app_id = input('post.id');
        $mchid = input('post.mchid');
        $key = input('post.key');  
        $info = Db::name('ticket_api_conf')->where(array('app_id'=>$app_id))->field('authorizer_appid')->find();
        if(empty($info)){
            echo   json_encode(array("status"=>2,"msg"=>"还未授权过，无法保存"));
            exit;
        }
        $update['appid'] = $info['authorizer_appid'];
        $update['appsecret'] = $info['authorizer_appid'];
        $update['mchid'] = $mchid;
        $update['key'] = $key;
        Db::name('app_config')->where(array('id'=>$app_id))->update($update);
        echo   json_encode(array("status"=>1,"msg"=>"成功"));
        exit;
    } 
    //上传小程序
    public function updatecode(){
       
        $app_id = input('post.id');

        $user_version = input('post.template_id');    
        $tinfo = Db::name('ticket_templates')->where(array('title_num'=>$user_version))->select();
        $list = [];
        foreach ($tinfo as $key => $value) {
            $list[$value['type']] = $value;
        }
        if(empty($tinfo)){
            echo(json_encode(['status'=>2,'msg'=>'不存在的版本']));
            exit;
        }  
        $info = Db::query("select t1.app_id,t2.* from
                    (select app_id, sub_template_id from cn_ticket_api_conf where app_id=$app_id) t1
                    left join cn_ticket_templates t2
                    on t1.sub_template_id = t2.template_id");   

        if(empty($info)){
            echo(json_encode(['status'=>2,'msg'=>'用户未授权']));
            exit;
        }  
        if(empty($info[0]['type'])){
            $template_id = $list[1]['template_id'];
            $user_desc = $list[1]['title'];
        }else{
            if(empty($list[$info[0]['type']])){
                $template_id = $list[1]['template_id'];
                $user_desc = $list[1]['title'];
            }else{
                $template_id = $list[$info[0]['type']]['template_id'];
                $user_desc = $list[$info[0]['type']]['title'];
            }

        }    
        $wxbizmsg = new \wxbiz\WxBizMsg();
        $back = $wxbizmsg->updateCode($app_id,$template_id,$user_version,$user_desc);
        echo   json_encode($back);
        exit;
    } 
    //提交小程序
    public function submitcode(){
        $app_id = input('post.id');
        $tags = input('post.tags');
        $tags = str_replace(' ',' ',$tags);
        $taglist = explode(' ',$tags);
        if(!empty($taglist)){
            if(count($taglist)>10){
                echo   json_encode(array("status"=>2,"msg"=>"标签不能大于10个"));
                exit;
            }
            foreach ($taglist as $key => $value) {
                $content = iconv("utf-8","gb2312//IGNORE",$value);
                if(strlen($content)>20){
                    echo   json_encode(array("status"=>2,"msg"=>"标签长度不超过20"));
                    exit;
                }
            }
        }
        $wxbizmsg = new \wxbiz\WxBizMsg();
        $back = $wxbizmsg->submitcode($app_id,$tags); 
        echo   json_encode($back);
        exit;      
    } 
    //查询审核状态
    public function findsubmitcode(){
        $app_id = input('post.id');
        $wxbizmsg = new \wxbiz\WxBizMsg();
        $back = $wxbizmsg->findsubmitcode($app_id); 
        echo   json_encode($back);
        exit;        
    }
    //查询最后一次审核状态
    public function findlastsubmitcode(){
        $app_id = input('post.id');
        $wxbizmsg = new \wxbiz\WxBizMsg();
        $back = $wxbizmsg->findlastsubmitcode($app_id); 
        echo   json_encode($back);
        exit;        
    } 
    //发布小程序
    public function releasecode(){
        $app_id = input('post.id');
        $wxbizmsg = new \wxbiz\WxBizMsg();
        $back = $wxbizmsg->releasecode($app_id); 
        echo   json_encode($back);
        exit;        
    } 
   public function undocodeaudit(){
        $app_id = input('post.id');
        $wxbizmsg = new \wxbiz\WxBizMsg();
        $authorizer_appid = Db::name('ticket_api_conf')->where(array('app_id'=>$app_id))->find();
        if(empty($authorizer_appid)){
            echo json_encode(['status'=>2,'msg'=>"用户未授权"]) ;
            exit;
        }  

        $token = $wxbizmsg->getAuthAccessToken($authorizer_appid['authorizer_appid']); 
        $url = "https://api.weixin.qq.com/wxa/undocodeaudit?access_token=".$token; 
        $back = $wxbizmsg->vget($url);
        $backdata = json_decode($back,true);
        if($backdata['errcode']==0){       
                Db::name('ticket_api_conf')->where(array('id'=>$app_id))->update(array('submit_status'=>0));
                echo json_encode(array('status'=>1,"msg"=>'撤回成功'));          
        }elseif($backdata['errcode']==-1){
            echo json_encode(array("status"=>2,"msg"=>"系统错误"));
        }elseif($backdata['errcode']==87013){
            echo json_encode(array("status"=>2,"msg"=>"撤回次数达到上限"));
        }else{
            echo json_encode(array("status"=>2,"msg"=>"系统错误"));
        }                 
        exit;           
    }
}
