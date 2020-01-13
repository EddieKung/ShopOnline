<?php
namespace app\common\model;

use think\Model;

class User extends BaseModel
{
    public function add($data = [])
    {
        // 如果提交的数据不是数组
        if(!is_array($data)) {
            exception('传递的数据不是数组');
        }

        $data['status'] = 1;
        return $this->data($data)->allowField(true)
            ->save();
    }
    //主后台用户列表获取
    public function getUserListByStatus()
    {
        $data = [
            'status' => 1,
        ];
        $order = [
            'id' => 'desc',
        ];
        return $this->where($data)
            ->order($order)
            ->select();
    }
    //删除的用户列表获取
    public function getDleUserBystatus($status)
    {
        $data = [
            'status' => $status,
        ];
        $order = [
            'id' => 'desc',
        ];
        return $this->where($data)
            ->order($order)
            ->select();
    }
    /**
     * 根据用户名获取用户信息
     * @param $username
     */
    public function getUserByUsername($username) 
    {
        if(!$username) {
            exception('用户名不合法');
        }

        $data = ['username' => $username];
        return $this->where($data)->find();
    }
}