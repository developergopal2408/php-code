<?php

error_reporting(0);
session_start();
include_once 'connect.php';

$name = $_SESSION['usr_name'];
$role = $_SESSION['role'];


$sql = "SELECT * FROM remittance  WHERE STATUS = 'PENDING'  ";

$result = mysqli_query($con, $sql);
$count = mysqli_num_rows($result);
/* if(!empty($count)) {
  print $count;
  } */

while ($row = mysqli_fetch_array($result)) {
    $branch = $row['BRANCHNAME'];
    
        if($role == 'Headquarter'){
    print "नया मेस्सेज आको छ कृपया चेक गर्नुहोस <a href='remittanceDetail.php'> $branch</a> बाट<br/> ";
    }
    
    
    
}
?>