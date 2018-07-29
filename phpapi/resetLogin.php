<?php
require_once 'db.php';
$code = $_REQUEST['code'];
$weblocked = $_REQUEST['weblocked'];
$select = odbc_exec($connection, "select * from staffmain where Code = '".sprintf("%04d", $code)."'");
$row = odbc_fetch_array($select);
if (empty($code) or empty($weblocked) or !preg_match('/^[0-9]*$/', $code)) {
    $response["Status"] = "Failure";
    $response["Message"] = "Please Enter StaffCode";
    $endoded = json_encode($response, JSON_PRETTY_PRINT);
    echo $endoded;
    exit;
} else if ($weblocked == 1 AND $row['Code'] == sprintf("%04d", $code)) {
    $sql = "UPDATE staffmain SET  IsWebLogged = '0' WHERE Code = '".sprintf("%04d", $code)."' ";
    $res = odbc_exec($connection, $sql);
    if ($res == true) {
        $response["Status"] = "Success";
        $response["Message"] = "Successfully Updated";
        $endoded = json_encode($response, JSON_PRETTY_PRINT);
        echo $endoded;
    } else {
        $response["Status"] = "Failure";
        $response["Message"] = "Failed to Update";
        $endoded = json_encode($response, JSON_PRETTY_PRINT);
        echo $endoded;
    }
} else if ($weblocked == 2 AND $row['Code'] == sprintf("%04d", $code)) {
    $sql = "UPDATE staffmain SET IsMobiLogged = '0' WHERE Code = '".sprintf("%04d", $code)."' ";
    $res = odbc_exec($connection, $sql);
	
    if ($res == true) {
        $response["Status"] = "Success";
        $response["Message"] = "Successfully Updated";
        $endoded = json_encode($response, JSON_PRETTY_PRINT);
        echo $endoded;
    } else {
        $response["Status"] = "Failure";
        $response["Message"] = "Failed to Update";
        $endoded = json_encode($response, JSON_PRETTY_PRINT);
        echo $endoded;
    }
} else if(!empty($weblocked) AND $row['Code'] == sprintf("%04d", $code)){
    $sql = "UPDATE staffmain SET IsWebLogged = '0',IsMobiLogged = '0',Token = '' WHERE Code = '".sprintf("%04d", $code)."' ";
    $res = odbc_exec($connection, $sql);
    if ($res == true) {
        $response["Status"] = "Success";
        $response["Message"] = "Successfully Updated";
        $endoded = json_encode($response, JSON_PRETTY_PRINT);
        echo $endoded;
    } else {
        $response["Status"] = "Failure";
        $response["Message"] = "Failed to Update";
        $endoded = json_encode($response, JSON_PRETTY_PRINT);
        echo $endoded;
    }
}else{
	$response["Status"] = "Failure";
        $response["Message"] = "No Any Staff Exist";
        $endoded = json_encode($response, JSON_PRETTY_PRINT);
        echo $endoded;
}
	/*$reso= odbc_exec($connection, "select * from staffmain where Code = '".sprintf("%04d", $code)."' ");
	$rows = odbc_fetch_array($reso);
	print_r($rows);*/


?>