<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

/**
 * 前台公共库文件
 * 主要定义前台公共函数库
 */

/**
 * 检测验证码
 * @param  integer $id 验证码ID
 * @return boolean     检测结果
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function check_verify($code, $id = 1){
	$verify = new \Think\Verify();
	return $verify->check($code, $id);
}

/**
 * 获取列表总行数
 * @param  string  $category 分类ID
 * @param  integer $status   数据状态
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function get_list_count($category, $status = 1){
    static $count;
    if(!isset($count[$category])){
        $count[$category] = D('Document')->listCount($category, $status);
    }
    return $count[$category];
}

/**
 * 获取段落总数
 * @param  string $id 文档ID
 * @return integer    段落总数
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function get_part_count($id){
    static $count;
    if(!isset($count[$id])){
        $count[$id] = D('Document')->partCount($id);
    }
    return $count[$id];
}
function get_tuan_count($id){
 $number=M('Tuanid')->where("tuanpid='$id'")->count();
    return  $number;
}
    /**
     * 邮件发送函数
     */
        function sendMail($to, $title, $content) {
         
            Vendor('PHPMailer.PHPMailer');     
            $mail = new \vendor\PHPMailer\PHPMailer(); //实例化
            $mail->IsSMTP(); // 启用SMTP
            $mail->Host=C('MAIL_HOST'); //smtp服务器的名称（这里以QQ邮箱为例）
            $mail->SMTPAuth = true; //启用smtp认证
            $mail->Username = C('MAIL_USERNAME'); //你的邮箱名
            $mail->Password = C('MAIL_PASSWORD') ; //邮箱密码
            $mail->From = C('MAIL_FROM'); //发件人地址（也就是你的邮箱地址）
            $mail->FromName = C('MAIL_FROMNAME'); //发件人姓名
            $mail->AddAddress($to,"尊敬的客户");
            $mail->WordWrap = 50; //设置每行字符长度
            $mail->IsHTML(TRUE); // 是否HTML格式邮件
            $mail->CharSet='utf-8'; //设置邮件编码
            $mail->Subject =$title; //邮件主题
            $mail->Body = $content; //邮件内容
            $mail->AltBody = "这是一个纯文本的身体在非营利的HTML电子邮件客户端"; //邮件正文不支持HTML的备用显示
            return($mail->Send());
        }
//根据订单编码，获取会员邮箱
 function get_email($id){
	 
    $uid= M('order')->where("orderid='$id'")->getField("uid");
	$row = M('ucenter_member')->where("id='$uid'")->getField("email");
    return $row;
}
/**
 * 获取导航URL
 * @param  string $url 导航URL
 * @return string      解析或的url
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function get_nav_url($url){
    switch ($url) {
        case 'http://' === substr($url, 0, 7):
        case '#' === substr($url, 0, 1):
            break;        
        default:
            $url = U($url);
            break;
    }
    return $url;
}

/**
 * 获取首页幻灯片
 * @param  string $url 导航URL
 * @return string      解析或的url
 * @author 烟消云散 <1010422715@qq.com>
 */
function get_slide(){
     $slide=M('slide');
    $slidelist=$slide->where('status=1')->select();
    return  $slidelist;
}

//在线交易订单支付处理函数
 //函数功能：根据支付接口传回的数据判断该订单是否已经支付成功；
 //返回值：如果订单已经成功支付，返回true，否则返回false；
 function checkorderstatus($ordid){
    $Ord=M('Orderlist');
    $ordstatus=$Ord->where('ordid='.$ordid)->getField('ordstatus');
    if($ordstatus==1){
        return true;
    }else{
        return false;    
    }
 }
//处理订单函数
 //更新订单状态，写入订单支付后返回的数据
 function orderhandle($parameter){
    $ordid=$parameter['out_trade_no'];//商户网站订单系统中唯一订单号
    $data['payment_trade_no']      =$parameter['trade_no']; //支付宝交易号
    $data['payment_trade_status']  =$parameter['trade_status'];
    $data['payment_notify_id']     =$parameter['notify_id'];//通知校验ID。
    $data['payment_notify_time']   =$parameter['notify_time'];
    $data['payment_buyer_email']   =$parameter['buyer_email']; //买家支付宝帐号；
    $data['ordstatus']             =1;
    $Ord=M('Orderlist');
    $Ord->where('ordid='.$ordid)->save($data);
	$data = array('status'=>'1','ispay'=>'2');//设置订单为已经支付,状态为已提交
	M('order')->where('orderid='.$ordid)->setField($data);
 } 
