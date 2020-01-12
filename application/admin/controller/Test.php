<?php
namespace app\admin\controller;
use think\Controller;
use app\admin\model\DPlc;
use think\Config;
header("Content-Type: text/html;charset=utf-8");
/**
 * 
 */
class Test extends Controller{

	public function test(){
		try {
			$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
			socket_connect($socket, config('ip'), config('port'));
			$in = "sfs3213213\n";
			socket_write($socket, $in, strlen($in));
			socket_close($socket);
		} catch (\Exception $e) {
			var_dump($e->getMessage());
		}
	}

	public function test1(){
		$sm['d'] = "a";
		$sm['f'] = "b";
		$c = json_encode($sm);
		$m = json_decode($c);
		$s = property_exists($m, "d");
		var_dump($s);exit;
		$this->view->engine(false);
		return $this->fetch();
	}

}