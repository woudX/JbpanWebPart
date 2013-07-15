<?php
require_once('Lib/Common/DiskAjaxResopnseFactory.class.php');

class WeiyunDiskModel extends BaseDiskArchivesModel {
	private $urlType = 0;
	private $urlParam = "";
	private $fcgUrl_0 = 'http://web.cgi.weiyun.com/wy_web.fcg';//第一种类型请求地址
	private $fcgUrl_1 = 'http://web.cgi.weiyun.com/wy_share.fcg';//第二种类型请求地址
		
	public function getRealFileUrl($param){
		$url = $param['url'];
		
		$this->setUrlType($url);
		$postData = $this->getPostData($url);
		
		if($this->urlType == 0){
			$file = $this->getHttpContents($this->fcgUrl_0,$postData);
			
			$retObj = json_decode($file);
			if(!empty($retObj) && $retObj->rsp_body != null){
				$realFileUrl = array();
					
				$realFileUrl[0]['fileName'] = $retObj->rsp_body->realnm;
 				$realFileUrl[0]['url'] = "http://" .$retObj->rsp_body->dl_svr_host . ":" .$retObj->rsp_body->dl_svr_port."/ftn_handler/".$retObj->rsp_body->dl_encrypt_url."/".urlencode($retObj->rsp_body->realnm);
				//$realFileUrl[0]['url'] = "http://web.weiyun.qq.com/php/downloadCheck.php?downloadn=" . $retObj->rsp_body->dl_cookie_name . "&downloadv=" . $retObj->rsp_body->dl_cookie_value;
				$realFileUrl[0]['fileSize'] = $this->sizeFormat($retObj->rsp_body->sz);
				$realFileUrl[0]['downloadcode'] = "开发中";
				
				$cookieUrl = "http://web.weiyun.qq.com/php/downloadCheck.php?downloadn=" . $retObj->rsp_body->dl_cookie_name . "&downloadv=" . $retObj->rsp_body->dl_cookie_value."&callback=jQuery16408104233503714975_1367255684443&_=".time();
				DiskAjaxResopnseFactory::setCallbackScript("$('body').append('<script src=\"$cookieUrl\"></script>');");
				return DiskAjaxResopnseFactory::getSuccessResponse($realFileUrl);
			}
			else {
				return DiskAjaxResopnseFactory::getFailResponse(L('DiskLostEfficacy_2'));
			}
		}
		elseif ($this->urlType == 1){
			$file = $this->getHttpContents($this->fcgUrl_1,$postData);
			$retObj = json_decode($file);
				
			if(!empty($retObj) && $retObj->rsp_body != null){
				$realFileUrl = array();
					
				$realFileUrl[0]['fileName'] = $retObj->rsp_body->file_list[0]->file_name;
// 				$realFileUrl[0]['url'] = "http://".$retObj->rsp_body->dl_svr_host.":".$retObj->rsp_body->dl_svr_port."/ftn_handler/".$retObj->rsp_body->dl_encrypt_url."/".urlencode($retObj->rsp_body->file_list[0]->file_name);
				$realFileUrl[0]['url'] = 'http://sync.box.qq.com/share_dl.fcg?sharekey='.$this->urlParam.'&uin='.$retObj->rsp_body->uin.'&skey=&fid='.$retObj->rsp_body->file_list[0]->file_id.'&dir=&pdir='.$retObj->rsp_body->pdir_key.'&zn='.urlencode($retObj->rsp_body->file_list[0]->file_name).'&ver=12';
				$realFileUrl[0]['fileSize'] = $this->sizeFormat($retObj->rsp_body->file_list[0]->file_size);
				$realFileUrl[0]['downloadcode'] = "开发中";
					
				return DiskAjaxResopnseFactory::getSuccessResponse($realFileUrl);
			}
			else {
				return DiskAjaxResopnseFactory::getFailResponse(L('DiskLostEfficacy_2'));
			}
		}
		
		
	}
	
	/* (non-PHPdoc)
	 * @see BaseDiskArchivesModel::checkcodeDeal($param)
	 */
	public function checkcodeDeal($param) {
		// TODO Auto-generated method stub
		
	}

	/* (non-PHPdoc)
	 * @see BaseDiskArchivesModel::checkLostEfficacy()
	 */
	public function checkLostEfficacy($param) {
		return false;
	}

	private function createCallbackScript_1(){
		
	}
	
	private function setUrlType($url){
		if(substr($url,0,38) == "http://www.weiyun.com/web/outlink.html"){
			$this->urlType = 0;
		}
		else if (substr($url,0,42) == "http://www.weiyun.com/share.html?sharekey=") {
			$this->urlType = 1;
		}
	}
	
	private function getPostData($url){
		if($this->urlType == 0){
			$fcgUrl = $this->fcgUrl_1;
		
			$urlParam = explode("?", $url);
			$urlParam = $urlParam[1];
		
			$postData = '{"req_header":{"proto_ver":10006,"main_v":12,"sub_v":1,"encrypt":0,"msg_seq":1,"source":30006,"token":"4d3754f563ad04a56fece81bbcc83302","client_ip":"127.0.0.1","cmd":"decode_url"},"req_body":{"url":"'.$urlParam.'"}}';
		}
		else if($this->urlType == 1){
			$fcgUrl = $this->fcgUrl_2;
				
			$urlParam = explode("=", $url);
			$urlParam = $urlParam[1];
				
			$postData = '{"req_header":{"net_type":3,"build_v":123,"proto_ver":10006,"main_v":12,"sub_v":1,"encrypt":0,"msg_seq":1,"source":30006,"token":"4d3754f563ad04a56fece81bbcc83302","client_ip":"127.0.0.1","cmd":"get_share_info","uin":0},"req_body":{"share_key":"'.$urlParam.'"}}';
		}
		
		$this->urlParam = $urlParam;
		return $postData;
	}
}

?>