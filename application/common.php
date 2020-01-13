<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
function status($status) 
{
    if($status==1){
        $str = "<span class='label label-success radius'>正常</span>";

    }elseif($status==0)
    {
        $str = "<span class='label label-danger radius'>待审</span>";
    }elseif($status==2)
    {
        $str = "<span class='label label-danger radius'>不通过</span>";
    }elseif($status==3)
    {
        $str = "<span class='label label-danger radius'>已下架</span>";
    }else
    {
        $str = "<span class='label label-danger radius'>删除</span>";
    }
    return $str;
}

function use_status($use_status) 
{
    if($use_status==1){
        $str = "<span class='label label-success radius'>已发送用户</span>";

    }elseif($use_status==2)
    {
        $str = "<span class='label label-danger radius'>已使用</span>";
    }elseif($use_status==3)
    {
        $str = "<span class='label label-danger radius'>警告</span>";
    }else
    {
        $str = "<span class='label label-danger radius'>未发送用户</span>";
    }
    return $str;
}

function pay_status($pay_status) 
{
    if($pay_status==1){
        $str = "<span class='label label-success radius'>支付成功</span>";

    }elseif($pay_status==0)
    {
        $str = "<span class='label label-danger radius'>尚未支付</span>";
    }else
    {
        $str = "<span class='label label-danger radius'>支付失败</span>";
    }
    return $str;
}

function is_main($is_main) 
{
    if($is_main==1){
        $str = "<span class='label label-success radius'>总店</span>";

    }else
    {
        $str = "<span class='label label-danger radius'>分店</span>";
    }
    return $str;
}

function doCurl($url, $type=0, $data=[]) {
    $ch = curl_init(); // 初始化
    // 设置选项
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER,0);

    if($type == 1) {
        // post
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }

    //执行并获取内容
    $output = curl_exec($ch);
    // 释放curl句柄
    curl_close($ch);
    return $output;
}
//商户入驻文案
function bisRegister($status)
{   
    if ($status == 1) {
        $str = "入驻申请成功！";
    }elseif ($status == 0) {
        $str = "待审核，审核后平台方会发送邮件通知，请关注邮件！";
    }elseif ($status == 2) {
        $str = "非常抱歉，您提交的材料不符合条件，请重新提交！";
    }else {
        $str = "该申请已被删除！";
    }
    return $str;
}

function getSeAreaName($path)
{
    if(empty($path)) {
        return '';
    }
    if(preg_match('/,/', $path)) {
        $areaPath = explode(',', $path);
        $areaId = $areaPath[1];
    }else {
        $areaId = $path;
    }

    $area = model('Area')->get($areaId);
    return $area->name;
}

function getSeProvincesName($path)
{
    if(empty($path)) {
        return '';
    }else {
        $areaId = $path;
    }
    $areaId = model('Area')->get($areaId)->parent_id;
    $province = model('Area')->get($areaId);
    return $province->name;
}

function getSeClassificationName($path)
{
    if(empty($path)) {
        return '';
    }
    if(preg_match('/,/', $path)) {
        $classificationPath = explode(',', $path);
        $classificationId = $classificationPath[1];
    }else {
        $classificationId = $path;
    }

    $classification = model('Classification')->get($classificationId);
    return $classification->name;
}

/**
 * 通用的分页样式
 * @param $obj
 */
function pagination($obj) {
    if(!$obj) {
        return '';
    }
    // 优化的方案
    $params = request()->param();
    return '<div class="cl pd-5 bg-1 bk-gray mt-20 tp5-o2o">'.$obj->appends($params)->render().'</div>';
}

function countLocation($ids) 
{
    if(!$ids) {
        return 1;
    }

    if(preg_match('/,/', $ids)) {
        $arr = explode(',', $ids);
        return count($arr);
    }

}

// 设置订单号
function setOrderSn() {
    list($t1, $t2) = explode(' ', microtime());
    $t3 = explode('.', $t1*10000);
    return $t2.$t3[0].(rand(10000, 99999));
}

//生产消费卷SN号
function setcouponsSn() {
    list($t1, $t2) = explode(' ', microtime());
    $t3 = explode('.', $t1*10);
    return $t2.$t3[0].(rand(10, 999));
}


function modify() {
    $id = input('get.id', 0,'intval');
    //获取相关信息
    $OrderData = model('Order')->get($id);
    $UserData = model('User')->get($OrderData->user_id);
    $DealData = $this->obj->get($OrderData->deal_id);
    $CouponsData = model('Coupons')->getCouponsByDealIdUserIdOrderId($id, $OrderData->user_id, $OrderData->deal_id);
    return $this->fetch('',[
        'OrderData' => $OrderData,
        'DealData' => $DealData,
        'UserData' => $UserData,
        'CouponsData' => $CouponsData,
    ]);
}