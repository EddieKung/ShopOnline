<?php
namespace app\common\model;

use think\Model;

class Featured extends BaseModel
{
    /**
     * 根据类型来获取列表数据
     * @param $type
     */
    public function getFeaturedsByType($type) 
    {
        $data = [
            'type' => $type,
            'status' => ['neq', -1],
        ];

        $order = ['id'=>'desc'];

        $result = $this->where($data)
            ->order($order)
            ->paginate();
        return $result;
    }

    //通过状态获取首页大图数据
    public function getFeaturedBigImageByStatus($type,$status)
    {
        $data = [
            'type' => $type,
            'status' => $status,
        ];
        $order = ['id'=>'desc'];

        $result = $this->where($data)
            ->order($order)
            ->select();
        return $result;
    }
    //获取首页右边广告信息
    public function getFeaturedAdImageBytype($type,$status)
    {
        $data = [
            'type' => $type,
            'status' => $status,
        ];
        $order = ['id'=>'desc'];

        $result = $this->where($data)
            ->order($order)
            ->select();
        return $result;
    }
}