<?php

namespace app\model;

use think\Model;
use think\Db;


class Material extends Model {

    // 模型初始化
    protected static function init()
    {
        //TODO:初始化内容
    }
    
    //添加数据
    public function addData($data) {
        $info = $this->insert($data);
        if ($info === false) {
            return false;
        } else {
            return true;
        }
    }
    
    //修改数据
    public function saveData($id, $data) {
        $info = $this->save($data, ['id' => $id]);
        if ($info === false) {
            return false;
        } else {
            return true;
        }
    }
    
}
