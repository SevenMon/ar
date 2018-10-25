<?php

namespace app\admin\controller;




use app\model\Partner;

class Partners extends Base
{

    public $left_menu_active = 'admin_partners_index';
    public $top_menu_active = 'partners';

    public function initialize()
    {
        parent::initialize();
    }

    public function index(){
        //获取数据  分页
        $partnerModel = new Partner();
        $limit = input('limit') == null || empty(input('limit')) ? getLimit() : input('limit');
        $where[] = array('status','>',0);
        $list = $partnerModel->where($where)->order('sort desc,id desc')->paginate($limit);

        $this->assign('list', $list);
        return $this->fetch();
    }

    public function addPage(){
        return $this->fetch();
    }

    public function editPage(){
        $partnerId = input('partner_id');
        $partnerModel = new Partner();
        $partnerData = $partnerModel->find($partnerId);
        if(empty($partnerData)){
            $this->error('合作商不存在！');
        }elseif($partnerData['status'] == 0){
            $this->error('合作商已删除！');
        }
        $this->assign('partnerData',$partnerData);
        return $this->fetch();
    }

    public function add(){
        $name = input('post.title');
        $phone = input('post.phone');
        $appid = input('post.appid');
        $appsecret = input('post.appsecret');
        $remark = input('post.remark');
        //$status = input('post.status');
        if(empty($name) || empty($remark) || empty($phone) || empty($appid) || empty($appsecret)){
            $this->error('名称、电话、appid、appsecret、备注不可为空！');
        }
        /*if($status == 'on'){
            $status = 1;
        }else{
            $status = 2;
        }*/
        $partnerModel = new Partner();

        //获取最大sort
        $maxSort = $partnerModel->field('max(sort) maxSort')->find();
        $maxSort = $maxSort['maxSort'];
        //添加标签
        $data = array(
            'name' => $name,
            'phone' => $phone,
            'appid' => $appid,
            'appsecret' => $appsecret,
            'sort' => ++$maxSort,
            'remark' => $remark,
            'status' => 1,
        );
        $id = $partnerModel->insertGetId($data);
        if($id >= 1){
            $this->redirect('admin/Partners/index');
        }else{
            $this->error('添加失败！');
        }
    }

    public function edit(){

        $partnerId = input('partner_id');
        $name = input('post.title');
        $phone = input('post.phone');
        $appid = input('post.appid');
        $appsecret = input('post.appsecret');
        $remark = input('post.remark');

        $partnerModel = new Partner();
        $data = $partnerModel->find($partnerId);
        if(empty($data)){
            $this->error('合作商不存在！');
        }elseif($data['status'] == 0){
            $this->error('合作商已删除！');
        }
        $status = input('post.status');
        if(empty($name) || empty($remark) || empty($phone) || empty($appid) || empty($appsecret)){
            $this->error('名称、电话、appid、appsecret、备注不可为空！');
        }
        if($status == 'on'){
            $status = 1;
        }else{
            $status = 2;
        }

        //添加标签
        $updataData = array(
            'name' => $name,
            'phone' => $phone,
            'appid' => $appid,
            'appsecret' => $appsecret,
            'remark' => $remark,
            'status' => $status,
        );
        $id = $partnerModel->where('id','=',$partnerId)->update($updataData);
        if($id >= 0){
            $this->redirect('admin/Partners/index');
        }else{
            $this->error('修改失败！');
        }
    }

    public function delete(){
        $partnerId = input('partner_id');
        $partnerModel = new Partner();
        $data = $partnerModel->find($partnerId);
        if(empty($data)){
            $this->error('合作商不存在！');
        }elseif($data['status'] == 0){
            $this->error('合作商已删除！');
        }
        $updateData['status'] = 0;
        $info = $partnerModel->where('id','=',$partnerId)->update($updateData);
        if(empty($info)){
            $this->error('删除失败！');
        }else{
            $this->redirect('admin/Partners/index');
        }
    }

    public function export(){
        $partnerModel = new Partner();
        $data = $partnerModel->where('status','>',0)->select();
        require '../extend/phpexcel/PHPExcel.php';
        $objPHPExcel  = new \PHPExcel();
        $objDrawing = new \PHPExcel_Worksheet_Drawing();
        //导出订单模板
        //定义配置
        $topNumber = 2;//表头有几行占用
        $cellKey = array(
            'A','B','C','D','E','F','G'
        );
        //写在处理的前面（了解表格基本知识，已测试）
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1','合作商id');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1','合作商名称');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1','联系方式');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1','appid');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1','appsecret');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1','状态');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1','创建时间');

        $objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(17);//所有单元格（列）默认宽度
        //$objPHPExcel->getActiveSheet()->getDefaultRowDimension(2)->setRowHeight(80);
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true)->setSize(12);
        $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true)->setSize(12);
        $objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setBold(true)->setSize(12);
        $objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setBold(true)->setSize(12);
        $objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setBold(true)->setSize(12);
        $objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setBold(true)->setSize(12);
        $objPHPExcel->getActiveSheet()->getStyle('G1')->getFont()->setBold(true)->setSize(12);


        $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('C1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('D1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('E1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('F1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('G1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $i = 2;
        foreach ($data as $value){
            $objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('B'.$i)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('C'.$i)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('D'.$i)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('E'.$i)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('F'.$i)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('G'.$i)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$i,$value['id']);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$i,$value['name']);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$i,$value['phone']);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$i,$value['appid']);
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$i,$value['appsecret']);
            $objPHPExcel->getActiveSheet()->setCellValue('F'.$i,$value['status'] == 1?'启用':'禁用');
            $objPHPExcel->getActiveSheet()->setCellValue('G'.$i,$value['create_time']);
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
}