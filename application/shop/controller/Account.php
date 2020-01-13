<?php
namespace app\shop\controller;
use think\Controller;

class Account extends  Base
{
    public function passWordEdit()
    {
        return $this->fetch();
    }

    public function edit()
    {
        $accountData = $this->getLoginUser();
        if(!request()->isPost()) {
            $this->error('请求错误');
        }
        $data = input('post.');
        //判断用户输入的旧密码是否与当前用户的密码是否一致 
        if ($accountData->password != md5($data['password_origin'].$accountData->code)) {
           $this->error('原密码输入错误，请重新输入！！！');
        }
        //判断新旧密码是否相同
        if($accountData->password == md5($data['newpassword'].$accountData->code)){
            $this->error('新旧密码一致,您未做任何修改！');
        //两次修改的密码是否一致
        }elseif($data['newpassword'] != $data['password_again']) {
            $this->error('密码确认不一致,请重新输入！');
        }else {
            //自动生成密码的加盐字符串
            $data['code'] = mt_rand(100, 10000);
            $passWordData = [
                'code' => $data['code'],
                'password' => md5($data['newpassword'].$data['code']),
            ];
            //修改数据库password字段的值
            $result = model('ShopAccount')->get($accountData->id)->save($passWordData);                    
            if($result) {
            //如果密码修改成功,首先删除之前用户的cookie登录信息  
                //cookie('adminInfo',null);           
                //cookie('isLogin',null);  
                // 清除session
                session(null, 'shop');         
                $this->success('密码修改成功！');
                // 跳出
                $this->redirect('shop/login/index');
            }else {                  
                $this->error('密码修改失败！');
            }  
        }
    }
}
