<?php
namespace app\admin\model;
use think\Model;

/**
 * 气象设备
 */
class DWeaRunStatus extends Model{

	private $uid;

	public function __construct($ad_uid){
		parent::__construct();
		$this->uid = $ad_uid;
	}

	public function Dev(){
		$param = input();
		$param['create_user'] = $this->uid;
		$wea_info = db('d_wea_run_status')->field('status')->where(['wea_id' => input('wea_id')])->find();
		switch (input('type')) {
			case 1://
				if ($wea_info) {
					throw new \Exception("该气象设备已存在", 400);
				}
				if ($wea_info['status'] != 0) {
					throw new \Exception("该气象设备已禁用", 400);
				}
				if (!input('wea_id')) {
					throw new \Exception("错误的气象编号", 400);
				}
				if (!input('lon')) {
					throw new \Exception("错误的经度", 400);
				}
				if (!input('lat')) {
					throw new \Exception("错误的纬度", 400);
				}
				$res = $this->save($param);
				if (!$res) {
					throw new \Exception("error add dev", 400);
				}
				break;

			case 2:
				if (!$wea_info) {
					throw new \Exception("该气象设备不存在", 400);
				}
				if ($wea_info['status'] != 0) {
					throw new \Exception("该气象设备已禁用", 400);
				}
				if (!input('wea_id')) {
					throw new \Exception("错误的气象编号", 400);
				}
				if (!input('lon')) {
					throw new \Exception("错误的经度", 400);
				}
				if (!input('lat')) {
					throw new \Exception("错误的纬度", 400);
				}
				$res = $this->save($param, ['wea_id' => input('wea_id')]);
				if (!$res) {
					throw new \Exception("error edit dev", 400);
				}
				break;

			case 3:
				if (!$wea_info) {
					throw new \Exception("该设备不存在", 400);
				}
				if ($wea_info['status'] != 0) {
					throw new \Exception("该气象设备已禁用", 400);
				}
				$res = $this->save(['status' => 1], ['wea_id' => input('wea_id')]);
				if (!$res) {
					throw new \Exception("error del dev", 400);
				}
				break;

			default:
				throw new \Exception("param type error", 400);
		}
		return $res;
	}

	public function getList($page, $length){
		$where['status'] = 0;
		return db('d_wea_run_status')->where($where)->order('create_time desc')->paginate(10);
	}

	public function getInfo($wea_id){
		$res = db('d_wea_run_status')->where(['status' => 0, 'wea_id' => $wea_id])->find();
		if ($res) {
			return $res;
		}else{
			throw new \Exception("该气象设备已禁用", 400);
		}
	}
}