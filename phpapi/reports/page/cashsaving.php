<?php
ini_set('max_execution_time', 300);
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
//echo date('Y/m/d');
$nepdate = $cal->eng_to_nep($year, $month, $day);
$ndate = $cal->eng_to_nep($year, $month, $day);

//$sdate = $ndate['year'] . "/" . '04' . "/" . '01';
$sdate = "2074/04/01";
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

                <small>Payment Slip</small>
            </h1>


            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>

                <li class="active">Payment Slip</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">

                    <div class="box box-danger">

                        <div class="box-header with-border">
                            <h4 class="text-bold text-red">Payment Slip</h4>
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
                                                <option value="">Select Branch</option>
                                                <?php
                                                $query = "SELECT ID,Name,Code from OfficeDetail";
                                                $sub = odbc_exec($connection, $query);
                                                while ($p = odbc_fetch_array($sub)) {
                                                    ?>
                                                    <option value="<?php echo $p['ID']; ?>" ><?php echo $p['Code'] . " - " . $p['Name']; ?></option>;
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="col-sm-2">
                                            <button type="submit" name="search" id="search-btn" class=" btn btn-flat bg-green"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                </form>
                                <!-- /.search form -->

                                <div class="box-tools pull-right" >
                                    <button id="excel" class="btn bg-blue" href="#" onClick ="$('#trial').tableExport({type: 'excel', escape: 'false'});">Export To Excel</button>
                                </div>

                            </div>
                        </div>

                        <div class="box-body">
                            <div class="box-title with-header text-bold text-center">
                                Office : 
                                <?php
                                if (isset($_POST['id'])) {
                                    $query = "SELECT ID,Name,Code FROM OfficeDetail  WHERE  ID = '" . $_POST['id'] . "'";

                                    $sub = odbc_exec($connection, $query);
                                    $p = odbc_fetch_array($sub);
                                    echo $_POST['date2']." - ".$p['Name'];
                                } else {
                                    echo $branchName;
                                }
                                ?>
                            </div>
                            <table id="trial" class="table table-bordered table-striped" bordered="1"> 
                                <thead class="bg-red" >
                                    <tr>
                                        <th>Member Code</th>
                                        <th>Member NAME</th>
                                        <th>Welfare</th>
                                        <th>Compulsory</th>
                                        <th>Personal</th>
                                        <th>Special</th>
                                        <th>Pension</th>
										<th>PenNominee</th>


                                    </tr>
                                </thead>

                                    <?php
                                    if (empty($_POST)) {
                                        $welfare = 0.0;
                                        $compulsory = 0.0;
                                        $personal = 0.0;
                                        $special = 0.0;
                                        $pension = 0.0;
										$pennomi = 0.0;
                                        $qry = "select m.membercode,m.firstname+' '+m.lastname as MemberName,
(select sum(dramount + intdr) from savingdetail where  contraid=301 and memberid=m.memberid and savingtypeid=1 and savedate='$cdate' and officeid = m.officeid and particulars <> 'Installment Deducted For Loan')welfare,
(select sum(dramount + intdr) from savingdetail where  contraid=301 and memberid=m.memberid and savingtypeid=2  and savedate='$cdate' and officeid = m.officeid and particulars <> 'Installment Deducted For Loan')Compulsory,
(select sum(dramount + intdr) from savingdetail where  contraid=301 and memberid=m.memberid and savingtypeid=3 and chequeno=0 and savedate='$cdate' and officeid = m.officeid and particulars <> 'Installment Deducted For Loan') Personal,
(select sum(dramount + intdr) from savingdetail where  contraid=301 and memberid=m.memberid and savingtypeid=4 and chequeno=0 and savedate='$cdate' and officeid = m.officeid and particulars <> 'Installment Deducted For Loan') Special,
(select sum(dramount) from savingdetail where  contraid=301 and memberid=m.memberid and savingtypeid=5 and savedate='$cdate' and officeid = m.officeid and particulars <> 'Installment Deducted For Loan')Pension,
(select sum(intdr) from savingdetail where  contraid=301 and memberid=m.memberid and savingtypeid=5 and savedate='$cdate' and officeid = m.officeid and particulars <> 'Installment Deducted For Loan')PenNomi

from member m,savingdetail s
where m.officeid='" . $_SESSION['ID'] . "' and m.officeid = s.officeid and m.memberid = s.memberid and s.particulars <> 'Installment Deducted For Loan'
group by m.membercode,m.firstname, m.lastname,m.memberid,m.officeid
order by m.membercode";
                                        $result = odbc_exec($connection, $qry);
										?>
										<tbody>
										<?php
                                        if (odbc_num_rows($result) > 0) {
                                            while ($res = odbc_fetch_array($result)) {
                                                if (!empty($res['welfare'] AND $res['welfare'] > 0) or ! empty($res['Compulsory'] AND $res['Compulsory'] > 0) or ! empty($res['Personal'] AND $res['Personal'] > 0) or ! empty($res['Special'] AND $res['Special'] > 0) or ! empty($res['Pension'] AND $res['Pension'] > 0)) {
                                                    $welfare += $res['welfare'];
                                                    $compulsory += $res['Compulsory'];
                                                    $personal += $res['Personal'];
                                                    $special += $res['Special'];
                                                    $pension += $res['Pension'];
													$pennomi += $res['PenNomi'];
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $res['membercode']; ?></td>
                                                        <td><?php echo $res['MemberName']; ?></td>
                                                        <td><?php echo number_format($res['welfare'],2); ?></td>
                                                        <td><?php echo number_format($res['Compulsory'],2); ?></td>
                                                        <td><?php echo number_format($res['Personal'],2); ?></td>
                                                        <td><?php echo number_format($res['Special'],2); ?></td>
                                                        <td><?php echo number_format($res['Pension'],2); ?></td>
														<td><?php echo number_format($res['PenNomi'],2); ?></td>

                                                    </tr>
                                                    <?php
                                                }
                                            }
                                            ?>
											</tbody>
											<tfoot class="bg-red text-bold">
                                            <tr>
                                                <td colspan=2>Total : </td>
                                                <td><?php echo number_format($welfare,2); ?></td>
                                                <td><?php echo number_format($compulsory,2); ?></td>
                                                <td><?php echo number_format($personal,2); ?></td>
                                                <td><?php echo number_format($special,2); ?></td>
                                                <td><?php echo number_format($pension,2); ?></td>
												<td><?php echo number_format($pennomi,2); ?></td>


                                            </tr>
											</tfoot>
                                            <?php
                                        }
                                    } else if (isset($_POST['search'])) {
                                        $welfare = 0.0;
                                        $compulsory = 0.0;
                                        $personal = 0.0;
                                        $special = 0.0;
                                        $pension = 0.0;
										$pennomi = 0.0;
                                        $ID = $_POST['id'];
                                        $date2 = $_POST['date2'];
                                        $qry = "select m.membercode,m.firstname+' '+m.lastname as MemberName,
(select sum(dramount + intdr) from savingdetail where  contraid=301 and memberid=m.memberid and savingtypeid=1 and savedate='$date2' and officeid = m.officeid and particulars <> 'Installment Deducted For Loan')welfare,
(select sum(dramount + intdr) from savingdetail where  contraid=301 and memberid=m.memberid and savingtypeid=2 and savedate='$date2' and officeid = m.officeid and particulars <> 'Installment Deducted For Loan')Compulsory,
(select sum(dramount + intdr) from savingdetail where  contraid=301 and memberid=m.memberid and savingtypeid=3 and chequeno=0 and savedate='$date2' and officeid = m.officeid and particulars <> 'Installment Deducted For Loan') Personal,
(select sum(dramount + intdr) from savingdetail where  contraid=301 and memberid=m.memberid and savingtypeid=4 and chequeno=0 and savedate='$date2' and officeid = m.officeid and particulars <> 'Installment Deducted For Loan') Special,
(select sum(dramount) from savingdetail where  contraid=301 and memberid=m.memberid and savingtypeid=5 and savedate='$date2' and officeid = m.officeid and particulars <> 'Installment Deducted For Loan')Pension,
(select sum(intdr) from savingdetail where  contraid=301 and memberid=m.memberid and savingtypeid=5 and savedate='$date2' and officeid = m.officeid and particulars <> 'Installment Deducted For Loan')PenNomi

from member m,savingdetail s
where m.officeid='$ID' and m.officeid = s.officeid and m.memberid = s.memberid and s.particulars <> 'Installment Deducted For Loan'
group by m.membercode,m.firstname, m.lastname,m.memberid,m.officeid
order by m.membercode";
                                        $result = odbc_exec($connection, $qry);
										?>
										<tbody>
										<?php
                                        while ($res = odbc_fetch_array($result)) {
                                            if (!empty($res['welfare'] AND $res['welfare'] > 0) or ! empty($res['Compulsory'] AND $res['Compulsory'] > 0) or ! empty($res['Personal'] AND $res['Personal'] > 0) or ! empty($res['Special'] AND $res['Special'] > 0) or ! empty($res['Pension'] AND $res['Pension'] > 0)) {
                                                $welfare += $res['welfare'];
                                                $compulsory += $res['Compulsory'];
                                                $personal += $res['Personal'];
                                                $special += $res['Special'];
                                                $pension += $res['Pension'];
												$pennomi += $res['PenNomi'];
                                                ?>
                                                <tr>
                                                    <td><?php echo $res['membercode']; ?></td>
                                                    <td><?php echo $res['MemberName']; ?></td>
                                                    <td><?php echo number_format($res['welfare'],2); ?></td>
                                                    <td><?php echo number_format($res['Compulsory'],2); ?></td>
                                                    <td><?php echo number_format($res['Personal'],2); ?></td>
                                                    <td><?php echo number_format($res['Special'],2); ?></td>
                                                    <td><?php echo number_format($res['Pension'],2); ?></td>
													<td><?php echo number_format($res['PenNomi'],2); ?></td>
                                                </tr>																				
                                                <?php
                                            }
                                        }
                                        ?>
										</tbody>
										<tfoot class="bg-red">
                                        <tr>
                                            <td colspan=2>Total : </td>
                                            <td><?php echo number_format($welfare,2); ?></td>
                                            <td><?php echo number_format($compulsory,2); ?></td>
                                            <td><?php echo number_format($personal,2); ?></td>
                                            <td><?php echo number_format($special,2); ?></td>
                                            <td><?php echo number_format($pension,2); ?></td>
											<td><?php echo number_format($pennomi,2); ?></td>
                                        </tr>
										</tfoot>
                                        <?php
                                    }
                                    ?>

                                
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
	

