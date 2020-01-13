<?php
namespace app\common\validate;
use think\Validate;

class Shop extends Validate 
{
    protected $rule = [
        'name' => 'require|max:25',
        'email' => 'email',
        'mobile' => 'require',
        'student_logo' => 'require',
        'student_picture' => 'require',
        'area_id' => 'require',
        'profession' => 'require',
        'student_id' => 'require',
        'department' => 'require',
    ];

    // 场景设置
    protected  $scene = [
        'add' => ['name', 'email', 'student_logo', 'area_id', 'student_picture', 'area_path', 'department', 'profession', 'student_id'],
    ];
}