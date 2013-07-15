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
				$this->error(L('ArchivesNotExist'));
				return;
			}
			
			$this->assign('extractCode',C('CODE_PREFIX').$archives['id']);
			$this->assign('archives',$archives);
			$this->display('');
		}
				
	}
			
}

?>