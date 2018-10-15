<?php

namespace app\admin\controller;

use app\model\Brand;


class Brands extends Base
{

    public $left_menu_active = 'admin_brands_index';
    public $top_menu_active = 'brands';

    public function initialize()
    {
        parent::initialize();
    }
    public function index(){

        //获取数据  分页
        $brandModel = new Brand();
        $limit = input('limit') == null || empty(input('limit')) ? getLimit() : input('limit');
        $where[] = array('status','>',0);
        $list = $brandModel->where($where)->order('sort desc')->paginate($limit);
        $this->assign('list', $list);
        return $this->fetch();
    }

    public function addPage(){
        return $this->fetch();
    }

    public function editPage(){
        $brandId = input('id');
        $brandModel = new Brand();
        $brandData = $brandModel->find($brandId);
        if(empty($brandData)){
            $this->error('品牌不存在！');
        }elseif($brandData['status'] == 0){
            $this->error('品牌已删除！');
        }
        $this->assign('brandData',$brandData);
        return $this->fetch();
    }

    public function add(){
        $name = input('post.title');
        /*$gameType = input('post.gameType');
        $time = input('post.time');
        $address = input('post.address');*/
        $appId = input('post.appId');
        $appSecret = input('post.appSecret');
        /*$img = input('post.upload');*/
        if(empty($name)||$name==null
            /*||empty($gameType)||$gameType==null||
            empty($time)||$time==null||
            empty($address)||$address==null||*/
            /*empty($appId)||$appId==null||
            empty($appSecret)||$appSecret==null*/
            /*||empty($img)||$img==null*/){
            $this->error('请补全参数！');
        }
        $brandModel = new Brand();
        //检查标签是否存在
        /*if($brandModel->checkName($name)){
            $this->error('品牌已存在！');
        }*/

        //获取最大sort
        $maxSort = $brandModel->field('max(sort) maxSort')->find();
        $maxSort = $maxSort['maxSort'];

        $data = array(
            'name' => $name,
            'sort' => ++$maxSort,
            /*'type' => $gameType,
            'time' => $time,
            'address' => $address,*/
            'app_id' => $appId,
            'app_secret' => $appSecret,
            /*'address_pic' => $img,*/
        );
        $id = $brandModel->addData($data);
        if($id >= 1){
            $this->redirect('admin/Brands/index');
        }else{
            $this->error('添加失败！');
        }
    }

    public function edit(){
        $brandId = input('id');
        $name = input('post.title');
        /*$gameType = input('post.gameType');
        $time = input('post.time');
        $address = input('post.address');*/
        $appId = input('post.appId');
        $appSecret = input('post.appSecret');
        /*$img = input('post.upload');*/
        if(empty($name)||$name==null
            /*||empty($gameType)||$gameType==null||
            empty($time)||$time==null||
            empty($address)||$address==null||*/
            /*empty($appId)||$appId==null||
            empty($appSecret)||$appSecret==null*/
            /*||empty($img)||$img==null*/){
            $this->error('请补全参数！');
        }
        $brandModel = new Brand();
        $data = $brandModel->find($brandId);
        if(empty($data)){
            $this->error('品牌不存在！');
        }elseif($data['status'] == 0){
            $this->error('品牌已删除！');
        }

        //检查标签是否存在
        /*if($data['name'] != $name){
            if($brandModel->checkName($name)){
                $this->error('品牌已存在！');
            }
        }*/

        $updataData = array(
            'name' => $name,
            /*'type' => $gameType,
            'time' => $time,
            'address' => $address,*/
            'app_id' => $appId,
            'app_secret' => $appSecret,
            /*'address_pic' => $img,*/
        );
        $status = input('status');
        if(empty($status) || $status == null){
            $updataData['status'] = 2;
        }else{
            $updataData['status'] = 1;
        }

        $id = $brandModel->saveData($brandId,$updataData);
        if($id >= 0){
            $this->redirect('admin/Brands/index');
            //$this->success('修改成功','admin/Brands/index');
        }else{
            $this->error('修改失败！');
        }
    }

    public function delete(){
        $brandId = input('id');
        $brandModel = new Brand();
        $data = $brandModel->find($brandId);
        if(empty($data)){
            $this->error('品牌不存在！');
        }elseif($data['status'] == 0){
            $this->error('品牌已删除！');
        }
        $updateData['status'] = 0;
        $info = $brandModel->saveData($brandId,$updateData);
        if(empty($info)){
            $this->error('删除失败！');
        }else{
            $this->redirect('admin/Brands/index');
            //$this->success('删除成功','admin/Brands/index');
        }
    }
}