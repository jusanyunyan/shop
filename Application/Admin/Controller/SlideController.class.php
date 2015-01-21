<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 1010422715@qq.com All rights reserved.
// +----------------------------------------------------------------------
// | author 烟消云散 <1010422715@qq.com>
// +----------------------------------------------------------------------

namespace Admin\Controller;

/**
 * 后台订单控制器
  * @author 烟消云散 <1010422715@qq.com>
 */
class SlideController extends AdminController {

    /**
     * 订单管理
     * author 烟消云散 <1010422715@qq.com>
     */
    public function index(){
        /* 查询条件初始化 */
	
       $map  = array('status' => 1);
        if(IS_GET){ $title=trim(I('get.title'));
		 $map['title'] = array('like',"%{$title}%");
        $list   =   M("Slide")->where($map)->field(true)->order('id desc')->select();}
     else 
		 { 
		 $list = $this->lists('Slide', $map,'id desc');
	 }

        $this->assign('list', $list);
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        
        $this->meta_title = '优惠券管理';
        $this->display();
    }

  /* 编辑分类 */
    public function edit($id = null, $pid = 0){
        $slide = D('slide');
       

        if(IS_POST){ //提交表单
            if(false !== $slide->update()){
					$slideid=$_POST["id"];
	 $good=$_POST["goodid"];
	 $this->Addgood($slideid,$good);//传值，商品id,幻灯片id
				
                $this->success('编辑成功！', U('index'));
            } else {
                $error = $slide->getError();
                $this->error(empty($error) ? '未知错误！' : $error);
            }
        } else {
            $cate = '';
            if($pid){
                /* 获取上级分类信息 */
                $cate = $slide->info($pid, 'id,name,title,status');
                if(!($cate && 1 == $cate['status'])){
                    $this->error('指定的上级分类不存在或被禁用！');
                }
            }

            /* 获取分类信息 */
            $info = $id ? $slide->info($id) : '';

            $this->assign('info',       $info);
            $this->assign('category',   $cate);
            $this->meta_title = '编辑优惠券';
            $this->display();
        }
    }

public function Addgood($a,$b){
	 $slideid=$a;
	 $good=$b;
     $array=explode(',',$good);
	 $User =M("slideid");  
	  if( $User->where("slidepid='$slideid'")->select()){  
	   $User->where("slidepid='$slideid'")->delete();//商品列表如果存在则删除，重新循环遍历
	   
	    for($i=0;$i<count($array);$i++)
	{ 
		$goodid = $array[$i];
		$data["goodid"]=$goodid;
	$data["slidepid"]=$slideid;
        $data["create_time"]=time(); // 设置用户的创建时间
	  $User->add($data);
    };
	}
		else{//商品列表不存在，遍历添加
    for($i=0;$i<count($array);$i++)
	{ 
		$goodid = $array[$i];
		$data["goodid"]=$goodid;
	$data["slidepid"]=$slideid;
     
       $data["create_time"]= time(); // 设置用户的创建时间
	  $User->add($data);
	  
	  }
		}
  }
    /* 新增分类 */
    public function add(){
        $slide = D('slide');
        if(IS_POST){ //提交表单
				$good=$_POST["goodid"];
            if(false !== $slide->update()){
				
				  $name=$_POST["title"];
           $slideid=$this->gettuanid($name);//获取分类的id
	 $this->Addgood($slideid,$good);//传值，商品id,幻灯片id
                $this->success('新增成功！', U('index'));
            } else {
                $error = $slide->getError();
                $this->error(empty($error) ? '未知错误！' : $error);
            }
        } else {
            $cate = array();
            if($pid){
                /* 获取上级优惠券信息 */
                $cate = $slide->info($pid, 'id,title,status');
                if(!($cate && 1 == $cate['status'])){
                    $this->error('指定的上级分类不存在或被禁用！');
                }
            }

            /* 获取优惠券信息 */
$this->assign('list',$list);
            $this->assign('info',       null);
            $this->assign('category', $cate);
            $this->meta_title = '新增优惠券';
            $this->display('edit');
        }
    }


 public function getslideid($name){
       
    $map["title"]=$name;    
	$list=M('tuan')->where($map)->getField("id");
   return $list;
} 
 public function del(){
       if(IS_POST){
             $ids = I('post.id');
            $order = M("bfcoupon");
			
            if(is_array($ids)){
                             foreach($ids as $id){
		
                             $order->where("id='$id'")->delete();
						
                }
            }
           $this->success("删除成功！");
        }else{
            $id = I('get.id');
            $db = M("slide");
            $status = $db->where("id='$id'")->delete();
            if ($status){
                $this->success("删除成功！");
            }else{
                $this->error("删除失败！");
            }
        } 
    }

}