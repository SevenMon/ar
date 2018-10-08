<?php

namespace app\admin\controller;

use think\Request;
use think\Db;
class Homes extends Base {
    public $left_menu_active = 'admin_homes_index';
    public $top_menu_active = 'home';

    public function initialize() {
        
        parent::initialize();
    }

    /*
     *  轮播图
     */

    public function index() {
        $list = Db::name('first_page')->where(['type'=>1])->order('order','asc')->select();
        for($i=0;$i<count($list);$i++){
            if($list[$i]['product_flag']==1){
                $list[$i]['type_name'] = '分类：'.db::name('type')->where(['id'=>$list[$i]['product_type']])->value('name');
            }else{
                $list[$i]['type_name'] = '商品：'.db::name('product')->where(['id'=>$list[$i]['product_id']])->value('title');
            }
        }
        // 模板变量赋值
        $this->assign('list', $list);
        return $this->fetch('index');
    }
    /*
     *  轮播图添加
     */
    public function indexadd() {
        //获得产品分类
        $this->assign('tinfo',$this->getType());
        //获得产品
        $this->assign('cinfo',$this->getProduct());
        return $this->fetch('indexadd');
    }
    /*
     *  轮播图添加
     */
    public function indexedit() {
        $id = intval(input('id'));
        $info = Db::name('first_page')->where(['id'=>$id])->find();
        if(empty($info)){
            $this->redirect('admin/Homes/index');
        }
        $this->assign('info',$info);
        //获得产品分类
        $this->assign('tinfo',$this->getType($info['product_type']));
        //获得产品
        $this->assign('cinfo',$this->getProduct($info['product_id']));
        return $this->fetch('indexedit');
    }
    /*
     *  获得配型
     */
    private function getType($type=0){
        $tlist = Db::name('type')->order('parent_id','asc')->select();
        $backarray = [];
        foreach($tlist as $key=>$value){
            if($value['parent_id']==0){
                $backarray[$value['id']] = ['name'=>$value['name'],'id'=>$value['id'],'child'=>[]];
            }else{
                array_push($backarray[$value['parent_id']]['child'],$value);
            }
        }
        $str = '';
        foreach($backarray as $key=>$value){
            if($type==$value['id']){
                $str = $str.' <option selected="selected" value="'.$value['id'].'">'.$value['name'].'</option>';
            }else{
                $str = $str.' <option value="'.$value['id'].'">'.$value['name'].'</option>';
            }
            foreach($value['child'] as $ckey=>$cvalue){
                if($type==$cvalue['id']){
                    $str = $str.' <option selected="selected" value="'.$cvalue['id'].'"> ---'.$cvalue['name'].'</option>';
                }else{
                    $str = $str.' <option value="'.$cvalue['id'].'"> ---'.$cvalue['name'].'</option>';
                }                
            }
        }
        return $str;
    }
    /*
     *  添加编辑首页图爿
     */
    public function ajaxchangeindex(){

        $id = intval(input('post.id'));
        $img = input('post.img');
        $flag = intval(input('post.flag'));
        $type = intval(input('post.type'));
        $order = intval(input('post.order'));
        $select_type = intval(input('post.select_type'));
        $select_product = intval(input('post.select_product'));
        $data['order'] = $order;
        $data['product_flag'] = $type; 
        $data['product_type'] = $select_type; 
        $data['product_id'] = $select_product; 
        //添加
        if($flag==1){
            //最多5个
            $count = Db::name('first_page')->where(['type'=>1])->count();
            if($count>=5){
                ajaxJsonReturn(2, '首页轮播图不能超过5个');
            } 
            $data['type'] = 1;
            $data['img'] = $img;
            Db::name('first_page')->insert($data);
            ajaxJsonReturn(1, '成功');           
        }else{
            $count = Db::name('first_page')->where(['id'=>$id])->count();
            if($count==0){
                ajaxJsonReturn(2, '编辑失败');
            }             
            if(!empty($img)){
                $data['img'] = $img;
            }
            Db::name('first_page')->where(['id'=>$id])->update($data);
            ajaxJsonReturn(1, '成功');    
        }

        
    }
    /*
     *  添加编辑按钮2，广告A3,广告B4
     */
    public function ajaxchangefirst(){
        $catgray = intval(input('post.catgray'));
        if(!in_array($catgray,[2,3,4])){
            ajaxJsonReturn(2, '编辑失败');
        }
        $id = intval(input('post.id'));
        $img = input('post.img');
        $type = intval(input('post.type'));
        $order = intval(input('post.order'));
        $select_type = intval(input('post.select_type'));
        $select_product = intval(input('post.select_product'));
        $data['order'] = $order;
        $data['product_flag'] = $type; 
        $data['product_type'] = $select_type; 
        $data['product_id'] = $select_product; 
        $data['type'] = $catgray;
        //添加
        if($id==0){
            //最多5个
            $count = Db::name('first_page')->where(['type'=>$catgray])->count();
            if($catgray==2){
                $data['content'] = input('post.content');
                if($count>=4){
                    ajaxJsonReturn(2, '首页按钮不能超过4个');
                }                 
            }elseif($catgray==3){
                if($count>=2){
                    ajaxJsonReturn(2, '广告区域A不能超过2个');
                }                
            }elseif($catgray==4){
                if($count>=4){
                    ajaxJsonReturn(2, '广告区域B不能超过4个');
                }                
            }

            
            $data['img'] = $img;
            Db::name('first_page')->insert($data);
            ajaxJsonReturn(1, '成功');           
        }else{
            $count = Db::name('first_page')->where(['id'=>$id,'type'=>$catgray])->count();
            if($count==0){
                ajaxJsonReturn(2, '编辑失败');
            }  
            if($catgray==2){
                $data['content'] = input('post.content');                
            }          
            if(!empty($img)){
                $data['img'] = $img;
            }
            Db::name('first_page')->where(['id'=>$id])->update($data);
            ajaxJsonReturn(1, '成功');    
        }        
    }
    /*
     *  首页按钮配置
     */
    public function button(){
        $list = Db::name('first_page')->where(['type'=>2])->order('order','asc')->select();
        for($i=0;$i<count($list);$i++){
            if($list[$i]['product_flag']==1){
                $list[$i]['type_name'] = '分类：'.db::name('type')->where(['id'=>$list[$i]['product_type']])->value('name');
            }else{
                $list[$i]['type_name'] = '商品：'.db::name('product')->where(['id'=>$list[$i]['product_id']])->value('title');
            }
        }
        // 模板变量赋值
        $this->assign('left_menu_active', 'admin_homes_button');
        $this->assign('list', $list);
        return $this->fetch();
    }
    /*
     *  广告区域A
     */
    public function adareaa(){
        $list = Db::name('first_page')->where(['type'=>3])->order('order','asc')->select();
        for($i=0;$i<count($list);$i++){
            if($list[$i]['product_flag']==1){
                $list[$i]['type_name'] = '分类：'.db::name('type')->where(['id'=>$list[$i]['product_type']])->value('name');
            }else{
                $list[$i]['type_name'] = '商品：'.db::name('product')->where(['id'=>$list[$i]['product_id']])->value('title');
            }
        }
        // 模板变量赋值
        $this->assign('left_menu_active', 'admin_homes_adareaa');
        $this->assign('list', $list);
        return $this->fetch();        
    }
    /*
     *  广告区域B
     */
    public function adareab(){
        $list = Db::name('first_page')->where(['type'=>4])->order('order','asc')->select();
        for($i=0;$i<count($list);$i++){
            if($list[$i]['product_flag']==1){
                $list[$i]['type_name'] = '分类：'.db::name('type')->where(['id'=>$list[$i]['product_type']])->value('name');
            }else{
                $list[$i]['type_name'] = '商品：'.db::name('product')->where(['id'=>$list[$i]['product_id']])->value('title');
            }
        }
        // 模板变量赋值
        $this->assign('left_menu_active', 'admin_homes_adareab');
        $this->assign('list', $list);
        return $this->fetch();        
    }
    /*
     *  编辑按钮
     */
    public function buttonedit(){
        $id = intval(input('id'));
        $info = Db::name('first_page')->where(['id'=>$id])->find();
        if(empty($info)){
            $info['id'] = 0;
            $info['product_type'] = 0;
            $info['product_id'] = 0;
        }
        $this->assign('info',$info);
        //获得产品分类
        $this->assign('tinfo',$this->getType($info['product_type']));
        //获得产品
        $this->assign('cinfo',$this->getProduct($info['product_id']));        
        $this->assign('left_menu_active', 'admin_homes_button');
        return $this->fetch();        
    }
    /*
     *  编辑广告区域A
     */
    public function adareaaedit(){
        $id = intval(input('id'));
        $info = Db::name('first_page')->where(['id'=>$id])->find();
        if(empty($info)){
            $info['id'] = 0;
            $info['product_type'] = 0;
            $info['product_id'] = 0;
        }
        $this->assign('info',$info);
        //获得产品分类
        $this->assign('tinfo',$this->getType($info['product_type']));
        //获得产品
        $this->assign('cinfo',$this->getProduct($info['product_id']));        
        $this->assign('left_menu_active', 'admin_homes_adareaa');
        return $this->fetch();        
    }
    /*
     *  编辑广告区域A
     */
    public function adareabedit(){
        $id = intval(input('id'));
        $info = Db::name('first_page')->where(['id'=>$id])->find();
        if(empty($info)){
            $info['id'] = 0;
            $info['product_type'] = 0;
            $info['product_id'] = 0;
        }
        $this->assign('info',$info);
        //获得产品分类
        $this->assign('tinfo',$this->getType($info['product_type']));
        //获得产品
        $this->assign('cinfo',$this->getProduct($info['product_id']));        
        $this->assign('left_menu_active', 'admin_homes_adareab');
        return $this->fetch();        
    }
    /*
     *  页脚自定义
     */
    public function foot(){
        $info = Db::name('first_page')->where(['type'=>6])->find();
        $this->assign('info',$info);      
        $this->assign('left_menu_active', 'admin_homes_foot');
        return $this->fetch();        
    }
    /*
     *  我的配置
     */
    public function myset(){
        $info = Db::name('first_page')->where(['type'=>7])->find();
        $this->assign('info',$info);      
        $this->assign('left_menu_active', 'admin_homes_myset');
        return $this->fetch();        
    }
    /*
     *  我的配置
     */
    public function updatemyset(){
        $info = Db::name('first_page')->where(['type'=>7])->find();
        if(empty($info)){
           Db::name('first_page')->insert(['type'=>7,'content'=>input('post.content'),'des_content'=>$_POST['editor']]);
        }else{
           Db::name('first_page')->where(['type'=>7])->update(['content'=>input('post.content'),'des_content'=>$_POST['editor']]);
        }
        ajaxJsonReturn(1, '成功');   
    }
    /*
     *  添加编辑首页图爿
     */
    public function ajaxchangefoot(){

        $img = input('post.img');
        $content = input('post.content');

        $data['content'] = $content; 
        $data['type'] = 6; 
        $count = Db::name('first_page')->where(['type'=>6])->count();
        //添加
        if($count==0){
            $data['img'] = $img;
            Db::name('first_page')->insert($data);
            ajaxJsonReturn(1, '成功');           
        }else{            
            if(!empty($img)){
                $data['img'] = $img;
            }
            Db::name('first_page')->where(['type'=>6])->update($data);
            ajaxJsonReturn(1, '成功');    
        }

        
    }
    /*
     *  推荐
     */
    public function recommend(){
        $list = Db::name('first_page')->where(['type'=>5,'parent_id'=>0])->order('order','asc')->select();
        for($i=0;$i<count($list);$i++){
            $list[$i]['type_name'] = '分类：'.db::name('type')->where(['id'=>$list[$i]['product_type']])->value('name');
        }
        $this->assign('list',$list);
        $this->assign('left_menu_active', 'admin_homes_recommend');
        return $this->fetch();  
    }
    /*
     *  推荐添加
     */
    public function recommendadd(){
        $tlist = Db::name('type')->where(['parent_id'=>0])->order('id','asc')->select();
        $this->assign('tlist',$tlist);
        $this->assign('left_menu_active', 'admin_homes_recommend');
        return $this->fetch();         
    }
    /*
     *  推荐编辑
     */
    public function recommendedit(){
        $id = intval(input('id'));
        $info = Db::name('first_page')->where(['id'=>$id])->find();
        if(empty($info)){
            $this->redirect('admin/Homes/recommend');
        }
        $this->assign('info',$info);       
        $tlist = Db::name('type')->where(['parent_id'=>0])->order('id','asc')->select();
        $this->assign('tlist',$tlist);
        $this->assign('left_menu_active', 'admin_homes_recommend');
        return $this->fetch();         
    }
    /*
     *  设置推荐分类
     */
    public function recommendtype(){
        $id = intval(input('id'));
        $info = Db::name('first_page')->where(['id'=>$id,'type'=>5,'parent_id'=>0])->find();
        if(empty($info)){
            $this->redirect('admin/Homes/recommend');
        }        
        $this->assign('info',$info);
        $list = Db::name('first_page')->where(['type'=>5,'parent_id'=>$id])->order('order','ASC')->select();
        for($i=0;$i<count($list);$i++){
            if(!empty($list[$i]['product_type'])){
                $list[$i]['type_name'] = '分类：'.db::name('type')->where(['id'=>$list[$i]['product_type']])->value('name');    
            }else{
                $list[$i]['type_name'] = '无';
            }
            $list[$i]['product_name'] = "左侧商品:".db::name('product')->where(['id'=>$list[$i]['product_id']])->value('title');
            $list[$i]['product_name1'] = "右侧商品:".db::name('product')->where(['id'=>$list[$i]['product_id1']])->value('title');
        }        
        
        $this->assign('list',$list);
        $tlist = Db::name('type')->where(['parent_id'=>$info['product_type']])->order('id','asc')->select();
        $this->assign('tlist',$tlist);
        $this->assign('left_menu_active', 'admin_homes_recommend');
        return $this->fetch();          
    }
    /*
     *  推荐编辑数据库
     */
    public function ajaxchangerecommend(){

        $id = intval(input('post.id'));
        $img = input('post.img');
        $title = input('post.title');
        $order = intval(input('post.order'));
        $select_type = intval(input('post.select_type'));
        $data['order'] = $order;
        $data['group_name'] = $title; 
        $data['product_type'] = $select_type; 
        $data['type'] = 5;
        //添加
        if($id==0){
            
            $data['img'] = $img;
            Db::name('first_page')->insert($data);
            ajaxJsonReturn(1, '成功');           
        }else{
            $count = Db::name('first_page')->where(['id'=>$id,'type'=>5])->count();
            if($count==0){
                ajaxJsonReturn(2, '编辑失败');
            }             
            if(!empty($img)){
                $data['img'] = $img;
            }
            Db::name('first_page')->where(['id'=>$id])->update($data);
            ajaxJsonReturn(1, '成功');    
        }         
    }
    /*
     *  根据分类获得产品
     */
    public  function ajaxgetproduct(){
        $id = intval(input('post.id'));
        $typeid = intval(input('post.typeid')); 
        $where['typeo_id'] = $id;
        if(!empty($typeid)){
            $where['typet_id'] = $typeid;
        }
        $where['status'] = 1;
        $list = Db::name('product')->where($where)->field('id,title')->order('id','ASC')->select();
        $str = '<option value="0">请选择商品</option>';
        foreach($list as $key=>$value){
            $str = $str.' <option value="'.$value['id'].'">'.$value['title'].'</option>';               
        } 
        ajaxJsonReturn(1, '成功',$str);       
    }
    /*
     *  根据分类获得产品
     */
    public function ajaxchangerecommendtype(){
        $id = intval(input('post.id'));
        $editid = intval(input('post.editid'));
        $info = Db::name('first_page')->where(['id'=>$id,'type'=>5,'parent_id'=>0])->find();
        if(empty($info)){
            ajaxJsonReturn(2, '编辑失败');
        }  
        $order = intval(input('post.order'));
        $content = input('post.content');
        $leftproduct = intval(input('post.leftproduct')); 
        $rightproduct = intval(input('post.rightproduct')); 
        $select_type = intval(input('post.select_type'));
        
        if(empty($leftproduct) || empty($rightproduct)){
            ajaxJsonReturn(2, '产品必须选择');            
        }
        $data['order'] = $order;
        $data['content'] = $content; 
        $data['parent_id'] = $id;
        $data['product_type'] = $select_type;
        $data['type'] = 5;
        $data['product_id'] = $leftproduct;
        $data['product_id1'] = $rightproduct;
        //添加
        if($editid==0){
            $count = Db::name('first_page')->where(['parent_id'=>$id,'type'=>5])->count();
            if($count<5){
                Db::name('first_page')->insert($data);
                ajaxJsonReturn(1, '成功');                
            }else{
                ajaxJsonReturn(2, '最多添加5个');
            }
           
        }else{
            $count = Db::name('first_page')->where(['id'=>$editid,'parent_id'=>$id,'type'=>5])->count();
            if($count==0){
                ajaxJsonReturn(2, '编辑失败');
            }             
            Db::name('first_page')->where(['id'=>$editid])->update($data);
            ajaxJsonReturn(1, '成功');    
        }         
    }
    /*
     *  删除
     */
    public function  ajaxdelete(){
        $id = intval(input('post.id'));
        if($id<1){
            ajaxJsonReturn(2, '删除失败');
        }
        $info = db::name('first_page')->where(['id'=>$id])->find();
        if(empty($info)){
            ajaxJsonReturn(1, '成功');
        }else{
            if($info['type']==5){
                db::name('first_page')->where(['parent_id'=>$id])->delete();
            }
        }
        db::name('first_page')->where(['id'=>$id])->delete();
        ajaxJsonReturn(1, '成功');    
    }
    /*
     *  获得产品
     */
    private function getProduct($type=0){
        $list = Db::query("SELECT title,`status`,id FROM cn_product WHERE `status`=1 ORDER BY id ASC");
        $str = '';
        foreach($list as $key=>$value){
            if($type==$value['id']){
                $str = $str.' <option selected="selected" value="'.$value['id'].'">'.$value['title'].'</option>';
            }else{
                $str = $str.' <option value="'.$value['id'].'">'.$value['title'].'</option>';
            }            
        }
        return $str;
    }    
}
