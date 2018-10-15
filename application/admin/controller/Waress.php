<?php

namespace app\admin\controller;

use app\model\Brand;
use app\model\BrandWares;
use app\model\Car;
use app\model\Genre;
use app\model\Label;
use app\model\ProductLabel;
use app\model\Property;
use app\model\PropertyValue;
use app\model\Stock;
use app\model\StockProperty;
use app\model\Type;
use think\Db;
use think\Request;
use app\model\Product;


class Waress extends Base {

    public $left_menu_active = 'admin_brands_index';
    public $top_menu_active = 'brands';

	public function initialize() {
		parent::initialize();
	}

    public function index(){

        $brandId = input('brand_id');
        $this->assign('brandId',$brandId);
        //获取数据  分页
        $brandWaresModel = Db::table('cn_brand_wares');
        $limit = input('limit') == null || empty(input('limit')) ? getLimit() : input('limit');
        $where[] = array('status','>',0);
        $where[] = array('brand_id','=',$brandId);
        $list = $brandWaresModel->where($where)->order('sort desc')->paginate($limit);
        $this->assign('list', $list);
        return $this->fetch();
    }

    public function addPage(){
        $brandId = input('brand_id');
        $this->assign('brandId',$brandId);
        return $this->fetch();
    }

    public function editPage(){
        $brandId = input('brand_id');
        $waresId = input('wares_id');

        $brandWaresModel = new BrandWares();
        $waresData = $brandWaresModel->find($waresId);
        if(empty($waresData)){
            $this->error('商品不存在！');
        }elseif($waresData['status'] == 0){
            $this->error('商品已删除！');
        }
        $this->assign('brandId',$brandId);
        $this->assign('waresData',$waresData);
        return $this->fetch();
    }

    public function add(){
        $brandId = input('brand_id');
        $name = input('post.title');
        $type = input('post.type');
        $time = input('post.time');
        $address = input('post.address');
        $map_pic = input('post.map_pic');
        $introduce = input('post.message');
        $img = input('post.upload');
        if(empty($name) || empty($introduce)){
            $this->error('名称和介绍不可为空！');
        }
        $brandWaresModel = new BrandWares();

        //获取最大sort
        $maxSort = $brandWaresModel->field('max(sort) maxSort')->find();
        $maxSort = $maxSort['maxSort'];
        //添加标签
        $datas = array(
            'brand_id' => $brandId,
            'name' => $name,
            'sort' => ++$maxSort,
            'introduce' => $introduce,
            'pic' => $img,
            'type' => $type,
            'time' => $time,
            'address' => $address,
            'address_pic' => $map_pic,
        );

        $id = $brandWaresModel->insert($datas);
        if($id >= 1){
            $this->redirect('admin/Waress/index?brand_id='.$brandId);
        }else{
            $this->error('添加失败！');
        }
    }

    public function edit(){
        $brandId = input('brand_id');
        $waresId = input('wares_id');
        $brandWaresModel = new BrandWares();
        $data = $brandWaresModel->find($waresId);
        if(empty($data)){
            $this->error('商品不存在！');
        }elseif($data['status'] == 0){
            $this->error('商品已删除！');
        }

        $name = input('post.title');
        $type = input('post.type');
        $time = input('post.time');
        $address = input('post.address');
        $map_pic = input('post.map_pic');
        $introduce = input('post.message');
        $img = input('post.upload');
        if(empty($name) || empty($introduce) || empty($img)){
            $this->error('名称和介绍和图片不可为空！');
        }
        $status = input('post.status');
        if($status == 'on'){
            $status = 1;
        }else{
            $status = 2;
        }
        //添加标签
        $updataData = array(
            'name' => $name,
            'introduce' => $introduce,
            'pic' => $img,
            'type' => $type,
            'time' => $time,
            'address' => $address,
            'address_pic' => $map_pic,
            'status' => $status
        );
        $id = $brandWaresModel->where('id','=',$waresId)->update($updataData);
        if($id >= 0){
            $this->redirect('admin/Waress/index?brand_id='.$brandId);
        }else{
            $this->error('修改失败！');
        }
    }

    public function delete(){
        $brandId = input('brand_id');
        $waresId = input('wares_id');
        $brandWaresModel = new BrandWares();
        $data = $brandWaresModel->find($waresId);
        if(empty($data)){
            $this->error('商品不存在！');
        }elseif($data['status'] == 0){
            $this->error('商品已删除！');
        }
        $updateData['status'] = 0;
        $info = $brandWaresModel->where('id','=',$waresId)->update($updateData);
        if(empty($info)){
            $this->error('删除失败！');
        }else{
            $this->redirect('admin/Waress/index?brand_id='.$brandId);
        }
    }

    public function getListByBrand(){
        $brandId = input('brand_id');
        $brandWaresModel = Db::table('cn_brand_wares');
        $where[] = array('status','=',1);
        $where[] = array('brand_id','=',$brandId);
        $list = $brandWaresModel->where($where)->order('sort desc')->select();
        ajaxJsonReturn(1,'获取成功',$list);
    }

}
