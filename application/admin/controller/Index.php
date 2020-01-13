<?php
namespace app\admin\controller;
use think\Controller;

class Index extends Base
{
    public function index()
    {
        return $this -> fetch();
    }
    public function welcome()
    {
        //\phpmailer\Email::send('873126462@qq.com','TINKPHP5-email','测试邮件！');
        return "欢迎来到系统主后台首页";
    }
}
