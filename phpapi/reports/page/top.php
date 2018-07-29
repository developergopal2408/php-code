<?php
error_reporting(1);
ini_set('session.gc_maxlifetime', 180);
session_set_cookie_params(180);
ini_set('max_execution_time', 300);
ob_start();
session_start();
require_once '../db.php';
if (!isset($_SESSION['StaffID'])) {
    header('Location:../login.php');
}
$BranchID = $_SESSION['BranchID'];
$StaffID = $_SESSION['StaffID'];
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
$res = odbc_exec($connection,$sql);
$p = odbc_fetch_array($res);
$fiscal = $p['FBsYear']; 
$fyear=substr($fiscal,0,4);
$fnext = substr($fiscal, 5,9);
$fis = str_replace("/", "-", $fiscal);
$fyearn=$fyear."/04/01";
$f = $fnext."/04/01";
//print_r($fyearn);
/*----for year and month----*/
//$fym = $fyear."/10";
$fym = $fyear."/".$nmonth;
//print_r($fym);
/*----for year and month----*/
$sql = "SELECT * FROM OfficeDetail WHERE ID='" . $_SESSION['BranchID'] . "' ";
$reso = odbc_exec($connection, $sql);
$row = odbc_fetch_array($reso);
$branchName = $row['Name'];
$branchcode = $row['Code'];

