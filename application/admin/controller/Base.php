<?php
namespace app\admin\controller;
use think\Controller;
use gmars\rbac\Rbac;

class Base extends Controller{

    protected $ad_uid;
    protected $rbac;//a/chanpinzhongxin/chanpinsilei

    protected function permission(){
        return $this->ad_uid = "1";
        try {
            $this->rbac = new Rbac;
            $result     = $this->rbac->can(request()->path());
            if ($result) {
                $this->ad_uid = $result;
            } else {
                throw new \Exception("您没有该操作权限", 401);
            }
        } catch (\Exception $e) {
            exit(json_encode(['code' => $e->getCode(), 'msg' => $e->getMessage()]));
        }
    }

    public function user($phone){
        $user = new \app\user\model\User();
        $list = $user->field('uid,phone,nickname')->where('phone', 'like', "%{$phone}%")->select();
        $this->suc('会员列表', $list);
    }

}