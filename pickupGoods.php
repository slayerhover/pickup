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

$start	= 1;
$end	= 3106;
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
	$target = $_DB->getAll("select * from target where id>={$start} and id<={$end} order by id ASC");
	if( empty($target) ){ throw new Exception('nothing to do.'); }
	foreach($target as $k=>$v){
		$goodsname = addslashes($v['name']);
		if($_DB->getValue("select count(*) from products where name='{$goodsname}'")>0)	continue;
		$data	=	curl_data($v['links']);
		if($data==FALSE){ 
			for($l=0;$l<4;$l++){
				sleep(rand(1,3));
				$data = curl_data($v['links']);
				if($data!=FALSE){ break; }
			}
			if($data==FALSE){
				//throw new Exception("页面获取失败.failed on {$v['links']}"); 
				echo "jump {$v['id']}\n";
	            continue;
			}
		}
		$from	=	strpos($data, '<div id="breadcrumb">');
		$to		=	strpos($data, '<div class="grid-width-1-5">');
		if($from==FALSE || $to==FALSE){
			//throw new Exception("字符串未截取到");
			echo "jump {$v['id']}\n";	
			continue;
		}
		$thtml	=	str_get_html(substr($data, $from, $to-$from));
		if(!$thtml){ 
			//throw new Exception("HTML内容加载失败."); 
			echo "jump {$v['id']}\n";
            continue;
		}
		echo "开始分析页面标签.\n";
			
		if( preg_match('#(S\d+)#is', trim($thtml->find('p.mlmt25', 0)->innertext), $catanum) ){
			$catanum = $catanum[1];
		}		
		$pathway= $thtml->find('div#breadcrumb',0)->find('a', 1)->innertext;
		$target	= $thtml->find('div#breadcrumb',0)->find('a', 2)->innertext;
		
		$from   =       strpos($data, '<h3 class="blue_line"><span>化学数据</span>');
                $to     =       strpos($data, '<div class="grid-width-2-4" id="preparingStockSId">');
                if($from==FALSE || $to==FALSE){
                        //throw new Exception("化学数据字符串未截取到");
						echo "jump {$v['id']}\n";
						continue;
                }
                $hhtml  = str_get_html(substr($data, $from, $to-$from));
                $huaxue	= $hhtml->find('tr');
                if(!empty($huaxue)){
                foreach($huaxue as $hx){
                        switch(TRUE){
                        case stristr($hx->find('th', 0)->innertext, '分子量'):
                                $mw = trim($hx->find('td', 0)->innertext);
                        break;
                        case stristr($hx->find('th', 0)->innertext, '化学式'):
                                $formula = trim($hx->find('td', 0)->innertext);
                        break;
                        case stristr($hx->find('th', 0)->innertext, 'CAS'):
                                $cas = trim($hx->find('td', 0)->innertext);
                        break;
                        }
                }}
		if(stristr($data, 'stock_status')){
			$pricesheet=$thtml->find('table#stock_status',0)->find('table',0);
			$pricesize = sizeof($thtml->find('table#stock_status',0)->find('table',0)->find('tr'))-1;
			$priceset	= [];
			for($i=0; $i<$pricesize; $i++){
				$size  = $pricesheet->find('tr', $i)->find('td', 0)->find('label', 0)->innertext;
				if( preg_match('#([\.\d]+)#is', $pricesheet->find('tr', $i)->find('td', 1)->innertext, $price) ){
					$price = $price[1];
				}
				$status= $pricesheet->find('tr', $i)->find('td', 2)->innertext;
				array_push($priceset, ['size'=>$size, 'price'=>$price, 'status'=>$status]);			
			}
			$priceset	= json_encode($priceset, JSON_UNESCAPED_UNICODE);
		}else{
			$priceset	= '';
		}
		$from   =       strpos($data, '<h3 class="blue_line"><span>溶解度 (25°C)</span></h3>');
                $to     =       strpos($data, '<h3 class="blue_line"><span>化学数据</span>');
                if($from==FALSE || $to==FALSE){
                        //throw new Exception("溶解度字符串未截取到");
			echo "jump {$v['id']}\n";
			continue;
                }
                $shtml	= str_get_html(substr($data, $from, $to-$from));
		$solution= $shtml->find('tr');
		if(!empty($solution)){
		foreach($solution as $so){
			switch(TRUE){
			case stristr($so->find('td', 0)->innertext, 'DMSO'):
				$dmso = preg_replace('#\s{2,}#', '', trim($so->find('td', 1)->innertext));
			break;
			case stristr($so->find('td', 0)->innertext, 'Water'):
                                $water = preg_replace('#\s{2,}#', '', trim($so->find('td', 1)->innertext));
                        break;
			case stristr($so->find('td', 0)->innertext, 'Ethanol'):
                                $ethanol = preg_replace('#\s{2,}#', '', trim($so->find('td', 1)->innertext));
                        break;
			}
		}}
		$rows = array(
			'name'			=> addslashes(trim($thtml->find('h1.fl', 0)->innertext)),
			'url'			=> $v['links'],
			'catanum'		=> $catanum,
			'target'		=> addslashes($target),
			'pathway'		=> addslashes($pathway),
			'priceset'		=> $priceset,
			'information'		=> addslashes(trim($thtml->find('p.mt5', 0)->innertext)),
			'thumb'			=> 'http:' . trim($thtml->find('div.grid-width-1-4', 0)->find('img', 0)->src),
			'cas'			=> $cas,
			'mw'			=> $mw,
			'formula'		=> $formula,
			'alcohol'		=> $ethanol,
			'dmso'			=> $dmso,
			'water'			=> $water,
		);
		$_DB->add('products', $rows);
		$thtml->clear();
		$hhtml->clear();
		$shtml->clear();
		unset($data);
		echo "{$v['id']}.Insert product:{$rows['name']}\n";		
		sleep(rand(5,10));
	}
}catch(Exception $e){
    echo "Failed: " . $e->getMessage();
}

$time = round(microtime(true) - (float)$starton, 5);
echo '浪费计算时间共：',$time,'    浪费内存共计：', (memory_get_usage(true) / 1024), "kb\n\nDone.\n";
