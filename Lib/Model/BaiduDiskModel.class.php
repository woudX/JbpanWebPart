<?php

/**
 * class class
 *
 * Description for class class
 *
 * @author:
*/
require_once('./Lib/Common/DiskAjaxResopnseFactory.class.php');

class BaiduDiskModel extends BaseDiskArchivesModel {


	function getRealFileUrl($param) {
		$file = $this->getHttpContents($param['url']);
		
		$pattern='/"\[{.*?}\]"/i';//正则表达式  
		preg_match($pattern,$file,$result);
		$result[0] = str_replace("\\\\","\\",$result[0]);
		$result[0] = str_replace("\\\"","\"",$result[0]);
		$result[0] = str_replace("\\/","/",$result[0]);
		$result[0] = substr($result[0], 1, strlen($result[0]) - 2);		

		$retObj = json_decode($result[0]);
		if(!empty($retObj)){
			$realFileUrl = array();
			foreach($retObj as $key=>$val){
				$realFileUrl[$key]['fileName'] = $val->server_filename;
				$realFileUrl[$key]['url'] = $val->dlink;
				$realFileUrl[$key]['fileSize'] = $this->sizeFormat($val->size);
				$realFileUrl[$key]['downloadcode'] = "开发中";
			}
			
			return DiskAjaxResopnseFactory::getSuccessResponse($realFileUrl);
		}
		else {
			return DiskAjaxResopnseFactory::getFailResponse(L('DiskLostEfficacy_2'));
		}
	}
	
	function checkLostEfficacy($param){
		$file = $this->getHttpContents($param['url']);


		
		if(strpos($file, '链接不存在') || strpos($file, '文件已删除')|| strpos($file,">&nbsp;<b class=")){
			return true;
		}
		
		return false;
	}
}

?>