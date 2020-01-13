<?php
namespace app\common\model;
use think\Model;

class ShopAccount extends BaseModel
{
    public function updateById($data, $id)
    {
        //allowField过滤data数组中非数据表中的数据
        return $this->allowField(true)->save($data, ['id'=>$id]);
    }
    public function getAccountByShopId($userId)
    {
        $data = [
            'id'=>$userId,
        ];
        $result = $this->where($data)->select();
        return $result[0];
    }
}
