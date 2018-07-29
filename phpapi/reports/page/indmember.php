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

                <small>Individual Member</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Individual Member</li>
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

                                        <?php
                                        if ($_SESSION['BranchID'] == 1) {
                                            ?>
                                            <div class="col-sm-3">
                                                <select name="id" id="id" class="form-control select2" >
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
                            $totalwelfare = $totalcomp = $totalpersonal = $totalspecial = $totalpension = $totalenergy = 0;
                            $totalgeneral = $totaleme = $totalhousing = $totaldse = $totaledu = $totalagi = 0;

                            if (isset($_POST['search'])) {
                                $query = "SELECT ID,Name FROM OfficeDetail  WHERE  ID = '" . $_POST['id'] . "'";
                                $sub = odbc_exec($connection, $query);
                                $p = odbc_fetch_array($sub);
                                $bname = $p['Name'];
                                if ($_POST['id']) {
                                    echo "<span class='text-bold'>" . $bname . " ( " . $_POST['date1'] . " )</span>";
                                } else {
                                    echo "<span class='text-bold'>" . "( " . $_POST['date1'] . " )</span>";
                                }
                            }
                            ?>
                            <table id="daybook2" class="table table-responsive table-bordered table-striped">
                                <thead class="bg-red ">
                                    <tr class="text-sm">
                                        <th>MemberID</th>
                                        <th>MemberCode</th>
                                        <th>MemberName</th>
                                        <th>Welfare</th>
                                        <th>Compulsory</th>
                                        <th>Personal</th>
                                        <th>Special</th>
                                        <th>Pension</th>
                                        <th>General</th>
                                        <th>Emergency</th>
                                        <th>Housing</th>
										<th>Energy</th>
                                        <th>DSE</th>
                                        <th>Education</th>
										<th>AgiLoan</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $id = $_POST['id'];
                                    $date1 = $_POST['date1'];
                                    if ($_SESSION['BranchID'] == 1 AND $id != "") {
                                        $idx = "and officeid='$id'";
                                        $idt = " m.officeid='$id'";
                                    } else {
                                        $idx = "and officeid = '" . $_SESSION['BranchID'] . "'";
                                        $idt = "m.officeid='" . $_SESSION['BranchID'] . "'";
                                    }
                                    if (isset($_POST['search'])) {
                                        $qry = "select m.memberid,m.MemberCode,m.firstname+' '+m.lastname as MemberName,
(select sum(cramount-dramount) as bal from savingdetail where savedate<='$date1'
and savingtypeid=1 and memberid=m.memberid $idx)welfare,
(select sum(cramount-dramount) as bal from savingdetail where savedate<='$date1'
and savingtypeid=2 and memberid=m.memberid $idx)Compulsory,
(select sum(cramount-dramount) as bal from savingdetail where savedate<='$date1'
and savingtypeid=3 and memberid=m.memberid $idx)Personal,
(select sum(cramount-dramount) as bal from savingdetail where savedate<='$date1'
and savingtypeid=4 and memberid=m.memberid $idx)special,
(select sum(cramount-dramount) as bal from savingdetail where savedate<='$date1'
and savingtypeid=5 and memberid=m.memberid $idx)Pension,
(select sum(loandr-loancr) as bal from loandetail where savedate<='$date1'
 and loantypeid=1 and memberid=m.memberid $idx)General,
(select sum(loandr-loancr) as bal from loandetail where savedate<='$date1'
and loantypeid=2 and memberid=m.memberid $idx)Emergency,
(select sum(loandr-loancr) as bal from loandetail where savedate<='$date1'
and loantypeid=3 and memberid=m.memberid $idx)Housing,
(select sum(loandr-loancr) as bal from loandetail where savedate<='$date1'
and loantypeid=4 and memberid=m.memberid $idx)Energy,
(select sum(loandr-loancr) as bal from loandetail where savedate<='$date1'
 and loantypeid=7 and memberid=m.memberid $idx)DSE,
(select sum(loandr-loancr) as bal from loandetail where savedate<='$date1'
 and loantypeid=9 and memberid=m.memberid $idx)Education,
 (select sum(loandr-loancr) as bal from loandetail where savedate<='$date1'
 and loantypeid=10 and memberid=m.memberid $idx)Agi
 from member m
where  $idt 
Group by m.memberid,m.membercode,m.firstname,m.lastname,m.memberid
order by m.membercode";
                                    } else {
                                        $qry = "select m.memberid,m.MemberCode,
                                            m.firstname+' '+m.lastname as MemberName,
(select sum(cramount-dramount) as bal from savingdetail where savedate<='$cdate'
and savingtypeid=1 and memberid=m.memberid $idx)welfare,
(select sum(cramount-dramount) as bal from savingdetail where savedate<='$cdate'
and savingtypeid=2 and memberid=m.memberid $idx)Compulsory,
(select sum(cramount-dramount) as bal from savingdetail where savedate<='$cdate'
and savingtypeid=3 and memberid=m.memberid $idx)Personal,
(select sum(cramount-dramount) as bal from savingdetail where savedate<='$cdate'
and savingtypeid=4 and memberid=m.memberid $idx)special,
(select sum(cramount-dramount) as bal from savingdetail where savedate<='$cdate'
and savingtypeid=5 and memberid=m.memberid $idx)Pension,
(select sum(loandr-loancr) as bal from loandetail where savedate<='$cdate'
 and loantypeid=1 and memberid=m.memberid $idx)General,
(select sum(loandr-loancr) as bal from loandetail where savedate<='$cdate'
and loantypeid=2 and memberid=m.memberid $idx)Emergency,
(select sum(loandr-loancr) as bal from loandetail where savedate<='$cdate'
and loantypeid=3 and memberid=m.memberid $idx)Housing,
(select sum(loandr-loancr) as bal from loandetail where savedate<='$cdate'
and loantypeid=4 and memberid=m.memberid $idx)Energy,
(select sum(loandr-loancr) as bal from loandetail where savedate<='$cdate'
 and loantypeid=7 and memberid=m.memberid $idx)DSE,
(select sum(loandr-loancr) as bal from loandetail where savedate<='$cdate'
 and loantypeid=9 and memberid=m.memberid $idx)Education,
 (select sum(loandr-loancr) as bal from loandetail where savedate<='$cdate'
 and loantypeid=10 and memberid=m.memberid $idx)Agi
 from member m
where  $idt 
Group by m.memberid,m.membercode,m.firstname,m.lastname,m.memberid
order by m.membercode";
                                    }
                                    $result = odbc_exec($connection, $qry);
                                    while ($res = odbc_fetch_array($result)) {
                                        $totalwelfare     += $res['welfare'];
                                       $totalcomp   += $res['Compulsory'];
                                        $totalpersonal   += $res['Personal'];
                                        $totalspecial   += $res['special'];
                                        $totalpension   += $res['Pension'];
                                        $totalgeneral   += $res['General'];
                                        $totaleme   += $res['Emergency'];
                                        $totalhousing   += $res['Housing'];
										$totalenergy   += $res['Energy'];
                                        $totaldse   += $res['DSE'];
                                        $totaledu   += $res['Education'];
										$totalagi   += $res['Agi'];
                                        
                                        ?>
                                        <tr class="text-sm">
                                            <td><?php echo $res['memberid']; ?></td>
                                            <td><?php echo $res['MemberCode']; ?></td>
                                            <td><?php echo $res['MemberName']; ?></td>
                                            <td><?php echo $res['welfare']; ?></td>
                                            <td><?php echo $res['Compulsory']; ?></td>
                                            <td><?php echo $res['Personal']; ?></td>
                                            <td><?php echo $res['special']; ?></td>
                                            <td><?php echo $res['Pension']; ?></td>
                                            <td><?php echo $res['General']; ?></td>
                                            <td><?php echo $res['Emergency']; ?></td>
                                            <td><?php echo $res['Housing']; ?></td>
											<td><?php echo $res['Energy']; ?></td>
                                            <td><?php echo $res['DSE']; ?></td>
                                            <td><?php echo $res['Education']; ?></td>
											<td><?php echo $res['Agi']; ?></td>

                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                                <tfoot class="text-sm bg-red">
                                    <tr>
                                        <td colspan="3" >Total</td>
                                        <td><?php echo $totalwelfare;?></td>
                                        <td><?php echo $totalcomp;?></td>
                                        <td><?php echo $totalpersonal;?></td>
                                        <td><?php echo $totalspecial;?></td>
                                        <td><?php echo $totalpension;?></td>
                                        <td><?php echo $totalgeneral;?></td>
                                        <td><?php echo $totaleme;?></td>
                                        <td><?php echo $totalhousing;?></td>
										<td><?php echo $totalenergy;?></td>
                                        <td><?php echo $totaldse;?></td>
                                        <td><?php echo $totaledu;?></td>
										<td><?php echo $totalagi;?></td>
                                    </tr>
                                </tfoot>


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
