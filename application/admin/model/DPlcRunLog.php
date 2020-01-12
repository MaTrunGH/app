<?php
namespace app\admin\model;
use think\Model;
use think\Request;

/**
 * 国控点
 */
class DPlcRunLog extends Model{

	public function getList($plc_id){
		if ($plc_id) $where['plc_id'] = $plc_id; else $where = "1=1";
		return self::where($where)->paginate(10);
	}

}
