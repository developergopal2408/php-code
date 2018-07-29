<?php
ob_start();
session_start();
require_once '../db.php';
if (!isset($_SESSION['StaffID'])) {
    header('Location:../login.php');
}
include_once 'sidebar_header.php'; //Include Sidebar_header.php-->
include_once 'sidebar.php'; //Include Sidebar.php-->
$sql = "SELECT * FROM OfficeDetail WHERE ID='" . $_SESSION['BranchID'] . "' ";
$res = odbc_exec($connection, $sql);
$row = odbc_fetch_array($res);
$branchName = $row['Name'];
$Code = $row['Code'];
$_SESSION['ID'] = $row['ID'];
//echo $branchName;
include_once 'header.php';
require_once('../js/nepali_calendar.php');
require_once('../js/functions.php');
$cal = new Nepali_Calendar();
list($year, $month, $day) = explode('/', date('Y/m/d'));
$nepdate = $cal->eng_to_nep($year, $month, $day);
$ndate = $cal->eng_to_nep($year, $month, 01);
$sdate = $ndate['year'] . "/" . $ndate['month'] . "/" . '01';
$nyr = $nepdate['year'];
$nmonth = $nepdate['month'];
$nday = $nepdate['date'];
$cdate = $nyr . "/" . $nmonth . "/" . $nday;
//$cdate = "2074/06/24";
?>
<!-- Site wrapper -->
<div class="wrapper">
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                <i class="fa fa-building"></i> <?php echo $branchName; ?>

                <small>Daily Cheque Transaction</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Daily Cheque Transaction</li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-danger">
                        <div class="box-header with-border">

                            <div class="col-sm-12">
                                <!-- search form -->
                                <form  action="" method="post" class="form-horizontal" >
                                    <div class=" form-group-sm">
                                        <div class="col-sm-2">
                                            <input maxlength="10" type="text" name="date1" id="date1" class=" nepali-calendar form-control" placeholder="Select Date" 
                                                   value="<?php
                                                   if (isset($_POST['date1'])) {
                                                       echo $_POST['date1'];
                                                   } else {
                                                       echo $cdate;
                                                   }
                                                   ?>"
                                                   >
                                        </div>
                                        <div class="col-sm-2">
                                            <input maxlength="10" type="text" name="date2" id="date2" class=" nepali-calendar form-control" placeholder="Select Date" 
                                                   value="<?php
                                                   if (isset($_POST['date2'])) {
                                                       echo $_POST['date2'];
                                                   } else {
                                                       echo $cdate;
                                                   }
                                                   ?>"
                                                   >
                                        </div>
                                        <?php
                                        if ($_SESSION['BranchID'] == 1) {
                                            ?>
                                            <div class="col-sm-3">
                                                <select name="id" id="id" class="form-control select2" required>
                                                    <option value="">Select Branch</option>
                                                    <?php
                                                    $sql1 = "SELECT * FROM OfficeDetail ORDER BY ID ASC ";
                                                    $result = odbc_exec($connection, $sql1);
                                                    while ($rows = odbc_fetch_array($result)) {
                                                        ?>
                                                        <option value="<?php echo $rows['ID']; ?>" ><?php echo $rows['Code'] . " - " . $rows['Name']; ?></option>;
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                        <div class="col-sm-2">
                                            <button type="submit" name="search" id="search-btn" class=" btn btn-flat bg-green"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                </form>
                                <!-- /.search form -->
                                <div class="box-tools pull-right" >
                                    <button id="excel" class="btn bg-blue" href="#" onClick ="$('#daybook2').tableExport({type: 'excel', escape: 'false'});">Export To Excel</button>
                                </div>

                            </div>
                        </div>

                        <div class="box-body">
                            <?php
                            if (isset($_POST['search'])) {
                                $query = "SELECT ID,Name FROM OfficeDetail  WHERE  ID = '" . $_POST['id'] . "'";
                                $sub = odbc_exec($connection, $query);
                                $p = odbc_fetch_array($sub);
                                $bname = $p['Name'];
                                if ($_POST['id']) {
                                    echo "<span class='text-bold'>" . $bname . " ( " . $_POST['date1'] . " - " . $_POST['date2'] . " )</span>";
                                } else {
                                    echo "<span class='text-bold'>" . "( " . $_POST['date1'] . " - " . $_POST['date2'] . " )</span>";
                                }
                            } 
                            ?>
                            <table id="daybook2" class="table table-responsive table-bordered table-striped">
                                <thead class="bg-red ">
                                    <tr class="text-sm">
                                        <th>MemberCode</th>
                                        <th>MemberName</th>
                                        <th>SavingType</th>
                                        <th>ChequeNo</th>
                                        <th>SaveDate</th>
                                        <th>WithdrawBalance</th>
                                        <th>WithdrawBy</th>
                                        <th>PaidBy</th>
                                        <th>Signature</th>
                                        <th>ApprovedBy</th>
                                        <th>Signatures</th>
                                        
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $id = $_POST['id'];
                                    $date1 = $_POST['date1'];
                                    $date2 = $_POST['date2'];
                                     if ($_SESSION['BranchID'] == 1 AND $id != "") {
                                        $idx = "and m.officeid='$id' and s.officeid='$id'  and d.officeid='$id'";
                                    } else {
                                        $idx = "and m.officeid = '" . $_SESSION['BranchID'] . "' and s.officeid='" . $_SESSION['BranchID'] . "'  and d.officeid='" . $_SESSION['BranchID'] . "'";
                                    }
                                    if (isset($_POST['search'])) {
                                        $qry = "select m.MemberCode,m.Firstname+' '+m.lastname as MemberName,t.SavingType,
                                                d.ChequeNo,s.SaveDate,(s.Dramount)WithdrawBalance,(d.Remarks)WithdrawBY,
                                                n.firstname+' '+n.Lastname as PaidBy,(' ')Signature,(' ')ApprovedBy,(' ')Signatures
                                                from member m, savingtype t,savingdetail s,staffmain n, chequedetail d
                                                where m.memberid=s.memberid and s.savingtypeid=t.savingtypeid and n.staffid=s.userid and m.memberid=d.memberid
                                                and  d.status='P' and s.chequeno=d.chequeno 
                                                $idx
                                                and s.SaveDate between '$date1' AND '$date2'
                                                order by m.membercode";
                                    } else {
                                        $qry = "select m.MemberCode,m.Firstname+' '+m.lastname as MemberName,t.SavingType,
                                                d.ChequeNo,s.SaveDate,(s.Dramount)WithdrawBalance,(d.Remarks)WithdrawBY,
                                                n.firstname+' '+n.Lastname as PaidBy,(' ')Signature,(' ')ApprovedBy,(' ')Signatures
                                                from member m, savingtype t,savingdetail s,staffmain n, chequedetail d
                                                where m.memberid=s.memberid and s.savingtypeid=t.savingtypeid and n.staffid=s.userid and m.memberid=d.memberid
                                                and  d.status='P' and s.chequeno=d.chequeno 
                                                $idx
                                                and s.SaveDate between '$date1' AND '$date2'
                                                order by m.membercode";
                                    }
                                    $result = odbc_exec($connection, $qry);
                                    while ($res = odbc_fetch_array($result)) {
                                        ?>
                                        <tr class="text-sm">
                                            <td><?php echo $res['MemberCode']; ?></td>
                                            <td><?php echo $res['MemberName']; ?></td>
                                            <td><?php echo $res['SavingType']; ?></td>
                                            <td><?php echo $res['ChequeNo']; ?></td>
                                            <td><?php echo $res['SaveDate']; ?></td>
                                            <td><?php echo $res['WithdrawBalance']; ?></td>
                                            <td><?php echo $res['WithdrawBY']; ?></td>
                                            <td><?php echo $res['PaidBy']; ?></td>
                                            <td><?php echo $res['Signature']; ?></td>
                                            <td><?php echo $res['ApprovedBy']; ?></td>
                                            <td><?php echo $res['Signatures']; ?></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>



                            </table>
                        </div>

                    </div>


                </div>

            </div>


        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->


    <?php
    include_once 'copyright.php';
    ?>

</div>
<!-- ./wrapper -->

<?php
include_once 'footer.php';
?>
