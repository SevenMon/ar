<?php

namespace app\model;

use think\Model;

class Admin extends Model {

	/*
	 * 查看用户名是否存在
	 */
	public function checkUsername($username){
		$info = $this->where('username','=',$username)->count();
		if(empty($info)){
			return -1;
		}else{
			return 1;
		}
	}

	/*
	 * 添加管理员
	 */
	public function addData($data){
		$aid = $this->insertGetId($data);
		if(empty($aid)){
			return -1;
		}else{
			return $aid;
		}
	}

	/*
	 * 修改
	 */
	public function saveData($aid,$managerData){

		$info = $this->where('aid','=',$aid)->update($managerData);
		if($info !== false){
			return 1;
		}else{
			return -1;
		}
	}
}
