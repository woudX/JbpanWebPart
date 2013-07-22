<?php
class UserAction extends DkAction {
 
	public function login() {
		$this->display ();
	}
	public function register() {
		$this->display ();
	}
	public function usercenter() {
		$uid = $_SESSION ['uid'];
		$this->checkLogin ();
		
		$avatar_big = "http://".C('SITE_URL' )."/ucenter/avatar.php?uid=$uid&size=big";
		$this->assign ( avatar_big, $avatar_big );
		$this->display ();
	}
	function personal_info_01() {
		// var_dump($_SESSION);
		$userdate = M ( 'user' );
		$uid = $_SESSION ["uid"];
		$user = $userdate->where ( "uid=$uid" )->find ();
		
		$info = array ();
		$info ['username'] = $_SESSION ['userdata'] ['username'];
		$info ['email'] = $_SESSION ['userdata'] ['email'];
		$info ['signature'] = $user ['introduce'];
		$info ['qq'] = $user ['QQ'];
		$info ['sex'] = $user ['sex'];
		$this->assign ( 'info', $info );
		
		$this->display ();
	}
	function personal_info_02() {
		$uid = $_SESSION ['uid'];
		
		$avatar_middle = "http://".C('SITE_URL' )."/ucenter/avatar.php?uid=$uid&size=middle";
		$this->assign ( avatar_middle, $avatar_middle );
		$html = uc_avatar ( $_SESSION ['uid'] );
	
		$this->assign ( html, $html );
	
		$this->display ();
	}

	function email_info_01() {
		// echo $uid=$_SESSION['uid'];
		// var_dump(uc_pm_send(10, 9, "22222d2222s22222 22222s" , "sad112we"));
		$uid = $_SESSION ['uid'];
		$list = uc_pm_list ( $uid, 1, 10, inbox, "privatepm", 10 );
		// var_dump($list);
		$maildateList = $list ['data'];
		
		$newpmlist = uc_pm_checknew ( $uid, 1 );
		$newpm = $newpmlist ['newpm'];
		
		$this->assign ( newpm, $newpm );
		
		$this->assign ( maildateList, $maildateList );
		$avatar_big = "http://".C('SITE_URL' )."/ucenter/avatar.php?uid=$uid&size=big";
		
		$this->assign ( avatar_big, $avatar_big );
		$this->display ();
	}
	function email_info_02() {
		$uid = $_SESSION ['uid'];
		$avatar_big = "http://".C('SITE_URL' )."/ucenter/avatar.php?uid=$uid&size=big";
		$this->assign ( avatar_big, $avatar_big );
		
		$this->display ();
	}
	function send_site_mail() {
		$uid = $_SESSION ['uid'];
		
		$title = $_POST ['title'];
		
		$reciever = $_POST ['reciever'];
		$reciever = uc_get_user ( $reciever );
		$reciever = $reciever [0];
		$content = $_POST ['content'];
		
		$pmid = uc_pm_send ( $uid, $reciever, $title, $content );
		$uc_pm_title = M ( "uc_pm_title" );
		
		$pm_title_date ['pmid'] = $pmid;
		$pm_title_date ['title'] = $title;
		
		$uc_pm_title->add ( $pm_title_date );
	}
	function email_info_01List() {
		$uid = $_SESSION ['uid'];
		$chatlist = uc_pm_view ( $_SESSION ['uid'], 0, $_GET ['touid'] );
		$uc_pm_title = M ( "uc_pm_title" );
		$userchatlist = array ();
		foreach ( $chatlist as $userchat ) {
			$pmid = $userchat ['pmid'];
			$titlelist = $uc_pm_title->where ( "pmid=$pmid" )->find ();
			$userchat ["title"] = $titlelist ['title'];
			array_push ( $userchatlist, $userchat );
		}
		$msgfrom = uc_get_user ( $_GET ['touid'], 1 );
		$avatar_big = "http://".C('SITE_URL' )."/ucenter/avatar.php?uid=$uid&size=big";
		$this->assign ( avatar_big, $avatar_big );
		$this->assign ( msgfrom, $msgfrom [1] );
		$this->assign ( userchatlist, $userchatlist );
		$this->display ();
	}
	function email_info_01x() {
		$mail = uc_pm_viewnode ( $_SESSION ['uid'], 0, $_GET ['pmid'] );
		// var_dump($mail['msgtoid']);
		$msgto = uc_get_user ( $mail ['msgtoid'], 1 );
		// var_dump($msgto);
		$this->assign ( mail, $mail );
		$this->assign ( msgto, $msgto [1] );
		$this->display ();
	}
	function pmm() {
		uc_pm_location ();
	}
	function delete_all_box() {
		$uid = $_SESSION ['uid'];
		
		$list = uc_pm_list ( $uid = $_SESSION ['uid'], 1, 10, inbox, "privatepm", 10 );
		$maildateList = $list ['data'];
		$touid = array ();
		foreach ( $maildateList as $a ) {
			array_push ( $touid, $a ['msgtoid'] );
		}
		uc_pm_deleteuser ( $uid, $touid );
		header ( "Location:http://" . C('SITE_URL' )/user );
		exit ();
	}
	function isread() {
		$uid = $_SESSION ['uid'];
		
		$list = uc_pm_list ( $uid = $_SESSION ['uid'], 1, 10, inbox, "privatepm", 10 );
		$maildateList = $list ['data'];
		$touid = array ();
		foreach ( $maildateList as $a ) {
			array_push ( $touid, $a ['msgtoid'] );
		}
		
		uc_pm_readstatus ( $uid, $touid );
		header ( "Location:http://" . C('SITE_URL' ) / user );
		exit ();
	}
	function skydriver_info_02() {
		$uid = $_SESSION ['uid'];
		$Data = M ( "archives" );
		import ( 'ORG.Util.Page' ); // 导入分页类
		$count = $Data->where ( "uid=$uid" )->count (); // 查询满足要求的总记录数
		$Page = new Page ( $count, 16 ); // 实例化分页类 传入总记录数
		                                   // 进行分页数据查询 注意page方法的参数的前面部分是当前的页数使用 $_GET[p]获取
		$nowPage = isset ( $_GET ['p'] ) ? $_GET ['p'] : 1;
		$list = $Data->where ( "uid=$uid" )->order ( 'dateline' )->page ( $nowPage . ',' . $Page->listRows )->select ();
		$show = $Page->show (); // 分页显示输出
		
		$this->assign ( 'page', $show ); // 赋值分页输出
		$this->assign ( 'archiveslist', $list ); // 赋值数据集
		                                     
		// $archiveslist=$archives->where("uid=$uid")->select();
		
		$this->assign ( 'codePrefix', C ( 'CODE_PREFIX' ) );
		// $this->assign('archiveslist',$archiveslist);
		$this->display ();
	}
	public function inform() {
		$muban = $_GET ['inform'];
		if ($muban == 4) {
			$helpinform = "诶呀呀，您尚未登录，请先登陆后在进行以下操作";
			$location = "__APP__/User/register";
		} else {
			$helpinform = "诶呀呀，您尚未登录，请先登陆后在进行以下操作";
			$location = "__APP__/User/register";
		}
		
		$this->assign ( muban, $muban );
		$this->assign ( helpinform, $helpinform );
		$this->assign ( location, $location );
		$this->display ();
	}
	function edit_user() {
		$uid = $_SESSION ["uid"];
		$userdate = M ( 'user' );
		$uid = $_SESSION ["uid"];
		$user = $userdate->where ( "uid=$uid" )->find ();
		$user ["QQ"] = ( int ) $_POST ['QQ'];
		$user ['introduce'] = $_POST ['introduction'];
		$user ['sex'] = $_POST ['sex'];
		
		if ($userdate->where ( "uid=$uid" )->save ( $user )) {
			header ( "Location:http://" . C('SITE_URL' ) . "/User/usercenter" );
		} else {
			echo "修改失败";
		}
	}
}
?>