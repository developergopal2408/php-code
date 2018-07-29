<?php
error_reporting(0);
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
ini_set('mx_execution_time',600);
$connection = odbc_connect("Driver={SQL Server};Server=JBS-DB;Database=FinliteX;", "", "");
if (!$connection) {
    echo json_encode(array("Connection Error" => "Error While Communicating With DB"));
}
?>