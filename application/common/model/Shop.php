<?php
namespace app\common\model;
use think\Model;

class Shop extends BaseModel
{
    //通过状态获取商家数据
    public function getShopByStatus($status=0) {
        $order = [
            'id' => 'desc',
        ];

        $data = [
            'status' => $status,
        ];
        $result = $this->where($data)
            ->order($order)
            ->paginate(6);
        return $result;
    }

    //通过商户ID获取商家信息
    public function getShopById($shopId)
    {
        $data = [
            'id' => $shopId,
        ];

        $result = $this->where($data)
            ->order('id', 'desc')
            ->select();
        return $result;
    }
}
