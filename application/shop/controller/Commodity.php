<?php
namespace app\shop\controller;
use think\Controller;
class Commodity extends  Base
{
    //实例化通用的model对象
    private $obj;
    public function _initialize() 
    {
        $this->obj = model("Commodity");
    }
    public function index()
    {
        $shopId = $this->getLoginUser()->shop_id;
        $CommodityData = model('Commodity')->getNormalCommoditysByshopId($shopId);
        return $this->fetch('',[
            'CommodityData' => $CommodityData,
        ]);
    }
    //添加
    public function  add() 
    {
        $shopId = $this->getLoginUser()->shop_id;
        if(request()->isPost()) {
            // 走插入逻辑
            $data = input('post.');
            //检验数据
            $validate = validate('Commodity');
            if(!$validate->scene('add')->check($data)) {
                $this->error($validate->getError());
            }
            
            $commoditys = [
                'shop_id' => $shopId,
                'name' => $data['name'],
                'image' => $data['image'],
                'classification_id' => $data['classification_id'],
                'se_classification_id' => empty($data['se_classification_id']) ? '' : implode(',', $data['se_classification_id']),
                'area_id' => $data['area_id'],                               
                'origin_price' => $data['origin_price'],
                'current_price' => $data['current_price'],
                'notes' => $data['notes'],
                'presentation' => $data['presentation'],
                'shop_account_id' => $this->getLoginUser()->id,
                'count' => $data['count'],
            ];

            $id = model('Commodity')->add($commoditys);
            if($id) {
                $this->success('添加成功', url('commodity/index'));
            }else {
                $this->error('添加失败');
            }
        }else {
            //获取一级校园的数据
            $areas = model('Area')->getNormalAreasByFatherId();
            //获取一级分类的数据
            $classifications = model('Classification')->getNormalClassificationByFatherId();
            return $this->fetch('', [
                'areas' => $areas,
                'classifications' => $classifications,
            ]);
        }
    }
    //更改状态
    public function status()
    {
        $data = input('get.');
        $res = $this->obj->save(['status'=>$data['status']],['id'=>$data['id']]);
        if($res){
            $this->success('状态更新成功');
        }
        else{
            $this->error('状态更新失败');
        } 
    }
    //下架的团购列表
    public function dellist()
    {
        $shopId = $this->getLoginUser()->shop_id;
        $dellistData = $this->obj->getDellistByshopIdStatus($shopId);
        return $this->fetch('',[
            'dellistData' => $dellistData,
        ]);
    }
    //编辑详情
    public function detail()
    {
        $id = input('get.id');
        if(empty($id)) {
            return $this->error('ID错误');
        }
        //获取一级城市的数据
        $areas = model('Area')->getNormalAreasByFatherId();
        //获取一级栏目的数据
        $classifications = model('Classification')->getNormalClassificationByFatherId();
        $commodityData = $this->obj->get($id);
        $shopId = $this->obj->get($id)->shop_id;
        return $this->fetch('',[
            'areas' => $areas,
            'classifications' => $classifications,
            'commodityData' => $commodityData,
            'shoplocations' => model('ShopLocation')->getNormalLocationByShopId($shopId),
        ]);
    }
}
