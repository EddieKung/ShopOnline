<?php
namespace app\common\model;

use think\Model;

class Order extends Model
{
    protected  $autoWriteTimestamp = true;
    public function add($data) {
        $data['status'] = 1;
        //$data['create_time'] = time();
        $this->save($data);
        return $this->id;

    }
    public function getOrderDataByStatus()
    {
        $data = [
            'status' => 1,
		];
		$order = ['status'=>'desc'];
		$result = $this->where($data)
			->order($order)
            ->select();
		return $result;
    }
    //更新订单表
    public function updateOrderByCouponsSn($couponsSn, $orderId)
    { 
        $data['transaction_id'] = $couponsSn;           
        $data['pay_status'] = 1;          
        $data['pay_time'] = time();       

        return $this->allowField(true)
            ->save($data, ['id' => $orderId]);
    }
    //根据dealid获取订单信息
    public function getOrderByDealId($DealId)
    {
        $data = [
            'deal_id' => $DealId,
            'status' => 1,
		];
		$order = ['deal_id'=>'desc'];
		$result = $this->where($data)
			->order($order)
            ->paginate();
		return $result;
    }
    //根据dealid获取被删除的订单信息
    public function getDelOrderByDealId($DealId)
    {
        $data = [
            'deal_id' => $DealId,
            'status' => 4,
		];
		$order = ['deal_id'=>'desc'];
		$result = $this->where($data)
			->order($order)
            ->paginate();
		return $result;
    }
}