<?php

class DkAction extends Action {
	protected $dkUserMode;
	protected $isLogin;
	protected $loginData;
	
	function DkAction(){
		parent::__construct();

		$this->dkUserMode = D("DkUser");				$this->loginData = $this->dkUserMode->getLoginData();
		$this->isLogin = true && $this->loginData;				if(!$this->isLogin){			$this->autoLogin();			$this->loginData = $this->dkUserMode->getLoginData();
			$this->isLogin = true && $this->loginData;		}
		$clientIp = BaseDiskArchivesModel::getClientIp();
		$this->assign('clientIp',$clientIp);
        $this->assign("isLogin", $this->isLogin);
        $this->assign("loginData",$this->loginData);
	}
	
	protected function checkLogin(){
		if(!$this->isLogin)
			header('Location: http://'.C('SITE_URL').'/User/inform');
	} 		protected function autoLogin(){
		$uid = isset($_COOKIE[AutologinModel::$COOKIE_UID_KEY]) ? $_COOKIE[AutologinModel::$COOKIE_UID_KEY] : false;
		$key = isset($_COOKIE[AutologinModel::$COOKIE_SECERT_KEY]) ? $_COOKIE[AutologinModel::$COOKIE_SECERT_KEY] : false;
	
		if($uid && $key && D('Autologin')->checkAutoLogin($uid,$key)){
			$user = uc_get_user($uid, true);
			if($user){
				$this->dkUserMode->login($uid, $user);			}		}	}
}

?>