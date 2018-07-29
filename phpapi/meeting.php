<?php

// server should keep session data for AT LEAST 1 hour
ini_set('session.gc_maxlifetime', 180);
// each client should remember their session id for EXACTLY 1 hour
session_set_cookie_params(180);
ini_set('max_execution_time', 500);
include_once "db.php";

$id = $_REQUEST['officeid'];
$date1 = $_REQUEST['fromdate'];
$date2 = $_REQUEST['todate'];
if ($id > 0) {
    $test = "and a.OfficeID=" . $id;
} else {
    $test = '';
}

$sql = "SELECT f.FBsYear FROM FisCal f, OfficeDetail a WHERE a.ID=1 and a.FyearID=f.FyearID";
$res = odbc_exec($connection, $sql);
$p = odbc_fetch_array($res);
$fiscal = $p['FBsYear'];
$fyear = substr($fiscal, 0, 4);
$fyearn = $fyear . "/04/01";

$data = [];

$query = "select o.id, o.code,o.name, count(a.MasterId) as nos, a.MeetingDate,
(SELECT Count(*) FROM CollMaster WHERE officeid=o.id and MeetingDate between '$date1' and '$date2' and IsDown=1)down,
(SELECT Count(*) FROM CollMaster WHERE officeid=o.id and MeetingDate between '$date1' and '$date2' and IsUp=1)up,
(SELECT Count(*) FROM CollMaster WHERE officeid=o.id and MeetingDate between '$date1' and '$date2' and IsGenerated=1)gen,
(SELECT Count(*) FROM CollMaster WHERE officeid=o.id and MeetingDate between '$date1' and '$date2' and IsPosted=1)post 
from officedetail o, CollMaster a 
where o.id=a.officeid  $test and a.MeetingDate between '$date1' and '$date2'
group by o.id, o.code,o.name, a.MeetingDate order by a.MeetingDate, o.code";
$return = [];
$result = odbc_exec($connection, $query);
if (odbc_num_rows($result) > 0) {
    while ($day = odbc_fetch_array($result)) {
        $return[] = $day;
    }
}
$data = $return;

if ($data != null) {
    $response = $data;
} else {
    $response = "No data";
}
header('Content-Type: application/json');
$endoded = json_encode($response, JSON_PRETTY_PRINT);
echo $endoded;
?>