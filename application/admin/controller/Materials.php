<?php

namespace app\admin\controller;

use app\model\Game1Material;
use think\facade\Request;
use app\model\Material;
use app\model\User;
use Db;
use app\model\Game;
use app\api\controller\GifFrameExtractor;

class Materials extends Base {

    public $left_menu_active = 'admin_games_index';
    public $top_menu_active = 'games';

    public function initialize()
    {
        parent::initialize();
    }


    public function index(){
        $gamesId = input('games_id');
        $gameModel = new Game();
        $gameData = $gameModel->find($gamesId);
        if(empty($gameData)){
            $this->error('游戏不存在！');
        }elseif($gameData['status'] == 0){
            $this->error('游戏已删除！');
        }

        $gameMaterialModel = Db::table('cn_game_'.$gameData['type'].'_material');
        $materailData = $gameMaterialModel->find($gameData['material_id']);

        $this->assign('gameData',$gameData);
        $this->assign('materailData',$materailData);
        return $this->fetch();
    }

    public function editPage(){
        $gamesId = input('games_id');
        $gameModel = new Game();
        $gameData = $gameModel->find($gamesId);
        if(empty($gameData)){
            $this->error('游戏不存在！');
        }elseif($gameData['status'] == 0){
            $this->error('游戏已删除！');
        }

        $gameMaterialModel = Db::table('cn_game_'.$gameData['type'].'_material');
        $materailData = $gameMaterialModel->find($gameData['material_id']);

        $this->assign('gameData',$gameData);
        $this->assign('materailData',$materailData);
        return $this->fetch();
    }


    public function edit(){
        $materialNum = input('material');
        $gamesId = input('games_id');
        $materailId = input('materail_id');
        $materialsSacn = input('materials_sacn');
        $materialsImg = input('materials_img');
        $materialsGif = input('materials_gif');
        $complete = input('upload');
        $uncomplete = input('uncomplete');
        $completeing = input('completeing');

        $gameModel = new Game();
        $gameData = $gameModel->find($gamesId);
        if(empty($gameData)){
            $this->error('游戏不存在！');
        }elseif($gameData['status'] == 0){
            $this->error('游戏已删除！');
        }
        //$gameMaterialModel = Db::table('cn_game_'.$gameData['type'].'_material');
        $gameMaterialModel = new Game1Material();
        if($gameData['material_id'] != $materailId){
            $this->error('参数错误！');
        }
        $easyar = new Easyar();
        //上传识别图
        $scancode[] = array();
        for($i = 1;$i <= count($materialsSacn) ;$i++){
            $tempScanImg = $materialsSacn[$i-1];
            $tempScanImgArr = explode('/',$tempScanImg);
            $info = $easyar->targetAdd($tempScanImgArr[count($tempScanImgArr) - 1],'.'.$tempScanImg);
            if($info['statusCode'] == 0){
                $scancode[$i] = $info['result']['targetId'];
            }else{
                $this->error('上传错误！');
                $easyar->targetDelete($scancode);
            }
        }

        $materailData = $gameMaterialModel->find($gameData['material_id']);
        $update = array();
        $update['status'] = 1;
        $update['material_num'] = $materialNum;
        $update['complete_pic'] = $complete;
        $update['uncomplete_pic'] = $uncomplete;
        $update['completeing_pic'] = $completeing;
        $this->decodeGif($completeing);
        $this->decodeGif($complete);

        for($i = 1;$i <= count($materialsSacn) ;$i++){
            $update['part'.$i] = $materialsImg[$i-1];
            $update['scan'.$i] = $materialsSacn[$i-1];
            $update['partgif'.$i] = $materialsGif[$i-1];
            $update['scan_id'.$i] = $scancode[$i];

            $this->decodeGif($materialsGif[$i-1]);
        }
        \think\Db::startTrans();
        $info = $gameMaterialModel->where('id','=',$materailId)->update($update);
        if(empty($info)){
            \think\Db::rollback();
            echo $gameMaterialModel->getLastSql();
            exit();
            $this->error('编辑错误1！');
        }
        $info = $gameModel->where('id','=',$gamesId)->update(array('status' => 1));
        if(empty($info)){
            \think\Db::rollback();
            $this->error('编辑错误2！');
        }
        \think\Db::commit();
        $this->redirect('admin/Games/index');
    }

    public function ajaxCheckScan(){
        $imgPath = input('imgPath');
        $easyar = new Easyar();
        $info = $easyar->checkSimilar('.'.$imgPath);
        if(empty($info['result']['results'])){
            ajaxJsonReturn(1,'没有重复');
        }else{
            ajaxJsonReturn(-1,'重复图片');
        }
    }

    public function decodeGif($file){
        //分解gif
        if(substr($file,-4,4) == '.gif'){
            $dirName = explode('/',$file);
            $dirName = $dirName['4'];
            $dirName = explode('.',$dirName);
            $dirName = $dirName[0];
            $dirName = './static/upload/game1/'.$dirName.'/';
            mkdir ($dirName,0777,true);
            $gfe = new GifFrameExtractor();
            $gfe->extract('.'.$file, true);
            $frameImages = $gfe->getFrameImages();
            $frameDurations = $gfe->getFrameDurations();
            $i = 0;
            foreach ($frameImages as $image) {
                imagepng($image, $dirName.$i.".png");
                $i++;
            }
        }
    }
    public function ceshi(){
        $parth = '/static/upload/game1/xiaoxiong.gif';
        $this->decodeGif($parth);
    }
}
