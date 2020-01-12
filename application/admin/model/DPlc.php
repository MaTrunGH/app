<?php
namespace app\admin\model;
use think\Model;
use think\Request;

/**
 * 国控点
 */
class DPlc extends Model{

	private $uid;

	public function __construct($ad_uid){
		parent::__construct();
		$this->uid = $ad_uid;
	}

	public function Dev(){
		$param = input();
		$param['create_user'] = $this->uid;
		$plc_info   = db('d_plc')->field('status')->where(['plc_id' => input('plc_id')])->find();
		$contr_info = db('d_contr')->where(['contr_id' => input('contr_id')])->find();
		$wea_info   = db('d_wea_run_status')->field('lon,lat')->where(['wea_id' => input('wea_id'), 'status' => 0])->find();
		switch (input('type')) {
			case 1://
				if ($plc_info) {
					throw new \Exception("该plc设备已存在", 400);
				}
				if ($plc_info['status'] != 0) {
					throw new \Exception("该plc设备已禁用", 400);
				}
				if (!$contr_info) {
					throw new \Exception("错误的国控编号", 400);
				}
				if ($contr_info['status'] != 0) {
					throw new \Exception("该国控编号已禁用", 400);
				}
				if (!$wea_info) {
					throw new \Exception("错误的气象编号", 400);
				}
				$wdir_limit = $this->calcWdirLimit($wea_info['lat'], $wea_info['lon'], input('lat'), input('lon'));
				$param['wdir_up']   = $wdir_limit['up'];
				$param['wdir_down'] =  $wdir_limit['down'];
				$res = $this->save($param);
				if (!$res) {
					throw new \Exception("error add dev", 400);
				}
				break;

			case 2:
				if (!$plc_info) {
					throw new \Exception("错误的plc编号", 400);
				}
				if ($plc_info['status'] != 0) {
					throw new \Exception("该plc设备已禁用", 400);
				}
				if (!$contr_info) {
					throw new \Exception("错误的国控编号", 400);
				}
				if ($contr_info['status'] != 0) {
					throw new \Exception("该国控编号已禁用", 400);
				}
				if (!$wea_info) {
					throw new \Exception("错误的气象编号", 400);
				}
				$wdir_limit = $this->calcWdirLimit($wea_info['lat'], $wea_info['lon'], input('lat'), input('lon'));
				$param['wdir_up']   = $wdir_limit['up'];
				$param['wdir_down'] =  $wdir_limit['down'];
				$res = $this->save($param, ['plc_id' => input('plc_id')]);
				if (!$res) {
					throw new \Exception("error edit dev", 400);
				}
				break;

			case 3:
				if (!$plc_info) {
					throw new \Exception("错误的plc编号", 400);
				}
				if ($plc_info['status'] != 0) {
					throw new \Exception("该plc设备已禁用", 400);
				}
				$res = $this->save(['status' => 1], ['plc_id' => input('plc_id')]);
				if (!$res) {
					throw new \Exception("error del dev", 400);
				}
				break;

			default:
				throw new \Exception("param type error", 400);
		}
		return $res;
	}

	private function calcWdirLimit($wea_lat, $wea_lon, $plc_lat, $plc_lon){
		$rela_angle = (int)(atan2(($plc_lat - $wea_lat),($plc_lon - $wea_lon)) * 180 / pi());
		if ($rela_angle < 0) {
			$rela_angle = 360 + $rela_angle;
		}
		$res['up'] = $rela_angle - 90 < 0 ? $rela_angle + 270 : $rela_angle - 90;
		$res['down'] = $rela_angle + 90 >= 360 ? $rela_angle -270 : $rela_angle + 90;
		return $res;
	}

	public function getList(){
		if (input('start')) {
			$start_time[] = ['p.create_time', '>', input('start')];
		}else{
			$start_time = '1=1';
		}
		if (input('end')) {
			$end_time[]= ['p.create_time', '<', input('end')];
		}else{
			$end_time = '1=1';
		}
		if (input('plc_id')) {
			$where['p.plc_id'] = input('plc_id');
		}
		if (input('name')) {
			$name[] = ['p.name','like', '%'.input('name').'%'];
		}else{
			$name = '1=1';
		}
		$where['p.status'] = 0;
		$res = db('d_plc')->alias('p')->field('p.plc_id,p.name as plc_name,p.area,p.region,p.lon,p.lat,p.wea_id,p.wdir_up,p.wdir_down,p.status,p.model,p.create_user,p.create_time,w.name as wea_name')->where($where)->where($start_time)->where($end_time)->where($name)->join('d_wea_run_status w', 'w.wea_id=p.wea_id', 'left')->order('create_time desc')->paginate(10);
		if ($res) {
			$res_data = $res->all();
			foreach ($res_data as $key => $value) {
				if ($value['region']) {
					$where_r['id'] = $value['region'];
				}else{
					$where_r['id'] = $value['area'];
				}
				$region_info = db('d_region')->field('shortname')->where($where_r)->find();
				$res_data[$key]['shortname'] = $region_info['shortname'];
			}
			$result['data'] = $res_data;
			$result['all'] = $res;
			return $result;
		}else{
			return false;
		}
		
	}

	public function getInfo($plc_id){
		$res = db('d_plc')->where(['status' => 0, 'plc_id' => $plc_id])->find();
		if ($res) {
			return $res;
		}else{
			throw new \Exception("该plc设备已禁用", 400);
		}
	}

	public function getWeaId($plc_id){
		$res = db('d_plc')->field('wea_id')->where(['status' => 0, 'plc_id' => $plc_id])->find();
		if ($res) {
			return $res['wea_id'];
		}else{
			throw new \Exception("该plc设备已禁用", 400);
		}
	}

	public function runDesc($plc_id){
		$res = db('d_plc_run_status')->where(['plc_id' => $plc_id])->find();
		if ($res) {
			return $res;
		}else{
			throw new \Exception("该plc设备不存在", 400);
		}
	}

	public function getRegionList(){
		
	}

}