<?php

class DkAction extends Action {
	protected $dkUserMode;
	protected $isLogin;
	protected $loginData;
	
	function DkAction(){
		parent::__construct();

		$this->dkUserMode = D("DkUser");
		$this->loginData = $this->dkUserMode->getLoginData();
		$this->isLogin = true && $this->loginData;
				$clientIp = BaseDiskArchivesModel::getClientIp();
				$this->assign('clientIp',$clientIp);
        		$this->assign("isLogin", $this->isLogin);
        		$this->assign("loginData",$this->loginData);
	}
	
	protected function checkLogin(){
		if(!$this->isLogin)
			header('Location: http://'.C('SITE_URL').'/User/inform');
	} 
}

?>