<?php
namespace app\admin\model;
use think\Model;

/**
 * 国控点
 */
class DContr extends Model{

	private $uid;

	public function __construct($ad_uid){
		parent::__construct();
		$this->uid = $ad_uid;
	}

	public function Dev(){
		$param = input();
		$param['create_user'] = $this->uid;
		$contr_info = db('d_contr')->where(['contr_id' => input('contr_id')])->find();
		switch (input('type')) {
			case 1://
				if ($contr_info) {
					throw new \Exception("该国控编号已存在", 400);
				}
				if ($contr_info['status'] != 0) {
					throw new \Exception("该国控编号已禁用", 400);
				}
				$res = $this->save($param);
				if (!$res) {
					throw new \Exception("error add dev", 400);
				}
				break;

			case 2:
				if (!$contr_info) {
					throw new \Exception("国控编号不存在", 400);
				}
				if ($contr_info['status'] != 0) {
					throw new \Exception("该国控编号已禁用", 400);
				}
				$res = $this->save($param, ['contr_id' => input('contr_id')]);
				if (!$res) {
					throw new \Exception("error edit dev", 400);
				}
				break;

			case 3:
				if (!$contr_info) {
					throw new \Exception("国控编号不存在", 400);
				}
				if ($contr_info['status'] != 0) {
					throw new \Exception("该国控编号已禁用", 400);
				}
				$res = $this->save(['status' => 1], ['contr_id' => input('contr_id')]);
				if (!$res) {
					throw new \Exception("error del dev", 400);
				}
				break;

			default:
				throw new \Exception("param type error", 400);

			return $res;
		}
	}

	public function getList($page, $length){
		$where['status'] = 0;
		return db('d_plc')->where($where)->limit($page * $length, $length)->order('create_time desc')->select();
	}

}