<?php
namespace app\admin\controller;
use think\Controller;
use app\admin\controller\Event;
require $_SERVER['DOCUMENT_ROOT'].'/Connection.php';
/**
 * 设备控制
 */
class Plccontr extends Event{
	
	// private $devId;

	private $db;
	private $prefix            = 'l_d_';
	private $plcTable          = 'plc';
	private $wdirTable         = 'wdir';
	private $plcRunStatusTable = 'plc_run_status';
	private $plcRunLogTable    = 'plc_run_log';
	private $order = [
		'aks' => 40011200,
		'rm' => 40008155,
		'run_start1' => 40119236,
		'run_stop1'  => 40120238,
		'run_start2' => 40121240,
		'run_stop2' => 40122242,
		'run_start3' => 40123244,
		'run_stop3' => 40124246,
		'run_start4' => 40125248,
		'run_stop4' => 40126250,
		'run_interval' => 40118234,
		'run_duration' => 40117232
	];

	private $devStatus = [
		'pm1s' => 40008140,
		'pm1f' => 40008141,
		'pm2s' => 40008142,
		'pm2f' => 40008143,
		'pfs' => 40008144,
		'pff' => 40008145,
		'pps' => 40008146,
		'ppf' => 40008147,
		'ivs' => 40008150,
		'ovs' => 40008151,
		'dvs' => 40008152,
		't1s' => 40008153,
		't2s' => 40008154
	];

	private $recData = [
		40011200 => 12,
		40117232 => 12,
		40120238 => 22
	];

	//18373145499

	function __construct(){
		parent::__construct();
		$this->plcTable  = $this->prefix.$this->plcTable;
		$this->wdirTable = $this->prefix.$this->wdirTable;
		$this->db = new \Workerman\MySQL\Connection("127.0.0.1", 3306, 'root', 'root', 'litchi');
	}

	public function test(){
		try {
			$orderMsg   = json_decode(json_encode($this->order), true);
			$recDataKey = array_keys($this->recData);
			foreach ($recDataKey as $k => $v) {
				if ($field = array_search($v, $orderMsg)) {
					$data[$field] = $this->recData[$v];
				}
			}
			var_dump($data);
			exit;
			// $this->switchRunModel(101, 0);
		} catch (\Exception $e) {
			return json_encode(['line' => $e->getLine(), 'msg' => $e->getMessage()]);
		}
	}



	//切换运行方式
	private function switchRunModel($devId, $model){
		$res = $this->db->select("model,run_start1,run_stop1,run_start2,run_stop2,run_start3,run_stop3,run_start4,run_stop4,run_interval,run_duration")->from($this->plcTable)->where('plc_id='.$devId)->row();
		if (!$res) throw new \Exception("devId does not exist", 400);
		switch ($model) {
			case 0://shoudong
				$this->db->update($this->plcTable)->cols(['model' => 0])->where('plc_id='.$devId)->query();
				self::sendToPlc($devId, $this->order['aks'], 1);
				return self::replyClient($clientId, $msg);
			
			case 1://dingshi
				for ($i=1; $i < 5; $i++) {
					self::sendToPlc($devId, $this->order['run_start'.$i], $res['run_start'.$i]);
					self::sendToPlc($devId, $this->order['run_stop'.$i], $res['run_stop'.$i]);
				}
				self::sendToPlc($devId, $this->order['run_interval'], $res['run_interval']);
				self::sendToPlc($devId, $this->order['run_duration'], $res['run_duration']);
				self::sendToPlc($devId, $this->order['rm'], 1);
				return;

			case 2://zhineng
				$res = $this->db->update($this->plcTable)->cols(['model' => 2])->where('plc_id='.$devId)->query();
				if ($res) return self::replyClient($clientId, 'success');
				else throw new \Exception("fail", 400);
		}
	}


	private static function replyClient($clientId, $msg){
		return true;
	}

	private static function sendToPlc($devId, $order, $val){
		// if (!Gateway::isUidOnline($devId)) throw new \Exception("plc not online", 400);
		// Gateway::sendToUid($devId, $order, $val);
	}

	public function recFromPlc($devId, $data){
		$tmp = json_decode($data);
		$res = $this->db->update($this->plcTable)->cols($data)->where('plc_id='.$devId)->query();
		if ($res) return sendToPlc($devId, $order, $val);
		else throw new \Exception("Error Processing Request", 1);
	}

	private static function sendToWdir($devId, $order, $val){
		
	}

	private static function recFromWdir($devId, $data){
		
	}

	private static function sendToWea($devId, $order, $val){
		
	}

	private static function recFromWea($devId, $data){
		
	}

	private static function regplc($devId){

	}

}
