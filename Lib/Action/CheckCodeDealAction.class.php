<?php

/**
 * class class
 *
 * Description for class class
 *
 * @author:
*/
require_once('./Lib/Common/DiskModelFactory.class.php');

class CheckCodeDealAction extends DkAction {

	function index(){		
		$extractCode = isset($_GET['code']) ? trim($_GET['code']) : "";
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
		
		$diskMode = DiskModelFactory::getDiskModel($archives['diskname']);
		
		$param['url'] = $archives['diskurl'];
		$diskMode->checkcodeDeal($param);
	}
}

?>