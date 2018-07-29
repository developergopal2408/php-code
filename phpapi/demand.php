<?php
include_once "db.php";

$id = $_REQUEST['officeid'];
$date1 = $_REQUEST['fromdate'];
$date2 = $_REQUEST['todate'];

$data= array();

$query = "select m.membercode,m.firstname+' '+m.lastname as MemberName, t.loantype, h.loanheading,d.savedate,d.demandloan
from member m, loantype t, loanheading h, demandloan d  where m.memberid=d.memberid and d.loantypeid=t.loantypeid and h.loanheadingid=d.loanheadingid 
and m.officeid=d.officeid and d.savedate between '$date1' and '$date2' and m.officeid='$id' order by m.membercode";

$return = [];
$result = odbc_exec($connection, $query);
if(odbc_num_rows($result) > 0){
while($day = odbc_fetch_array($result)){
	$return[] = $day;	
}
$data['demand'] = $return;
}

if ($data!= null){
			$response["status"] = "Success";
			$response["data"] = $data;
        } else{
			$response["status"] = "Success";
			$response["data"] = "No data";	
		}
header('Content-Type: application/json');
$endoded = json_encode($response, JSON_PRETTY_PRINT);
echo $endoded;
?>