<?php
namespace app\admin\controller;
use think\Controller;

class User extends Base
{
    //实例化通用的model对象
    private $obj;
    public function _initialize() 
    {
        $this->obj = model("User");
    }
    //会员列表
    public function index()
    {
        $userList = $this->obj->getUserListByStatus();
        return $this->fetch('', [
            'userList' => $userList,
        ]);
    }
    //删除的会员列表获取
    public function dellist()
    {
        $dellist = $this->obj->getDleUserBystatus(4);
        return $this->fetch('',[
            'dellist' => $dellist,
        ]);
    }
}
