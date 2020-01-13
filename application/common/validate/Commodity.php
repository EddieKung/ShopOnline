<?php
namespace app\common\validate;
use think\Validate;

class Commodity extends Validate 
{
    protected $rule = [
        'name' => 'require|max:25',
        'image' => 'require',
        'area_id' => 'require',
        'classification_id' => 'require',
        'se_cclassification_id_id' => 'require',
        'origin_price' => 'require',
        'current_price' => 'require',
        'coupons_end_time' => 'require',
        'presentation' => 'require',
        'notes' => 'require',
        'notes' => 'require',
    ];

    // 场景设置
    protected  $scene = [
        'add' => ['name', 'image', 'area_id', 'classification_id', 'se_classification_id', 'origin_price', 'current_price', 'presentation', 'notes', ],
    ];
}