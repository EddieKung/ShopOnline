<?php
namespace app\admin\controller;
use think\Controller;

class Login extends Controller
{
    //登陆信息
    public function index()
    {
        if(request()->isPost()) {
            //获取相关的数据
            $data = input('post.');
            //通过用户名获取用户相关信息
            $ret = model('AdminAccount')->get(['adminuser'=>$data['adminuser']]);
            if(empty($ret->password) || empty($ret->adminuser)) {
                $this->error('请输入用户名或密码！！！');
            }
            if(empty($ret->adminuser)) {
                $this->error('该超级管理员不存在！！！');
            }
            if($ret->password != md5($data['password'].$ret->code)) {
                $this->error('密码不正确！！！');
            }
            //验证码
            if(!captcha_check($data['verifycode'])) {
                // 校验失败
                $this->error('验证码不正确');
            }
            model('AdminAccount')->updateById(['last_login_time'=>time()], $ret->id);
            //保存用户信息admin是作用域
            session('AdminAccount', $ret, 'admin');
            return $this->success('登录成功！！！', url('index/index'));


        }else {
            //获取session
            $account = session('AdminAccount', '', 'admin');
            if($account && $account->id) {
                return $this->redirect(url('index/index'));
            }
            return $this->fetch();
        }
    }
    //退出登录
    public function logout() {
        // 清除session
        session(null, 'admin');
        // 跳出
        $this->redirect('login/index');
    }
}