<?php
namespace app\admin\controller;
use think\Controller;

class Commodity extends  Base
{
    private  $obj;
    public function _initialize() {
        $this->obj = model("Commodity");
    }
    //团购列表显示
    public function index() {
    	$data = input('get.');
    	$sdata = [];
    	if(!empty($data['start_time']) && !empty($data['end_time']) && strtotime($data['end_time']) > strtotime($data['start_time'])) {
    		$sdata['create_time'] = [
    			['gt', strtotime($data['start_time'])],
    			['lt', strtotime($data['end_time'])],
    		];
    	}
    	if(!empty($data['classification_id'])) {
    		$sdata['classification_id'] = $data['classification_id'];
    	}
    	if(!empty($data['area_id'])) {
    		$sdata['area_id'] = $data['area_id'];
    	}
    	if(!empty($data['name'])) {
    		$sdata['name'] = ['like', '%'.$data['name'].'%'];
    	}
    	$areaArrs = $classificationArrs = [];
        $classifications = model("Classification")->getNormalClassificationByParentId();
        foreach($classifications as $classification) {
        	$classificationArrs[$classification->id] = $classification->name;
        }

        $areas = model("Area")->getNormalAreas();
        foreach($areas as $area) {
        	$areaArrs[$area->id] = $area->name;
        }

        $deals = $this->obj->getNormalCommoditys($sdata);

        
        return $this->fetch('', [
        	'classifications' => $classifications,
        	'areas' => $areas,
        	'deals' => $deals,
        	'classification_id' => empty($data['classification_id']) ? '' : $data['classification_id'],
        	'area_id' => empty($data['area_id']) ? '' : $data['area_id'],
        	'start_time' => empty($data['start_time']) ? '' : $data['start_time'],
        	'end_time' => empty($data['end_time']) ? '' : $data['end_time'],
        	'name' => empty($data['name']) ? '' : $data['name'],
        	'classificationArrs' => $classificationArrs,
        	'areaArrs' => $areaArrs,
        ]);
    }
    //申请
    public function apply() {
        $data = input('get.');
        $sdata = [];
        if(!empty($data['start_time']) && !empty($data['end_time']) && strtotime($data['end_time']) > strtotime($data['start_time'])) {
            $sdata['create_time'] = [
                ['gt', strtotime($data['start_time'])],
                ['lt', strtotime($data['end_time'])],
            ];
        }
        if(!empty($data['classification_id'])) {
            $sdata['classification_id'] = $data['classification_id'];
        }
        if(!empty($data['area_id'])) {
            $sdata['area_id'] = $data['area_id'];
        }
        if(!empty($data['name'])) {
            $sdata['name'] = ['like', '%'.$data['name'].'%'];
        }
        $areaArrs = $classificationArrs = [];
        $classifications = model("Classification")->getNormalClassificationByParentId();
        foreach($classifications as $classification) {
            $classificationArrs[$classification->id] = $classification->name;
        }

        $areas = model("Area")->getNormalAreas();
        foreach($areas as $area) {
            $areaArrs[$area->id] = $area->name;
        }

        $deals = $this->obj->getApplyCommoditys($sdata);
        return $this->fetch('', [
            'classifications' => $classifications,
            'areas' => $areas,
            'deals' => $deals,
            'classification_id' => empty($data['classification_id']) ? '' : $data['classification_id'],
            'area_id' => empty($data['area_id']) ? '' : $data['area_id'],
            'start_time' => empty($data['start_time']) ? '' : $data['start_time'],
            'end_time' => empty($data['end_time']) ? '' : $data['end_time'],
            'name' => empty($data['name']) ? '' : $data['name'],
            'classificationArrs' => $classificationArrs,
            'areaArrs' => $areaArrs,
        ]);
    }
    //修改状态
    public function status()
    {
        $data = input('get.');
        $validate = validate('Commodity');
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
    //编辑详情
    public function detail()
    {
        $id = input('get.id');
        if(empty($id)) {
            return $this->error('ID错误');
        }
        //获取一级城市的数据
        $areas = model('Area')->getNormalAreasByParentId();
        //获取一级栏目的数据
        $classifications = model('Classification')->getNormalClassificationByParentId();
        $dealData = $this->obj->get($id);
        $bisId = $this->obj->get($id)->bis_id;
        return $this->fetch('',[
            'areas' => $areas,
            'classifications' => $classifications,
            'dealData' => $dealData,
            'bislocations' => model('BisLocation')->getNormalLocationByBisId($bisId),
        ]);
    }
}
