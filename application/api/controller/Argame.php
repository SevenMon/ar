<?php

// 命名空间

namespace app\api\controller;

use app\model\Game;
use app\model\Project;
use app\model\Show;
use app\model\User;
use app\model\UserGameArData;
use think\Request;
use think\Db;

class Argame extends Base {
//class Argame {

    //获取游戏素材
    public function getGameMaterials(){
        $partner_id = input('partner_id');
        $partner_id = decode($partner_id);

        //获取项目
        $projectModel = new Project();
        $where = array();
        $where[] = array('partner_id' ,'=' ,$this->userInfo['partner_id']);
        $where[] = array('status','=',1);
        $projectData = $projectModel->where($where)->find();
        if(empty($projectData) || $projectData == null || $projectData['status'] != 1){
            ajaxJsonReturn(-1,'该合作商还没有开启项目，不能进入游戏',array());
        }

        //获取游戏获取部件的情况
        $where = array();
        $where[] = array('user_id','=',$this->userId);
        $where[] = array('project','=',$projectData['id']);
        $userGameModel = Db::table('cn_user_game_ar_data');
        $userGameData = $userGameModel->where($where)->find();
        if(empty($userGameData)){
            $userGameData = array();
        }

        //游戏
        $gameModel = new Game();
        $gameData = $gameModel->find($projectData['game_id']);
        if(empty($gameData) || $gameData == null || $gameData['status'] != 1){
            ajaxJsonReturn(-2,'该游戏不存在或还没有启动，不能进入游戏',array());
        }

        //游戏素材
        $gameMaterial = Db::table('cn_game_'.$gameData['type'].'_material');
        $gameMaterialData = $gameMaterial->find($gameData['material_id']);
        if(empty($gameMaterialData) || $gameMaterialData == null || $gameMaterialData['status'] != 1){
            ajaxJsonReturn(-3,'游戏没有设置相应的素材，请设置好素材在进行游戏',array());
        }
        ajaxJsonReturn(1,'获取成功',array('data' => $gameMaterialData,'userGameData'=>$userGameData));
    }

    //获取用户游戏信息
    public function getUserGameData(){
        //判断用户今日  已经玩的次数 和总共有多少次  TODO 没有算上分享的
        //获取项目
        $projectModel = new Project();
        $where = array();
        $where[] = array('partner_id' ,'=' ,$this->userInfo['partner_id']);
        $where[] = array('status','=',1);
        $projectData = $projectModel->where($where)->find();
        if(empty($projectData) || $projectData == null || $projectData['status'] != 1){
            ajaxJsonReturn(-1,'该合作商还没有开启项目，不能进入游戏',array());
        }
        $playDataModel = Db::table('cn_play_game_ar_data');
        $where = array();
        $where[] = array('user_id','=',$this->userId);
        $where[] = array('project','=',$projectData['id']);
        $where[] = array('create_time','>',date('Y-m-d 00:00:00',time()));
        $where[] = array('create_time','<',date('Y-m-d 23:59:59',time()));
        $todayPlayNum = $playDataModel->where($where)->count();

        $playNumParamModel = Db::table('cn_game_ar_param');
        $where = array();
        $where[] = array('start_time','<=',date('Y-m-d',time()));
        $where[] = array('end_time','>=',date('Y-m-d',time()));
        $paramData = $playNumParamModel->where($where)->order('play_number desc')->find();
        if(empty($paramData) || $paramData == null){
            $allPlayNum = 0;
        }else{
            $allPlayNum = $paramData['play_number'];
        }

        //获取游戏获取部件的情况
        $where = array();
        $where[] = array('user_id','=',$this->userId);
        $where[] = array('project','=',$projectData['id']);
        $userGameModel = Db::table('cn_user_game_ar_data');
        $userGameData = $userGameModel->where($where)->find();
        if(empty($userGameData)){
            $userGameData = array();
        }

        ajaxJsonReturn(1,'获取成功',array(
            'todayPlayNUm' => $todayPlayNum,
            'allPlayNum' => $allPlayNum,
            'userGameData' => $userGameData
        ));

    }

    //扫描验证接口
    public function scan(){
        $easyar = new Easyar();
        $result = $easyar->check();
        if($result['statusCode'] != 0){
            ajaxJsonReturn($result['statusCode'],$result['result'],array());
        }

        $targetId = $result['result']->targetId;
        $gameModel = Db::table('cn_game_1_material');
        $data = $gameModel->field('scan_id1,scan_id2,scan_id3,scan_id4,scan_id5')->where('scan_id1','=',$targetId)
            ->whereOr('scan_id2','=',$targetId)
            ->whereOr('scan_id3','=',$targetId)
            ->whereOr('scan_id4','=',$targetId)
            ->whereOr('scan_id5','=',$targetId)
            ->find();
        if(empty($data) || $data == null){
            ajaxJsonReturn(-4,'扫描失败',array());
        }

        //判断是部件几
        $part_num = '';
        foreach ($data as $key => $value){
            if($value == $targetId){
                $part_num = substr($key,-1,1);
                break;
            }
        }

        //获取项目
        $projectModel = new Project();
        $where = array();
        $where[] = array('partner_id' ,'=' ,$this->userInfo['partner_id']);
        $where[] = array('status','=',1);
        $projectData = $projectModel->where($where)->find();
        if(empty($projectData) || $projectData == null || $projectData['status'] != 1){
            ajaxJsonReturn(-1,'该合作商还没有开启项目，不能进入游戏',array());
        }
        //获取游戏
        $gameModel = new Game();
        $gameData = $gameModel->find($projectData['game_id']);
        if(empty($gameData) || $gameData == null || $gameData['status'] != 1){
            ajaxJsonReturn(-2,'该游戏不存在或还没有启动，不能进入游戏',array());
        }
        //游戏素材
        $gameMaterial = Db::table('cn_game_'.$gameData['type'].'_material');
        $gameMaterialData = $gameMaterial->find($gameData['material_id']);
        if(empty($gameMaterialData) || $gameMaterialData == null || $gameMaterialData['status'] != 1){
            ajaxJsonReturn(-3,'游戏没有设置相应的素材，请设置好素材在进行游戏',array());
        }



        //记录
        $playGameModel = Db::table('cn_play_game_ar_data');
        $playData = array(
            'user_id' => $this->userId,
            'part' => $part_num,
            'project' => $projectData['id'],
        );
        $playGameModel->insertGetId($playData);

        $where = array();
        $where[] = array('user_id','=',$this->userId);
        $where[] = array('project_id','=',$projectData['id']);

        $userGameModel = Db::table('cn_user_game_ar_data');
        $userGameData = $userGameModel->where($where)->find();
        if(empty($userGameData) || $userGameData == null){
            $userGameAddData = array(
                'user_id' => $this->userId,
                'project_id' => $projectData['id']
            );
            $userGameAddData['part'.$part_num.'_num'] = 1;
            $userGameData->insertGetId($userGameAddData);
        }else{
            $userGameData['part'.$part_num.'_num'] = $userGameData['part'.$part_num.'_num'] + 1;
            //判断是否集齐  先看集齐状态  如果已经集齐了  那就不用管了  可以继续搜集  不过不可以变换状态  只有未集齐状态  在扫描之后如果集齐了  那么变换状态为集齐
            if($userGameData['is_complete'] == 0){
                $or = true;
                for($i=1;$i <= $gameMaterialData['material_num'];$i++){
                    if($userGameData['part'.$i.'_num'] == 0){
                        $or = false;
                    }
                }
            }
            if($or){
                $userGameUpdate['is_complete'] = 1;
            }
            $userGameUpdate['part'.$part_num.'_num'] = $userGameData['part'.$part_num.'_num'];
            $userGameModel->where($where)->update($userGameUpdate);
        }
        ajaxJsonReturn(1,'扫描成功',array('data' => $userGameUpdate));

    }

    //分享部件接口
    public function showPart(){
        //部件编号
        $part_num = input('part_num');
        //检查用户是否有此部件
        $userGameDataModel = new UserGameArData();
        $data = $userGameDataModel->getUserData($this->userInfo);
        if(empty($data) || $data['part'.$part_num.'_num'] == 0){
            ajaxJsonReturn(-2,'分享部件不存在',array());
        }
        $data = array(
            'part_num' => $part_num,
            'type' => 1,
            'start_user_id' => $this->userId,
            'project_id' =>$data['project_id']
        );
        $showModel = new Show();
        $show_id = $showModel->insertGetId($data);
        if(empty($show_id)){
            ajaxJsonReturn(-1,'分享失败',array());
        }else{
            ajaxJsonReturn(1,'分享成功',array('show_id'=>$show_id));
        }
    }

    //接收分享部件接口
    public function receivePart(){
        $show_id = input('show_id');
        $showModel = new Show();
        $showData = $showModel->find($show_id);
        if(empty($showData) || $showData == null){
            ajaxJsonReturn(-1,'分享id错误',array());
        }
        if($showData['type'] != 1){
            ajaxJsonReturn(-2,'分享类型错误',array());
        }
        if($showData['status'] == 1){
            ajaxJsonReturn(-4,'已经被其他用户领取',array());
        }
        $userModel = new User();
        $userInfo = $userModel->find($showData['start_user_id']);
        $userGameDataModel = new UserGameArData();
        //自己的游戏数据
        $userGameDataModel = new UserGameArData();
        $selfData = $userGameDataModel->getUserData($this->userInfo);
        if($selfData['project_id'] != $showData['project_id']){
            ajaxJsonReturn(-5,'该给予部件的分享已经过期！');
        }

        $showUserdata = $userGameDataModel->getUserData($userInfo);
        if($showUserdata['part'.$showData['part_num'].'_num'] < 1){
            ajaxJsonReturn(-3,'用户已经使用了该部件',array());
        }
        //改变分享人部件数量
        $showUserUpdateinfo = $userGameDataModel->where('id','=',$showUserdata['id'])->update(array('part'.$showData['part_num'].'_num' => ($showUserdata['part'.$showData['part_num'].'_num']-1)));
        //改变分享数据的状态
        $showUpdateInfo = $showModel->where('id','=',$show_id)->update(array('status' => 1,'end_user_id' => $this->userId));
        //改变被分享人部件数量 并且判断是否已经收集完成 并改变状态
        //获取游戏素材数据
        $projectModel = new Project();
        $projectData = $projectModel->find($selfData['project_id']);
        $gameModel = new Game();
        $gameData = $gameModel->find($projectData['game_id']);
        $gameMaterialModel = Db::table('cn_game_'.$gameData['type'].'_material');
        $gameMaterialData = $gameMaterialModel->find($gameData['material_id']);

        ++$selfData['part'.$showData['part_num'].'_num'];
        $check = true;
        for($i = 1;$i <= $gameMaterialData['material_num'];$i++){
            if($selfData['part'.$i.'_num'] <= 0){
                $check = false;
                break;
            }
        }

        $update = array(
            'part'.$showData['part_num'].'_num' => $selfData['part'.$showData['part_num'].'_num']
        );
        if($check){
            $update['is_complete'] = 1;
        }
        $selfUserUpdateInfo = $userGameDataModel->where('id','=',$selfData['id'])->update($update);

        if(empty($selfUserUpdateInfo)){
            ajaxJsonReturn(-7,'获取部件失败',array());
        }else{
            ajaxJsonReturn(1,'获取部件成功',array());
        }



    }

    //索要部件分享接口
    public function wantPart(){
        //部件编号
        $part_num = input('part_num');
        $userGameDataModel = new UserGameArData();
        $userdata = $userGameDataModel->getUserData($this->userInfo);
        $data = array(
            'part_num' => $part_num,
            'type' => 2,
            'start_user_id' => $this->userId,
            'project_id' => $userdata['project_id']
        );
        $showModel = new Show();
        $show_id = $showModel->insertGetId($data);
        if(empty($show_id)){
            ajaxJsonReturn(-1,'分享失败',array());
        }else{
            ajaxJsonReturn(1,'分享成功',array('show_id'=>$show_id));
        }
    }

    //确认给予接口
    public function confirmWantPart(){
        $show_id = input('show_id');
        $showModel = new Show();
        $showData = $showModel->find($show_id);
        if(empty($showData) || $showData == null){
            ajaxJsonReturn(-1,'分享id错误',array());
        }
        if($showData['type'] != 2){
            ajaxJsonReturn(-2,'分享类型错误',array());
        }
        if($showData['status'] == 1){
            ajaxJsonReturn(-3,'已经确认过给予部件',array());
        }
        //自己的游戏数据
        $userGameDataModel = new UserGameArData();
        $selfData = $userGameDataModel->getUserData($this->userInfo);
        if($selfData['project_id'] != $showData['project_id']){
            ajaxJsonReturn(-4,'该分享已经过期！');
        }
        //判断自己是否有这个部件
        if($selfData['part'.$showData['part_num'].'_num'] < 1){
            ajaxJsonReturn(-5,'您没有多余的部件可以分享！');
        }

        //更新自己的部件数量
        $selfUpdate = array(
            'part'.$showData['part_num'].'_num' => $selfData['part'.$showData['part_num'].'_num'] - 1
        );
        $selfUpdateInfo = $userGameDataModel->where('id','=',$selfData['id'])->update($selfUpdate);
        //更新分享数据
        $showUpdate = array(
            'status' => 1,
            'end_user_id' => $this->userId
        );
        $showUpdateInfo = $showModel->where('id','=',$show_id)->update($showUpdate);
        //更新索要人的部件数量和皇台
        $userModel = new User();
        $userInfo = $userModel->find($showData['start_user_id']);
        $showUserData = $userGameDataModel->getUserData($userInfo);

        $projectModel = new Project();
        $projectData = $projectModel->find($showUserData['project_id']);
        $gameModel = new Game();
        $gameData = $gameModel->find($projectData['game_id']);
        $gameMaterialModel = Db::table('cn_game_'.$gameData['type'].'_material');
        $gameMaterialData = $gameMaterialModel->find($gameData['material_id']);

        ++$showUserData['part'.$showData['part_num'].'_num'];
        $check = true;
        for($i = 1;$i <= $gameMaterialData['material_num'];$i++){
            if($showUserData['part'.$i.'_num'] <= 0){
                $check = false;
                break;
            }
        }

        $update = array(
            'part'.$showData['part_num'].'_num' => $showUserData['part'.$showData['part_num'].'_num']
        );
        if($check){
            $update['is_complete'] = 1;
        }
        $showUserUpdateInfo = $userGameDataModel->where('id','=',$showUserData['id'])->update($update);

        if(empty($showUserUpdateInfo)){
            ajaxJsonReturn(-6,'给予部件失败',array());
        }else{
            ajaxJsonReturn(1,'给予部件成功',array());
        }

    }

    //获取兑奖信息
    public function prize(){

    }

    //发送验证码接口
    public function sendcode(){
        $phone = input('phone');
        $txsms = new Txsms();
        $code = rand(1000,9999);
        //mysql存储并且验证
        $codeModel = Db::table('cn_code');
        $where = array();
        $where[] = array('phone','=',$phone);
        $data = $codeModel->where($where)->find();
        if(empty($data) || $data == null){
            $addData = array(
                'phone' => $phone,
                'code' => $code,
                'create_time' => time(),
                'time' => 1,
            );
            $codeModel->insertGetId($addData);
        }else{
            //判断次数  和 时间限制
            if($data['time'] >= 10){
                ajaxJsonReturn(-1,'该手机号最多可以发送十次');
            }
            if(time() - $data['create_time'] < 60){
                ajaxJsonReturn(-2,'60秒内不可重复发送短信，剩余'.(60 - time() - $data['create_time']),array('overplus_time' => 60 - time() - $data['create_time']));
            }
            $updateData = array(
                'code' => $code,
                'create_time' => time(),
                'time' => $data['time'] + 1,
            );
            $codeModel->where($where)->update($updateData);
        }
        $txsms->sendSingleTemplateSms($phone,204282,array($code),'行云时空',array('code'=> $code));
    }

    //校验验证码接口 并进行兑奖操作
    public function confirmcode(){
        $phone = input('phone');
        $code = input('code');
        $codeModel = Db::table('cn_code');
        $where = array();
        $where[] = array('phone','=',$phone);
        $where[] = array('code','=',$code);
        $data = $codeModel->where($where)->find();
        if(empty($data) || $data == null){
            ajaxJsonReturn(-1,'验证码错误请重新输入！',array());
        }
        if(time() - $data['create_time'] > 300){
            ajaxJsonReturn(-2,'验证码过期，请重新获取并验证！',array());
        }

        //获取项目
        $projectModel = new Project();
        $where = array();
        $where[] = array('partner_id' ,'=' ,$this->userInfo['partner_id']);
        $where[] = array('status','=',1);
        $projectData = $projectModel->where($where)->find();
        if(empty($projectData) || $projectData == null || $projectData['status'] != 1){
            ajaxJsonReturn(-1,'该合作商还没有开启项目，不能进入游戏',array());
        }
        $update = array(
            'is_complete' => 3
        );
        //获取游戏获取部件的情况
        $where = array();
        $where[] = array('user_id','=',$this->userId);
        $where[] = array('project','=',$projectData['id']);
        $userGameModel = Db::table('cn_user_game_ar_data');
        $info = $userGameModel->where($where)->update($update);
        if(empty($info)){
            ajaxJsonReturn(-1,'兑奖失败',array());
        }else{
            ajaxJsonReturn(1,'兑奖成功',array());
        }
    }

    //合成操作
    public function complete(){
        //获取项目
        $projectModel = new Project();
        $where = array();
        $where[] = array('partner_id' ,'=' ,$this->userInfo['partner_id']);
        $where[] = array('status','=',1);
        $projectData = $projectModel->where($where)->find();
        if(empty($projectData) || $projectData == null || $projectData['status'] != 1){
            ajaxJsonReturn(-1,'该合作商还没有开启项目，不能进入游戏',array());
        }

        //获取游戏获取部件的情况
        $where = array();
        $where[] = array('user_id','=',$this->userId);
        $where[] = array('project','=',$projectData['id']);
        $userGameModel = Db::table('cn_user_game_ar_data');
        $userGameData = $userGameModel->where($where)->find();
        if(empty($userGameData) || $userGameData['is_complete'] != 1){
            ajaxJsonReturn(-3,'参数错误');
        }

        $update = array(
            'is_complete' => 2,
            'part1_num' => $userGameData['part1_num'] != 0 ? $userGameData['part1_num'] - 1 : 0,
            'part2_num' => $userGameData['part2_num'] != 0 ? $userGameData['part2_num'] - 1 : 0,
            'part3_num' => $userGameData['part3_num'] != 0 ? $userGameData['part3_num'] - 1 : 0,
            'part4_num' => $userGameData['part4_num'] != 0 ? $userGameData['part4_num'] - 1 : 0,
            'part5_num' => $userGameData['part5_num'] != 0 ? $userGameData['part5_num'] - 1 : 0,
        );
        $info = $userGameModel->where($where)->update($update);
        if(empty($info)){
            ajaxJsonReturn(-1,'合成失败',array());
        }else{
            ajaxJsonReturn(1,'合成成功',array());
        }

    }

    //获取剩余次数
    public function getPlayTime(){

    }

}
