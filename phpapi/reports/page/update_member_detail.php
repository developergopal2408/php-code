<?php
require_once 'top.php';
$id = $_POST['member_id'];
$cid = $_POST['cid'];
$fname = ucfirst($_POST['first_name']);
$lname = ucfirst($_POST['last_name']);
$ctypeid = $_POST['ctypeid'];
$citino = $_POST['citino'];
$date1 = $_POST['date1'];
$did = $_POST['did'];
$father_name = ucfirst($_POST['father_name']);
$gfather_name = ucfirst($_POST['gfather_name']);
odbc_autocommit(FALSE);
$sql = odbc_exec($connection, "update member set FirstName='$fname',LastName='$lname',"
        . "CitizenShipNo='$citino',cDistrictID='$did',IdTypeID='$ctypeid',cIssueDate='$date1',FatherName = '$father_name',GrandFatherName = '$gfather_name' "
        . " where OfficeID ='" . $_SESSION['BranchID'] . "' and MemberID = '$id'")or die(print_r(odbc_errormsg(), true));
if ($sql) {
    odbc_commit($connection);
    echo "<script type='text/javascript'>alert('Successfully Update Member Detail!');window.location = 'view_member.php?id=$cid';</script>";
} else {
    odbc_rollback($connection);
    echo "<script type='text/javascript'>alert('Error While Updating Member Details!');</script>";
}
?>
