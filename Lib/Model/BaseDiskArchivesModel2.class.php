<?php

/**
 * class class
 *
 * Description for class class
 *
 * @author:bluedust
*/

interface iBaseDiskArchives {
	
	//分析真实文件URL
	public function getRealFileUrl($param);
	
	//检查有效状态
	public function checkLostEfficacy($param);
	
	//处理验证码
	public function checkcodeDeal($param);
}

class BaseDiskArchivesModel extends Model implements iBaseDiskArchives {
	protected $tableName = "archives";
	
	protected $_validate = array(
		array('name','require','文件名称必须填写！', 1), 
		array('diskurl','require','网盘地址必须填写！', 1),
		array('password','require','管理密码必须填写！', 1, "", 1),
		array('email', 'email', '邮箱地址错误', 2),
	);
	
	//分析真实文件URL
	//子类需要覆盖
	public function getRealFileUrl($param){
		return "";
	}
	
	//检查有效状态
	//子类需要覆盖
	public function checkLostEfficacy($param){
		return false;
	}
	
	//处理验证码
	//子类需要覆盖
	public function checkcodeDeal($param){
		
	}

	public function setData($archivesData){
		$this->data = $archivesData;
	}
	
	protected function getHttpContents($url, $param){
		$iipp = $this->getClientIp();
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "$url");//确定解析对象
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322; .NET CLR 2.0.50727)");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-FORWARDED-FOR:$iipp", "CLIENT-IP:$iipp"));  //构造IP
		curl_setopt($ch, CURLOPT_REFERER,$iipp);   //构造来路
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);  //是否抓取跳转后的页面
		
		if(!empty($param)){//POST数据
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
		}
		
		$file = curl_exec($ch);//数据流存放文件
		curl_close($ch);
		
		return $file;
	}
	
	static public function getClientIp(){
		if(isset($_COOKIE['IpAmend'])) return $_COOKIE['IpAmend'];
		
		if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")) 
			$ip = getenv("HTTP_CLIENT_IP"); 
		else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")) 
			$ip = getenv("HTTP_X_FORWARDED_FOR"); 
		else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")) 
			$ip = getenv("REMOTE_ADDR"); 
		else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")) 
			$ip = $_SERVER['REMOTE_ADDR']; 
		else 
			$ip = "unknown"; 
		return $ip; 
	}
	
	public function search($key, $page){
		if($key == "")return array();
		
		$condition['name'] = array('like' , "%$key%");
		$condition['allowsearch'] = 1;
		$limit = ($page - 1)*C('SEARCH_PAGESIZE') . ',' . C('SEARCH_PAGESIZE');
		$order = 'dateline desc,isautocollect asc,id desc';

		$result = $this->field(array('name','id','diskname'))->where($condition)->limit($limit)->order($order)->select();
		return $result;
	}
	
	public function getList($page){

		$condition['allowsearch'] = 1;
		$limit = ($page - 1)*C('SEARCH_PAGESIZE') . ',' . C('SEARCH_PAGESIZE');
		$order = 'dateline desc,isautocollect asc,id desc';
		D('BaseDiskArchives')->limit($limit);
		$result = $this->field(array('name','id','diskname'))->where($condition)->limit($limit)->order($order)->select();
		return $result;
	}
	
	public function setEffective($status,$archivesId = 0){
		if($archivesId)$this->where(array('id'=>$archivesId));
		$this->limit(1)->save(array('iseffective'=>$status));
	}
	
}

?>