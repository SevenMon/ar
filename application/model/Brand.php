<?php

namespace app\model;

use think\Model;

class Brand extends Model {

	public function checkName($name){
        $where[] = array('name','=',$name);
        $where[] = array('status','=',1);
        $info = $this->where($where)->count();
        if(empty($info)){
            return false;
        }else{
            return true;
        }
	}


	public function addData($data){
		$id = $this->insertGetId($data);
		if($id >= 1){
			return $id;
		}else{
			return -1;
		}
	}

    public function saveData($id,$data){
        $info = $this->where('id','=',$id)->update($data);
        if($info === false){
            return -1;
        }else{
            return 1;
        }
    }

}
