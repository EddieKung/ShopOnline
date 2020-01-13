<?php
namespace app\shop\controller;
use think\Controller;

class Login extends Controller
{
    public function index()
    {
        if(request()->isPost()) {
            //获取相关的数据
            $data = input('post.');
            //通过用户名获取用户相关信息
            $ret = model('ShopAccount')->get(['username'=>$data['username']]);
            if(!$ret || $ret->status !=1 ) {
                $this->error('该用户不存在，获取用户未被审核通过');
            }

            if($ret->password != md5($data['password'].$ret->code)) {
                $this->error('密码不正确');
            }

            model('ShopAccount')->updateById(['last_login_time'=>time()], $ret->id);
            //保存用户信息shop是作用域
            session('shopAccount', $ret, 'shop');
            return $this->success('登录成功', url('index/index'));


        }else {
            //获取session
            $account = session('shopAccount', '', 'shop');
            if($account && $account->id) {
                return $this->redirect(url('index/index'));
            }
            return $this->fetch();
        }
    }
    //退出登录
    public function logout() {
        // 清除session
        session(null, 'shop');
        // 跳出
        $this->redirect('login/index');
    }
}