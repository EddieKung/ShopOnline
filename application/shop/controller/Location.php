<?php
namespace app\bis\controller;
use think\Controller;

class Location extends Base
{
    //实例化通用的model对象
    private $obj;
    public function _initialize() 
    {
        $this->obj = model("BisLocation");
    }
    //门店列表
    public function index()
    {
        //获取当前登陆商户的Bis_id
        $BisId = $this->getLoginUser()->bis_id;
        $location = $this->obj->getLocationByBisId($BisId);
        return $this->fetch('',[
            'location' => $location,
        ]);

    }
    //添加新分店
    public function add()
    {
        if(request()->isPost()) {
            $data = input('post.');
            //检验数据
            $validate = validate('Location');
            if(!$validate->scene('add')->check($data)) {
                $this->error($validate->getError());
            }

            $bisId = $this->getLoginUser()->bis_id;
            //判断
            $data['cat'] = '';
            if(!empty($data['se_category_id'])) {
                $data['cat'] = implode('|', $data['se_category_id']);
            }

            // 获取经纬度
            $lnglat = \Map::getLngLat($data['address']);
            if(empty($lnglat) || $lnglat['status'] !=0 || $lnglat['result']['precise'] !=1) {
                $this->error('无法获取数据，或者匹配的地址不精确');
            }

            // 门店入库操作
            // 总店相关信息入库
            $locationData = [
                'bis_id' => $bisId,
                'name' => $data['name'],
                'logo' => $data['logo'],
                'tel' => $data['tel'],
                'contact' => $data['contact'],
                'category_id' => $data['category_id'],
                'category_path' => $data['category_id'] . ',' . $data['cat'],
                'city_id' => $data['city_id'],
                'city_path' => empty($data['se_city_id']) ? $data['city_id'] : $data['city_id'].','.$data['se_city_id'],
                'address' => $data['address'],
                'open_time' => $data['open_time'],
                'content' => empty($data['content']) ? '' : $data['content'],
                'is_main' => 0,
                'xpoint' => empty($lnglat['result']['location']['lng']) ? '' : $lnglat['result']['location']['lng'],
                'ypoint' => empty($lnglat['result']['location']['lat']) ? '' : $lnglat['result']['location']['lat'],
            ];
            $locationId = model('BisLocation')->add($locationData);
            if($locationId) {
                return $this->success('门店申请成功');
            }else {
                return $this->error('门店申请失败');
            }
        }else {
            //获取一级城市的数据
            $citys = model('City')->getNormalCitysByParentId();
            //获取一级栏目的数据
            $categorys = model('Category')->getNormalCategoryByParentId();
            return $this->fetch('', [
                'citys' => $citys,
                'categorys' => $categorys,
            ]);
        }
    }
    //删除状态
    public function status()
    {
        $data = input('get.');
        $validate = validate('Location');
        if(!$validate->scene('status')->check($data)) {
            $this->error($validate->getError());
        }

        $res = $this->obj->save(['status'=>$data['status']],['id'=>$data['id']]);
        if($res){
            $this->success('状态更新成功');
        }
        else{
            $this->error('状态更新失败');
        } 
    }
    //详情
    public function detail()
    {
        $id = input('get.id');
        if(empty($id)) {
            return $this->error('ID错误');
        }
        //获取一级城市的数据
        $citys = model('City')->getNormalCitysByParentId();
        //获取一级栏目的数据
        $categorys = model('Category')->getNormalCategoryByParentId();
        // 获取商户数据
        $locationData = $this->obj->get($id);
        $bisId = $this->obj->get($id)->bis_id;
        $bisData = model('Bis')->get(['id'=>$bisId, 'status'=>1]);
        $accountData = model('BisAccount')->get(['bis_id'=>$bisId, 'status'=>1]);
        return $this->fetch('',[
            'citys' => $citys,
            'categorys' => $categorys,
            'bisData' => $bisData,
            'locationData' => $locationData,
            'accountData' => $accountData,
        ]);
    }
}