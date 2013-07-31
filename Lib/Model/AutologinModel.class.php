<?php 
class AutologinModel extends Model {
	public static $COOKIE_UID_KEY = "uid";
	public static $COOKIE_SECERT_KEY = "sid";
	private $expire = 315360000; //十年
	
	function setAutoLogin($uid){
		$data['uid'] = $uid;
		$data['secretkey'] = md5(rand(0, 99999999));
		$old = $this->where('uid='.uid)->find();
		
		if(empty($old)){
			$this->add($data);
		}
		else {
			$this->save($data);
		}
		
		setcookie(self::$COOKIE_UID_KEY, $uid, time() +  $this->expire, '/');
		setcookie(self::$COOKIE_SECERT_KEY, $data['secretkey'], time() +  $this->expire, '/');
	}
	
	function clearAutoLogin(){
		setcookie(self::$COOKIE_SECERT_KEY, "", time() - 1000000, '/');
	}
	
	function checkAutoLogin($uid, $secretKey){
		$data["uid"] = $uid;
		$data["secretkey"] = $secretKey;
		return 1 && $this->where($data)->find();
	}
}
?>