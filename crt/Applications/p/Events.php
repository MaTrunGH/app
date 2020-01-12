<?php
use \GatewayWorker\Lib\Gateway;
use \app\plc\Plc;
use \app\plc\Web;

class Events{

   public static function onMessage($cid, $m){
     $plcModel = new Plc($cid, $m);
     
   }

}
