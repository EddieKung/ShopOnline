<?php
namespace app\admin\validate;
use think\Validate;

class BisLocation extends Validate
{
    protected $rule = [
        ['id', 'number'],
        ['status', 'number|in:-1,0,1,2','状态必须是数字|状态范围不合法'],
    ];

    //场景设置
    protected $scene = [
        'branchStatus' => ['id', 'status'],//状态
    ];
}