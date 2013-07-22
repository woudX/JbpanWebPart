	<?php

/**
 * class class
 *
 * Description for class class
 *
 * @author:
*/
require_once('./Lib/Common/DiskModelFactory.class.php');

class DiskSubmitAction extends DkAction {
	
	private $cookieLife = 2592000;
	
		
	function index(){
		$this->checkLogin();
		
		$this->display();
	}

    //PHP验证邮箱格式的函数
    private function valid_email($email) {
    	//地址统一转换为小写
    	$email=strtolower($email);
    
    	
    //首先确认是否有一个@符号的存在，同时验证邮箱长度是否正确
    if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {
        //如果@符号的个数不对，或者邮箱每部分的长度不对则输出错误
        echo 1;
        return false;
        
    }
    //把邮箱按“@”符号和“.”符号分割成几个部分分别用正则表达式匹配
    $email_array = explode("@", $email);
    $local_array = explode(".", $email_array[0]);
    for ($i = 0; $i < sizeof($local_array); $i++) {
        if (!ereg("^(([A-Za-z0-9!#$%&#038;'*+/=?^_`{|}~-][A-Za-z0-9!#$%&#038;'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) {
            echo 2;
        	return false;
        }
    }
    if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) {
        //检查域名部分是否是IP地址，如果不是则应该是有效域名
        $domain_array = explode(".", $email_array[1]);
        $emailaddress=array( "qq.com","163.com","yahoo.com.cn","gmail.com","126.com","vip.qq.com","hacg.me",
        "iyw.tw","yeah.net","sina.com");
  
        if (in_array($email_array[1],$emailaddress)==false)
        {
        	
        	
        	 echo "以下为支持邮箱:";
        	
        	 foreach ($emailaddress as $val)
        	 {
        	 	echo $val."<br>";
        	 }
        	 echo "<br><br><br><br>";
        	return false;
        }
        
        if (sizeof($domain_array) < 2) {
            //域名部分的长度不能太短，否则输出错误
             echo 4;
            return false;
        }
        for ($i = 0; $i < sizeof($domain_array); $i++) {
            if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i])) {
                //域名部分如果不是字母和数字，或者允许的其他字符，则输出错误
                 echo 5;
            	return false;
            }
        }
    }
    //所有检测通过，输出邮箱格式正确
    return true;
}

	function submit(){
		$archivesData = array();
		
		$diskUrl = isset($_POST['diskurl']) ? trim($_POST['diskurl']) : "";
		if(substr($diskUrl,0,7) != "http://" && substr($diskUrl,0,7) != 'magnet:')$diskUrl = "http://" . $diskUrl;
		
		$diskName = DiskModelFactory::getDiskNameFromUrl($diskUrl);
	
		if($diskName == null){
			$this->error(L('NonsupportDisk'));
			return;
		}

		var_dump($this->loginData);
		$archivesData['uid'] = $this->loginData['uid'];	
		
		$archivesData['diskurl'] = $diskUrl;
		$archivesData['name'] = htmlspecialchars(trim($_POST['name']));
		$archivesData['diskname'] = $diskName;
		$archivesData['remark'] = trim($_POST['remark']);//富文本不能编码
		$archivesData['allowsearch'] = htmlspecialchars(trim($_POST['allowsearch']));
		$archivesData['category'] = htmlspecialchars(trim($_POST['category']));
		if(isset($_POST['__hash__']))$archivesData['__hash__'] = $_POST['__hash__'];
		
		$archivesData['remark'] = str_replace("<script>", htmlspecialchars("<script>"), $archivesData['remark']);
		$archivesData['remark'] = str_replace("</script>", htmlspecialchars("</script>"), $archivesData['remark']);
		
		$archivesMode = DiskModelFactory::getDiskModel($diskName);
	
			
		
		if(!$archivesMode->create($archivesData)){
			$this->error($archivesMode->getError());
		}
		else{
			
			
			if($archivesMode->add()){
				$this->assign('extractCode',C('CODE_PREFIX').$archivesMode->getLastInsID());
				$this->display('submitok');
			}
			else{
				echo $archivesMode->getDbError();
				$this->error(L('PublishField'));
			}
		}

	}
	
	//修改前密码输入界面
	function editPre(){
		$extractCode = isset($_GET['code']) ? trim($_GET['code']) : "";
		if(!preg_match('/^'.C('CODE_PREFIX').'\d+$/i',$extractCode)){
			return;
		}
		$submitPassword = isset($_COOKIE['submitPassword']) ? $_COOKIE['submitPassword'] : "";
		$this->assign('extractCode', $extractCode);
		$this->assign('submitPassword', $submitPassword);
		$this->display();
	}
	
	function edit(){
		//匹配提取码，不符合规则则退出
		$extractCode = isset($_REQUEST['code']) ? trim($_REQUEST['code']) : "";
		if(!preg_match('/^'.C('CODE_PREFIX').'\d+$/i',$extractCode)){
			return;
		}

		//剔除前缀，得到ID
		$archivesId = substr($extractCode,strlen(C('CODE_PREFIX')));
		
		$archivesModel = D('BaseDiskArchives');

		//根据ID查询数据
		$archives = $archivesModel->find($archivesId);
		
		if(!empty($archives)){
			$password = isset($_POST['password']) ? htmlspecialchars(trim($_POST['password'])) : "";
			//检查密码是否正确
			if($archives['uid'] != $this->loginData['uid'] && ($archives['password'] == "" || $password != $archives['password']))
			{
				$this->error(L('PassowrdError'));
				return;
			}
			
			$this->assign('password', $password);
			$this->assign('extractCode', $extractCode);
			$this->assign('archives', $archives);
			$this->display();
		}
		else {
			$this->error(L('ArchivesNotExist'));
		}
	}
	
	function editSubmit(){
				//匹配提取码，不符合规则则退出
		$extractCode = isset($_GET['code']) ? trim($_GET['code']) : "";
		if(!preg_match('/^'.C('CODE_PREFIX').'\d+$/i',$extractCode)){
			return;
		}
		
		//剔除前缀，得到ID
		$archivesId = substr($extractCode,strlen(C('CODE_PREFIX')));
		
		$archivesModel = D('BaseDiskArchives');

		//根据ID查询数据
		$archives = $archivesModel->find($archivesId);
		
		//如果该ID的档案存在
		if(!empty($archives)){
			
			$archivesData = array();
			
			$password = isset($_POST['password']) ? htmlspecialchars(trim($_POST['password'])) : "";
			//检查密码是否正确
			if($archives['uid'] != $this->loginData['uid'] && ($archives['password'] == "" || $password != $archives['password']))
			{
				$this->error(L('PassowrdError'));
				return;
			}
			
			$diskUrl = isset($_POST['diskurl']) ? trim($_POST['diskurl']) : "";
			if(substr($diskUrl,0,7) != "http://" && substr($diskUrl,0,7) != 'magnet:')$diskUrl = "http://" . $diskUrl;
			
			$diskName = DiskModelFactory::getDiskNameFromUrl($diskUrl);

			if($diskName == null){
				$this->error(L('NonsupportDisk'));
				return;
			}
			
					 
// 				if($this->valid_email(($_POST['email'])) == false){
// 					$this->error(L('erroremail'));
// 					return;
// 				}
		
			$archivesData['id'] = $archivesId;
			$archivesData['diskurl'] = $diskUrl;
			$archivesData['name'] = htmlspecialchars(trim($_POST['name']));
			$archivesData['diskname'] = $diskName;
			$archivesData['remark'] = trim($_POST['remark']);
			$archivesData['allowsearch'] = htmlspecialchars(trim($_POST['allowsearch']));
			$archivesData['category'] = htmlspecialchars(trim($_POST['category']));
			if(isset($_POST['__hash__']))$archivesData['__hash__'] = $_POST['__hash__'];
			
			$archivesData['remark'] = str_replace("<script>", htmlspecialchars("<script>"), $archivesData['remark']);
			$archivesData['remark'] = str_replace("</script>", htmlspecialchars("</script>"), $archivesData['remark']);
			
			$archivesMode = DiskModelFactory::getDiskModel($diskName);

			
			if(!$archivesMode->create($archivesData)){
				$this->error($archivesMode->getError());
			}
			else{				
				$archivesMode->save();
				$archivesModel->setEffective(1,$archivesId);
				
				$this->assign('extractCode', $extractCode);
				$this->display('editok');

			}
		}
		else {
			$this->error(L('ArchivesNotExist'));
		}
	}
	
	function delete() {
		$extractCode = isset($_REQUEST['code']) ? trim($_REQUEST['code']) : "";
		if(!preg_match('/^'.C('CODE_PREFIX').'\d+$/i',$extractCode)){
			return;
		}
		
		//剔除前缀，得到ID
		$archivesId = substr($extractCode,strlen(C('CODE_PREFIX')));
		
		$uid=$_SESSION['uid'];

		$archives=M('archives');
		$date=$archives->where("id=$archivesId ")->find();
		if ($uid==$date['uid']) {
			$archives->where("id=$archivesId ")->delete();
						header("Location:http://".C('SITE_URL')."/User/usercenter");
// 					$url="http://".C('SITE_URL')."/User/skydriver_info_02";
// 			echo '<script type="text/javascript">top.location.href="'.$url.'";</script>';
		}else{
			echo "删除失败";
		}
		
	}
}

?>