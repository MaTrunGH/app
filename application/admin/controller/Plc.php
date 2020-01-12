<?php
namespace app\admin\controller;
use think\Request;
use app\admin\controller\Base;
use app\admin\model\DPlc;
use app\admin\model\DWeaRunStatus;
use app\admin\model\DPlcRunLog;

class Plc extends Base{

    protected $model;
    protected $request;
    protected $param;

    protected $beforeActionList = [
        'permission' => ['except' => 'login,getlist']
    ];

    protected function permission(){
        parent::permission();
        $this->param = $this->request->param();
        $this->model = new DPlc($this->ad_uid);
    }

    public function dev(){
      try {
        $res = $this->model->Dev();
        return json_encode(['code' => 200, 'msg' => 'success']);
      } catch (\Exception $e) {
        exit(json_encode(['code' => $e->getCode(), 'msg' => $e->getMessage()]));
      }
    }

    public function devList(){
        $list = $this->model->getList();
        $this->view->engine->layout(false);
        $this->assign('list', $list['data']);
        $this->assign('page', $list['all']->render());
        return $this->fetch();
    }

    public function devAdd(){
        $wea_list = (new DWeaRunStatus($this->ad_uid))->getList(0, 200);
        $this->assign('wea_list', $wea_list);
        $this->view->engine->layout(false);
        return $this->fetch();
    }

    public function devEdit(){
        try {
            $wea_list = (new DWeaRunStatus($this->ad_uid))->getList(0, 200);
            $this->assign('wea_list', $wea_list);
            $this->view->engine->layout(false);
            return $this->fetch(); 
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    public function devRunStatus(){

    }

    public function devRunDesc($dev_id){
        try {
            $param = $this->model->runDesc($dev_id);
            $wea_id = $this->model->getWeaId($dev_id);
            $wea_run_param = (new DWeaRunStatus($this->ad_uid))->getInfo($wea_id);
            $this->assign('wea_run_param', $wea_run_param);
            $this->assign("param", $param);
            $this->view->engine(false);
            return $this->fetch();
        } catch (\Exception $e) {
            echo '该设备不存在';
            // $this->error($e->getMessage());
        }
    }

    public function devRunLog($dev_id = null){
        $runLogModel = new DPlcRunLog();
        $list = $runLogModel->getList(null);
        // var_dump($list);exit;
        $this->view->engine->layout(false);
        $this->assign('list', $list);
        $this->assign('page', $list->render());
        return $this->fetch(); 
    }

    public function sendOperation($dev_id, $type){
        switch ($type) {
            case 'aKeyOpen':
                
                break;
        }
    }

}
