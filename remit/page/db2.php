<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
ini_set('mx_execution_time', 300);
$conn = mysqli_connect("localhost", "root", "", "remittance") or die("Error " . mysqli_error($con));
if (!$conn) {
    echo("Error Connecting to Database");
    exit;
}
