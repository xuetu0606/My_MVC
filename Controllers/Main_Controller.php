<?php
	/**
	 * 加载邮件类
	 */
	// require_once "../Codes/send_email.php";
	/**
	 * 直接发送邮件与配置类
	 * @param  string  $smtpserver     [SMTP服务器]
	 * @param  integer $smtpserverport [SMTP服务器端口]
	 * @param  string  $smtpusermail   [SMTP服务器的用户邮箱]
	 * @param  string  $smtpemailto    [发送给谁]
	 * @param  string  $smtpuser       [SMTP服务器的用户帐号]
	 * @param  string  $smtppass       [SMTP服务器的用户密码]
	 * @param  string  $mailtitle      [邮件主题]
	 * @param  string  $mailcontent    [邮件内容]
	 * @param  string  $mailtype       [邮件格式]
	 * @return string                 成功或失败
	 */
	// send_email($smtpserver,$smtpserverport = 25,$smtpusermail,$smtpemailto,$smtpuser,$smtppass,$mailtitle,$mailcontent,$mailtype = 'TXT');
	
	/**
	 * 加载阿里大于短信发送类
	 */
	// require_once "../Codes/aldy.php";
	/**
	 * @param $sms_template_code	短信模板代码
	 * @param $rec_num				手机号
	 * @param $sms_param			短信参数数组
	 * @return bool				是否处理成功
	 */
	// aliqinFcSmsNumSend($api_url,$appKey,$appSecret,$sms_template_code, $rec_num, $sms_param, $sms_free_sign_name = '');
	// send_note('http://gw.api.taobao.com/router/rest','23399583','14876b9d2aa57c274129fbc73c4617af','SMS_11520097', '15898863779', array('code'=>'短信测试','product'=>'哈哈哈'), $sms_free_sign_name = '优客美测试');
	
	
?>