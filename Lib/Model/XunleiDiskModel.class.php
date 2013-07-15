<?php

/**
 * class class
 *
 * Description for class class
 *
 * @author:
*/
require_once('Lib/Common/DiskAjaxResopnseFactory.class.php');

class XunleiDiskModel extends BaseDiskArchivesModel {
	
	function getRealFileUrl($param) {
		$file = $this->getHttpContents($param['url']);

		$meta = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';
		$dom = new DOMDocument();
		$dom->loadHTML($meta.$file);
		$xml = simplexml_import_dom($dom);

		$q = $xml->xpath("//div[@class='file_src file_list liebiao']/ul/li/div/span[@class='c_2']/a");
		$fileSize = $xml->xpath("//div[@class='file_src file_list liebiao']/ul/li/div/span[@class='c4']");
		
		if(!empty($q)){
			$realFileUrl = array();			
		
			foreach($q as $key=>$val){
				
				$attributes = $val->attributes();
				
				$realFileUrl[$key]['fileName'] = (string)$attributes['title'];
				$realFileUrl[$key]['url'] = (string)$attributes['href'];
				$realFileUrl[$key]['diskName'] = "Xunlei";
				$realFileUrl[$key]['fileSize'] = (string)$fileSize[$key];
				//加密
					$str = $param['url'];
					 $keym = "jbpan";
					$keylen = strlen($keym);
					for($i=0;$i<strlen($str);$i++)
					{  
					 $k = $i%$keylen;
					 $txt .= $str[$i] ^ $keym[$k];
					
					}
					$jmurl= base64_encode($txt);
					//加密
				$realFileUrl[$key]['downloadcode'] = $jmurl;
			}
			
				
			return DiskAjaxResopnseFactory::getSuccessResponse($realFileUrl);
		}
		else if(strpos($file,"webfilemail_interface"))
		{	
			return DiskAjaxResopnseFactory::getCheckResponse(L('NeedCheck'));
		}
		else {
			return DiskAjaxResopnseFactory::getFailResponse(L('DiskLostEfficacy_2'));
		}
	}
	
	public function checkcodeDeal($param){
		$file = $this->getHttpContents($param['url']);
		
		if(strpos($file,"webfilemail_interface"))
		{	
			//$tempurl= $url."/webfilemail_interface";
			$filetemp=str_ireplace("/webfilemail_interface", "http://kuai.xunlei.com/webfilemail_interface", $file);
			echo $filetemp;
		}
		else {
			$mate = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';
			echo $mate;
			echo L('UnwantedCheck');
		}
	}
	
	public function checkLostEfficacy($param){
		$file = $this->getHttpContents($param['url']);

		if(strpos($file, 'z_no_result')){
			return true;
		}
		
		return false;
	}
}

?>