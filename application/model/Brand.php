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

    public function getAccessToken($id){
	    $data = $this->find($id);
	    if(empty($data)){
            return '';
        }
        if(time() - $data['access_token_create_time'] > 7190){
            $ch = curl_init(); //初始化一个CURL对象
            curl_setopt($ch, CURLOPT_URL, "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$data['app_id']."&secret=".$data['app_secret']);//设置你所需要抓取的URL
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//设置curl参数，要求结果是否输出到屏幕上，为true的时候是不返回到网页中,假设上面的0换成1的话，那么接下来的$data就需要echo一下。
            $data = json_decode(curl_exec($ch));
            if($data->access_token){
                $update = array(
                    'access_token_create_time' => time(),
                    'access_token' => $data->access_token,
                );
                $this->where('id','=',$id)->update($update);
                return $data->access_token;
            }else{
               return '';
            }
            curl_close($ch);
        }else{
	        return $data['access_token'];
        }
    }

}
