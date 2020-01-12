<?php
try {
	require_once("WeaParse.php");
	$msg = "3A01000000F1000000050122010B0000000C0F061180AE0279EB0D";//79EB
	$wea = new WeaParse($msg);
	// $crc = crc(pack("H*", substr($msg, 2, -2)));
	// $t   = unpack("H*", $crc);
	// var_dump($t);exit;
	// $msg = '3A013A0000F1000000050122010B0000000C0F061180AE0d79EB0D';
	// $msg = '3A017e7f3b0000f1000000050122010b0000000c0f061180ae7e7f0e79eb0D';
	// $msg = strtolower($msg);
	// $s   = parseEscapeMsg(substr($msg, 2, -2));
	var_dump($wea);
	exit;
} catch (Exception $e) {
	var_dump($e->getCode(), $e->getMessage());
}

