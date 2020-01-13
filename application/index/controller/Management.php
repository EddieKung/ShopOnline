<?php
namespace app\index\controller;
use think\Controller;

class Management extends Base
{
    public function index()
    {
        $userData = $this->getLoginUser()->id;
        var_dump($userData);
        return $this->fetch('', [

        ]);
    }
}
