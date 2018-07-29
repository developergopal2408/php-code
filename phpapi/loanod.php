<?php
include_once "db.php";

$id = $_REQUEST['officeid'];
$date1 = $_REQUEST['fromdate'];
$date2 = $_REQUEST['todate'];

$data= array();
       
$query = "select m.memberid, m.membercode, m.firstname+' '+m.LastName as MemberName, l.loantype, a.savedate, sum(a.pridue)pridue,sum(a.intdue)intdue
from member m, loandetail a, loantype l
where m.officeid=a.officeid and m.memberid=a.memberid  and a.loantypeid=l.loantypeid and a.officeid=$id  and a.savedate BETWEEN '$date1' and '$date2' 
group by m.memberid, m.membercode,m.firstname,m.lastname,a.savedate, l.loantype 
having sum(a.pridue+a.intdue)>0 order by a.savedate,m.membercode";  
       
$return = [];
$result = odbc_exec($connection, $query);
if(odbc_num_rows($result) > 0){
while($day = odbc_fetch_array($result)){
	$return[] = $day;	
}
$data['loanod'] = $return;
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