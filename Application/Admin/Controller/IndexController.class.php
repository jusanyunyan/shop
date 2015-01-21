<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Admin\Controller;
use User\Api\UserApi as UserApi;

/**
 * 后台首页控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class IndexController extends AdminController {

    /**
     * 后台首页
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function index(){
        $this->meta_title = 'Yershop管理首页';
        $damain=$_SERVER['SERVER_NAME'];
        $this->assign('data',$damain); 
	    $url="http://".$damain;
        M("config")->where('id=50')->setField('DOMAIN',$url);
		 $this->display();
    }

   public function insert(){
	if($_POST['code']){
	  $code=$_POST['code'];
     M("config")->where('id=75')->setField('SCODE',$code);
    $ycode=M("config")->where('id=75')->getField('code');
    if($ycode){
	 $data['ycode'] = $ycode;
     $this->ajaxReturn($data); 
	  }
	  else
		  {$ycode=M("config")->where('id=75')->getField('code');

	  $data['ycode'] = $ycode;
      $this->ajaxReturn($data);
	  
	  }

	}
	else



    $this->display();
	}

}
