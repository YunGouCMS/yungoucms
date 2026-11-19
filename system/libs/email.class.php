<?php
require 'vendor/phpmailer/phpmailer/PHPMailerAutoload.php';

//发送邮件类
class email {

	static private $mail;
	static private $config;
	//配置邮件参数
	static public function config($config=array()){
		if(!is_array($config)){return false;}	
		self::$config=$config;
		self::$mail= new PHPMailer(true); // 创建一个新的 PHPMailer 实例并启用异常处理
		
		self::$mail->IsSMTP();                 		      // 启用SMTP
		self::$mail->Host = $config['stmp_host'];           //SMTP服务器
		self::$mail->SMTPAuth = true;                  	  //开启SMTP认证
		self::$mail->Username =$config['user'];            // SMTP用户名
		self::$mail->Password = $config['pass'];            // SMTP密码
		self::$mail->SMTPSecure = 'tls'; // 或者 'tls'，根据你的服务器配置选择
		// 设置 SMTP 端口号
		self::$mail->Port = 587; // 或者 587，根据你的服务器配置选择
		self::$mail->From = $config['from'];            	//发件人地址
		self::$mail->FromName = $config['fromName'];          //发件人
	
		self::$mail->AddReplyTo($config['from'], $config['fromName']);    //回复地址
		self::$mail->WordWrap = 50; 					  //设置每行字符长度 
	
	}
	

	
	//添加收件人
	//$userpath 邮件地址 可以是二维数组
	//$username 收件人名
	static public function adduser($userpath,$username=''){
		//$user 是二维数组
		if(!is_array($userpath)){
			self::$mail->AddAddress($userpath,$username); //添加收件人		
		}else{		
			$len=count($userpath);
			foreach($userpath as $v){
				self::$mail->AddAddress($v['email'], $v['name']); //添加收件人
			}
		}	
	}

	//添加附件
	static public function addfile($filepath,$filename=''){
		
		if(empty($filename)){
			self::$mail->AddAttachment($filepath);        // 添加附件
		}else{
			self::$mail->AddAttachment($filepath,$filename);   // 添加附件,并指定名称
		}
		
	}
	//发送邮件
	//$title   邮件标题
	//$content 邮件内容
	//$type    是否用HTML格式
	//$nohtml  不支持HTML格式显示的内容
	static public function send($title='',$content='',$type=true,$nohtml=''){
	
			
			self::$mail->IsHTML($type);    				// 是否HTML格式邮件
			self::$mail->CharSet = self::$config['big'];// 这里指定字符集！
			self::$mail->Encoding = "base64";
			
			self::$mail->Subject = $title;       //邮件主题
			self::$mail->Body = $content;        //邮件内容
			
			if(empty($nohtml)){
				$nohtml=self::$config['nohtml'];
			}			
			self::$mail->AltBody = $nohtml; //邮件正文不支持HTML的备用显示

			
			if(!self::$mail->Send())
			{
			 return false;
			//self::$mail->ErrorInfo;
			}else{
				return true;
			}
	}

	static public function test(){
		$mail = new PHPMailer(true); // 创建一个新的 PHPMailer 实例并启用异常处理
		try {
			// 设置使用 SMTP
			$mail->isSMTP();
			// 设置 SMTP 服务器地址
			$mail->Host = 'smtp.gmail.com';
			// 使用 SSL/TLS 安全连接
			$mail->SMTPSecure = 'tls'; // ssl或者 'tls'，根据你的服务器配置选择
			// 设置 SMTP 端口号
			$mail->Port = 587; // 465或者 587，根据你的服务器配置选择
			// 设置 SMTP 认证信息
			$mail->SMTPAuth = true;
			$mail->Username = 'taolongcc@gmail.com'; // QQ 邮箱地址
			$mail->Password = 'REDACTED_CONFIGURE_LOCALLY'; // QQ 邮箱的授权码，不是登录密码
			// 设置发件人邮箱和名称
			$mail->setFrom('taolongcc@gmail.com', 'Aling');
			// 设置收件人邮箱和名称
			$mail->addAddress('2945408821@qq.com', 'test');
			// 设置邮件主题
			$mail->Subject = 'Test Email';
			// 设置邮件正文内容
			$mail->Body = 'This is the body of the email.';
			// 可选：设置 HTML 格式的邮件正文内容
			$mail->isHTML(true);  // 如果邮件正文是 HTML 格式的，设置为 true
			// 发送邮件
			$mail->send();
			echo 'Email sent successfully';
		} catch (Exception $e) {
			echo $mail->ErrorInfo;
		}
	}
}

?>
