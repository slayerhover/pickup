<?php
/***
返回一段时间内的，时间列表
***/
function timelist($begin,$end)
{
    $begin = strtotime($begin);//开始时间
    $end = strtotime($end);//结束时间
	$retrun_time = array();
    for($i=$begin; $i<=$end;$i+=(24*3600))
    {
		$date = array();
		$date[0] = date("Ymd",$i).'000000';
		$date[1] = date("Ymd",$i).'235959';
		array_push($retrun_time,$date);
    }
	return $retrun_time;
}
/***
* 根据年月日时分秒返回时间戳
*
*/
function get_ctime($time){
	if($time !='' &&  strlen($time)==14 ){
		$year = substr($time,0,4);
		$month = substr($time,4,2);
		$day = substr($time,6,2);
		$hours = substr($time,8,2);
		$minutes = substr($time,10,2);
		$seconds  = substr($time,12,2);
		$t = mktime($hours,$minutes,$seconds,$month,$day,$year);
		return $t;
	}else return $time;
}
function write_log($msg=''){
	$log_file = date("Y-m-d");
	$handle = @fopen("/mnt/webroot/MoneyMoreMore/data/log/".$log_file.".txt", "a+");
	@flock($handle, LOCK_EX) ;
	$text = date("Y-m-d H:i:s")." ".$msg."\r\n";
    @fwrite($handle,$text);
	@flock($handle, LOCK_UN);
	@fclose($handle);
}
/****
生成execl 内容
expTitle 标题
expCellName 列名
expTableData 数据
objPHPExcel PHPExcel 对象
***/
function makeExcel($expTitle,$expCellName,$expTableData,$objExcel){        
	$objProps = $objExcel->getProperties();       
	$objProps->setCreator($expTitle);
	$objProps->setTitle($expTitle);
	$objProps->setSubject($expTitle);
	$objExcel->setActiveSheetIndex(0);
	$objActSheet = $objExcel->getActiveSheet();
	$objActSheet->setTitle($expTitle);
			
	$cellNum = count($expCellName);
	$dataNum = count($expTableData);
	$cellName = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W',
	'X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT',
	'AU','AV','AW','AX','AY','AZ');
	$objExcel->getActiveSheet()->mergeCells('A1:'.$cellName[$cellNum-1].'1');//合并单元格
	$objExcel->getActiveSheet()->setCellValue('A1', $expTitle.' 生成时间:'.date('Y-m-d H:i:s'));//合并单元格生成标题
		
	for($i=0;$i<$cellNum;$i++){
		   $objExcel->getActiveSheet()->setCellValueExplicit($cellName[$i].'2', $expCellName[$i][1], 
		   PHPExcel_Cell_DataType::TYPE_STRING);
	} 
	for($i=0;$i<$dataNum;$i++){
		   for($j=0;$j<$cellNum;$j++){
			$objExcel->getActiveSheet()->setCellValueExplicit($cellName[$j].($i+3), 
			$expTableData[$i][$expCellName[$j][0]], PHPExcel_Cell_DataType::TYPE_STRING);
		   }             
	} 
	return $objExcel;
}
function mk_dir($dir,$dir_perms=0775){
	/* 循环创建目录 */
	if (DIRECTORY_SEPARATOR!='/') {
		$dir = str_replace('\\','/', $dir);
	}
	if (is_dir($dir)){
		return true;
	}
	if (@mkdir($dir, $dir_perms)){
		return true;
	}
	if (!mk_dir(dirname($dir))){
		return false;
	}
	return mkdir($dir, $dir_perms);
}
/**
 * 校验日期格式是否正确
 *
 * @param string $date 日期
 * @param string $formats 需要检验的格式数组
 * @return boolean
 */
function checkDateIsValid($date, $format="Y-m-d") {
    if (!strtotime($date)) { //strtotime转换不对，日期格式显然不对。
        return false;
    }
	
	$strArr = explode("-",$date);
	if(empty($strArr)){	return false; }
	foreach($strArr as $val){
		if(strlen($val)<2){
			$val="0".$val;
		}
		$newArr[]=$val;
	}
	$str =implode("-",$newArr);
	$unixTime=strtotime($str);
	$checkDate= date($format,$unixTime);
	return ($checkDate==$str);
}

function getIp(){
	if(!empty($_SERVER['HTTP_CLIENT_IP'])){
	   return $_SERVER['HTTP_CLIENT_IP']; 
	}elseif(!empty($_SERVER['HTTP_X_FORVARDED_FOR'])){
	   return $_SERVER['HTTP_X_FORVARDED_FOR'];
	}elseif(!empty($_SERVER['REMOTE_ADDR'])){
	   return $_SERVER['REMOTE_ADDR'];
	}else{
	   return "未知IP";
	}
}

function json($vars)
{
	header("Content-type: application/json");
    echo json_encode($data);
	exit;
}
