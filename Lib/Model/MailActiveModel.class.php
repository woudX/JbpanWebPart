<?php

class MailActiveModel extends Model {
	//默认1小时过期
	static private $DEFAULT_EXPIRE = 7200;
	
	function createCode($email, $expire = null){
		if($expire == null) $expire = self::$DEFAULT_EXPIRE;
		
		$where['email'] = $email;
		$old = $this->where($where)->find();
		
		$data['email'] = $email;
		$data['encrycode'] = md5(uniqid().dechex(rand(0x1000,0xFFFF)));
		$data['expire'] = date('Y-m-d H:i:s',time()+$expire);
		
		if(empty($old)){
			$this->add($data);
		}
		else{
			$this->save($data);
		}
		
		return $data['encrycode'];
	}
	
	function checkCode($email, $encryCode){
		$where['email'] = $email;
		$where['encrycode'] = $encryCode;
		$where['expire'] = array('egt',date('Y-m-d H:i:s'));
		$old = $this->where($where)->find();

		return !empty($old);
	}
	
	function removeCode($email){
		$where['email'] = $email;
		$old = $this->where($where)->delete();
	}
}

?>