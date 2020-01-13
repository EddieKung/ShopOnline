<?php
namespace app\common\model;
use think\Model;

class Classification extends Model
{
    protected $autoWriteTimestamp = true;//tp5带有的时间创建
    public function add($data)
    {
        $data['status'] = 1;
        return $this->save($data);
    }
    //获取正常的一级分类数据
    public function getNormalFirstClassification()
    {
        $data = [
            'status' => 1,
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
    public function getFirstClassifications($fatherId = 0)
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
    //通过fatherId获取分类一级分类
    public function getNormalClassificationByFatherId($fatherId=0) 
    {
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
    //首页分类获取
    public function getNormalRecommendClassificationByFatherId($id=0, $limit=5) 
    {
        $data = [
            'father_id' => $id,
            'status' => 1,
        ];

        $order = [
            'listorder' => 'desc',
            'id' => 'desc',
        ];

        $result = $this->where($data)
            ->order($order);
        if($limit) {
            $result = $result->limit($limit);
        }

        return $result->select();

    }
    //首页二级分类获取
    public function getNormalClassificationIdFatherId($ids) 
    {
        $data = [
            'father_id' => ['in', implode(',', $ids)],
            'status' => 1,
        ];

        $order = [
            'listorder' => 'desc',
            'id' => 'desc',
        ];

        $result = $this->where($data)
            ->order($order)
            ->select();

        return $result;
    }
}
