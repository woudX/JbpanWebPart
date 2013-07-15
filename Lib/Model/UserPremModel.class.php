<?php 
class UserPremModel extends Model {
	function getUserPrem($uid){
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