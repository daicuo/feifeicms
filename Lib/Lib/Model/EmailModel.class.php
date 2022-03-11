<?php
class EmailModel extends Model {
	public function send($address, $username, $title, $content, $ishtml=false){
		require THINK_PATH.'/Vendor/Email/class.phpmailer.php';
		$mail = new PHPMailer();//建立邮件发送类
		$mail->IsSMTP();//使用SMTP方式发送 
		if(C('email_port')){
			$mail->Port = C('email_port'); 
		}
		if(C('email_secure')){
			$mail->SMTPSecure = C('email_secure'); 
		}
		$mail->CharSet = "utf-8"; //编码
		$mail->Host = C("email_host"); //您的企业邮局域名 
		$mail->SMTPAuth = true; // 启用SMTP验证功能 
		$mail->Username = C("email_username"); // 邮局用户名(请填写完整的email地址)
		$mail->Password = C("email_password"); // 邮局密码
		$mail->From = C("email_username"); // 邮件发送者email地址
		$mail->FromName = C('site_name');// 发件人姓名
		$mail->AddAddress($address, $username);//收件人地址,可以替换成任何想要接收邮件的email信箱,格式是AddAddress("收件人email","收件人姓名")
		$mail->Subject = $title; //邮件标题
		$mail->Body = $content; //邮件内容
		$mail->IsHTML($ishtml);//是否使用HTML格式
		//$mail->AddReplyTo("", "");
		//$mail->AddAttachment("/var/tmp/file.tar.gz"); // 添加附件
		//$mail->IsHTML(true); // set email format to HTML //是否使用HTML格式
		//$mail->AltBody = "This is the body in plain text for non-HTML mail clients"; //附加信息，可以省略
		if(!$mail->Send()) {
			$this->error = $mail->ErrorInfo;
			return false;
		}
		return true;
	}	
}
?>