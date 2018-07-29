<?php
ob_start();
session_start();
require_once '../db.php';
if (!isset($_SESSION['StaffID'])) {
    header('Location:../login.php');
}
$sql = "SELECT * FROM OfficeDetail WHERE ID='" . $_SESSION['BranchID'] . "' ";
$res = odbc_exec($connection, $sql);
$row = odbc_fetch_array($res);
$branchName = $row['Name'];
$Code = $row['Code'];
$_SESSION['ID'] = $row['ID'];
include_once 'header.php';
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
?>
<!-- Site wrapper -->
<div class="wrapper">
    <?php
    include_once 'sidebar_header.php'; //Include Sidebar_header.php-->
    include_once 'sidebar.php'; //Include Sidebar.php-->
    ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                <i class="fa fa-building"></i> <?php echo $branchName; ?>

                <small>Ledger With Compile</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Ledger With Compile </li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-danger">
                        <div class="box-header with-border">
                            <h4 class="text-bold text-red">Ledger With Compile</h4>
                            <div class="col-sm-12">
                                <!-- search form -->
                                <form  action="" method="post" class="form-horizontal" >
                                    <div class=" form-group-sm">
                                        <div class="col-sm-2">
                                            <input maxlength="10" type="text" name="date2" id="date2" class=" nepali-calendar form-control" placeholder="Select Date" value="<?php
                                            if (isset($_POST['date2'])) {
                                                echo $_POST['date2'];
                                            } else {
                                                echo $cdate;
                                            }
                                            ?>">
                                        </div>
                                        <div class="col-sm-3">
                                            <select name="id" id="id" class="form-control select2" required>
                                                <option value="">Select Ledger With Compile</option>
                                                <option value="loan" >Loan</option> 
                                                <option value="saving" >Saving</option>                                                   
                                            </select>
                                        </div>

                                        <div class="col-sm-2">
                                            <button type="submit" name="search" id="search-btn" class=" btn btn-flat bg-green"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                </form>
                                <!-- /.search form -->

                                <div class="box-tools pull-right" >
                                    <button id="excel" class="btn bg-blue" href="#" onClick ="$('#ledger').tableExport({type: 'excel', escape: 'false'});">Export To Excel</button>
                                </div>

                            </div>
                        </div>

                        <div class="box-body">
                            <div class="box-title with-header text-bold text-center">
                                <?php
                                if (isset($_POST['id'])) {
                                    echo  $_POST['date2'] . " - " . $_POST['id'];
                                } 
                                ?>
                            </div>
                            <table id="ledger" class="table table-responsive table-bordered table-striped" >
                                <?php
                                if (isset($_POST['search'])) {
                                    ?>
                                    <thead class="bg-red">
                                        <tr>
                                            <th>ID</th>
											<?php 
											if($_POST['id'] == 'saving'){
                                            echo "<th>SavingType</th><th>Balance</th>";
											}
											else if($_POST['id'] == 'loan'){
                                            echo "<th>LoanType</th><th>Balance</th>";
											}
											?>
                                        </tr>
                                    </thead>
                                    <?php
                                }
                                ?>
                                <tbody>
                                    <?php
                                    if (isset($_POST['search'])) {
                                        $ID = $_POST['id'];
                                        $date2 = $_POST['date2'];
                                        if ($ID === 'loan') {
                                            /*$qry = "select o.code,o.name,
                                                    (select sum(loandr-loancr) from loandetail where officeid=o.id and loantypeid=1 and savedate<='$date2')General,
                                                    (select sum(loandr-loancr) from loandetail where officeid=o.id and loantypeid=2 and savedate<='$date2')Emergency,
                                                    (select sum(loandr-loancr) from loandetail where officeid=o.id and loantypeid=3 and savedate<='$date2')Housing,
                                                    (select sum(loandr-loancr) from loandetail where officeid=o.id and loantypeid=7 and savedate<='$date2')DSE,
                                                    (select sum(loandr-loancr) from loandetail where officeid=o.id and loantypeid=9 and savedate<='$date2')Education,
                                                    (select sum(loandr-loancr) from loandetail where officeid=o.id and loantypeid=10 and savedate<='$date2')Agricultur
                                                    from officedetail o , loandetail l
                                                    where  o.id = l.officeid and o.id = '" . $_SESSION['ID'] . "' 
                                                    group by o.code,o.name,o.id order by o.code";*/
													
											$qry = "select t.loantypeid,t.loantype,sum(s.loandr-s.loancr)balance
													from loantype t,loandetail s
													where t.loantypeid=s.loantypeid and savedate<='$date2' and s.officeid= '" . $_SESSION['ID'] . "' 
													group by t.loantypeid,t.loantype
													order by t.loantypeid";
                                        } else if($ID === 'saving'){
                                            /*$qry = "select o.code,o.name,
                                                    (select sum(cramount-dramount) from savingdetail where officeid=o.id and savingtypeid=1 and savedate<='$date2')Welfare,
                                                    (select sum(cramount-dramount) from savingdetail where officeid=o.id and savingtypeid=2 and savedate<='$date2')Compulsory,
                                                    (select sum(cramount-dramount) from savingdetail where officeid=o.id and savingtypeid=3 and savedate<='$date2')Personal,
                                                    (select sum(cramount-dramount) from savingdetail where officeid=o.id and savingtypeid=4 and savedate<='$date2')Special,
                                                    (select sum(cramount-dramount) from savingdetail where officeid=o.id and savingtypeid=5 and savedate<='$date2')Pension
                                                    from officedetail o ,savingdetail s
                                                    where o.id = s.officeid  and o.id = '" . $_SESSION['ID'] . "' 
                                                    group by o.code,o.name,o.id order by o.code";*/
											$qry = "select t.savingtypeid,t.savingtype,sum(s.cramount-s.dramount)bal
													from savingtype t,savingdetail s
													where t.savingtypeid=s.savingtypeid and savedate<='$date2' and s.officeid='" . $_SESSION['ID'] . "'
													group by t.savingtypeid,t.savingtype
													order by t.savingtypeid";
                                        }/*else{
											$qry = "select o.code,o.name,
													(select sum(cramount-dramount) from savingdetail where officeid=o.id and savingtypeid=1 and savedate<='$date2')Welfare,
													(select sum(cramount-dramount) from savingdetail where officeid=o.id and savingtypeid=2 and savedate<='$date2')Compulsory,
													(select sum(cramount-dramount) from savingdetail where officeid=o.id and savingtypeid=3 and savedate<='$date2')Personal,
													(select sum(cramount-dramount) from savingdetail where officeid=o.id and savingtypeid=4 and savedate<='$date2')Special,
													(select sum(cramount-dramount) from savingdetail where officeid=o.id and savingtypeid=5 and savedate<='$date2')Pension,
													(select sum(loandr-loancr) from loandetail where officeid=o.id and loantypeid=1 and savedate<='$date2')General,
													(select sum(loandr-loancr) from loandetail where officeid=o.id and loantypeid=2 and savedate<='$date2')Emergency,
													(select sum(loandr-loancr) from loandetail where officeid=o.id and loantypeid=3 and savedate<='$date2')Housing,
													(select sum(loandr-loancr) from loandetail where officeid=o.id and loantypeid=7 and savedate<='$date2')DSE,
													(select sum(loandr-loancr) from loandetail where officeid=o.id and loantypeid=9 and savedate<='$date2')Education,
													(select sum(loandr-loancr) from loandetail where officeid=o.id and loantypeid=10 and savedate<='$date2')Agricultur
													from officedetail o ,savingdetail s, loandetail l
													where o.id = s.officeid and o.id = l.officeid and o.id = '".$_SESSION['ID']."' group by o.code,o.name,o.id order by o.code";
										}*/
                                        $result = odbc_exec($connection, $qry);
                                        while ($res = odbc_fetch_array($result)) {
                                            ?>
                                            <tr>
                                                <td><?php echo ++$counter; ?></td>
													<?php
													if($ID == 'saving'){
													?>
                                                    <td><?php echo $res['savingtype']; ?></td>
                                                    <td><?php echo number_format($res['bal'], 2); ?></td>
													<?php
													}else if($ID == 'loan'){
													?>
                                                    <td><?php echo $res['loantype']; ?></td>
                                                    <td><?php echo number_format($res['balance'], 2); ?></td>
													<?php
														}
													?>
                                             </tr>																				
                                            <?php
                                        }
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
	

