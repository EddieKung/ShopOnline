<?php
namespace app\api\controller;
use think\Controller;
class Area extends Controller
{
    //实例化数据模型
    private  $obj;
    public function _initialize() {
        $this->obj = model("Area");
    }
    //获取区域分类数据
    public function getAreasByFatherId() {
        $id = input('post.id');
        if(!$id) {
            $this->error('ID不合法');
        }
        //通过id获取二级分类数据
        $citys = $this->obj->getNormalAreasByFatherId($id);
        if(!$citys) {
            return show(0,'error');
        }
        return show(1,'success', $citys);
    }
}
