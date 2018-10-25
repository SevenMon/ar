<?php

namespace app\model;

use think\Model;

class Shop extends Model {

    /*
     * 获取该品牌商的所有店铺
     */

    public function getShopByBrand($brand_id){
        $where = array();
        $where[] = array('brand_id','=',$brand_id);
        $where[] = array('status','=',1);
        $data = $this->where($where)->select();
        return $data;
    }

}
