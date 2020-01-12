<?php
require_once("Applications/common.php");
use Exception;

/**
 * 
 */
class WeaParse
{
	private $dev_id;
	private $tag;
	private $chan;
	private $data;
	private $data_len;
	private $crc;

	function __construct($msg){
		$msg = strtolower($msg);
		if (substr($msg, 0, 2) != '3a') {
			throw new Exception("error frame", 301);
		}
		if (substr($msg, -2, 2) != '0d') {
			throw new Exception("error frame", 302);
		}
		$msg = $this->converseEscape(substr($msg, 2, -2));
		$this->dev_id   = substr($msg, 0, 16);
		$this->tag      = substr($msg, 16, 4);
		$this->chan     = substr($msg, 20, 4);
		$this->data_len = substr($msg, 24, 4);
		$this->crc      = substr($msg, -6, 4);
		// if (crc(substr($msg, 2, -2)) != $this->crc) {
		// 	throw new Exception("error crc", 302);
		// }
		var_dump(self);
		// var_dump(self);exit;
		// foreach (self as $key => $value) {
			
		// }
		// return 'success';
	}

	private function toEscape($msg){
		if (strpos($msg, '3a')%2 == 0) {
			$msg = str_replace('3a', '7e3b', $msg);
		}
		if (strpos($msg, '0d')%2 == 0) {
			$msg = str_replace('0d', '7e0e', $msg);
		}
		if (strpos($msg, '7e')%2 == 0) {
			$msg = str_replace('7e', '7e7f', $msg);
		}
		return $msg;
	}

	private function converseEscape($msg){
		if (strpos($msg, '7e3b')%2 == 0) {
			$msg = str_replace('7e3b', '3a', $msg);
		}
		if (strpos($msg, '7e0e')%2 == 0) {
			$msg = str_replace('7e0e', '0d', $msg);
		}
		if (strpos($msg, '7e7f')%2 == 0) {
			$msg = str_replace('7e7f', '7e', $msg);
		}
		return $msg;
	}

}