<?php
header('content-type:text/html;charset=utf-8');
error_reporting(7);
ini_set('memory_limit', '-1'); 
set_time_limit(0);
$starton = microtime(true);
require_once('./includes/db.class.php');
$dbconfig = array(
                'dsn'         =>    'mysql:host=localhost;dbname=pickup',
                'name'        =>    'pickup',
                'password'    =>    'pickup',
            ); 
$_DB =new DB($dbconfig);

$start	= 0;
$end	= 1000;
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

/**
  * 图片本地化
  **/
class	ImgLocally{
	public static function writePhoto($file, $imgurl){
		if(!file_exists($file)){
			$wgetshell='wget -O '.$file.' "'.$imgurl.'" ';
			shell_exec($wgetshell);
			//self::cut($file);
			return $file;
		}
		return false;
	}
	public static function writePhotoInWeb($file, $imgurl){
		if(!file_exists($file)){				
			file_put_contents($file, file_get_contents($imgurl));
			//self::cut($file);
			return $file;
		}
		return false;
	}
	private static function cut($file){
		$im = imagecreatefromjpeg($file);  
		$x	= imagesx($im);
		$y	= imagesy($im)-70;		//剪切掉底部	
		$dim= imagecreatetruecolor($x, $y); // 创建目标图gd2
		imagecopyresized ($dim,$im,0,0,0,0,$x,$y,$x,$y);
		imagejpeg($dim, $file);
		imagedestroy($im); 
		imagedestroy($dim);
	}
}

try{
	$sql = "select id,catanum,name,thumb from products limit $start, $end";
	$rows= $_DB->getAll($sql);
	if(!empty($rows)&&is_array($rows)){
	foreach($rows as $k=>$v){		
		$cDir 	=	"./images";
		if (! is_dir ( $cDir )) {  mkdir($cDir, 0777);	}		
		$name	=	preg_replace('#\s+|\(+|\)+|\*+|\<+|\>|\\+|\/+|\%+|\-+|\++#', '-', trim($v['name']));
		$name	=	$name.'-'.$v['catanum'].'.gif';
		$path	=	"{$cDir}/{$name}";
		if($newimg	=	ImgLocally::writePhoto($path, $v['thumb'])){
			$data	=	['newthumb'=>$path];
			$_DB->update('products', $data, "id={$v['id']}");
			echo $path, "\n";
		}else{
			echo "jump {$v['thumb']}\n";
		}
	}}
}catch(Exception $e){
    echo "Failed: " . $e->getMessage();
}

$time = round(microtime(true) - (float)$starton, 5);
echo '浪费计算时间共：',$time,'    浪费内存共计：', (memory_get_usage(true) / 1024), "kb\n\nDone.\n";
