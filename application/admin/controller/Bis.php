<?php
namespace app\admin\controller;
use think\Controller;

class Bis extends Controller
{
    //实例化通用的model对象
    private $obj;
    public function _initialize() 
    {
        $this->obj = model("Bis");
    }
    //过审商户列表
    public function index() {
        $bis = $this->obj->getBisByStatus(1);
        return $this->fetch('', [
            'bis' => $bis,
        ]);
    }
    //入驻申请列表
    public function apply()
    {
        $bis = $this->obj->getBisByStatus();
        return $this->fetch('',[
            'bis' =>$bis,
        ]);
    }
    //新增分店入驻申请列表
    public function branch()
    {
        $locationData = model('BisLocation')->getLocationByStatus();
        return $this->fetch('',[
            'location' =>$locationData,
        ]);
    }
    //分店列表
    public function bisBranch()
    {
        $id = input('get.id');
        if (empty($id)) {
            return $this->error('获取ID错误！');
        }
        $branchData = model('BisLocation')->getBisBranchBystatus($id);
        return $this->fetch('',[
            'branchData' =>$branchData,
        ]);
    }
    //分店详情
    public function branchDetail()
    {
        $id = input('get.id');
        if (empty($id)) {
            return $this->error('ID错误！');
        }
        //获取一级城市的数据
        $citys = model('City')->getNormalCitysByParentId();
        //获取一级服务分类数据
        $categorys = model('Category')->getNormalCategoryByParentId();
        // 获取商户数据
        $locationData = model('BisLocation')->get($id);
        $bisId = model('BisLocation')->get($id)->bis_id;
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
    //分店状态更改
    public function branchStatus()
    {
        $data = input('get.');
        print_r($data);
        
        $validate = validate('BisLocation');
        if(!$validate->scene('status')->check($data)) {
            $this->error($validate->getError());
        }
        $res = model('BisLocation')->save(['status'=>$data['status']],['id'=>$data['id']]);
        if($res){
            $this->success('恢复成功');
        }
        else{
            $this->error('恢复失败');
        } 
    }
    //主商户详情
    public function detail()
    {
        $id = input('get.id');
        if (empty($id)) {
            return $this->error('ID错误！');
        }
        //获取一级城市的数据
        $citys = model('City')->getNormalCitysByParentId();
        //获取一级服务分类数据
        $categorys = model('Category')->getNormalCategoryByParentId();
        // 获取商户数据
        $bisData = model('Bis')->get($id);
        $locationData = model('BisLocation')->get(['bis_id'=>$id, 'is_main'=>1]);
        $accountData = model('BisAccount')->get(['bis_id'=>$id, 'is_main'=>1]);
        return $this->fetch('',[
            'citys' => $citys,
            'categorys' => $categorys,
            'bisData' => $bisData,
            'locationData' => $locationData,
            'accountData' => $accountData,
        ]);
    }
    //显示删除的商户的列表
    public function dellist()
    {
       $deleteData = model('BisLocation')->getDellistByStatus();
       return $this->fetch('',[
            'deleteData' =>$deleteData,
        ]);
    }
    // 通过审核
    public function status()
    {
        $data = input('get.');
        //检验
        $validate = validate('Bis');
        if(!$validate->scene('status')->check($data)) {
            $this->error($validate->getError());
        }
           
        $res = $this->obj->save(['status'=>$data['status']], ['id'=>$data['id']]);
        $location = model('BisLocation')->save(['status'=>$data['status']], ['bis_id'=>$data['id'], 'is_main'=>1]);
        $account = model('BisAccount')->save(['status'=>$data['status']], ['bis_id'=>$data['id'], 'is_main'=>1]);
        if($res && $location && $account) {
            //审核通过后发送邮件给商户
            $id = input('get.id');         
            $bisData = model('Bis')->get($id);
            $url = request()->domain().url('bis/register/waiting', ['id'=>$bisData['id']]);
            $title = "GWB团购网入驻申请进度通知";
            $content = "您提交的入驻申请审核有了新进度，您可以通过点击链接<a href='".$url."' target='_blank'>查看链接</a> 查看审核状态";
            \phpmailer\Email::send($bisData['email'],$title, $content);//线上关闭 发送邮件服务
            $this->success('状态更新成功');
        }else {
            $this->error('状态更新失败');
        }
    }
    //修改总店列表的总店为删除状态
    public function delete()
    {
        $data = input('get.');
        $validate = validate('Bis');
        if(!$validate->scene('status')->check($data)) {
            $this->error($validate->getError());
        }
        $res = $this->obj->save(['status'=>$data['status']], ['id'=>$data['id']]);
        $location = model('BisLocation')->save(['status'=>$data['status']], ['bis_id'=>$data['id']]);
        $account = model('BisAccount')->save(['status'=>$data['status']], ['bis_id'=>$data['id'], 'is_main'=>1]);
        
        if($res){
            $this->success('删除成功');
        }
        else{
            $this->error('删除失败');
        } 
    }
}
