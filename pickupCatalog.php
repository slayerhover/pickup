<?php
header('content-type:text/html;charset=utf-8');
error_reporting(7);
ini_set('memory_limit', '-1'); 
set_time_limit(0);
$starton = microtime(true);
require_once('./includes/db.class.php');
require_once('./includes/pinyin.php');
include_once("./includes/curl.php");
include_once("./includes/simple_html_dom.php");
$dbconfig = array(
                'dsn'         =>    'mysql:host=localhost;dbname=pickup',
                'name'        =>    'pickup',
                'password'    =>    'pickup',
            ); 
$_DB =new DB($dbconfig);

$start	= 65;
$end	= 90;
if($argc>1){
switch($argc){
	case 2:
		$start	=	intval($argv[1]);
		break;
	default:
		$start	=	intval($argv[1]);
		$end	=	intval($argv[2]);
		break;
}}

try{
	$url	=	"http://www.jd.com/frontend/ajaxproduct.jhtml?queryName=";
	for($i=$start;$i<=$end;$i++){
		$data	=	curl_get($url . chr($i));
		if($data==FALSE){ 
			for($l=0;$l<3;$l++){
				sleep(rand(1,3));
				$data = curl_get($url . chr($i));
				if($data!=FALSE){ break; }
			}
			if($data==FALSE){
				throw new Exception("页面获取失败.failed on {$url}" . chr($i)); 
			}
		}		
		$thtml	=	str_get_html($data);
		if(!$thtml){ throw new Exception("HTML内容加载失败."); }
		echo "开始分析页面标签.\n";
		$datalist	=	$thtml->find('tr');
		if( !empty($datalist) ){
		foreach($datalist as $tr){
			$onelist	=   $tr->find('td');
			if( empty($onelist) ){ continue; }
			
			$rows = array(
				'links'			=> 'http://www.jd.com' . $tr->find('a', 0)->href,
				'name'			=> $tr->find('a', 0)->innertext,
				'description'	=> $tr->find('td', 1)->innertext,
			);
            $_DB->add('target', $rows);
            echo "{$i}.Insert product:{$rows['name']}\n";
		}}else{
			throw new Exception('未找到想要的标签.');
		}
		usleep(500);
	}
}catch(Exception $e){
    echo "Failed: " . $e->getMessage();
}

$time = round(microtime(true) - (float)$starton, 5);
echo '浪费计算时间共：',$time,'    浪费内存共计：', (memory_get_usage(true) / 1024), "kb\n\nDone.\n";
