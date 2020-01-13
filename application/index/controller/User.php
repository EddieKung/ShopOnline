<?php
namespace app\index\controller;
use think\Controller;

class User extends Controller
{
    //主页登陆后跳转
    public function login()
    {
        $user = session('o2o_user','', 'o2o');
        if($user && $user->id) {
           $this->redirect(url('index/index'));
        }
        return $this->fetch();
    }
    //注册
    public function register()
    {
        if(request()->isPost()){
            $data = input('post.');

            if(!captcha_check($data['verifycode'])) {
                // 校验失败
                $this->error('验证码不正确');
            }
            //校验
            $validate = validate('User');
            if(!$validate->scene('add')->check($data)) {
                $this->error($validate->getError());
            }

            $userData = model('User')->get(['username'=>$data['username']]);
            if($userData) {
                $this->error('该用户名已存在，请重新输入！');
            }
            $emailData = model('User')->get(['email'=>$data['email']]);
            if($emailData) {
                $this->error('该邮箱已存在，请重新输入！');
            }
            if($data['password'] != $data['repassword']) {
                $this->error('两次输入的密码不一样');
            }
            // 自动生成 密码的加盐字符串
            $data['code'] = mt_rand(100, 10000);
            $data['password'] = md5($data['password'].$data['code']);
            try {
                $res = model('User')->add($data);
            }catch (\Exception $e) {
                $this->error($e->getMessage());
            }
            if($res) {
                $this->success('注册成功',url('user/login'));
            }else{
                $this->error('注册失败');
            }

        }else {
            return $this->fetch();
        }
    }
    //登录信息
    public function logincheck() {
        //判定
        if(!request()->isPost()) {
           $this->error('提交不合法');
        }
        $data = input('post.');       
        try {
            $user = model('User')->getUserByUsername($data['username']);
        }catch (\Exception $e){
            $this->error($e->getMessage());
        }

        if(!$user || $user->status != 1) {
            $this->error('该用户不存在');
        }
        // 判定密码是否合理
        if(md5($data['password'].$user->code) != $user->password) {
            $this->error('密码不正确');
        }

        // 登录成功
        model('User')->updateById(['last_login_time'=>time()], $user->id);

        //把用户信息记录到session
        session('o2o_user', $user, 'o2o');

        $this->success('登录成功', url('index/index'));

    }
    //退出登录
    public function logout() {
        session(null, 'o2o');
        $this->redirect(url('user/login'));
    }
}
