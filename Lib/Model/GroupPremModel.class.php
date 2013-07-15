<?php 
class GroupPremModel extends Model {
	function getGroupPrem($groupids){
		if(gettype($groupids) == 'array'){
			$groupids = implode(',', $groupids);
		}
		
		$result = $this->query("SELECT DISTINCT premid FROM dk_group_prem WHERE groupid IN($groupids)");
		$premArr = array();
		foreach ($result as $k => $v){
			array_push($premArr, $v['premid']);
		}
		return $premArr;
	}
}
?>