<?php
/** 
*basemdoel公共的model 层
*/
namespace app\common\model;
use think\Model;

class BaseModel extends Model
{
    protected $autoWriteTimestamp = true;//tp5带有的时间创建
    public function add($data)
    {
        $data['status'] = 0;
        $this->save($data);
        return $this->id;
    }

    public function updateById($data, $id)
    {
        return $this->allowField(true)->save($data, ['id'=>$id]);
    }
}