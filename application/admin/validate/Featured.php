<?php
namespace app\admin\validate;
use think\Validate;

class Featured extends Validate
{
    protected $rule = [
        ['title', 'require|max:15', '分类名必须传递|分类名不能超过10个字符'],
        ['image','require'],
        ['type', 'require'],
        ['url', 'require'],
        ['description', 'require'],
    ];

    //场景设置
    protected $scene = [
        'add' => ['title', 'image', 'type', 'url', 'description'],// 添加
    ];
}