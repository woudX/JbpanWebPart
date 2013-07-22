<?php

/**
 * class MailRemind
 *
 * Description for class MailRemind
 *
 * @author:
*/
require_once ('./Lib/Tools/PhpMailer/class.phpmailer.php');
class MailRemind {
	static public function diskLoseEfficacy($archives) {
		$archivesUrl = 'http://' . C ( 'SITE_URL' ) . U ( 'Disk_Extract/index/code/' . C ( 'CODE_PREFIX' ) . $archives ['id'] );
		
		$body = "<meta http-equiv='Content-Type' content='text/html; charset=gb2312'/><p>您在【";
		$body .= C ( 'SITE_NAME' );
		$body .= "】发布的网盘资源【<a href='$archivesUrl' target='_blank'>" . $archives ['name'] . "</a>】（提取码：【" . C ( 'CODE_PREFIX' ) . $archives ['id'] . "】,管理密码：【【" . $archives ['password'] . "】）已经失效，请到原网盘地址<a href='" . $archives ['diskurl'] . "'>" . $archives ['diskurl'] . "</a>检查原因！<br>
									新连接请前往网站修改，凭管理密码。   如有疑问或提交新建议请回复此邮件  <br> 目前已知妙传的资源和在其他地方泄露源地址依然会被菊爆；<br>
					
									迅雷快船可能因为迅雷服务器抽风导致暂时失效1-2天，请先确认自己网盘内显示的是否失效 </p>";
		
		$body = eregi_replace ( "[\]", '', $body );
		
		$body = iconv ( "utf-8", "GB2312//IGNORE", $body ); // 将邮件正文转为gb2312
		$mail->IsSMTP (); // telling the class to use SMTP
		$mail->Host = "smtp.163.com"; // SMTP server
		$mail->SMTPDebug = 0; // enables SMTP debug information (for testing)
		                       // 1 = errors and messages
		                       // 2 = messages only
		$mail->SMTPAuth = true; // enable SMTP authentication
		$mail->SMTPSecure = "ssl"; // sets the prefix to the servier
		$mail->Host = "smtp.163.com"; // sets GMAIL as the SMTP server
		$mail->Port = 465; // set the SMTP port for the GMAIL server
		$mail->Username = "zyanshijie@163.com"; // GMAIL username
		$mail->Password = "a85939846"; // GMAIL password
		$mail->SetFrom ( 'zyanshijie@163.com', 'zyanshijie' );
		
		$mail->Subject = iconv ( "utf-8", "GB2312//IGNORE", L ( 'DiskLostEfficacy' ) ); // 将邮件标题转为gb2312
		$mail->AltBody = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
		$mail->MsgHTML ( $body );
		$mail->AddAddress ( $archives ['email'], $archives ['email'] );
		
		return $mail->Send ();
	}
	static public function test() {
		echo 11111111111;
	}
	static public function send_Email($title, $content, $email) {
		// 发送邮件
	
		
		$mail = new PHPMailer (); // 建立邮件发送类
		$mail->IsSMTP (); // 使用SMTP方式发送
		$mail->CharSet = "utf-8";
		$mail->SMTPDebug = 1;
		$mail->Encoding = "base64";
		$mail->Host = "smtp.163.com"; // 您的企业邮局域名
		$mail->SMTPAuth = true; // 启用SMTP验证功能
		$mail->Username = "zyanshijie@163.com"; // 邮局用户名(请填写完整的email地址)
		$mail->Password = "a85939846"; // 邮局密码
		
		$mail->Subject = "$title"; // 邮件标题
		$mail->Body = "$content"; // 邮件内容
		$mail->AltBody = ""; // 附加信息，可以省略
		
		$mail->From = "zyanshijie@163.com"; // 邮件发送者email地址
		$mail->FromName = "菊爆盘";
		
	
			$mail->AddAddress ( "$email", "User" ); // 收件人地址，可以替换成任何想要接收邮件的email信箱,格式是AddAddress("收件人email","收件人姓名")
			             

			return $mail->Send();
		}
	
}

?>