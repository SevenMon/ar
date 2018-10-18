<?php
namespace app\admin\controller;
use app\model\VisitData;
use think\Request;
use app\admin\model\Admin;

class Index  extends Base
{
    public $left_menu_active = 'admin_index_index';
    public $top_menu_active = 'index';

    public function initialize() {

        parent::initialize();
    }
    /**
     *
     *首页模板
     */
    public function index()
    {
        //总访问量  总访问人数
        $visitModel = new VisitData();
        $allData = $visitModel->field('count(DISTINCT(user_id)) user_time,count(user_id) time')->where('type','=',2)->find();

        //页面访问数
        $pageData = $visitModel->field('address_url,address_name,count(id) time')->group('address_url')->order('time desc')->select();

        //近三十天的访问量 和 人数
        $date = date('Y-m-d 00:00:00', strtotime('-30 days'));
        $data = $visitModel->field('id,user_id,left(create_time,10) time')
            ->where('type','=',2)
            ->where('create_time','>',$date)
            ->order('create_time desc')
            ->select();

        $sevenData = array();
        $thirtyData = array();
        foreach ($data as $value){
            if(strtotime(date('Y-m-d', strtotime('-7 days'))) <= strtotime($value['time'])){
                $sevenData[$value['time']][] =' ';
                $sevenData[$value['time']]['user'][$value['user_id']] =' ';
            }
            $thirtyData[$value['time']][] =' ';
            $thirtyData[$value['time']]['user'][$value['user_id']] =' ';
        }
        $thirtyResult = array();
        $sevenResult = array();
        for($i = 29;$i >= 0;$i--){
            $date = date('Y-m-d', strtotime('-'.$i.' days'));
            if(isset($thirtyData[$date])){
                $tempdata = array(
                    '日期' => $date,
                    '访问量' => count($thirtyData[$date]) - 1,
                    '访问人数' => count($thirtyData[$date]['user'])
                );
            }else{
                $tempdata = array(
                    '日期' => $date,
                    '访问量' => 0,
                    '访问人数' => 0
                );
            }
            $thirtyResult[] = $tempdata;
            if($i < 7){
                $sevenResult[] = $tempdata;
            }
        }
        $this->assign('allData',$allData);
        $this->assign('pageData',$pageData);
        $this->assign('thirtyResult',json_encode($thirtyResult));
        $this->assign('sevenResult',json_encode($sevenResult));
        $this->assign('left_menu_active', 'admin_index_index');
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
