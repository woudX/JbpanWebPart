<?php

class IpAmendAction extends DkAction {

	function index(){		
		$this->assign(re,9);
		$this->display("User:register");
	}
	
	function submit(){
		
		$ip = isset($_GET['p']) ? $_GET['p'] : "";
		
		if($ip == "" || !preg_match('/^(([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]).){3}([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/',$ip)){
			
			
			$this->error(L('IPError'));
			return;
		}
		
		$_COOKIE['IpAmend'] = $ip;
		
		setcookie('IpAmend',$_COOKIE['IpAmend'], time()+7200 , "/");

		$this->display();
		
	}
}
