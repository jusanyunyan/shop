<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | author 烟消云散 <1010422715@qq.com>
// +----------------------------------------------------------------------

namespace Admin\Controller;

/**
 * 后台订单控制器
  * @author 烟消云散 <1010422715@qq.com>
 */
class ChangeagreeController extends AdminController {

    /**
     * 订单管理
     * author 烟消云散 <1010422715@qq.com>
     */
    public function index(){
        /* 查询条件初始化 */
	
        $map  = array('status' =>2);
        $list = $this->lists('change', $map,'id');

        $this->assign('list', $list);
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        
        $this->meta_title = '退货管理';
        $this->display();
    }

    /**
     * 新增订单
     * @author 烟消云散 <1010422715@qq.com>
     */
    public function add(){
      if(IS_POST){
            $Config = D('change');
            $data = $Config->create();
            /* 新增时间并更新时间*/
             $Config->time = NOW_TIME;

           $Config->update_time = NOW_TIME;
            if($data){
                if($Config->add()){
                    S('DB_CONFIG_DATA',null);
                    $this->success('新增成功', U('index'));
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error($Config->getError());
            }
        } else {
            $this->meta_title = '新增配置';
            $this->assign('info',null);
            $this->display('edit');
        }
    }

    /**
     * 编辑订单
     * @author 烟消云散 <1010422715@qq.com>
     */
    public function edit($id = 0){
        if(IS_POST){
            $Form = D('change');
        
            if($_POST["id"]){ 
				$id=$_POST["id"];
            
			    /*更新时间*/
            $Form->update_time = NOW_TIME;
			/* 编辑后保存编辑人*/
			$Form->assistant = $_POST["assistant"];
	
			 /* 编辑后新增系统反馈信息*/
			$Form->backinfo = $_POST["backinfo"];
          	$Form->info = $_POST["info"];
           $result=$Form->where("id='$id'")->save();
                if($result){
                    //记录行为
                    action_log('update_order', 'order', $data['id'], UID);
                    $this->success('更新成功', Cookie('__forward__'));
                } else {
                    $this->error('更新失败55'.$id);
                }
            } else {
                $this->error($Config->getError());
            }
        } else {
            $info = array();
            /* 获取数据 */
            $info = M('change')->find($id);
$detail= M('change')->where("id='$id'")->select();
$list=M('shoplist')->where("orderid='$id'")->select();

            if(false === $info){
                $this->error('获取订单信息错误');
            }
$this->assign('list', $list);
            $this->assign('detail', $detail);
			 $this->assign('info', $info);
            $this->meta_title = '编辑订单';
            $this->display();
        }
    }
 /**
     * 同意订单
     * @author 烟消云散 <1010422715@qq.com>
     */
    public function complete($id = 0){
       if(IS_POST){
            $Form = D('change');
        
            if($_POST["id"]){ 
			$id=$_POST["id"];
              
			    /*更新时间*/
            $Form->update_time = NOW_TIME;
			/* 编辑后保存编辑人*/
			$Form->assistant = $_POST["assistant"];
			 /* 编辑后新增系统反馈信息*/
			$Form->backinfo = $_POST["backinfo"];
          	$Form->info = $_POST["info"];
				$Form->status = "3";
			  $result=$Form->where("id='$id'")->save();
                if($result){
                    //记录行为
                    action_log('update_order', 'order', $data['id'], UID);
                    $this->success('更新成功', Cookie('__forward__'));
                } else {
                    $this->error('更新失败'.$id);
                }
            } else {
                $this->error($Config->getError());
            }
        } else {
            $info = array();
            /* 获取数据 */
            $info = M('change')->find($id);

            if(false === $info){
                $this->error('获取订单信息错误');
            }

			 $this->assign('info', $info);
			 
            $this->meta_title = '编辑订单';
           $this->display();
        }
    }

  
   /**
     * 删除订单
     * @author yangweijie <yangweijiester@gmail.com>
     */
    public function del(){
       if(IS_POST){
             $ids = I('post.id');
            $order = M("change");
			
            if(is_array($ids)){
                             foreach($ids as $id){
		
                             $order->where("id='$id'")->delete();
						
                }
            }
           $this->success("删除成功！");
        }else{
            $id = I('get.id');
            $db = M("change");
            $status = $db->where("id='$id'")->delete();
            if ($status){
                $this->success("删除成功！");
            }else{
                $this->error("删除失败！");
            }
        } 
    }


}