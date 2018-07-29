<?php
include_once "db.php";

$id = $_REQUEST['officeid'];
$date1 = $_REQUEST['fromdate'];
$date2 = $_REQUEST['todate'];

$data= array();
       
$query = "select m.memberid, m.membercode, m.firstname+' '+m.LastName as MemberName, s.savingtype, a.savedate, sum(a.prebal)prebal from member m, savingdetail a, savingtype s
where m.officeid=a.officeid and m.memberid=a.memberid and s.savingtypeid=a.savingtypeid and a.officeid=$id  and a.savedate BETWEEN '$date1' and '$date2' 
group by m.memberid, m.membercode,m.firstname,m.lastname,a.savedate,s.savingtype having sum(a.prebal)>0 order by a.savedate,m.membercode";  
       
$return = [];
$result = odbc_exec($connection, $query);
if(odbc_num_rows($result) > 0){
while($day = odbc_fetch_array($result)){
	$return[] = $day;	
}
$data['savingod'] = $return;
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