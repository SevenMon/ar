<?php

namespace app\model;

use think\Model;

class Coupon extends Model {


    /*
     * 获取还没有领取过的优惠券
     */
    public function getUnusered($brandId,$projectId){
        $where = array();
        $where[] = array('brand_id','=',$brandId);
        $where[] = array('project_id','=',$projectId);
        $where[] = array('status','=',1);
        $data = $this->where($where)->find();
        $this->where('id','=',$data['id'])->update(array('status' => 2));
        return $data;
    }
}
