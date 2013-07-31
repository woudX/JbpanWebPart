<?php

/**
 * class class
 *
 * Description for class class
 *
 * @author:
*/
require_once('./Lib/Common/DiskModelFactory.class.php');

class DiskExtractAction extends DkAction {
	function index() {
		
		// 		var_dump($_GET['searchType']);
// 		var_dump($_GET['searchInfo']);
		if ($_GET['searchType'] == "search") {
 			header("Location:http://".C('SITE_URL')."/Search?key=".$_GET['searchInfo']);
		}
		else{
			$extractCode = isset($_GET['searchInfo']) ? trim($_GET['searchInfo']) : "";
			if(!preg_match('/^'.C('CODE_PREFIX').'\d+$/i',$extractCode)){
				$this->error(L('ArchivesNotExist'));
				return;
			}
			
			$archivesId = substr($extractCode,strlen(C('CODE_PREFIX')));
	
			$archivesModel = D('BaseDiskArchives');

			$archives = $archivesModel->find($archivesId);
		
			if(!$archives){
			//	$this->error(L('ArchivesNotExist'));
				$this->assign(muban,6);
				$this->display("User:inform");
				return;
			}
			$fq=$_GET['fq'];
			
			$UserModle=M('user');
			$uid=$_SESSION['uid'];
				
			$user=$UserModle->where("uid=$uid")->find();
			if  ($fq>0&&$_SESSION['uid']>0){
				if (strtotime($user['dateline'])+180<time()) {
					$dateModle=M('archives');
			
					$date=$dateModle->where("id=$archivesId")->find();
					$date['archivespoint']=$date['archivespoint']+$fq-2;
				$dateModle->where("id=$archivesId")->save($date);
		
				$user['dateline']=date("Y-m-d H:i:s",strtotime("+3 minutes"));
				$UserModle->where("uid=$uid")->save($user);
				}
			}
			$this->assign('extractCode',C('CODE_PREFIX').$archives['id']);
			$this->assign('archives',$archives);
			$this->display();
		}
				
	}
			
}

?>