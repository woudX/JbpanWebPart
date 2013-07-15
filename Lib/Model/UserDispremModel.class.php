<?php 
class UserDispremModel extends Model {
	
	function getUserDisprem($uid){
		$where['uid'] = $uid;
		$result = $this->field('premid')->where($where)->findAll();
		
		$premArr = array();
		foreach ($result as $k => $v){
			array_push($premArr, $v['premid']);
		}
		return $premArr;
	}
	
}
?>