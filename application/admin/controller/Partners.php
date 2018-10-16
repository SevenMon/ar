<?php

namespace app\admin\controller;




use app\model\Partner;

class Partners extends Base
{

    public $left_menu_active = 'admin_partners_index';
    public $top_menu_active = 'partners';

    public function initialize()
    {
        parent::initialize();
    }

    public function index(){
        //获取数据  分页
        $partnerModel = new Partner();
        $limit = input('limit') == null || empty(input('limit')) ? getLimit() : input('limit');
        $where[] = array('status','>',0);
        $list = $partnerModel->where($where)->order('sort desc,id desc')->paginate($limit);

        $this->assign('list', $list);
        return $this->fetch();
    }

    public function addPage(){
        return $this->fetch();
    }

    public function editPage(){
        $partnerId = input('partner_id');
        $partnerModel = new Partner();
        $partnerData = $partnerModel->find($partnerId);
        if(empty($partnerData)){
            $this->error('合作商不存在！');
        }elseif($partnerData['status'] == 0){
            $this->error('合作商已删除！');
        }
        $this->assign('partnerData',$partnerData);
        return $this->fetch();
    }

    public function add(){
        $name = input('post.title');
        $phone = input('post.phone');
        $appid = input('post.appid');
        $appsecret = input('post.appsecret');
        $remark = input('post.remark');
        //$status = input('post.status');
        if(empty($name) || empty($remark) || empty($phone) || empty($appid) || empty($appsecret)){
            $this->error('名称、电话、appid、appsecret、备注不可为空！');
        }
        /*if($status == 'on'){
            $status = 1;
        }else{
            $status = 2;
        }*/
        $partnerModel = new Partner();

        //获取最大sort
        $maxSort = $partnerModel->field('max(sort) maxSort')->find();
        $maxSort = $maxSort['maxSort'];
        //添加标签
        $data = array(
            'name' => $name,
            'phone' => $phone,
            'appid' => $appid,
            'appsecret' => $appsecret,
            'sort' => ++$maxSort,
            'remark' => $remark,
            'status' => 1,
        );
        $id = $partnerModel->insertGetId($data);
        if($id >= 1){
            $this->redirect('admin/Partners/index');
        }else{
            $this->error('添加失败！');
        }
    }

    public function edit(){

        $partnerId = input('partner_id');
        $name = input('post.title');
        $phone = input('post.phone');
        $appid = input('post.appid');
        $appsecret = input('post.appsecret');
        $remark = input('post.remark');

        $partnerModel = new Partner();
        $data = $partnerModel->find($partnerId);
        if(empty($data)){
            $this->error('合作商不存在！');
        }elseif($data['status'] == 0){
            $this->error('合作商已删除！');
        }
        $status = input('post.status');
        if(empty($name) || empty($remark) || empty($phone) || empty($appid) || empty($appsecret)){
            $this->error('名称、电话、appid、appsecret、备注不可为空！');
        }
        if($status == 'on'){
            $status = 1;
        }else{
            $status = 2;
        }

        //添加标签
        $updataData = array(
            'name' => $name,
            'phone' => $phone,
            'appid' => $appid,
            'appsecret' => $appsecret,
            'remark' => $remark,
            'status' => $status,
        );
        $id = $partnerModel->where('id','=',$partnerId)->update($updataData);
        if($id >= 0){
            $this->redirect('admin/Partners/index');
        }else{
            $this->error('修改失败！');
        }
    }

    public function delete(){
        $partnerId = input('partner_id');
        $partnerModel = new Partner();
        $data = $partnerModel->find($partnerId);
        if(empty($data)){
            $this->error('合作商不存在！');
        }elseif($data['status'] == 0){
            $this->error('合作商已删除！');
        }
        $updateData['status'] = 0;
        $info = $partnerModel->where('id','=',$partnerId)->update($updateData);
        if(empty($info)){
            $this->error('删除失败！');
        }else{
            $this->redirect('admin/Partners/index');
        }
    }
}