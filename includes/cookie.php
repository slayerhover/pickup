<?php    
function vlogin($url,$data,$cookie_file){ // 模拟登录获取Cookie函数    
    $curl = curl_init(); // 启动一个CURL会话    
    curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址                
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查    
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在    
    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/536.11 (KHTML, like Gecko) Chrome/20.0.1132.47 Safari/536.11Mozilla/5.0 (Windows NT 6.1) AppleWebKit/536.11 (KHTML, like Gecko) Chrome/20.0.1132.47 Safari/536.11'); // 模拟用户使用的浏览器    
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 0); // 使用自动跳转    
    curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer    
    curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求    
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包    
    curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_file); // 存放Cookie信息的文件名称    
    curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie_file); // 读取上面所储存的Cookie信息    
    curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环    
    curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容    
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回    
    $tmpInfo = curl_exec($curl); // 执行操作    
    if (curl_errno($curl)) {    
       echo 'Errno'.curl_error($curl);    
    }    
    curl_close($curl); // 关闭CURL会话    
    return $tmpInfo; // 返回数据    
}    
   
function vget($url,$cookie_file){ // 模拟获取内容函数    
    $curl = curl_init(); // 启动一个CURL会话    
    curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址                
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查    
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在    
    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/536.11 (KHTML, like Gecko) Chrome/20.0.1132.47 Safari/536.11Mozilla/5.0 (Windows NT 6.1) AppleWebKit/536.11 (KHTML, like Gecko) Chrome/20.0.1132.47 Safari/536.11'); // 模拟用户使用的浏览器    
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 0); // 使用自动跳转    
    curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer    
    curl_setopt($curl, CURLOPT_HTTPGET, 1); // 发送一个常规的Get请求    
    curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_file); // 存放Cookie信息的文件名称    
    curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie_file); // 读取上面所储存的Cookie信    
    curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环    
    curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容    
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回    
    $tmpInfo = curl_exec($curl); // 执行操作    
    if (curl_errno($curl)) {    
       //echo 'Errno'.curl_error($curl);    
	return FALSE;
    }    
    curl_close($curl); // 关闭CURL会话    
    return $tmpInfo; // 返回数据    
}    
   
function vpost($url,$data,$cookie_file,$ip='8.8.8.8'){ // 模拟提交数据函数    
    $curl = curl_init(); // 启动一个CURL会话    
    curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址                
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1); // 对认证证书来源的检查    
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在    
    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/536.11 (KHTML, like Gecko) Chrome/20.0.1132.47 Safari/536.11Mozilla/5.0 (Windows NT 6.1) AppleWebKit/536.11 (KHTML, like Gecko) Chrome/20.0.1132.47 Safari/536.11'); // 模拟用户使用的浏览器    
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转    
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:'.$ip, 'CLIENT-IP:'.$ip));
    curl_setopt($curl, CURLOPT_AUTOREFERER, 0); // 自动设置Referer    
    curl_setopt($curl, CURLOPT_REFERER, $url);
    curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求    
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包    	
    curl_setopt($curl, CURLOPT_COOKIEFILE, $cookie_file); // 读取上面所储存的Cookie信息    
    curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环    
    curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容    
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回    
    $tmpInfo = curl_exec($curl); // 执行操作    
    if (curl_errno($curl)) {    
       echo 'Errno'.curl_error($curl);    
    }    
    curl_close($curl); // 关键CURL会话    
    return $tmpInfo; // 返回数据    
}    
   
function delcookie($cookie_file){ // 删除Cookie函数    
 @unlink($cookie_file); // 执行删除    
}   
	//删除所有空格，换行字符
	function delnull($str){
		$qian=array(" ","　","\t","\n","\r");
		$hou=array("","","","","");
		return str_replace($qian,$hou,$str);   
	}

	//分割标签
	function cuttag($str,$tag){
		$arr=explode($tag,$str);
		return $arr;
	}
	
	//去标签函数
	function deltag($str,$tag){
		return strip_tags($str,$tag);
	}
	
	//对字符串进行操作
	function han($str,$flag=1){
		$str=deltag($str,'<table>,<tbody>,<tr>,<td>,<a>');	//去掉影响的标签，保留table、tr、td标签
		//$str=delnull($str);	//先删除所有空格
		$str=cuttag($str,'</table>');		//将字符串以</table>进行分割为数组
		$str=array_filter($str);	//删除空元素
		$str1=$str[0];	//车主信息
		array_shift($str);	//违章信息
		//var_dump($str);exit;
		if($flag==1){
			$str1=delnull($str1);
			$str1=cutStr($str1,500,509,0);
			$str1_1=cutStr($str1,12,0,0);
			$arr_str1=cuttag($str1_1,'：');
			$str1_2=cutStr($str1,500,12,0);
			$arr2=han2($str1,'<tr>');	//调用han2函数进一步处理
			$aa=array_merge($arr2,$arr_str1);
			return $aa;
		}
		if($flag == 2){
			return han3($str);	//调用han2函数进一步处理
		}
	}
	
		//对字符串进行操作2
	function han2($sss,$tag){
		$sss=cuttag($sss,$tag);
		for($i=1;$i<count($sss)-1;$i++){
			$sss1[$i]=deltag($sss[$i]);
		}
		return $sss1;
	}
	
	function han3($arr){
		array_pop($arr);	//删除最后一个元素
		$arr_str=array();
		foreach($arr as $key){
			$key=cuttag(deltag($key,'<tr>,<td>,<a>'),'<tr>');
			//操作第一个元素
			array_shift ($key);
			$key1=cuttag(delnull(deltag($key[0],'<td>')),'<td>');
			$key1[0]=deltag($key1[0]);
			$key1[1]=deltag($key1[1]);
			//操作第二个元素
			$key2=cuttag(deltag($key[1],'<td>,<a>'),'<td>');
			$key2[0]=deltag(delnull($key2[0])).'元';
			$m=match_links($key2[1]);
			$url=$m['link'][0];
			$kk=han4($url);	//对远程获取的文件进行操作
			array_pop($key2);
			foreach($kk as $vv){
				array_push($key2,$vv);
			}
			//操作第三个元素
			$key3=delnull(deltag($key[2]));
			
			$array_con=array_merge($key1,$key2);
			array_push($array_con,$key3);
			array_push($arr_str,$array_con);
		}
		return $arr_str;
	}
	
	function han4($url){
		$con = file_get_contents($url);	//远程获取文件内容
		$con=delnull(deltag($con,'<div>,<h2>'));
		$arr_con=cuttag($con,'</div>');
		array_pop($arr_con);
		array_pop($arr_con);
		array_pop($arr_con);
		$bb=array_splice($arr_con,1);
		for($i=0;$i<count($bb);$i++){
			$bb[$i]=deltag($bb[$i]);
			//$bb[$i]=autoCharset($bb[$i]);
		}
		return $bb;
		//var_dump(autoCharset($bb));exit;
	}
	
	function match_links($document) { 
		preg_match_all("'<\s*a\s.*?href\s*=\s*([\"\'])?(?(1)(.*?)\\1|([^\s\>]+))[^>]*>?(.*?)</a>'isx",$document,$links); 
		while(list($key,$val) = each($links[2])) { 
			if(!empty($val)) $match['link'][] = $val; 
		} 
		while(list($key,$val) = each($links[3])) { 
			if(!empty($val)) $match['link'][] = $val; 
		} 
		return $match; 
	}
	
	function cutStr($str, $len = 100, $start = 0, $suffix = 1) {
		$str = trim($str);
		$str = str_replace(array("\n", "\t"), "", $str);
		$strlen = mb_strlen($str);
		while ($strlen) {
			$array[] = mb_substr($str, 0, 1, "utf8");
			$str = mb_substr($str, 1, $strlen, "utf8");
			$strlen = mb_strlen($str);
		}
		$end = $len + $start;
		$str = '';
		for ($i = $start; $i < $end; $i++) {
			$str.=$array[$i];
		}
		return count($array) > $len ? ($suffix == 1 ? $str . "&hellip;" : $str) : $str;
	}
	
	//字符串转换函数--一维数组
	function autoCharset($arr) {
        if (is_array($arr)) {
			foreach($arr as $key){
				$arr1[]=mb_convert_encoding($key, "GBK", "UTF-8");
			}
            return $arr1;
        }
        else {
            return $arr;
        }
    }
	
	//字符串转换函数--二维数组
	function autoCharset2($arr) {
        if (is_array($arr)){
			for($i=0;$i<count($arr);$i++){
				foreach($arr[$i] as $key){
					$arr1[$i][]=mb_convert_encoding($key, "GBK", "UTF-8");
				}
			}
            return $arr1;
        }else {
            return $arr;
        }
    }
?>
