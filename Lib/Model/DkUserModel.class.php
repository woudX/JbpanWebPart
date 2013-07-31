<?php 
class DkUserModel extends Model {
	protected $tableName = "user";
	private $userDataSessionKey = "userdata";
	
	function register($uid, $username, $email){
		$data['uid'] = $uid;
		$data['username'] = $username;
		$data['email'] = $email;
		return $this->add($data);
	}
	
	//登录时将用户信息放入Session
	function login($uid, $addInfo){
		$where['uid'] = $uid;
		$userData = $this->where($where)->find();
		$userData = array_merge($addInfo,$userData);
		Session::set(C('SESSION_USER_KEY'), $uid);
		Session::set($this->userDataSessionKey, $userData);
	}
	
	function logout($userName){
		Session::destroy();
	}
	
	function getUser($user){
		if(is_numeric($user)){
			$where['uid'] = $user;
		}
		else {
			$where['username'] = $user;
		}
		
		return $this->where($where)->find();
	}
	
	function getLoginData(){
		if(!Session::get(C('SESSION_USER_KEY')))return false;
		else return Session::get($this->userDataSessionKey);
	}
	
	function joinToGroup($uid, $group){
		$userToGroupModel = D('UserToGroup');
		return $userToGroupModel->userJoinGroup($uid, $group);
	}
	
	function AllArchives($uid){
		
		$where['uid'] = $uid;
		$user = $this->where($where)->find();
		
		$user['allfile']=$user['allfile']+1;
	
		$this->save($user);
	}
	function AlterArchivesFile($uid,$lose=true) {
		
		if ($lose){
			$where['uid'] = $uid;
			$user = $this->where($where)->find();
			
			$user['losefile']=$user['losefile']+1;
		
			$this->save($user);
		}
		else {
			$where['uid'] = $uid;
			$user = $this->where($where)->find();
			
			$user['losefile']=$user['losefile']-1;
		
			$this->save($user);
		}
	}
	
	function GetArchivesPoint($uid) {
		echo $uid;
			$where['uid'] = $uid;
			$user = $this->where($where)->find();

			$filepoint=ceil(100*(1-($user['losefile']/($user['allfile']-$user['losefile']))));
			
			return $filepoint;
	}
	
	
}
?>