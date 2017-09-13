<?php
/**** 
	* curl采集函数
	* 
	* @param  $url 需要采集的链接
	* @param  $postdata 需要提交的post数据，非post方式访问则留空
	* @param  $pre_url  伪造来源url
	* @proxyip 设置代理IP
	* @compression 目标url代码压缩方式
	* 	
	* @return $result 返回目标url的内容
	*/
	function curl_get($url,$pre_url='http://www.baidu.com',$cookie='',$proxyip=false,$compression='gzip, deflate'){
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_TIMEOUT,5);
		
		$client_ip	= rand(1,254).'.'.rand(1,254).'.'.rand(1,254).'.'.rand(1,254);
		$x_ip		= rand(1,254).'.'.rand(1,254).'.'.rand(1,254).'.'.rand(1,254);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:'.$x_ip,'CLIENT-IP:'.$client_ip));//构造IP				
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//处理https链接				
		curl_setopt($ch, CURLOPT_REFERER, $pre_url);		
		if($proxyip){
			curl_setopt($ch, CURLOPT_PROXY, $proxyip);
		}		
		if($compression!='') {	
			curl_setopt($ch, CURLOPT_ENCODING, $compression);	
		}
		
		//Mozilla/5.0 (Linux; U; Android 2.3.7; zh-cn; c8650 Build/GWK74) AppleWebKit/533.1 (KHTML, like Gecko)Version/4.0 MQQBrowser/4.5 Mobile Safari/533.1s		
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/536.11 (KHTML, like Gecko) Chrome/20.0.1132.47 Safari/536.11Mozilla/5.0 (Windows NT 6.1) AppleWebKit/536.11 (KHTML, like Gecko) Chrome/20.0.1132.47 Safari/536.11'); 
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		
		if(!empty($cookie)){
			if(file_exists($cookie)){
				curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie); //读取cookies
			}else{
				curl_setopt($ch, CURLOPT_COOKIEJAR,  $cookie); //存储cookies
			}
		}
		
		$result = curl_exec($ch);
		curl_close($ch);
		
		if(! mb_check_encoding($result, 'utf-8')) {
			$result = mb_convert_encoding($result, 'utf-8', 'gbk');
		}		
		return $result;
	}
	
	function curl_post($url,$postdata='',$pre_url='http://www.baidu.com',$cookie='',$proxyip=false,$compression='gzip, deflate'){
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_TIMEOUT,5);
		
		$client_ip	= rand(1,254).'.'.rand(1,254).'.'.rand(1,254).'.'.rand(1,254);
		$x_ip		= rand(1,254).'.'.rand(1,254).'.'.rand(1,254).'.'.rand(1,254);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:'.$x_ip,'CLIENT-IP:'.$client_ip));//构造IP				
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//处理https链接		
		if($postdata!=''){
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postdata));
		}
		curl_setopt($ch, CURLOPT_REFERER, $pre_url);
		if($proxyip){
			curl_setopt($ch, CURLOPT_PROXY, $proxyip);
		}		
		if($compression!='') {	
			curl_setopt($ch, CURLOPT_ENCODING, $compression);	
		}
		
		//Mozilla/5.0 (Linux; U; Android 2.3.7; zh-cn; c8650 Build/GWK74) AppleWebKit/533.1 (KHTML, like Gecko)Version/4.0 MQQBrowser/4.5 Mobile Safari/533.1s		
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/536.11 (KHTML, like Gecko) Chrome/20.0.1132.47 Safari/536.11Mozilla/5.0 (Windows NT 6.1) AppleWebKit/536.11 (KHTML, like Gecko) Chrome/20.0.1132.47 Safari/536.11'); 
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		
		if(!empty($cookie)){
			if(file_exists($cookie)){
				curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie); //读取cookies
			}else{
				curl_setopt($ch, CURLOPT_COOKIEJAR,  $cookie); //存储cookies
			}
		}
		
		$result = curl_exec($ch);
		curl_close($ch);
		
		if(! mb_check_encoding($result, 'utf-8')) {
			$result = mb_convert_encoding($result, 'utf-8', 'gbk');
		}		
		return $result;
	}
	function dump($arr)
	{
			print "<pre>";
			print_r($arr);
			print "</pre>";
	}
	function _rand() 
	{ 
		$length=26; 
		$chars = "0123456789abcdefghijklmnopqrstuvwxyz"; 
		$max = strlen($chars) - 1; 
		mt_srand((double)microtime() * 1000000); 
		$string = ''; 
		for($i = 0; $i < $length; $i++) { 
			$string .= $chars[mt_rand(0, $max)]; 
		} 
		return $string; 
	} 
?>
