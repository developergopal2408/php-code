<?php
require_once 'db.php';
require_once 'PHPMailer/class.phpmailer.php';
require_once 'PHPMailer/PHPMailerAutoload.php';
require_once('reports/js/nepali_calendar.php');
require_once('reports/js/functions.php');
$cal = new Nepali_Calendar();
list($year, $month, $day) = explode('/', date('Y/m/d', mktime(0, 0, 0, date("m") , date("d")-1,date("Y"))));
$nepdate = $cal->eng_to_nep($year, $month, $day);
$ndate = $cal->eng_to_nep($year, $month, $day);
$sdate = $ndate['year'] . "/" . $ndate['month'] . "/" . '01';
$nyr = $nepdate['year'];
$nmonth = $nepdate['month'];
$nday = $nepdate['date'];
$cdate = $nyr . "/" . $nmonth . "/" . $nday;

$qry = "select o.Code,o.Name, m.membercode,m.firstname+' '+ m.lastname as MemberName,
i.Name as InsuredPerson,m.DOB,i.Husband,i.FatherName,I.Gender,i.insuredamount,i.policyno, i.finstamount,i.Instamount ,i.startdate,i.isSelf
from member m, insuranceaccount i,Officedetail o
where m.memberid=i.memberid and i.isactive='Y' and m.status='active' and  i.startdate = '$cdate'
and o.id=m.officeid and o.id=i.officeid  and i.IsSelf =1
Union all
select o.Code,o.Name, m.membercode,m.firstname+' '+ m.lastname as MemberName,
F.name as InsuredPerson,F.DOB,i.Husband,i.FatherName,i.Gender,i.insuredamount,i.policyno, i.finstamount,i.Instamount ,i.startdate,i.isSelf
from member m, insuranceaccount i,Officedetail o,MemberFamilyDetail f
where m.memberid=i.memberid and i.isactive='Y' and m.status='active' and i.startdate ='$cdate' 
and o.id=m.officeid and o.id=i.officeid and m.MemberID =i.MemberID and f.ID=i.InsuredPersonID and f.OfficeID =i.OfficeID 
and i.isself=0";                                        
$result = odbc_exec($connection, $qry);
require_once("Classes/PHPExcel.php");
//Create a PHPExcel object
$objPHPExcel = new PHPExcel();
//Set document properties
$objPHPExcel->getProperties()->setCreator("Gopal Kumar Shah");

// Set default font
$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')
                                          ->setSize(10);
//Set the first row as the header row
$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Name')
							  ->setCellValue('B1', 'MemberCode')
							  ->setCellValue('C1', 'MemberName')
							  ->setCellValue('D1', 'InsuredPerson')
							  ->setCellValue('E1', 'Gender')
							  ->setCellValue('F1', 'DOB')
							  ->setCellValue('G1', 'InsuredAmount')
							  ->setCellValue('H1', 'PolicyNumner')
							  ->setCellValue('I1', 'FatherName')
							  ->setCellValue('J1', 'Husband')
							  ->setCellValue('K1', 'FirstInstallement')
							  ->setCellValue('L1', 'StartDate');
							  
//Rename the worksheet
$objPHPExcel->getActiveSheet()->setTitle('LIC Account Opened');

//Set active worksheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);
if(odbc_num_rows($result)>0)
{
	 $i = 1;
	 while($row = odbc_fetch_object($result)) {
		$objPHPExcel->getActiveSheet()->setCellValue('A'.($i+1), $row->Name)
									  ->setCellValue('B'.($i+1), $row->membercode)
									  ->setCellValue('C'.($i+1), $row->MemberName)
									  ->setCellValue('D'.($i+1), $row->InsuredPerson)		
									  ->setCellValue('E'.($i+1), $row->Gender)
									  ->setCellValue('F'.($i+1), $row->DOB)
									  ->setCellValue('G'.($i+1), $row->insuredamount)
									  ->setCellValue('H'.($i+1), $row->policyno)
									  ->setCellValue('I'.($i+1), $row->FatherName)
									  ->setCellValue('J'.($i+1), $row->Husband)
									  ->setCellValue('K'.($i+1), $row->finstamount)
									  ->setCellValue('L'.($i+1), $row->startdate);
	    $i++;
	 }

}						  

//Dynamic name, the combination of date and time
$filename = date('d-m-Y_H-i-s').".xls";
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
unlink($filename);
$uploadfile = "uploads/" . $filename;
$objWriter->save(str_replace('.php', '.xls', "uploads/".$filename));

/*if(!unlink($uploadfile)){
	echo "No File Deleted";
}else{
	echo $uploadfile;
}*/

$to = "manojsharma@jeevanbikas.org.np";

$message = '<!DOCTYPE html><html>
				<head></head><body style="font-family:Calibri; font-size:14px; width:100%;background:#ffffff;"><div style="padding:5px 10px 0px 10px;width:100%;float:left;color:black;"><div style="width:100%;float:left;background:#fde3c2;border-radius:0.3em 0.3em 0 0;"><img src="http://www.jbs.finlitex.com/img/finlite-logo.png" style="padding-top:10px;padding-bottom:10px;width:200px;height:50px;"></div>
								   <div style="width:100%;padding:5px 10px 0px 10px;float:left;color:black; background-color:#ffffff;">
						<p>Please Find the attachment of Account Opened for LIC '. $cdate.'</p>
							<div style="width:100%;padding:5px 10px 0px 10px;float:left;color:black; background-color:#ffffff;">
<p style="text-align:left;font-family:Calibri;color:black;">
Thanks & Regards,<br />
<b>FinliteX Team</b>,<br />
<b>Contact No:- 9802714703</b>.
</p>
</div>
<div style="width:100%;float:left;background:#fde3c2;border-radius: 0 0 0.3em 0.3em;">
<p style="text-align:center;font-family: Open Sans Condensed, sans-serif;color:black;padding-left:15px">

<br>

</p>
</div>
									
					</div>
				</body>
			</html>';
        $mail = new PHPMailer();
		$mail->SMTPOptions = array(
									'ssl' => array(
									'verify_peer' => false,
									'verify_peer_name' => false,
									'allow_self_signed' => true
									)
									);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->SMTPSecure = 'TLS';
        $mail->SMTPAuth = true;
		$mail->Username = 'abbsportal@jeevanbikas.org.np';                
		$mail->Password = '!@#googlebhai.com';  
        $mail->From = 'abbsportal@jeevanbikas.org.np';
        $mail->FromName = 'Core Banking System - FinliteX';
        $mail->Subject = 'New LIC Account Added';
        $mail->Body = $message;
        $mail->IsHTML(true);
        $mail->AddAddress($to);
		
		$mail->addCustomHeader('Content-Disposition: attachment; filename='.$filename);
		
		$mail->addBcc("gopal@jeevanbikas.org.np");
		$mail->AddAttachment($uploadfile);
        $mailsent = $mail->Send();
		if($mailsent){
					if(!unlink($uploadfile)){
			echo "No File Deleted";
		}else{
			echo $uploadfile;
		}
			echo "<script>setTimeout(\"location.href = 'https://www.google.com/';\",1000);</script>";
		}






?>
