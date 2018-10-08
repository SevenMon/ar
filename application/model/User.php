<?php

namespace app\model;

use think\Model;

class User extends Model {

   //修改数据
    public function saveData($id, $data) {
        $info = $this->save($data, ['id' => $id]);
        if ($info === false) {
            return false;
        } else {
            return true;
        }
    }

    //获取用户信息
    public function getDetail($id){
        return $this->leftJoin('cn_grade','cn_user.grade_id = cn_grade.id')->where('cn_user.id','=',$id)->find();
    }
}
