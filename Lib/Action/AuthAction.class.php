<?php
class AuthAction extends DkAction
{	
	private $checkNameErr = array(
		-1=>"用户名不合法",
		-2=>"包含要允许注册的词语",
		-3=>"用户名已经存在"
	);
	
	private $registerErr = array(
		-1=>"用户名不合法",
		-2=>"包含不允许注册的词语",
		-3=>"用户名已经存在",
		-4=>"Email 格式有误",
		-5=>"Email 不允许注册",
		-6=>"该 Email 已经被注册"
	);
	
	private $checkEmailErr = array(
		-4=>"Email 格式有误",
		-5=>"Email 不允许注册",
		-6=>"该 Email 已经被注册"
	);
	
	public function index(){
		Session::set(C('SESSION_USER_KEY'), 37);
		Import("@.Common.PremAccess");


	}
	
	private $loginErr = array(
			-1=>"用户不存在，或者被删除",
			-2=>"密码错误",
			-3=>"安全提问错"
	);
	
    public function login()
    {
    	if(isset($_POST['submit'])){
    		$username = htmlspecialchars(trim($_POST['username']));
    		$password = htmlspecialchars(trim($_POST['password']));    		    	
    			$isAutoLogin = intval($_POST['autologin']);    		
    		$loginResult = uc_user_login($username, $password);
    		if($loginResult[0] > 0){
    			list($uid, $username, $password, $email) = $loginResult;
    			$userData['uid'] = $loginResult[0];
    			$userData['username'] = $loginResult[1];
    			$userData['email'] = $loginResult[3];
    			
    			$dkUserModel = D('DkUser');
    			$dkUserModel->login($uid,$userData);
    			if($isAutoLogin == 1)D('Autologin')->setAutologin($uid);
    			$dkUserModel = D("DkUser");
				header("Location:http://".C('SITE_URL')."/User/inform?inform=1");
    		}
    		else {
    			$this->error($this->loginErr[$loginResult[0]]);
    		}
    	}
    	else {
    		$this->display();
    	}
    }

	public function logout()
	{		if($this->isLogin){			D('Autologin')->clearAutologin($this->loginData['uid']);						$dkUserModel = D('DkUser');			$dkUserModel->logout();		}
		header("Location:http://".C('SITE_URL'));;
	}
	
	//显示邮箱注册页面
	public function register(){
		$this->display();
	}
	
	//邮箱注册结果
	function register2(){
		Import("@.Common.MailRemind");
		if(!isset($_POST['email']))return;
		$email = htmlspecialchars(trim($_POST['email']));
		
		$checkmail = uc_user_checkemail($email);
		if($checkmail != 1){
			$this->error($this->checkEmailErr[$checkmail]);
		}

		$mailActiveModel = D('MailActive');

	/*	echo "已发送邮件<br/>";*/
		$url= 'http://'.C('SITE_URL').__URL__.'/register3/email/'.urlencode($email).'/encrycode/'.$mailActiveModel->createCode($email);
	
	
	if( MailRemind::send_Email("注册链接","$url","$email")==TRUE){
		$this->assign(muban,'2');
		$this->display("User:inform");
	}

	}
	
	//注册信息填写页面
	function register3(){
		$email = htmlspecialchars(trim($_GET['email']));
		$encrycode = htmlspecialchars(trim($_GET['encrycode']));
		
		$checkmail = uc_user_checkemail($email);
		if($checkmail != 1){
			$this->error($this->checkEmailErr[$checkmail]);
		}
		
		$mailActiveModel = D('MailActive');

		if(!$mailActiveModel->checkCode($email,$encrycode)){
			$this->error("该地址已失效，请重新申请！");
		}
		$this->assign(re,"1");
		$this->assign("email",$email);
		$this->assign("encryCode",$encrycode);
		$this->display("User:register");
	}
	
	//最终注册结果
	function register4(){
		$email = htmlspecialchars(trim($_POST['email']));
		$encryCode = htmlspecialchars(trim($_POST['encrycode']));
		
		$username = htmlspecialchars(trim($_POST['username']));
		
		$password = htmlspecialchars(trim($_POST['password']));
		$repassword = htmlspecialchars(trim($_POST['repassword']));
		
		if($password != $repassword){
			$this->error("两次密码不相同，请重新输入！");
		}
		
		$mailActiveModel = D('MailActive');
		if(!$mailActiveModel->checkCode($email,$encryCode)){
// 			$this->error("该地址已失效，请重新申请！");
		}
		
		$registerId = uc_user_register($username,$password,$email);
		if($registerId > 0){
			$userModel = D('DkUser');
			$userModel->register($registerId,$username,$email);
			$userModel->joinToGroup($registerId,1);
			$mailActiveModel->removeCode($email);
				header("Location:http://".C('SITE_URL')."/User/inform?inform=3");
		}
		else {
			$this->error($this->registerErr[$registerId]);
		}
	}
	
	
	function user_edit() {
			
		$oldpassword=htmlspecialchars(trim($_POST['oldpassword']));
		$password=htmlspecialchars(trim($_POST['password']));
		$email=htmlspecialchars(trim($_SESSION['userdata']['email']));
		$username=htmlspecialchars(trim($_SESSION['userdata']['username']));
			
	
		$ucresult = uc_user_edit($username, $oldpassword, $password,$email);
		echo $ucresult;
		if($ucresult == -1) {
			echo '旧密码不正确';
		} elseif($ucresult == -4) {
			echo 'Email 格式有误';
		} elseif($ucresult == -5) {
			echo 'Email 不允许注册';
		} elseif($ucresult == -6) {
			echo '该 Email 已经被注册';
		} elseif ($ucresult==1){
			echo '修改成功，请用新密码重新登录';

		}
		
		
}

}
?>