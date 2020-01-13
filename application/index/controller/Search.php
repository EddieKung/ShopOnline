<?php
namespace app\index\controller;
use think\Controller;

class Search extends Base
{
    public function index()
    {
        //获取查询关键字
        $keywords = input('get.name');
        if($keywords){
            $searchres = model('Deal')->getDealDataByKeywords($keywords);
        }else {
            $this->error('请输入商品关键字！！！');
        }
        cookie('keyword', $keywords, 180);
        /**
         * 根据关键字查询后的结果进行二次查询
         * 
         */
        $firstCatIds = [];
        // 一级栏目获取
        $categorys = model("Category")->getNormalCategoryByParentId();
        foreach($categorys as $category) {
            $firstCatIds[] = $category->id;
        }
        $id = input('id', 0, 'intval');
        $data = [];
        // id=0 一级分类 二级分类
        if(in_array($id, $firstCatIds)) { // 一级分类
            // todo
            $categoryParentId = $id;
            $data['category_id'] = $id;
        }elseif($id) { // 二级分类
            // 获取二级分类的数据
            $category = model('Category')->get($id);
            if(!$category || $category->status !=1) {
                $this->error('数据不合法');
            }
            $categoryParentId = $category->parent_id;
            $data['se_category_id'] = $id;
        }else{ // 0
            $categoryParentId = 0;
        }
        $sedcategorys = [];
        //获取父类下的所有 子分类
        if($categoryParentId) {
            $sedcategorys = model('Category')->getNormalCategoryByParentId($categoryParentId);
        }
        //定义一个空的数组
        $orders = [];
        // 排序数据获取的逻辑
        $order_sales = input('order_sales','');
        $order_price = input('order_price','');
        $order_time = input('order_time','');
        if(!empty($order_sales)) {
            $orderflag = 'order_sales';
            $orders['order_sales'] = $order_sales;
        }elseif(!empty($order_price)) {
            $orderflag = 'order_price';
            $orders['order_price'] = $order_price;
        }elseif(!empty($order_time)) {
            $orderflag = 'order_time';
            $orders['order_time'] = $order_time;
        }else{
            $orderflag = '';
        }
        return $this->fetch('',[
            'searchres' => $searchres,
            'categorys' => $categorys,
            'sedcategorys' => $sedcategorys,
            'id' => $id,
            'categoryParentId' => $categoryParentId,
            'orderflag' => $orderflag,
        ]);
    }
    //搜索结果数据二次检索处理
    public function deal()
    {
        $keywords = cookie('keyword');
        /**
         * 根据关键字查询后的结果进行二次查询
         * 
         */
        $firstCatIds = [];
        // 一级栏目获取
        $categorys = model("Category")->getNormalCategoryByParentId();
        foreach($categorys as $category) {
            $firstCatIds[] = $category->id;
        }
        $id = input('id', 0, 'intval');
        $data = [];
        // id=0 一级分类 二级分类
        if(in_array($id, $firstCatIds)) { // 一级分类
            // todo
            $categoryParentId = $id;
            $data['category_id'] = $id;
        }elseif($id) { // 二级分类
            // 获取二级分类的数据
            $category = model('Category')->get($id);
            if(!$category || $category->status !=1) {
                $this->error('数据不合法');
            }
            $categoryParentId = $category->parent_id;
            $data['se_category_id'] = $id;
        }else{ // 0
            $categoryParentId = 0;
        }
        $sedcategorys = [];
        //获取父类下的所有 子分类
        if($categoryParentId) {
            $sedcategorys = model('Category')->getNormalCategoryByParentId($categoryParentId);
        }
        //定义一个空的数组
        $orders = [];
        // 排序数据获取的逻辑
        $order_sales = input('order_sales','');
        $order_price = input('order_price','');
        $order_time = input('order_time','');
        if(!empty($order_sales)) {
            $orderflag = 'order_sales';
            $orders['order_sales'] = $order_sales;
        }elseif(!empty($order_price)) {
            $orderflag = 'order_price';
            $orders['order_price'] = $order_price;
        }elseif(!empty($order_time)) {
            $orderflag = 'order_time';
            $orders['order_time'] = $order_time;
        }else{
            $orderflag = '';
        }
        //获取当前城市
        $data['city_id'] = $this->city->id;

        // 根据上面条件来查询商品列表数据
        $searchres = model('Deal')->getDealDataByConditions($data, $orders, $keywords);
        //var_dump($searchres);
        return $this->fetch('index',[
            'searchres' => $searchres,
            'categorys' => $categorys,
            'sedcategorys' => $sedcategorys,
            'id' => $id,
            'categoryParentId' => $categoryParentId,
            'orderflag' => $orderflag,
        ]);
    }
}