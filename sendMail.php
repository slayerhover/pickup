<?php
header('content-type:text/html;charset=utf-8');
error_reporting(7);
ini_set('memory_limit', '-1'); 
set_time_limit(0);
$starton = microtime(true);
date_default_timezone_set('PRC');
require_once('./includes/db.class.php');
require_once('./includes/function.php');
require_once("./PhpExcel/PHPExcel.php");
require_once("./PhpExcel/PHPExcel/Writer/Excel2007.php");
include_once('./PhpMailer/phpmailer.php');
$dbconfig = array(
                'dsn'         =>    'mysql:host=localhost;dbname=pickup',
                'name'        =>    'pickup',
                'password'    =>    'pickup',
            ); 
$_DB =new DB($dbconfig);

function export($date, $user_id, $dataset){
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->setTitle('消费清单');
		$cellname  = array(
			array('repaydate','还款日'),
			array('name','消费项目'),
			array('money','金额'),	
			array('times','时间'),
		);
		$r = exportExcel($date.'账单', $cellname, $dataset[0], $objPHPExcel);
		$objWriter = new PHPExcel_Writer_Excel2007($r);
		$dirname   = './Uploads';
		if( !is_dir($dirname) )	mk_dir('./Uploads');
		$file_name = $dirname.'/'.$date.'-'.$user_id.'.xlsx';
		$objWriter->save($file_name);
		
		return $file_name;
}
function sendmail($email, $coname, $subject, $body, $file){
		$mail = new PHPMailer();
		$mail->CharSet = 'utf-8';
		$mail->IsSMTP(); // set mailer to use SMTP
		$mail->IsHTML(true);
		$mail->Host = "smtp.exmail.qq.com"; // specify main and backup server
		$mail->SMTPAuth = true; // turn on SMTP authentication
		$mail->Username = "server@website.com"; // SMTP username
		$mail->Password = "mypassword"; // SMTP password
		$mail->From = "server@website.com";
		$mail->FromName = "邮件系统通知";
		$mail->AddAddress($email, $coname);
		$mail->Subject =$subject;
		$mail->Body = $body;
		$mail->AddAttachment($file);		
		return $mail->Send();		 
}

try{	
	$date	=	date('Y年m月d日');
	$sql	=	"SELECT user_id, username, email from members";
	$user	=	$_DB->getAll($sql);	
	if( empty($user) ){ throw new Exception("用户记录为空."); }
	
	foreach($user as $key=>$value){		
		$sql		= "SELECT * from bill where user_id='{$value['user_id']}' and date='{$date}'";
		$dataset	= $_DB->getAll($sql);		
		$filename	= export($date, $value['user_id'], $dataset);
		$body = "<br /><b>【用户消费清单】</b><p>尊敬的{$username}：<br />您的账单在附件中送达。";
		
		sendmail($value['email'], $value['username'], $date.'消费清单', $body, $filename);		
		write_log("发送邮件到：".$value['email'].", 给".$value['username'].".");
	}
}catch(Exception $e){
    echo "Failed: " . $e->getMessage();
}

$time = round(microtime(true) - (float)$starton, 5);
echo '浪费计算时间共：',$time,'    浪费内存共计：', (memory_get_usage(true) / 1024), "kb\n\nDone.\n";
