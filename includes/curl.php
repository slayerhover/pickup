<?php
		if($compression!='') {	
		$result = curl_exec($ch);
		curl_close($ch);
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