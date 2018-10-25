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
use app\model\Shop;
use app\model\Show;
use app\model\Stock;
use app\model\StockProperty;
use app\model\Type;
use think\Db;
use think\Request;
use app\model\Product;


class Shops extends Base {

    public $left_menu_active = 'admin_brands_index';
    public $top_menu_active = 'brands';

	public function initialize() {
		parent::initialize();
	}

    public function index(){
        $brandId = input('brand_id');
        $this->assign('brandId',$brandId);
        //获取数据  分页
        $shopModel = new Shop();
        $limit = input('limit') == null || empty(input('limit')) ? getLimit() : input('limit');
        $where[] = array('status','>',0);
        $where[] = array('brand_id','=',$brandId);
        $list = $shopModel->where($where)->order('id desc')->paginate($limit);
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
        $shopId = input('shop_id');

        $shopModel = new Shop();
        $shopData = $shopModel->find($shopId);
        if(empty($shopData)){
            $this->error('门店不存在！');
        }elseif($shopData['status'] == 0){
            $this->error('门店已删除！');
        }
        $this->assign('brandId',$brandId);
        $this->assign('shopData',$shopData);
        return $this->fetch();
    }

    public function add(){
        $brandId = input('brand_id');
        $name = input('post.title');
        $phone = input('post.phone');
        $address = input('post.address');
        $map_pic = input('post.map_pic');
        if(empty($name) || $name == null ||empty($phone) || $phone == null ||empty($address) || $address == null ||empty($map_pic) || $map_pic == null){
            $this->error('补充参数！');
        }
        $shopModel = new Shop();

        //获取最大sort
        $maxSort = $shopModel->field('max(sort) maxSort')->find();
        $maxSort = $maxSort['maxSort'];
        //添加标签
        $datas = array(
            'brand_id' => $brandId,
            'name' => $name,
            'sort' => ++$maxSort,
            'phone' => $phone,
            'address' => $address,
            'address_pic' => $map_pic,
        );

        $id = $shopModel->insert($datas);
        if($id >= 1){
            $this->redirect('admin/Shops/index?brand_id='.$brandId);
        }else{
            $this->error('添加失败！');
        }
    }

    public function edit(){
        $brandId = input('brand_id');
        $shopId = input('shop_id');
        $shopModel = new Shop();
        $data = $shopModel->find($shopId);
        if(empty($data)){
            $this->error('门店不存在！');
        }elseif($data['status'] == 0){
            $this->error('商门店已删除！');
        }

        $name = input('post.title');
        $phone = input('post.phone');
        $address = input('post.address');
        $map_pic = input('post.map_pic');

        if(empty($name) || $name == null ||empty($phone) || $phone == null ||empty($address) || $address == null ||empty($map_pic) || $map_pic == null){
            $this->error('补充参数！');
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
            'phone' => $phone,
            'address' => $address,
            'address_pic' => $map_pic,
            'status' => $status
        );
        $id = $shopModel->where('id','=',$shopId)->update($updataData);
        if($id >= 0){
            $this->redirect('admin/Shops/index?brand_id='.$brandId);
        }else{
            $this->error('修改失败！');
        }
    }

    public function delete(){
        $brandId = input('brand_id');
        $shopId = input('shop_id');
        $shopModel = new Shop();
        $data = $shopModel->find($shopId);
        if(empty($data)){
            $this->error('门店不存在！');
        }elseif($data['status'] == 0){
            $this->error('门店已删除！');
        }
        $updateData['status'] = 0;
        $info = $shopModel->where('id','=',$shopId)->update($updateData);
        if(empty($info)){
            $this->error('删除失败！');
        }else{
            $this->redirect('admin/Shops/index?brand_id='.$brandId);
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
