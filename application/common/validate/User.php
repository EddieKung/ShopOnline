<?php
namespace app\common\validate;
use think\Validate;

class User extends Validate 
{
    protected $rule = [
        ['username', 'require|max:25'],
        ['email', 'email', '邮箱格式不正确'],
        ['password', 'require|min:6', '密码长度必须为六位以上'],
    ];

    // 场景设置
    protected  $scene = [
        'add' => ['username', 'email', 'password'],
    ];
}