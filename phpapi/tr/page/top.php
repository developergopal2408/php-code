<?php
error_reporting(1);
ini_set('session.gc_maxlifetime', 180);
session_set_cookie_params(180);
ini_set('max_execution_time', 300);
ob_start();
session_start();
require_once '../dba.php';
if (!isset($_SESSION['StaffID'])) {
    header('Location:../login.php');
}
$BranchID = $_SESSION['BranchID'];
require_once('../js/nepali_calendar.php');
require_once('../js/functions.php');
$cal = new Nepali_Calendar();
list($year, $month, $day) = explode('/', date('Y/m/d'));
$nepdate = $cal->eng_to_nep($year, $month, $day);
$ndate = $cal->eng_to_nep($year, $month, $day);
$sdate = $ndate['year'] . "/" . $ndate['month'] . "/" . '01';
$nyr = $nepdate['year'];
$nmonth = $nepdate['month'];
$nday = $nepdate['date'];
$cdate = $nyr . "/" . $nmonth . "/" . $nday;
$sql = "SELECT f.FBsYear FROM FisCal f, OfficeDetail a WHERE a.ID=1 and a.FyearID=f.FyearID";
$res = sqlsrv_query($connection,$sql);
$p = sqlsrv_fetch_array($res);
$fiscal = $p['FBsYear']; 
$fyear=substr($fiscal,0,4);
$fyearn=$fyear."/04/01";
/*----for year and month----*/
//$fym = $fyear."/10";
$fym = $fyear."/".$nmonth;
//print_r($fym);
/*----for year and month----*/
$sql = "SELECT * FROM OfficeDetail WHERE ID='" . $_SESSION['BranchID'] . "' ";
$reso = sqlsrv_query($connection, $sql);
$row = sqlsrv_fetch_array($reso);
$branchName = $row['Name'];

