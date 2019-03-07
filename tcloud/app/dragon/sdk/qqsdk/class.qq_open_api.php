<?php

class QQOpenAPI
{
	protected $app_id;
	protected $app_secret;

	function __construct($app_id, $app_secret)
	{
		$this->app_id = $app_id;
		$this->app_secret = $app_secret;
	}

	public function get_qq_api($path, $query)
	{
		$url = 'https://graph.qq.com/' . ltrim($path, '/') . '?' . http_build_query($query);
		log_trace($url);
		$data = file_get_contents($url);
		if (!$data) {
			return null;
		}
		// $data = json_decode($data, true);
		return $data;
	}

	public function request_userinfo($access_token, $openid)
	{
		$json = $this->get_qq_api('user/get_user_info', array(
			'access_token' => $access_token,
			'oauth_consumer_key'=>$this->app_id,
			'openid' => $openid
		));
		$json = json_decode($json, true);
		return $json;
	}

	public function get_qq_openid($access_token,$is_unionid = 0){
		if($is_unionid != 0){
			$json = $this->get_qq_api('oauth2.0/me', array(
				'access_token' => $access_token,
				'unionid' => $is_unionid,//$is_unionid 1 表示请求unionid  0 表示不请求
			));
		}else{
			$json = $this->get_qq_api('oauth2.0/me', array(
				'access_token' => $access_token,
			));
		}
		if (strpos($json, "callback") !== false){
			$lpos = strpos($json, "(");
			$rpos = strrpos($json, ")");
			$json  = substr($json, $lpos + 1, $rpos - $lpos -1);
		}
		$json = json_decode($json, true);
		return $json;
	}
} // END

/* END file */