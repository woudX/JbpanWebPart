<?php

/**
 * class class
 *
 * Description for class class
 *
 * @author:
*/
require_once('./Lib/Common/DiskModelFactory.class.php');

class DownloadAction extends DkAction {

	function index() {
		$extractCode = isset($_GET['code']) ? trim($_GET['code']) : "";
		if(!preg_match('/^'.C('CODE_PREFIX').'\d+$/i',$extractCode)){
			$this->display('Public:downloaderror');
			return;
		}
		
		$archivesId = substr($extractCode,strlen(C('CODE_PREFIX')));
		
		$archivesModel = D('BaseDiskArchives');

		$archives = $archivesModel->find($archivesId);
		if(!$archives){
			$this->display('Public:downloaderror');
			return;
		}

		$diskMode = DiskModelFactory::getDiskModel($archives['diskname']);
		
		$param['url'] = $archives['diskurl'];
		
		$realFileUrl = $diskMode->getRealFileUrl($param);

		//原来失效，现在可以的话设置有效位为1
		if($archives['iseffective'] == 0 && (is_array($realFileUrl) || $realFileUrl == "needcheck")){
			$archivesModel->setEffective(1,$archivesId);
		}
		
		echo json_encode($realFileUrl);
	}
	
	//下载计数
	function _t(){
		$k = "asfdtr12#e";

		$extractCode = isset($_GET['code']) ? trim($_GET['code']) : "";
		if(!preg_match('/^'.C('CODE_PREFIX').'\d+$/i',$extractCode)){
			return;
		}

		$archivesId = substr($extractCode,strlen(C('CODE_PREFIX')));
		$cookieKey = '_t['.md5($extractCode.$k).']';

		if(!isset($_COOKIE['_t'][md5($extractCode.$k)])){
			$archivesModel = D('BaseDiskArchives');
			$archivesModel->downloadsInc($archivesId);
			setcookie($cookieKey, uniqid(), $_SERVER['REQUEST_TIME']+3600*24);
		}
	}
}

?>