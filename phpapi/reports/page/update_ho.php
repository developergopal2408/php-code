<?php

include_once 'top.php';
$con = mysqli_connect("localhost", "root", "", "file_management");
$fid = $_GET['id'];
$sol = mysqli_query($con, "select * from document where Id = '$fid'");
$row = mysqli_fetch_array($sol);
$dtype = $row['Document_Type'];
if ($dtype == 'Circular') {
    $query = mysqli_query($con, "UPDATE document SET Update_Ho = '1' WHERE Id = '$fid'");
    header("Location:circular_list.php");
} else {
    $query = mysqli_query($con, "UPDATE document SET Update_Ho = '1' WHERE Id = '$fid'");
    header("Location:view_uploaded_files.php?dtype=$dtype");
}
?>


