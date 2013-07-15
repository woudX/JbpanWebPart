<?php
class PremissionModel extends Model {
	static private $PremissionIdMap = null;
	static private $PremissionNameMap = null;
	
	//设置映射表
	private function setPremissionMap(){
		$prems = $this->findAll();
		
		$_prems = array();
		foreach ($prems as $k => $v){
			$_prems[$v['premid']] = $v;
		}
		self::$PremissionIdMap = $_prems;
		
		$_prems = array();
		foreach ($prems as $k => $v){
			$_prems[$v['premname']] = $v;
		}
		self::$PremissionNameMap = $_prems;
	}
	
	//获得权限标识
	function getPremNameFromId($premid){
		if(self::$PremissionIdMap == null)$this->setPremissionMap();
		return isset(self::$PremissionIdMap[$premid]) ? self::$PremissionIdMap[$premid]['premname'] : false;
	}
	
	//获得权限ID
	function getIdFromPremName($premName){
		if(self::$PremissionNameMap == null)$this->setPremissionMap();
		return isset(self::$PremissionNameMap[$premName]) ? self::$PremissionNameMap[$premName]['premid'] : false;
	}
}
?>