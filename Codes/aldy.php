<?php
header("Content-Type: text/html; charset=utf-8");
ini_set('date.timezone','Asia/Shanghai');
/**
 * 阿里大于短信发送类
 */
class aldy{
	private $api_url 		= '';		//接口url
	private $appKey			= '';		//API接口key
	private $appSecret		= '';		//API接口Secret
	private $sms_template_code = ''; 
	private $rec_num = '';
	private $sms_param = '';
	private $sms_free_sign_name = '';  //短信签名
	public function aldy($api_url,$appKey,$appSecret,$sms_template_code, $rec_num, $sms_param, $sms_free_sign_name = ''){
		$this->api_url = $api_url;		//接口url
		$this->appKey = $appKey;		//API接口key
		$this->appSecret = $appSecret;		//API接口Secret
		$this->sms_template_code = $sms_template_code; 
		$this->rec_num = $rec_num;
		$this->sms_param = $sms_param;
		$this->sms_free_sign_name = $sms_free_sign_name;
	}
	/************************ 公用部分开始 *************************/
	/**
	 * 生成签名
	 * @param $params			参数数组，值不可为数组
	 * @param $secret			密钥
	 * @param string $type		加密方式	notify - 通知加密   密钥 + 参数			api - api加密		密钥 + 参数 + 密钥
	 * @return string			返回签名
	 */
	public function makeSign($params,$secret){
		$sign_data = '';
		//升序键值
		ksort($params);
		//重组参数，拼接字符串
		foreach ($params as $k => $v){
			if('sign' !== $k && '' !== $v){
				$sign_data .= $k.$v;
			}
		}
		$sign_data = strtoupper(md5($secret.$sign_data.$secret));

		return $sign_data;

	}
	/**
	 * 生成公用数组
	 * @param $method					方法名
	 * @return mixed					返回公用数组
	 */
	public function makePublicData($method){
		$data['v'] = '2.0';
		$data['method'] = $method;
		$data['app_key'] = $this->appKey;
		$data['timestamp'] = date('Y-m-d H:i:s',$_SERVER['REQUEST_TIME']);
		$data['format'] = 'json';
		$data['sign_method'] = 'md5';
		return $data;
	}
	/************************ 公用部分结束 *************************/
	/**************** 阿里大鱼部分 *****************/
	public function aliqinFcSmsNumSend(){
		$post_data = $this->makePublicData('alibaba.aliqin.fc.sms.num.send');
		$post_data['sms_type'] = 'normal';
		$post_data['sms_free_sign_name'] = $this->sms_free_sign_name != "" ? $this->sms_free_sign_name : '';
		$post_data['sms_param'] = json_encode($this->sms_param);
		$post_data['rec_num'] = $this->rec_num;
		$post_data['sms_template_code'] = $this->sms_template_code;

		$post_data['sign'] = $this->makeSign($post_data,$this->appSecret);

		$post_data = http_build_query($post_data);

		$rt = $this->curl_do($this->api_url,$post_data,'POST');

		if($rt[0] === 200){
			$info = json_decode($rt[1],true);
			if(isset($info['error_response']['code'])){
				return false;
			}else{
				return true;
			}
		}else{	// 请求对方服务器失败
			return false;
		}

	}
	/**
	 * 通过curl请求一个url,data为k=v&k1=v1的格式(数组时自动整理)或post一个xml数据
	 * @param  [type] $url    [description]
	 * @param  string $data   [description]
	 * @param  string $method [description]
	 * @return [type]         [description]
	 */
	function curl_do($url,$data = '',$method = 'GET'){	

		if(is_array($data)){
			$data_str = '';
			foreach($data as $k => $v){
				$data_str .= '&'.$k.'='.urlencode($v);
			}
			$data_str = substr($data_str,1);
		}else{
			$data_str = &$data;
		}

		$ch = curl_init();
		switch($method){
			case 'GET':
				curl_setopt($ch,CURLOPT_URL,$url.($data_str === '' ? '' : '?'.$data_str));
			break;
			case 'POST':
				curl_setopt($ch,CURLOPT_URL,$url);
				curl_setopt($ch,CURLOPT_POST,TRUE);
				curl_setopt($ch,CURLOPT_POSTFIELDS,$data_str);
			break;
		}

		if(substr($url,0,5) === 'https'){	// 如果是ssl安全请求
			curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,2);
			curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
		}

		curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);	// 要求结果为字符串且输出到屏幕
		$rt = [];
		$rt[1] = curl_exec($ch);
		$rt[0] = curl_getinfo($ch,CURLINFO_HTTP_CODE);
		curl_close($ch);
		return $rt;
	}
}
/**
 * 短信发送方法
 * @param  string $api_url            [接口url]
 * @param  string $appKey             [API接口Key]
 * @param  string $appSecret          [API接口Secret]
 * @param  string $sms_template_code  [短信模板代码]
 * @param  string $rec_num            [发送手机号]
 * @param  array  $sms_param          [短信参数数组]{短信模板内变量:给定变量}
 * @param  string $sms_free_sign_name [短信公司]
 * @return bool                       [是否处理成功]
 */
function send_note($api_url,$appKey,$appSecret,$sms_template_code, $rec_num, $sms_param, $sms_free_sign_name){
	$aldy = new aldy($api_url,$appKey,$appSecret,$sms_template_code, $rec_num, $sms_param, $sms_free_sign_name);
	$aldy->aliqinFcSmsNumSend();
}
?>