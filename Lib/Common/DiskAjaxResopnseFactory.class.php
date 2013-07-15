<?php
class DiskAjaxResopnseFactory {
	//code -1：失效
	//code 0：成功
	//code 1：需要验证
	
	static private $callbackScript = null;

	static function getSuccessResponse($data, $message = ""){
		$response = null;
		
		$response->code = 0;
		$response->data = $data;
		$response->message = $message;
		$response->callback = self::$callbackScript;
		
		return $response;
	}
	
	static function getFailResponse($message = ""){
		$response = null;
	
		$response->code = -1;
		$response->message = $message;
		$response->callback = self::$callbackScript;
	
		return $response;
	}
	
	static function getCheckResponse($message = ""){
		$response = null;
	
		$response->code = 1;
		$response->message = $message;
		$response->callback = self::$callbackScript;
	
		return $response;
	}
	
	static function setCallbackScript($scriptStr){
		self::$callbackScript = $scriptStr;
	}
}

?>