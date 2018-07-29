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
$LIC = [];
$data = array();
$message1 = "";
if (odbc_num_rows($result) > 0) {
while($row = odbc_fetch_array($result)){
	$LIC [] = $row; 
}
$data = $LIC;
$to = "manojsharma@jeevanbikas.org.np";
$message1 .= '<!DOCTYPE html><html>
				<head></head><body style="font-family:Calibri; font-size:14px; width:100%;background:#ffffff;"><div style="padding:5px 10px 0px 10px;width:100%;float:left;color:black;"><div style="width:100%;float:left;background:#fde3c2;border-radius:0.3em 0.3em 0 0;"><img src="http://www.jbs.finlitex.com/img/finlite-logo.png" style="padding-top:10px;padding-bottom:10px;width:200px;height:50px;"></div>
								   <div style="width:100%;padding:5px 10px 0px 10px;float:left;color:black; background-color:#ffffff;">
						<table border="1" style="border:1px solid #000;">
						<tr>
							<th>BranchName</th>
							<th>M.Code</th>
							<th>M.Name</th>
							<th>IPerson</th>
							<th>DOB</th>
							<th>Gender</th>
							<th>Husband</th>
							<th>FName</th>
							<th>PolicyNo</th>
							<th>IAmount</th>
							<th>1stInst</th>
							<th>StartDate</th>
						</tr>';
							
        foreach($data as $key){

						$message1 = $message1 .'<tr>
										<td> '.str_ireplace("Branch Office","",$key['Name']).'</td>
										<td> '.$key['membercode'].'</td>
										<td> '.$key['MemberName'].'</td>
										<td> '.$key['InsuredPerson'].'</td>
										<td> '.$key['DOB'].'</td>
										<td> '.$key['Gender'].'</td>
										<td> '.$key['Husband'].'</td>
										<td> '.$key['FatherName'].'</td>
										<td> '.$key['policyno'].'</td>
										<td> '.$key['insuredamount'].'</td>
										<td> '.$key['finstamount'].'</td>
										<td> '.$key['startdate'].'</td>
									 </tr>';
		} 
					$message1 = $message1 .'</table>
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
        $mail->Body = $message1;
        $mail->IsHTML(true);
        $mail->AddAddress($to);
		$mail->addBcc("gopal@jeevanbikas.org.np");
        $mailsent = $mail->Send();
		if($mailsent){
			echo "<script>setTimeout(\"location.href = 'https://www.google.com/';\",1000);</script>";
		}
}else{
	echo  "NO Data Today";
}


        
    


?>
