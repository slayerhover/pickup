<?php
header('content-type:text/html;charset=utf-8');
error_reporting(7);
ini_set('memory_limit', '-1'); 
set_time_limit(0);
date_default_timezone_set('PRC');
require_once('./includes/db.class.php');
require_once('./includes/function.php');
require_once("./Excel/PHPExcel.php");
require_once("./Excel/PHPExcel/Writer/Excel5.php");
$dbconfig = array(
                'dsn'         =>    'mysql:host=localhost;dbname=pickup',
                'name'        =>    'pickup',
                'password'    =>    'sioned',
            ); 
$_DB =new DB($dbconfig);
$objExcel	= new PHPExcel();
$objWriter	= new PHPExcel_Writer_Excel5($objExcel);        

$cellname  = array(
		array('id','ID'),
		array('catanum','目录号'),
		array('name','产品名称'),
		array('size','Size'),
		array('price','价格'),
		array('status','状态'),
		array('target','靶点'),
		array('pathway','路径'),
		array('information','信息'),
		array('thumb','图标'),
		array('cas', 'CAS'),
		array('mw', 'MW'),
		array('formula', 'Formula'),
		array('alcohol', 'Alcohol'),
		array('dmso', 'DMSO'),
		array('water', 'Water'),
		array('small', 'Small'),
		array('url', '来源路径'),
);

$dataset	= $_DB->getAll("SELECT * from products");
$rows		= [];
foreach($dataset as $k=>$v){
	if(empty($v['priceset'])){
		$rows[]	=array(
			'id'		=>$v['id'],
			'catanum'	=>$v['catanum'],
			'name'		=>$v['name'],
			'size'		=>'',
			'price'		=>'',
			'status'	=>'',
			'target'	=>$v['target'],
			'pathway'	=>$v['pathway'],
			'information'=>$v['information'],
			'thumb'		=>$v['thumb'],
			'cas'		=>$v['cas'],
			'mw'		=>$v['mw'],
			'formula'	=>$v['formula'],
			'alcohol'	=>$v['alcohol'],
			'dmso'		=>$v['dmso'],
			'water'		=>$v['water'],
			'small'		=>$v['small'],
			'url'		=>$v['url'],
		);
	}else{
		$priceset = json_decode($v['priceset'], TRUE);
		if(!empty($priceset)&&is_array($priceset)){						
		foreach($priceset as $k1=>$v1){
			if($v1['size']=='10mM (in 1mL DMSO)')	continue;
			if($v1['price']<500)	continue;
			
			$rows[]	=	array(
				'id'		=>$v['id'],
				'catanum'	=>$v['catanum'],
				'name'		=>$v['name'],
				'size'		=>$v1['size'],
				'price'		=>$v1['price'],
				'status'	=>$v1['status'],
				'target'	=>$v['target'],
				'pathway'	=>$v['pathway'],
				'information'=>$v['information'],
				'thumb'		=>$v['thumb'],
				'cas'		=>$v['cas'],
				'mw'		=>$v['mw'],
				'formula'	=>$v['formula'],
				'alcohol'	=>$v['alcohol'],
				'dmso'		=>$v['dmso'],
				'water'		=>$v['water'],
				'small'		=>trim($v['small']),
				'url'		=>$v['url'],
			);			
		}}
	}
}
$objExcel   = makeExcel('导出产品', $cellname, $rows, $objExcel);
$outputFilePath = "./Uploads/";        
$outputFileName = "goods" . date('YmdHis') . ".xls"; //使用Excel2007导出.xlsx文件
$objWriter->save($outputFilePath . $outputFileName);
        
    /***下载文件***
        Header("Content-type: application/octet-stream");
        Header("Accept-Ranges: bytes");
        Header("Accept-Length: ".filesize($outputFilePath . $outputFileName));
        Header("Content-Disposition: attachment; filename=" . $outputFileName);
        $file = fopen($outputFilePath . $outputFileName,"r");
        echo fread($file,filesize($outputFilePath . $outputFileName));
        fclose($file);
	***/