<?php
require_once('./Lib/Common/DiskAjaxResopnseFactory.class.php');
require_once('./Lib/Tools/Torrent.class.php');

class TorrentAction extends DkAction {

	function index(){

		//if(!getenv('HTTP_REFERER'))return ;
		
		import("ORG.Net.UploadFile");
		$upload = new UploadFile(); // 实例化上传类
		$upload->maxSize = 1024000 ; // 讴置附件上传大小
		$upload->allowExts = array('torrent'); // 讴置附件上传类型
		$upload->savePath = './Runtime/Torrents/'; // 讴置附件上传目录
		$upload->saveRule = 'uniqid';
		if(!$upload->upload()) { // 上传错诣 提示错诣信息
			echo $this->getAjaxJsCode(DiskAjaxResopnseFactory::getFailResponse($upload->getErrorMsg()));
			return;
		}else{
			$info = $upload->getUploadFileInfo();
		}

		$torrent = new Torrent($info[0]['savepath'] . $info[0]['savename']);
		if(!$torrent->size()){
			unlink($info[0]['savepath'] . $info[0]['savename']);
			echo $this->getAjaxJsCode(DiskAjaxResopnseFactory::getFailResponse(L('TorrentParseFail')));
			return;
		}
		
		$data->magnetUri = "magnet:?xt=urn:btih:" . $torrent->hash_info();
		$data->fileSize = $this->sizeformat($torrent->size());
		$data->fileName = $torrent->name();
		
		unlink($info[0]['savepath'] . $info[0]['savename']);
		echo $this->getAjaxJsCode(DiskAjaxResopnseFactory::getSuccessResponse($data));
	}

	function hasvideo(){
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
		$hash_info = strtoupper(substr($archives['diskurl'], 20));
	
		$playInfoUrl = 'http://i.vod.xunlei.com/req_subBT/info_hash/'.$hash_info.'/req_num/1000/req_offset/0/';
		$playInfo = json_decode(file_get_contents($playInfoUrl));
		if($playInfo && $playInfo->resp->record_num > 0){
			echo "true";
		}
		else {
			echo "false";
		}
	}
	
	function play(){
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
		$hash_info = strtoupper(substr($archives['diskurl'], 20));
	
		//http://i.vod.xunlei.com/req_subBT/info_hash/D28DE702D4682F72488E69FF9925E57A6C8FFFF4/req_num/1000/req_offset/0/
		$playInfoUrl = 'http://i.vod.xunlei.com/req_subBT/info_hash/'.$hash_info.'/req_num/1000/req_offset/0/';
		$playInfo = json_decode(file_get_contents($playInfoUrl));
		if($playInfo && $playInfo->resp->record_num > 0){
				
			$fileList = $playInfo->resp->subfile_list;
				
			//http://vod.kcplayer.com/vod_player.html?url=bt://D28DE702D4682F72488E69FF9925E57A6C8FFFF4/4
			if(0 && $playInfo->resp->record_num == 1){
				header("Location: http://vod.kcplayer.com/vod_player.html?url=bt://$hash_info/".$fileList[0]->index);
			}
			else {
				$playUrlInfos = array();
				foreach ($fileList as $key => $file){
					$playUrlInfos[$key]['name'] = urldecode($file->name);
					$playUrlInfos[$key]['playUrl'] = "http://vod.kcplayer.com/vod_player.html?url=bt://$hash_info/$file->index";
				}
				$this->assign('playUrlInfos',$playUrlInfos);
				$this->display();
			}
		}
	}
	private function getAjaxJsCode($responseObj){
		return '<script type="text/javascript">parent.uploadResult('.json_encode($responseObj).');</script>';
	}
	
	private function sizeformat($bytesize){
        $i=0;

        //当$bytesize 大于是1024字节时，开始循环，当循环到第4次时跳出；
        while(abs($bytesize)>=1024){         
        $bytesize=$bytesize/1024;
        $i++;
        if($i==4)break;
        }

        //将Bytes,KB,MB,GB,TB定义成一维数组；

        $units= array("Bytes","KB","MB","GB","TB");
        $newsize=round($bytesize,2);
        return("$newsize $units[$i]");
	}

}