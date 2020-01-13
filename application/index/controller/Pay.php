<?php
namespace app\index\controller;
use think\Controller;
use wxpay\database\WxPayUnifiedOrder;
use wxpay\NativePay;
use wxpay\WxPayConfig;
use wxpay\WxPayApi;
use wxpay\WxPayNotify;
use wxpay\PayNotifyCallBack;

class Pay extends Base
{
    public function index() {
        if(!$this->getLoginUser()) {
            $this->error('请登录', 'user/login');
        }
        $orderId = input('get.id', 0, 'intval');
        if(empty($orderId)) {
            $this->error('请求不合法');
        }

        $order = model('Order')->get($orderId);
        if(empty($order) || $order->status != 1 || $order->pay_status !=0 ) {
            $this->error('无法进行该项操作');
        }
        // 严格判定 订单是否 是用户 本人
        if($order->username != $this->getLoginUser()->username) {
           $this->error('不是你的订单！出错！');
        }
        $deal = model('Deal')->get($order->deal_id);
        //生成消费卷兑换码
        $couponsSn = setcouponsSn();
        //更新订单表、商品表数据
        $orderRes = model('Order')->updateOrderByCouponsSn($couponsSn, $orderId);

        model('Deal')->updateBuyCountById($order->deal_id, $order->deal_count);
 
        //消费券生成
        $coupons = [
            'sn' => $couponsSn,
            'password' => rand(10000, 99999),
            'user_id' => $order->user_id,
            'deal_id' => $order->deal_id,
            'order_id' => $order->id,
        ];
        model('Coupons')->add($coupons);
        
        // 发送邮件 给用户 
        $title = "GWB团购网购买通知";
        $content = "您在GWB团购网上购买的商品的兑换码为：".$coupons['sn']."，兑换密码为：".$coupons['password'];
        \phpmailer\Email::send($User = $this->getLoginUser()->email,$title, $content);//线上关闭 发送邮件服务

        header("refresh:10;url=paysuccess");

        return $this->fetch('', [
            'deal' => $deal,
            'order' => $order,
        ]);
    }

    public function paysuccess()
    {

        if(!$this->getLoginUser()) {
            $this->error('请登录', 'user/login');
        }

        return $this->fetch();
        
    }

}
