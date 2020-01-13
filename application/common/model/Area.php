<?php
namespace app\common\model;
use think\Model;

class Area extends Model
{
    protected $autoWriteTimestamp = true;//tp5带有的时间创建
    public function add($data)
    {
        $data['status'] = 1;
        return $this->save($data);
    }
    //获取正常的一级分类数据
    public function getNormalFirstArea()
    {
        $data = [
            'status' => 1,//
            'father_id' => 0,
        ];
        $order = [
            'id' => 'desc',//排序--倒序
        ];

        return $this->where($data)
            ->order($order)
            ->select();
    }
    //获取一级分类数据
    public function getFirstAreas($fatherId = 0)
    {
        $data = [
            'father_id' => $fatherId,
            'status' => ['neq',-1],
        ];
        $order = [
            'listorder' => 'desc',
            'id' => 'desc',//排序--倒序
        ];

        $result = $this->where($data)
            ->order($order)
            ->paginate();

        return $result;
    }
    
    public function getNormalAreasByFatherId($fatherId=0) {
        $data = [
            'status' => 1,
            'father_id' => $fatherId,
        ];
        $order = [
            'id' => 'desc',
        ];
        return $this->where($data)
            ->order($order)
            ->select();
    }
    //在deal中获取城市列表
    public function getNormalAreas() {
        $data = [
            'status' => 1,
            'father_id' => ['gt', 0],
        ];

        $order = ['id'=>'desc'];

        return $this->where($data)
            ->order($order)
            ->select();

    }
}
