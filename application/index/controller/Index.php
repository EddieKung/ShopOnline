<?php
namespace app\index\controller;
use think\Controller;

class Index extends Base
{
    public function index()
    {
        $bisData = [];

        //获取首页大图相关数据
        $bigImageData = model('Featured')->getFeaturedBigImageByStatus(0, 1);
        //获取右边广告数据
        $adImageData = model('Featured')->getFeaturedAdImageBytype(1,1);
        //获取商品分类 数据-美食 推荐的数据
        $foodDatas = model('Deal')->getNormalDealByCategoryCityId(1, $this->city->id);
        //获取商品分类 数据-丽人 推荐的数据
        $liRenData = model('Deal')->getNormalDealByCategoryCityId(6, $this->city->id);
        //获取商品分类 数据-休闲 推荐的数据

        //获取商品分类 数据-生活服务 推荐的数据

        // 获取4个子分类
        $meishicates = model('Category')->getNormalRecommendCategoryByParentId(1, 4);
        return $this->fetch('',[
            'bigImageData' => $bigImageData,
            'adImageData' => $adImageData,
            'foodDatas' => $foodDatas,
            'meishicates' => $meishicates,
            'controller' => 'ms',
        ]);
    }
}
