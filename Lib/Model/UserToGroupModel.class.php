<?php
class UserToGroupModel extends Model {
	function getUserGroups($uid){
		$where['uid'] = $uid;
		$result = $this->where($where)->findAll();
		
		$groups = array();
		foreach ($result as $v){
			array_push($groups, $v['groupid']);
		}
		
		return $groups;
	}
	
	function userJoinGroup($uid, $groupid){
		$data['groupid'] = $groupid;
		$data['uid'] = $uid;
		
		return $this->add($data);
	}
}
