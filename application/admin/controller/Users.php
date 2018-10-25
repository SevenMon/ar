<?php

namespace app\admin\controller;

use app\model\GameArPrize;
use app\model\GameType;
use app\model\Partner;
use app\model\Project;
use app\model\UserGameArData;
use think\Db;
use think\facade\Request;
use app\model\User;
use app\model\GradeType;
use app\model\UserBank;

class Users extends Base {

    public $left_menu_active = 'admin_users_index';
    public $top_menu_active = 'users';

    public function initialize() {
        parent::initialize();
    }

    /*
     *  列表
     */

    public function index(){
        //获取所有激活的合作商
        $partnerModel = new Partner();
        $where = array();
        $where[] = array('status','>',0);
        $partnerList = $partnerModel->where($where)->select();
        $this->assign('partnerList',$partnerList);
        //获取数据  分页
        $partnerId = input('partner_id');
        $nickname = input('nickname');
        $phone = input('phone');
        $where = array();
        $query = array();
        if(!empty($partnerId) && $partnerId != null){
            $where[] = array('partner_id','=',$partnerId);
            $this->assign('partnerId',$partnerId);
            $query['partner_id'] = $partnerId;
        }
        if(!empty($nickname) && $nickname != null){
            $where[] = array('nickname_unbase','like','%'.$nickname.'%');
            $this->assign('nickname',$nickname);
            $query['nickname'] = $nickname;
        }
        if(!empty($phone) && $phone != null){
            $where[] = array('mobile','like','%'.$phone.'%');
            $this->assign('phone',$phone);
            $query['phone'] = $phone;
        }
        $userModel = new User();
        $limit = input('limit') == null || empty(input('limit')) ? getLimit() : input('limit');
        $where[] = array('status','=',1);
        $list = $userModel->where($where)->order('id desc')->paginate($limit,false, [
            'query' => $query,
        ]);
        $this->assign('list', $list);
        $this->assign('query', $query);
        return $this->fetch();
    }

    public function export(){
        //获取所有激活的合作商
        $partnerModel = new Partner();
        $where = array();
        $where[] = array('status','>',0);
        $partnerList = $partnerModel->where($where)->select();
        $this->assign('partnerList',$partnerList);
        //获取数据  分页
        $partnerId = input('partner_id');
        $nickname = input('nickname');
        $phone = input('phone');
        $where = array();
        $query = array();
        if(!empty($partnerId) && $partnerId != null){
            $where[] = array('partner_id','=',$partnerId);
            $this->assign('partnerId',$partnerId);
            $query['partner_id'] = $partnerId;
        }
        if(!empty($nickname) && $nickname != null){
            $where[] = array('nickname_unbase','like','%'.$nickname.'%');
            $this->assign('nickname',$nickname);
            $query['nickname'] = $nickname;
        }
        if(!empty($phone) && $phone != null){
            $where[] = array('mobile','like','%'.$phone.'%');
            $this->assign('phone',$phone);
            $query['phone'] = $phone;
        }
        $userModel = new User();
        $where[] = array('status','=',1);
        $data = $userModel->where($where)->order('id desc')->select();
        require '../extend/phpexcel/PHPExcel.php';
        $objPHPExcel  = new \PHPExcel();
        //导出订单模板
        //定义配置
        $topNumber = 2;//表头有几行占用
        $cellKey = array(
            'A','B','C','D','E','F','G'
        );
        //写在处理的前面（了解表格基本知识，已测试）
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1','用户id');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1','手机');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1','openid');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1','昵称');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1','性别');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1','语言');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1','城市');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1','省份');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1','国家');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J1','头像');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K1','关注时间');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L1','状态');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M1','合作商id');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N1','合作商名称');

        $objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(17);//所有单元格（列）默认宽度
        //$objPHPExcel->getActiveSheet()->getDefaultRowDimension(2)->setRowHeight(80);
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true)->setSize(12);
        $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true)->setSize(12);
        $objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setBold(true)->setSize(12);
        $objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setBold(true)->setSize(12);
        $objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setBold(true)->setSize(12);
        $objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setBold(true)->setSize(12);
        $objPHPExcel->getActiveSheet()->getStyle('G1')->getFont()->setBold(true)->setSize(12);
        $objPHPExcel->getActiveSheet()->getStyle('H1')->getFont()->setBold(true)->setSize(12);
        $objPHPExcel->getActiveSheet()->getStyle('I1')->getFont()->setBold(true)->setSize(12);
        $objPHPExcel->getActiveSheet()->getStyle('J1')->getFont()->setBold(true)->setSize(12);
        $objPHPExcel->getActiveSheet()->getStyle('K1')->getFont()->setBold(true)->setSize(12);
        $objPHPExcel->getActiveSheet()->getStyle('L1')->getFont()->setBold(true)->setSize(12);
        $objPHPExcel->getActiveSheet()->getStyle('M1')->getFont()->setBold(true)->setSize(12);
        $objPHPExcel->getActiveSheet()->getStyle('N1')->getFont()->setBold(true)->setSize(12);

        $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('C1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('D1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('E1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('F1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('G1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('H1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('I1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('J1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('K1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('L1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('M1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('N1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $i = 2;
        foreach ($data as $value){
            $objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('B'.$i)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('C'.$i)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('D'.$i)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('E'.$i)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('F'.$i)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('G'.$i)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('H'.$i)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('I'.$i)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('J'.$i)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('K'.$i)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('L'.$i)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('M'.$i)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            switch ($value['gender'])
            {
                case 1:
                    $value['gender'] = '男';
                    break;
                case 2:
                    $value['gender'] = '女';
                    break;
                default:
                    $value['gender'] = '未知';
                    break;
            }
            if($value['avatar_url']==null || empty($value['avatar_url'])){
                $objPHPExcel->getActiveSheet()->setCellValue('J'.$i,'');
            }else{
                $img = file_get_contents($value['avatar_url']);
                file_put_contents('./Uploads/tempImg/img'.$value['id'],$img);
                $objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(80);
                // 图片生成
                $objDrawing = new \PHPExcel_Worksheet_Drawing();
                $objDrawing->setPath('./Uploads/tempImg/img'.$value['id']);
                // 设置宽度高度
                $objDrawing->setHeight(80);//照片高度
                $objDrawing->setWidth(80); //照片宽度
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates('J'.$i);
                // 图片偏移距离
                $objDrawing->setOffsetX(12);
                $objDrawing->setOffsetY(12);
                $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
            }
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$i,$value['id']);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$i,$value['mobile']);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$i,$value['openid']);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$i,base64_decode($value['nickname']));
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$i,$value['gender']);
            $objPHPExcel->getActiveSheet()->setCellValue('F'.$i,$value['language']);
            $objPHPExcel->getActiveSheet()->setCellValue('G'.$i,$value['city']);
            $objPHPExcel->getActiveSheet()->setCellValue('H'.$i,$value['province']);
            $objPHPExcel->getActiveSheet()->setCellValue('I'.$i,$value['country']);

            //$objPHPExcel->getActiveSheet()->setCellValue('J'.$i,$value['avatar_url']);

            $objPHPExcel->getActiveSheet()->setCellValue('K'.$i,date('Y-m-d',$value['timestamp']));
            $objPHPExcel->getActiveSheet()->setCellValue('L'.$i,$value['status'] == 1?'启用':'黑名单');
            $objPHPExcel->getActiveSheet()->setCellValue('M'.$i,$value['partner_id']);
            $objPHPExcel->getActiveSheet()->setCellValue('N'.$i,getPartnerInfo($value['partner_id'],'name'));
            $i++;
        }
        //导出execl
        header('Content-Type: application/vnd.ms-excel');//storeSelfTradeTemplatet
        header('Content-Disposition: attachment;filename="partner_'.date('Y-m-d',time()).'.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    /*
     * 黑名单列表
     */
    public function blacklist(){
        //获取所有激活的合作商
        $partnerModel = new Partner();
        $where = array();
        $where[] = array('status','>',0);
        $partnerList = $partnerModel->where($where)->select();
        $this->assign('partnerList',$partnerList);
        //获取数据  分页
        $partnerId = input('partner_id');
        $nickname = input('nickname');
        $phone = input('phone');
        $where = array();
        $query = array();
        if(!empty($partnerId) && $partnerId != null){
            $where[] = array('partner_id','=',$partnerId);
            $this->assign('partnerId',$partnerId);
            $query['partner_id'] = $partnerId;
        }
        if(!empty($nickname) && $nickname != null){
            $where[] = array('nickname_unbase','like','%'.$nickname.'%');
            $this->assign('nickname',$nickname);
            $query['nickname'] = $nickname;
        }
        if(!empty($phone) && $phone != null){
            $where[] = array('mobile','like','%'.$phone.'%');
            $this->assign('phone',$phone);
            $query['phone'] = $phone;
        }
        $userModel = new User();
        $limit = input('limit') == null || empty(input('limit')) ? getLimit() : input('limit');
        $where[] = array('status','=',2);
        $list = $userModel->where($where)->order('id desc')->paginate($limit,false, [
            'query' => $query,
        ]);
        $this->assign('list', $list);
        $this->assign('left_menu_active', 'admin_users_blacklist');
        return $this->fetch();
    }
    /*
     * 用户详情
     */
    public function detail(){
        $user_id = input('user_id');
        $userModel = new User();
        $data = $userModel->find($user_id);
        $this->assign('userData',$data);
        $this->assign('partnerData',getPartnerInfo($data['partner_id']));
        return $this->fetch();
    }

    /*
     * 加入黑名单
     */
    public function ajaxAddBlackLIst(){
        $user_id = input('user_id');
        $userModel = new User();
        $data = $userModel->find($user_id);
        if(empty($data)){
            $this->error('会员不存在');
        }

        $update = array(
            'status' => 2
        );
        $info = $userModel->where('id','=',$user_id)->update($update);
        if(empty($info)){
            ajaxJsonReturn(-1,'设置失败');
        }else{
            ajaxJsonReturn(1,'设置成功');
        }
    }

    /*
     * 加入白名单
     */
    public function ajaxAddWhiteLIst(){
        $user_id = input('user_id');
        $userModel = new User();
        $data = $userModel->find($user_id);
        if(empty($data)){
            $this->error('会员不存在');
        }

        $update = array(
            'status' => 1
        );
        $info = $userModel->where('id','=',$user_id)->update($update);
        if(empty($info)){
            ajaxJsonReturn(-1,'设置失败');
        }else{
            ajaxJsonReturn(1,'设置成功');
        }
    }





    public function game(){
        $game_type_id = input('game_type_id');
        if(empty($game_type_id) || $game_type_id == null){
            $game_type_id = 1;
        }
        switch ($game_type_id)
        {
            case 1:
                return $this->argame();
                break;
            default:
                ajaxJsonReturn(-1,'请输入正确的gametypeid',array());
                break;
        }
    }

    public function argame(){
        $partner_id = input('partner_id');
        $is_complete = input('is_complete');
        $project_id = input('project_id');
        $nickname = input('nickname');
        $phone = input('phone');
        //获取游戏类型
        $gameList = GameType::TYPE;
        $this->assign('gameList',$gameList);
        //获取所有激活的合作商
        $partnerModel = new Partner();
        $where = array();
        $where[] = array('status','=',1);
        $partnerList = $partnerModel->where($where)->select();
        $this->assign('partnerList',$partnerList);
        //获取所有项目
        $projectModel = new Project();
        $projectData = $projectModel->where('status','>',0)->select();
        $this->assign('projectData',$projectData);
        //获取用户及其游戏信息
        $userModel = new User();
        $query = array();
        $limit = input('limit') == null || empty(input('limit')) ? getLimit() : input('limit');
        $where = array();
        if(!(empty($partner_id) || $partner_id == null)){
            $where[] = array('cn_user.partner_id','=',$partner_id);
            $this->assign('partner_id',$partner_id);
            $query['partner_id'] = $partner_id;
        }
        if(!(empty($is_complete) || $is_complete == null) || $is_complete === '0'){
            $where[] = array('cn_user_game_ar_data.is_complete','=',$is_complete);
            $this->assign('is_complete',$is_complete);
            $query['is_complete'] = $is_complete;
        }
        if(!(empty($project_id) || $project_id == null) || $project_id === '0'){
            $where[] = array('cn_user_game_ar_data.project_id','=',$project_id);
            $this->assign('project_id',$project_id);
            $query['project_id'] = $project_id;
        }
        if(!empty($nickname) && $nickname != null){
            $where[] = array('nickname_unbase','like','%'.$nickname.'%');
            $this->assign('nickname',$nickname);
            $query['nickname'] = $nickname;
        }
        if(!empty($phone) && $phone != null){
            $where[] = array('mobile','like','%'.$phone.'%');
            $this->assign('phone',$phone);
            $query['phone'] = $phone;
        }
        $where[] = array('cn_project.status','>',0);
        $list = $userModel->field('cn_user.*,cn_partner.*,
        cn_user_game_ar_data.*,cn_user.id user_id,
        cn_project.*,cn_project.name project_name,
        cn_project.id project_id,cn_partner.id partner_id,
        cn_partner.name partner_name,cn_user_game_ar_data.id id,
        cn_user.status user_status,cn_user.mobile user_phone
        ')
            ->join('cn_user_game_ar_data','cn_user.id=cn_user_game_ar_data.user_id')
            ->join('cn_project','cn_project.id=cn_user_game_ar_data.project_id')
            ->leftJoin('cn_partner','cn_user.partner_id=cn_partner.id')
            ->where($where)->order('cn_user_game_ar_data.id desc')->paginate($limit,false,array('query'=>$query));
        //获取部件信息
        $userGameModel = new UserGameArData();
        foreach ($list as &$value){
            $playGameDataModel = Db::table('cn_play_game_ar_data');
            $where = array();
            $where[] = array('user_id','=',$value['user_id']);
            $where[] = array('project','=',$value['project_id']);
            $count = $playGameDataModel->where($where)->count();
            $tempData = $userGameModel->getMateria($value['game_id']);
            $partStr = '';
            for($i=1;$i <= $tempData['gameMaterialData']['material_num'];$i++){
                $partStr .= '<p>部件'.$i.':'.$value['part'.$i.'_num'].'个</p>';
            }
            $partStr .= '<p>总获取数量：'.$count.'</p>';

            $value['partStr'] = $partStr;
            switch ($value['is_complete']){
                case 0:
                    $value['is_complete_str'] = '未集齐';
                    break;
                case 1:
                    $value['is_complete_str'] = '已集齐';
                    break;
                case 2:
                    $value['is_complete_str'] = '已合成';
                    break;
                case 3:
                    $value['is_complete_str'] = '已领奖';
                    break;
            }
            $value['project_name'] = $value['project_name'];
        }
        $this->assign('list', $list);
        $this->assign('left_menu_active', 'admin_users_game');
        return $this->fetch('argame');
    }

    /*
     * 中奖信息
     */
    public function lucky(){
        $partner_id = input('partner_id');
        $project_id = input('project_id');
        $nickname = input('nickname');
        $phone = input('phone');
        //获取游戏类型
        $gameList = GameType::TYPE;
        $this->assign('gameList',$gameList);
        //获取所有项目
        $projectModel = new Project();
        $projectData = $projectModel->where('status','>',0)->select();
        $this->assign('projectData',$projectData);
        //获取所有激活的合作商
        $partnerModel = new Partner();
        $where = array();
        $query = array();
        //$where[] = array('status','=',1);
        $partnerList = $partnerModel->where($where)->select();
        $this->assign('partnerList',$partnerList);

        if(!(empty($partner_id) || $partner_id == null)){
            $where[] = array('cn_user.partner_id','=',$partner_id);
            $this->assign('partner_id',$partner_id);
            $query['partner_id'] = $partner_id;
        }
        if(!(empty($project_id) || $project_id == null) || $project_id === '0'){
            $where[] = array('cn_project.id','=',$project_id);
            $this->assign('project_id',$project_id);
            $query['project_id'] = $project_id;
        }
        if(!empty($nickname) && $nickname != null){
            $where[] = array('nickname_unbase','like','%'.$nickname.'%');
            $this->assign('nickname',$nickname);
            $query['nickname'] = $nickname;
        }
        if(!empty($phone) && $phone != null){
            $where[] = array('mobile','like','%'.$phone.'%');
            $this->assign('phone',$phone);
            $query['phone'] = $phone;
        }
        $where[] = array('cn_project.status','>',0);
        $gameArPrize = new GameArPrize();
        $limit = input('limit') == null || empty(input('limit')) ? getLimit() : input('limit');
        $list = $gameArPrize->field('cn_user.*,cn_project.id project_id,
        cn_project.name project_name,cn_brand.id brand_id,
        cn_brand.name brand_name,cn_game_ar_prize.type prize_type,
        cn_game_ar_prize.*,cn_user.status user_status,cn_user.mobile user_phone')
            ->join('cn_project','cn_project.id=cn_game_ar_prize.project_id')
            ->leftJoin('cn_brand','cn_game_ar_prize.brand_id=cn_brand.id')
            ->leftJoin('cn_user','cn_game_ar_prize.user_id=cn_user.id')
            ->where($where)->paginate($limit,false,array('query'=>$query));
        $this->assign('list',$list);
        $this->assign('left_menu_active', 'admin_users_lucky');
        return $this->fetch();
    }

    public function playdata(){
        $partner_id = input('partner_id');
        $project_id = input('project_id');
        $nickname = input('nickname');
        $phone = input('phone');
        //获取游戏类型
        $gameList = GameType::TYPE;
        $this->assign('gameList',$gameList);
        //获取所有项目
        $projectModel = new Project();
        $projectData = $projectModel->where('status','>',0)->select();
        $this->assign('projectData',$projectData);
        //获取所有激活的合作商
        $partnerModel = new Partner();
        $where = array();
        $query = array();
        //$where[] = array('status','=',1);
        $partnerList = $partnerModel->where($where)->select();
        $this->assign('partnerList',$partnerList);

        if(!(empty($partner_id) || $partner_id == null)){
            $where[] = array('cn_user.partner_id','=',$partner_id);
            $this->assign('partner_id',$partner_id);
            $query['partner_id'] = $partner_id;
        }
        if(!(empty($project_id) || $project_id == null) || $project_id === '0'){
            $where[] = array('cn_project.id','=',$project_id);
            $this->assign('project_id',$project_id);
            $query['project_id'] = $project_id;
        }
        if(!empty($nickname) && $nickname != null){
            $where[] = array('nickname_unbase','like','%'.$nickname.'%');
            $this->assign('nickname',$nickname);
            $query['nickname'] = $nickname;
        }
        if(!empty($phone) && $phone != null){
            $where[] = array('mobile','like','%'.$phone.'%');
            $this->assign('phone',$phone);
            $query['phone'] = $phone;
        }

        $playGameDataModel = Db::table('cn_play_game_ar_data');
        $limit = input('limit') == null || empty(input('limit')) ? getLimit() : input('limit');
        $list = $playGameDataModel
            ->field('cn_user.id user_id,cn_user.nickname user_nickname,cn_user.mobile user_phone,cn_user.status user_status,cn_user.avatar_url avatar_url,
            cn_play_game_ar_data.create_time create_time,cn_play_game_ar_data.id play_game_id,cn_play_game_ar_data.part part_number,
            cn_project.id project_id,cn_project.name project_name,cn_project.partner_id partner_id
            ')
            ->leftJoin('cn_user','cn_user.id = cn_play_game_ar_data.user_id')
            ->leftJoin('cn_project','cn_project.id = cn_play_game_ar_data.project')
            ->where($where)
            ->paginate($limit,false,array('query'=>$query));
        $this->assign('list',$list);
        $this->assign('left_menu_active', 'admin_users_playdata');
        return $this->fetch();
    }

    public function deletePlayData(){
        $id = input('id');
        $playGameDataModel = Db::table('cn_play_game_ar_data');
        $info = $playGameDataModel->where('id','=',$id)->delete();
        if($info){
            ajaxJsonReturn(1,'删除成功');
        }else{
            ajaxJsonReturn(-1,'删除失败');
        }
    }

    public function showdata(){
        $partner_id = input('partner_id');
        $project_id = input('project_id');
        $nickname = input('nickname');
        $phone = input('phone');
        //获取游戏类型
        $gameList = GameType::TYPE;
        $this->assign('gameList',$gameList);
        //获取所有项目
        $projectModel = new Project();
        $projectData = $projectModel->where('status','>',0)->select();
        $this->assign('projectData',$projectData);
        //获取所有激活的合作商
        $partnerModel = new Partner();
        $where = array();
        $query = array();
        //$where[] = array('status','=',1);
        $partnerList = $partnerModel->where($where)->select();
        $this->assign('partnerList',$partnerList);

        if(!(empty($partner_id) || $partner_id == null)){
            $where[] = array('cn_user.partner_id','=',$partner_id);
            $this->assign('partner_id',$partner_id);
            $query['partner_id'] = $partner_id;
        }
        if(!(empty($project_id) || $project_id == null) || $project_id === '0'){
            $where[] = array('cn_project.id','=',$project_id);
            $this->assign('project_id',$project_id);
            $query['project_id'] = $project_id;
        }
        if(!empty($nickname) && $nickname != null){
            $where[] = array('nickname_unbase','like','%'.$nickname.'%');
            $this->assign('nickname',$nickname);
            $query['nickname'] = $nickname;
        }
        if(!empty($phone) && $phone != null){
            $where[] = array('mobile','like','%'.$phone.'%');
            $this->assign('phone',$phone);
            $query['phone'] = $phone;
        }

        $playGameDataModel = Db::table('cn_play_game_ar_data');
        $limit = input('limit') == null || empty(input('limit')) ? getLimit() : input('limit');
        $list = $playGameDataModel
            ->field('cn_user.id user_id,cn_user.nickname user_nickname,cn_user.mobile user_phone,cn_user.status user_status,cn_user.avatar_url avatar_url,
            cn_play_game_ar_data.create_time create_time,cn_play_game_ar_data.id play_game_id,cn_play_game_ar_data.part part_number,
            cn_project.id project_id,cn_project.name project_name,cn_project.partner_id partner_id
            ')
            ->leftJoin('cn_user','cn_user.id = cn_play_game_ar_data.user_id')
            ->leftJoin('cn_project','cn_project.id = cn_play_game_ar_data.project')
            ->where($where)
            ->paginate($limit,false,array('query'=>$query));
        $this->assign('list',$list);
        $this->assign('left_menu_active', 'admin_users_playdata');
        return $this->fetch();
    }

}
