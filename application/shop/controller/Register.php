<?php
namespace app\bis\controller;
use think\Controller;
class Register extends Controller
{
    public function index()
    {
        //获取一级区域的数据
        $areas = model('Area')->getNormalAreasByFatherId();
        return $this->fetch('',[
            'areas' => $areas,
        ]);
    }
    public function add()
    {
        if(!request()->isPost()) {
            $this->error('请求错误');
        }
        // 获取表单的值
        $data = input('post.', '' , 'htmlentities');
        //检验数据
        $validate = validate('Shop');
        if(!$validate->scene('add')->check($data)) {
            $this->error($validate->getError());
        }
        
        //判断提交的用户是否存在
        $accountResult = model('ShopAccount')->get(['username'=>$data['username']]);
        if($accountResult) {
            $this->error('该用户存在，请重新分配');
        }

        //商户基本信息入库
        $ShopData = [
            'name' => htmlentities($data['name']),
            'area_id' => $data['area_id'],
            'area_path' => empty($data['se_area_id']) ? $data['area_id'] : $data['area_id'].','.$data['se_area_id'],
            'student_logo' => $data['student_logo'],
            'student_picture' => $data['student_picture'],
            'presentation' => empty($data['presentation']) ? '' : $data['presentation'],
            'profession' =>  $data['profession'],
            'department' =>  $data['department'],
            'mobile' =>  $data['mobile'],
            'email' =>  $data['email'],
            'student_id' =>  $data['student_id'],
        ];
        $shopId = model('Shop')->add($ShopData);
        
        //判断
        $data['cat'] = '';
        if(!empty($data['se_classification_id'])) {
            $data['cat'] = implode('|', $data['se_classification_id']);
        }

      
        // 自动生成密码的加盐字符串
        $data['code'] = mt_rand(100, 10000);
        //账户相关信息检验
        $accounData = [
            'shop_id' => $shopId,
            'username' => $data['username'],
            'code' => $data['code'],
            'password' => md5($data['password'].$data['code']),
        ];
        $accountId = model('ShopAccount')->add($accounData);
        if(!$accountId) {
            $this->error('申请失败');
        }

        //数据获取成功后发送邮件给商户
        $url = request()->domain().url('shop/register/waiting', ['id'=>$shopId]);
        $title = "进驻申请通知";
        $content = "您提交的进驻申请需等待平台方审核，您可以通过点击链接<a href='".$url."' target='_blank'>查看链接</a> 查看审核状态";
        \phpmailer\Email::send($data['email'],$title, $content);//线上关闭 发送邮件服务 */

        $this->success('申请成功', url('register/waiting',['id'=>$shopId]));
    }

    public function waiting($id)
    {
        if(empty($id)) {
            $this->error('error');
        }
        $detail = model('Shop')->get($id);

        return $this->fetch('',[
            'detail' => $detail,
        ]);
    }
}