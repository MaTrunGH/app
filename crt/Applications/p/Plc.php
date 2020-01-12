<?php
namespace app\plc;
use \GatewayWorker\Lib\Gateway;

/**
 * 设备控制
 */
class Plc{
	private $cid;
	private $pid;
	private $db;
	private $mf;
	private $md;
	private $ml;
	private $ms = false;
	private $plcTable    = 'l_d_plc';
	private $plcRunTable = 'l_d_plc_run_status';
	private $plcLogTable = 'l_d_plc_run_log';

	private $o = [
		'aks' => 40011200,
		'rm'  => 40008155,
		'run_start1'   => 40119236,
		'run_stop1'    => 40120238,
		'run_start2'   => 40121240,
		'run_stop2'    => 40122242,
		'run_start3'   => 40123244,
		'run_stop3'    => 40124246,
		'run_start4'   => 40125248,
		'run_stop4'    => 40126250,
		'run_interval' => 40118234,
		'run_duration' => 40117232
	];

	private $stp = [
		'pm1s' => 40008140,
		'pm1f' => 40008141,
		'pm2s' => 40008142,
		'pm2f' => 40008143,
		'pfs'  => 40008144,
		'pff'  => 40008145,
		'pps'  => 40008146,
		'ppf'  => 40008147,
		'ivs'  => 40008150,
		'ovs'  => 40008151,
		'dvs'  => 40008152,
		't1s'  => 40008153,
		't2s'  => 40008154
	];

	private $recData = [
		40011200 => 12,
		40117232 => 12,
		40120238 => 22
	];

 	public function __contruct($cid, $data){

 		$dataLen = substr($data, 0, 5); $dataOrigin = substr($data, 5);
 		if ($dataLen != strlen($dataOrigin)) return true;
		$this->cid = $cid;$this->pid = $data->a;$this->mf = $data->f;$this->md = $data->d;$this->ml = substr($data, 0,5);
 		if(property_exists($data, "s") && $data->s == 's') $this->ms = true;
 		$this->db = new Workerman\MySQL\Connection("localhost", 3306, "root", "root", "litchi");
 		switch ($this->mf) {
 			case '01':
 				return self::getParam();
 			
 			case '02':
 				return self::sendMsg();
 		}
 	}

 	private function getAllParameters(){
 		print_log('uplaod_param', $this->md, '上传所有参数');
 	}

 	private function sendMsg(){
 		print_log('send_param', $this->md, '下发参数')
 	}

}
