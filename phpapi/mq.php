<?php

include_once "db.php";
header('Content-Type: application/json;');
$MemberMessage = [];
$getdata = array();
$query = "select  Top 10 *  from MemberQueries ORDER BY ID DESC";
$result = odbc_exec($connection, $query);
if (odbc_num_rows($result) > 0) {
    while ($rows = odbc_fetch_array($result)) {
        $MemberMessage [] = array(
            "ID" => $rows['ID'],
            "Message" => htmlentities($rows['Message'], ENT_IGNORE, "UTF-8"),
            //"Subject" => htmlentities($rows['Subject'], ENT_IGNORE, "UTF-8")
        );
    }
    $getdata = $MemberMessage;
    if ($getdata != null) {
        $response["Status"] = "Success";
        $response["data"] = $getdata;
    } else {
        $response["Status"] = "Success";
        $response["data"] = "No data";
    }

    $endoded = json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    echo $endoded;
} else {
    $failure = "Session Expired";
    $response = array('Status' => false, 'Message' => $failure);
    echo json_encode($response, JSON_PRETTY_PRINT);
}
?>