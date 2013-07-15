<?php

class PremAccess  {
	static private $PREM_KEY = 'dk_Premission';
	static private $ADMIN_PREM_NAME = 'administrator';
	
	//访问某权限
	static public function access( $premNames ){	
		$userKey = C('SESSION_USER_KEY');
		$uid = Session::get($userKey);
		
		if(empty($uid))return false;
		
		$premNameArr = explode('|', $premNames);

		$premissions = Session::get(self::$PREM_KEY);
		
		//查询数据库，将权限放在session
		if(C('PREMISSION_MODE') == 1 || !is_array($premissions)){
			self::setUserPremSession( $uid );
			$premissions = Session::get(self::$PREM_KEY);
		}
		
		//拥有administrator权限直接允许
		if($premissions[self::$ADMIN_PREM_NAME])return true;
		
		foreach ($premNameArr as $v){
			if($v != ''){
				if(!isset($premissions[$v]))return false;
			}
		}
		return true;
	}
	
	static private function setUserPremSession( $uid ){
		$finallyPremission = array();
		
		//获得自身权限
		$userPremModel = D('UserPrem');
		$userPrems = $userPremModel->getUserPrem($uid);

		//获得用户所属组
		$UserToGroupModel = D('UserToGroup');
		$userGroups = $UserToGroupModel->getUserGroups($uid);
		
		//获得用户组权限
		$groupPremModel = D('GroupPrem');
		$groupPrems = $groupPremModel->getGroupPrem($userGroups);
		
		
		//获得反向权限
		$userDisremModel = D('UserDisprem');
		$userdisprems = $userDisremModel->getUserDisprem($uid);
		
		//合并组权限与自身权限
		$finallyPremission = self::arrayMergeUnique($userPrems,$groupPrems);
		
		//删除反向权限
		$finallyPremission = array_diff($finallyPremission, $userdisprems);
		
		$PremissionModel = D('Premission');
		
		$finallyPremissionMap = array();
		foreach ($finallyPremission as $v){
			$finallyPremissionMap[$PremissionModel->getPremNameFromId($v)] = true;
		}
		
		Session::set(self::$PREM_KEY, $finallyPremissionMap);
	}
	
	static private function arrayMergeUnique($arr1,$arr2){
		foreach ($arr2 as $k => $v){
			if(!in_array($v, $arr1))array_push($arr1, $v);
		}
		return $arr1;
	}
}

?>