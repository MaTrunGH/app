<?php
namespace app\admin\controller;
use think\Controller;
// use Workerman\Worker;
// use Workerman\Lib\Timer;
// include __DIR__ . '/../vendor/autoload.php';

/**
 * 设备控制
 */
class Event extends Controller{

	function __construct(){
		// $channel_server = new Channel\Server();
		// $worker = new Worker();
		// $worker->onWorkerStart = function()
		// {
		//     Channel\Client::on('test event', function($event_data){
		//         echo 'test event triggered event_data :';
		//         var_dump($event_data);
		//     });
		//     Timer::add(5, function(){
		//         Channel\Client::publish('test event', 'some data');
		//     });
		// };

		// Worker::runAll();
	}
}