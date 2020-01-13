<?php
namespace app\admin\controller;
use think\Controller;

class Classification extends Controller
{
    //实例化通用的model对象
    private $obj;
    public function _initialize() 
    {
        $this->obj = model("Classification");
    }

    //admin主页分类数据加载
    public function index()
    {
        $fatherId = input('get.father_id', 0,'intval');
        $Classifications = $this->obj->getFirstClassifications($fatherId);
        return $this->fetch('',[
            'classifications'=>$Classifications,
        ]);
    }
    //详情
    public function detail()
    {
        $fatherId = input('get.father_id', 0,'intval');
        $Classifications = $this->obj->getFirstClassifications($fatherId);
        return $this->fetch('',[
            'classifications'=>$Classifications,
        ]);
    }

    //新增
    public function add()//
    {
        $Classifications = $this->obj->getNormalFirstClassification();                                     
        return $this->fetch('',[
            'classifications'=>$Classifications,
            ]);
    }
    //编辑页面
    public function edit($id=0)//
    {
        if(intval($id) < 1){
            $this->error('参数不合法');
        }
        $Classification = $this->obj->get($id);
        $Classifications = $this->obj->getNormalFirstClassification();                                     
        return $this->fetch('',[
            'classifications'=>$Classifications,
            'classification'=>$Classification,
            ]);
    }


    //保存新增分类
    public function save()
    {
        //print_r($_POST);原生的数据获取
        //print_r(input('post.'));thinkphp5带有的数据获取之一
        //print_r(request()->post());thinkphp5带有的数据获取之二

        //做严格校验
        if(!request()->isPost()) {
            $this->error('请求失败');
        }
        $data = input('post.');
        //$data['status'] = 10;
        $validate = validate('Classification');
        if(!$validate->scene('add')->check($data)) {
            $this->error($validate->getError());
        }
        //如果id存在，走更新操作
        if (!empty($data['id'])) {
            return $this->update($data);
        }

        //把$data数据提交到model层
        $res = $this->obj->add($data);
       if($res){
            $this->success('新增成功');
        }
        else{
            $this->error('新增失败');
        } 
    }


    //修改分类数据
    public function update($data) 
    {
        $res =  $this->obj->save($data, ['id'=>intval($data['id'])]);
        if($res){
            $this->success('新增成功');
        }
        else{
            $this->error('新增失败');
        } 
    }


    //排序逻辑
    public function listorder($id,$listorder)
    {
        $res = $this->obj->save(['listorder'=>$listorder], ['id'=>$id]);

        if($res){
            $this->result($_SERVER['HTTP_REFERER'], 1, '更新成功');
        }
        else{
            $this->result($_SERVER['HTTP_REFERER'], 0, '更新失败');
        } 
    }
    //修改状态
    public function status()
    {
        $data = input('get.');
        $validate = validate('Classification');
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
}
