<?php
class UserAction extends DKAction
{
    public function login()
    {
		$this->display();
    }
	
	public function register()
    {
		$this->display();
    }
	
	public function usercenter()
	{
		$uid=$_SESSION['uid'];


		$avatar_big="http://localhost/ucenter/avatar.php?uid=$uid&size=big";
		$this->assign(avatar_big,$avatar_big);
		$this->display();
	}
	function personal_info_01(){
	//	var_dump($_SESSION);
		$info = array();
		$info['username']=$_SESSION['userdata']['username'];
		$info['email']=$_SESSION['userdata']['email'];
		$info['signature']="签名签名签名签名签名签名签名签名签名签名签名签名";
		$info['qq']="123456789";
		$info['sex']= "woman";
		$this->assign('info',$info);

		$this->display();
		
		
	}
	function personal_info_02() {
	$uid=$_SESSION['uid'];

	$avatar_middle="http://localhost/ucenter/avatar.php?uid=$uid&size=middle";
	$this->assign(avatar_middle,$avatar_middle);
	$html=		uc_avatar($_SESSION['uid']);
	$this->assign(html,$html);
	$this->display();
	}

	function email_info_01(){
	//echo $uid=$_SESSION['uid'];
	  //  var_dump(uc_pm_send(10, 9, "22222d2222s22222 22222s" , "sad112we"));
		$uid=$_SESSION['uid'];
	    $list=uc_pm_list( $uid, 1, 10,inbox,"privatepm",10);
	   // var_dump($list);
	    $maildateList = $list['data'];
	
	    
	    
	  $newpmlist=  uc_pm_checknew($uid,1);
	  $newpm=$newpmlist['newpm'];

	  $this->assign(newpm,$newpm);

	    $this->assign(maildateList,$maildateList);
	    $avatar_big="http://localhost/ucenter/avatar.php?uid=$uid&size=big";
	
	    $this->assign(avatar_big,$avatar_big);
	    $this->display();

	}
	function email_info_02(){
	
		$uid=$_SESSION['uid'];
	
		$avatar_big="http://localhost/ucenter/avatar.php?uid=$uid&size=big";
		$this->assign(avatar_big,$avatar_big);

		$this->display();
	
	}
	
	function send_site_mail() {
		$uid=$_SESSION['uid'];
	
		$title=$_POST['title'];
		
		$reciever=$_POST['reciever'];
		$reciever=uc_get_user($reciever);
		$reciever=$reciever[0];
		$content=$_POST['content'];
		
		$pmid=uc_pm_send($uid,$reciever,$title,$content);
		$uc_pm_title     = M("uc_pm_title");

		$pm_title_date['pmid']=$pmid;
		$pm_title_date['title']=$title;
		
		$uc_pm_title->add($pm_title_date);
	
		}



		function email_info_01List() {
             $uid=$_SESSION['uid'];
			  $chatlist=uc_pm_view($_SESSION['uid'],0,$_GET['touid']);
			  $uc_pm_title     = M("uc_pm_title");
			  $userchatlist=array();
			 foreach ($chatlist as $userchat){
				 	$pmid=$userchat['pmid'];
				 	$titlelist=$uc_pm_title->where("pmid=$pmid")->find();
				 	$userchat["title"]=$titlelist['title'];
				 	array_push($userchatlist,$userchat);
			
				 }
                $msgfrom=uc_get_user($_GET['touid'],1);
                $avatar_big="http://localhost/ucenter/avatar.php?uid=$uid&size=big";
             $this->assign(avatar_big,$avatar_big);
              $this->assign(msgfrom,$msgfrom[1]);
			  $this->assign(userchatlist,$userchatlist);
			$this->display();
		}
		
		function email_info_01x() {

		$mail=uc_pm_viewnode($_SESSION['uid'],0,$_GET['pmid']);
	//	var_dump($mail['msgtoid']);
		$msgto=uc_get_user($mail['msgtoid'],1);
	//	var_dump($msgto);
		$this->assign(mail,$mail);
		$this->assign(msgto,$msgto[1]);
		$this->display();
		}


		function pmm() {
			uc_pm_location();
		}
	
		function delete_all_box() {
			
			$uid=$_SESSION['uid'];
		
			$list=uc_pm_list( $uid=$_SESSION['uid'], 1, 10,inbox,"privatepm",10);
			$maildateList = $list['data'];
			$touid=array();
			 foreach ($maildateList as $a){
				array_push($touid,$a['msgtoid']);
			 }
			uc_pm_deleteuser($uid, $touid);
			header("Location:http://".C('SITE_URL')/user);
		exit;
		}
		
		
		function isread() {
			$uid=$_SESSION['uid'];
				
			$list=uc_pm_list( $uid=$_SESSION['uid'], 1, 10,inbox,"privatepm",10);
			$maildateList = $list['data'];
			$touid=array();
			foreach ($maildateList as $a){
				array_push($touid,$a['msgtoid']);
			}
				
			uc_pm_readstatus($uid, $touid);
			header("Location:http://".C('SITE_URL')/user);
			exit;
		}
		
		function skydriver_info_02() {
			$uid=$_SESSION['uid'];
			$archives = M("archives");
			$archiveslist=$archives->where("uid=$uid")->select();

			$this->assign('codePrefix',C('CODE_PREFIX'));
			$this->assign('archiveslist',$archiveslist);
			$this->display();
		}

}
?>