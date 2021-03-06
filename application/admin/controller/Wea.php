<?php
namespace app\admin\controller;
use app\admin\controller\Base;
use app\admin\model\DWeaRunStatus;
use think\Request;

class Wea extends Base{

    protected $model;
    protected $request;
    protected $param;

    protected $beforeActionList = [
        'permission' => ['except' => 'login']
    ];

    protected function permission(){
        parent::permission(); // TODO: Change the autogenerated stub
        $this->param = $this->request->param();
        $this->model = new DWeaRunStatus($this->ad_uid);
    }

    public function dev(){
      try {
        return json_encode(input());
        // var_dump(input('type'));exit;
        $res = $this->model->Dev();
        return json_encode(['code' => 200, 'msg' => 'success']);
      } catch (\Exception $e) {
        return json_encode(['code' => $e->getCode(), 'msg' => $e->getMessage()]);
      }
    }

    public function devList($page = 0, $length = 10){
        try {
            $list = $this->model->getList($page, $length);
            return json_encode(['code' => 200, 'msg' => 'success', 'data' => $list]);
        } catch (\Exception $e) {
            return json_encode(['code' => $e->getCode(), 'msg' => $e->getMessage()]);
        }
    }
    
}
