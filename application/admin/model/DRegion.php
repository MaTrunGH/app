<?php
namespace app\admin\model;
use think\Model;
use think\Request;

/**
 * 国控点
 */
class DRegion extends Model{

	public function getLevel($region_id){
		$res = db('d_region')->field('level')->where('region_id', $region_id)->find();
		if ($res) {
			return $res['level'];
		}else{
			throw new \Exception("错误的regionId", 400);
		}
	}

	public function getLowerRegion($region_id, $uid){
		if ($uid == '1') {
			
		}else{

		}
	}

}