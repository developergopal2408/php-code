<?php

header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json; charset=UTF-8");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
ini_set('mx_execution_time', 600);
$serverName = "JBS-DB"; //serverName\instanceName
$connectionInfo = array("Database" => "FinliteX");
$connection = sqlsrv_connect($serverName, $connectionInfo);
if (!$connection) {
    echo "Connection could not be established.<br />";
    die(print_r(sqlsrv_errors(), true));
}
?>