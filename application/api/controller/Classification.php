<?php
namespace app\api\controller;
use think\Controller;
class Classification extends Controller
{
    //实例化数据模型
    private  $obj;
    public function _initialize() {
        $this->obj = model("Classification");
    }
    //获取服务分类数据
    public function getClassificationByFatherId() {
        $id = input('post.id');
        if(!intval($id)) {
            $this->error('ID不合法');
        }
        // 通过id获取二级分类数据
        $classifications = $this->obj->getNormalClassificationByFatherId($id);
        if(!$classifications) {
            return show(0,'error');
        }
        return show(1,'success', $classifications);
    }
}
