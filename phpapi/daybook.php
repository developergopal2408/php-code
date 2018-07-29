<?php
include_once "db.php";
$id = $_REQUEST['officeid'];
$contra = $_REQUEST['contra'];//cash or non-cash or all

if($contra == 'cash'){
	$contra = ' and contraid = 301';
}else if($contra == 'non-cash'){
	$contra = ' and contraid != 301';
}else{
	$contra = '';
}

//print $contra;

$date1 = $_REQUEST['fromdate'];
$date2 = $_REQUEST['todate'];
//$type = $_REQUEST['type'];//center-member-office

$sql = "SELECT * FROM OfficeDetail WHERE ID='$id' ";
$res = odbc_exec($connection,$sql);
$p = odbc_fetch_array($res);
$pid = $p['ID']; 



require_once('js/nepali_calendar.php');
require_once('js/functions.php');
$cal = new Nepali_Calendar();
list($year, $month, $day) = explode('/', date('Y/m/d'));

$nepdate = $cal->eng_to_nep($year, $month, $day);
$ndate = $cal->eng_to_nep($year, $month, 01);
$sdate = $ndate['year']."/".$ndate['month']."/".'01';

$nyr = $nepdate['year'];
$nmonth = $nepdate['month'];
$nday = $nepdate['date'];

$cdate = $nyr . "/" . $nmonth . "/" . $nday;
//$cdate='2074/04/01';
//print $cdate;

$daybook = array();

$q1 = "select centerid from savingdetail where savedate BETWEEN '$date1' and '$date2' and officeid = '$id' group by centerid";
$res = odbc_exec($connection, $q1);
while($row = odbc_fetch_array($res)){
$centerid = rtrim(implode(',',$row),',');
echo $centerid;
	
}



/*$prefix = $centerid = '';
	foreach($row as $center){
		$centerid  .= $prefix . '' . $center . '';
		$prefix = ', ';
		
	}*/
	
	
$query = "select c.centercode,
(select sum(cramount)from savingdetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and savingtypeid=1 and officeid=c.officeid)welCr,
(select sum(dramount)from savingdetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and savingtypeid=1 and officeid=c.officeid)welDr,
(select sum(cramount)from savingdetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and savingtypeid=2 and officeid=c.officeid)ComCr,
(select sum(dramount)from savingdetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and savingtypeid=2 and officeid=c.officeid)ComDr,
(select sum(cramount)from savingdetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and savingtypeid=3 and officeid=c.officeid)PerCr,
(select sum(dramount)from savingdetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and savingtypeid=3 and officeid=c.officeid)PerDr,
(select sum(cramount)from savingdetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and savingtypeid=4 and officeid=c.officeid )SpeCr,
(select sum(dramount)from savingdetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and savingtypeid=4 and officeid=c.officeid )SpeDr,
(select sum(cramount)from savingdetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and savingtypeid=5 and officeid=c.officeid)PenCr,
(select sum(dramount + IntDr)from savingdetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and savingtypeid=5 and officeid=c.officeid $contra)PenDr,
(select sum(loancr) from loandetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and loantypeid=1 and officeid=c.officeid)GenCr,
(select sum(intcr) from loandetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and loantypeid=1 and officeid=c.officeid)GenInt,
(select sum(loandr) from loandetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and loantypeid=1 and officeid=c.officeid)GenDr,
(select sum(loancr) from loandetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and loantypeid=2 and officeid=c.officeid)EmeCr,
(select sum(intcr) from loandetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and loantypeid=2 and officeid=c.officeid)EmeInt,
(select sum(loandr) from loandetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and loantypeid=2 and officeid=c.officeid)EmeDr,
(select sum(loancr) from loandetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and loantypeid=3 and officeid=c.officeid)HouCr,
(select sum(intcr) from loandetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and loantypeid=3 and officeid=c.officeid)HouInt,
(select sum(loandr) from loandetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and loantypeid=3 and officeid=c.officeid)HouDr,
(select sum(loancr) from loandetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and loantypeid=7 and officeid=c.officeid)DseCr,
(select sum(intcr) from loandetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and loantypeid=7 and officeid=c.officeid)DseInt,
(select sum(loandr) from loandetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and loantypeid=7 and officeid=c.officeid)DseDr,
(select sum(loancr) from loandetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and loantypeid=9 and officeid=c.officeid)EduCr,
(select sum(intcr) from loandetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and loantypeid=9 and officeid=c.officeid)EduInt,
(select sum(loandr) from loandetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and loantypeid=9 and officeid=c.officeid)EduDr,
(select sum(cramount)from insurancedetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and insurancetypeid=1 and officeid=c.officeid)EFCr,
(select sum(Dramount)from insurancedetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and insurancetypeid=1 and officeid=c.officeid)EFDr,
(select sum(cramount)from insurancedetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and insurancetypeid=2 and officeid=c.officeid)Catt,
(select sum(cramount)from insurancedetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and insurancetypeid=3 and officeid=c.officeid)LIc,
(select sum(cramount)from incomedetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and incometypeid=1 and officeid=c.officeid)Passbook,
(select sum(cramount)from incomedetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and incometypeid=2 and officeid=c.officeid)Att,
(select sum(cramount)from incomedetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and incometypeid=5 and officeid=c.officeid)Cheq,
(select sum(cramount)from incomedetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and incometypeid=7 and officeid=c.officeid)Other,
(select sum(dramount)from savingdetail where particulars<>'Withdraw for Installement and Saving' and c.centerid=centerid and officeid=c.officeid and savedate='$cdate')cashsw
from centermain c where c.officeid='$pid' and c.centerid IN($centerid)  group by c.centercode,c.centerid,c.officeid order by c.centercode";
//echo $query;
/*$result = odbc_exec($connection, $query);

while($day = odbc_fetch_array($result)){
	$daybook ['Data'][] = $day;
	$response["Status"]="Success";
	$response["responseText"]=$daybook;
	
}
header('Content-Type: application/json');
if($result){
$endoded= json_encode($response,JSON_PRETTY_PRINT);
	echo $endoded;
}else {
	$response["Status"]="Failed";
	$response["responseText"]="No Data Feeded";
	$endoded= json_encode($response,JSON_PRETTY_PRINT);
	echo $endoded;
	}
 //header('Content-Type: application/json');
//print json_encode($daybook,JSON_PRETTY_PRINT);

*/

?>