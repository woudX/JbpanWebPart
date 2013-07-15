<?php

/**
 * class DiskFactory
 *
 * Description for class DiskFactory
 *
 * @author:
*/
class DiskModelFactory {
	
	static public $diskList = array(
		'/^(http:\/\/)?pan.baidu.com\/share\/((link)|(init))\?shareid=\d+&uk=\d+/i' => 'Baidu', 
		'/^(http:\/\/)?kuai.xunlei.com\/d\//i' => 'Xunlei',
		'/^magnet:\?xt=urn:/i' => 'Magnet',
		'/^(http:\/\/)?www.weiyun.com\/(share.html\?sharekey=|web\/outlink.html\?|php\/outlink.php\?)/i' => 'Weiyun'
	);

	static public function getDiskModel($diskName){
		D($diskName."Disk");
		return D($diskName."Disk");
	}
	
	static public function getDiskNameFromUrl(&$url){
		$diskName = null;
		
		if(preg_match("/^(http:\/\/)?url.cn\//i", $url)){
			$url = self::getLoactionInfo($url);
		}
		echo "<!-- ";
		print_r($url);
		echo " -->";
		
		foreach(self::$diskList as $key => $val){		
			
			if(preg_match($key,$url)){
				$diskName = $val;
				break;
			}
		}

		return $diskName;
	}
	
	static public function getLoactionInfo($url){
		$locationUrl = "";
		$headers = get_headers($url);
	
		
		if(empty($headers))return $locationUrl;
		
		foreach ($headers as $value){
			if(strpos($value, "Location: ") === 0){
				$locationUrl = substr($value, 10);
				break;
			}
		}

		return $locationUrl;
	}
}

?>