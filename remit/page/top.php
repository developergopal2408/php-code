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
$branchid = $_SESSION['BranchID'];
require_once('../js/nepali_calendar.php');
require_once('../js/functions.php');
$cal = new Nepali_Calendar();
list($year, $month, $day) = explode('-', date('Y-m-d'));
$nepdate = $cal->eng_to_nep($year, $month, $day);
$ndate = $cal->eng_to_nep($year, $month, 01);
$sdate = $ndate['year'] . "-" . $ndate['month'] . "-" . '01';
$nyr = $nepdate['year'];
$nmonth = $nepdate['month'];
$nday = $nepdate['date'];
$cdate = $nyr . "-" . $nmonth . "-" . $nday;
