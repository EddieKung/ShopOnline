<?php
namespace app\common\model;

use think\Model;

class Commodity extends BaseModel
{
	//通过id查订单详情
	public function getCommodityDataById($id)
	{
		$data['id'] = array("in",$id);
		$result = $this->where($data)
			->select();
		return $result;
	}
	//通过shopid，status获取被删的售卖
	public function getDellistByshopIdStatus($ShopId)
	{
		$data = [
			'shop_id' => $ShopId,
			'status' => 3,
		];
		$order = ['id'=>'desc'];
		$result = $this->where($data)
			->order($order)
			->paginate();
		return $result;
	}
	//通过shopid获取该学生商店售出的商品的dealID编号
	public function getCommodityIdByShopId($ShopId)
	{
		$data = [
			'shop_id' => $ShopId,
        ];
		$result = $this->field('id')
			->where($data)
			->order('id', 'desc')
			->select();
        return $result;
	}

	//通过shopid获取学生商店售卖商品
	public function getNormalCommoditysByshopId($ShopId)
	{
		$data = [
			'shop_id' => $ShopId,
			'status' => 1,
        ];
        $result = $this->where($data)
			->order('status', 'desc')
			->select();
        return $result;
	}
	//主后台获取学生商店商品
	public function getNormalCommoditys($data = []) {
		$data['status'] = 1;
		$order = ['id'=>'desc'];

		$result = $this->where($data)
			->order($order)
			->paginate();
		//echo $this->getLastSql();
		return  $result;
	}
	//
	public function getApplyCommoditys($data = []) {
		$data['status'] = 0;
		$order = ['id'=>'desc'];

		$result = $this->where($data)
			->order($order)
			->paginate();

		//echo $this->getLastSql();
		return  $result;
	}

	/**
	 * 根据分类 以及 校区来获取 商品数据
	 * @param $id 分类
	 * @param $cityId 校区
	 * @param int $limit 条数
	 */
	public function getNormalCommodityByCategoryCityId($id, $cityId, $limit=10) {
		$data  = [
			'end_time' => ['gt', time()],
			'category_id' => $id,
			'city_id' => $cityId,
			'status' => 1,
		];

		$order = [
			'listorder'=>'desc',
			'id'=>'desc',
		];

		$result = $this->where($data)
			->order($order);
		if($limit) {
			$result = $result->limit($limit);
		}
		//echo $this->getLastSql();
		return $result->select();
	}
	//根据排序条件查询商品
	public function getCommodityByConditions($data=[], $orders)
	{
		if(!empty($orders['order_sales'])) {
			$order['buy_count'] = 'desc';
		}
		if(!empty($orders['order_price'])) {
			$order['current_price'] = 'desc';
		}
		if(!empty($orders['order_time'])) {
			$order['create_time'] = 'desc';
		}
		$order['id'] = 'desc';
		

		$datas[] = ' end_time> '.time();
		$datas[] = ' status= 1';

		if(!empty($data['se_category_id'])) {
			
			$datas[]="find_in_set(".$data['se_category_id'].",se_category_id)";
		}
		if(!empty($data['category_id'])) {
			
			$datas[]="category_id = ".$data['category_id'];
		}
		if(!empty($data['city_id'])) {
			
			$datas[]="city_id = ".$data['city_id'];
		}	
		
		$result = $this->where(implode(' AND ',$datas))
			->order($order)
			->paginate();
			
		return $result;
	}
	//搜索商品后数据根据排序条件查询商品
	public function getCommodityDataByConditions($data=[], $orders, $keywords)
	{
		if(!empty($orders['order_sales'])) {
			$order['buy_count'] = 'desc';
		}
		if(!empty($orders['order_price'])) {
			$order['current_price'] = 'desc';
		}
		if(!empty($orders['order_time'])) {
			$order['create_time'] = 'desc';
		}
		$order['id'] = 'desc';
		

		$datas[] = ' end_time> '.time();
		$datas[] = ' status= 1';

		if(!empty($data['se_category_id'])) {
			
			$datas[]="find_in_set(".$data['se_category_id'].",se_category_id)";
		}
		if(!empty($data['category_id'])) {
			
			$datas[]="category_id = ".$data['category_id'];
		}
		if(!empty($data['city_id'])) {
			
			$datas[]="city_id = ".$data['city_id'];
		}	
		
		$result = $this->where(implode(' AND ',$datas))
			->whereLike('name', '%'.$keywords.'%')
			->order($order)
			->paginate();
			//echo $this->getLastSql();
		return $result;
	}
	//更新
	public function updateBuyCountById($id, $buyCount) {
		return $this->where(['id' => $id])->setInc('buy_count', $buyCount);

	}

	//根据学生商店所售出的订单dealid获取订单信息
    public function getCommodityDataByCommodityId($id)
    {
        $data = [
			'id' => $id,
			'status' => 1,
        ];
        $result = $this->where($data)
			->order('status', 'desc')
			->select();
        return $result;
	}
	//主后台订单数据获取
    public function getOrderData()
    {
        $result = $this->alias('a')
            ->join('Order b','a.id = b.deal_id')
            ->where('b.status',1)
            ->select();
        return $result;
	}
	//主后台订单数据获取
    public function getOrderDelData()
    {
        $result = $this->alias('a')
            ->join('Order b','a.id = b.deal_id')
            ->where('b.status',4)
            ->select();
        return $result;
	}
	//学生商店后台订单数据获取
	public function getOrderDataByShopId($ShopId)
	{
		$data = [
			'a.shop_id' => $ShopId,
			'b.status' => 1,
		];
		$result = $this->alias('a')
            ->join('Order b','a.id = b.deal_id')
            ->where($data)
			->select();
		//echo $this->getLastSql();
        return $result;
	}

	//搜索关键字
    public function getCommodityDataByKeywords($keywords)
    {
		$order = ['id'=>'desc'];
		$result = $this->whereLike('name', '%'.$keywords.'%')
			->order($order)
			->paginate(4);
			//echo $this->getLastSql();
		return $result;
    }
}