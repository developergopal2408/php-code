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
//echo $branchName;
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
//$cdate = "2074/06/24";
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
                <small>Loan Analysis Detail</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Loan Analysis Detail</li>
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
                                                       echo $sdate;
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
                            <table id="daybook2" class="table table-responsive table-bordered table-striped" >
                                <thead class="bg-red text-sm" style="font-size:10px;">
                                    <tr  >
                                        <th>MemID</th>
                                        <th>MCode</th>
                                        <th>MName</th>
                                        <th>LoanType</th>
                                        <th>AnalysedLoan</th>
                                        <th>AnalysedDate</th>
                                        <th>HouseNo</th>
                                        <th>HouseValue</th>
                                        <th>LandArea</th>
                                        <th>LandValue</th>
                                        
                                        <th>LiveStockNo</th>
                                        <th>LiveStockValue</th>
                                        <th>FarmingAmt</th>
                                        <th>BusinessAmt</th>
                                        <th>CashAmt</th>
                                        <th>JwelleryAmt</th>
                                        <th>OtherAmt</th>
                                        <th>IBusiness</th>
                                        <th>Ijob</th>
                                        <th>Iwages</th>
                                        
                                        <th>Ifarming</th>
                                        <th>IOther</th>
                                        <th>EBusiness</th>
                                        <th>Ehealth</th>
                                        <th>Efood</th>
                                        <th>Erepair</th>
                                        <th>Eclothes</th>
                                        <th>Ecommunication</th>
                                        <th>EEducation</th>
                                        <th>EloanPayment</th>
                                        
                                        <th>Erent</th>
                                        <th>ETravel</th>
                                        <th>EwaterElec</th>
                                        <th>Lorg1Loan</th>
                                        <th>Lorg2Loan</th>
                                        <th>Lorg3Loan</th>
                                        <th>LorgotherLoan</th>
                                        <th>Curr_saving</th>
                                        <th>Curr_loan</th>
                                        <th>Net_cash</th>
                                        
                                        <th>Net_worth</th>
										<th>Analysis By</th>
										<th>Approved By</th>

                                    </tr>
                                </thead>

                                <tbody class="text-sm" style="font-size:10px;">
                                    <?php
                                    $id = $_POST['id'];
                                    $date1 = $_POST['date1'];
                                    $date2 = $_POST['date2'];
                                    if ($_SESSION['BranchID'] == 1 AND $id != "") {
                                        $idw = "and officeid = '$id'";
                                        $idx = "and m.officeid = '$id'";
                                    } else {
                                        $idw = "and officeid = '" . $_SESSION['BranchID'] . "'";
                                        $idx = "and m.officeid = '" . $_SESSION['BranchID'] . "'";
                                    }
                                    if (isset($_POST['search'])) {
                                        $qry = "select m.MemberId, m.membercode,m.Firstname+' '+m.LastName as MemberName,
										(select  code from staffmain where staffid=a.userid)userid,(select  code from staffmain where staffid=a.approvedby)approvedby,
(select loantype from loantype where loantypeid=a.loantypeid)Loantype,
a.AnalyzedLoan,a.AnalyzedDate,a.HouseNo,a.HouseValue,a.LandArea,a.LandValue,
a.LiveStockNo,a.LivestockValue,a.FarmingAmount,a.BusinessAmount,a.CashAmount,
a.JewelryAmount,a.OtherAmount,a.IBusiness,a.Ijob,a.IWages,a.IFarming,a.Iother,
a.Ebusiness,a.EHealth,a.EFood,a.ERepair,a.EClothes,a.ECommunication,a.EEducation,
a.ELoanPayment,a.Erent,a.Etravel,a.EWaterElec,a.Lorg1Loan,a.Lorg2Loan,a.Lorg3Loan,a.LorgOtherLoan,
(select sum(cramount-dramount) from savingdetail where memberid=m.memberid $idw)Curr_saving,
(select sum(loandr-loancr) from loandetail where memberid=m.memberid $idw)Curr_Loan,
a.Netcash,a.Networth
from member m, analysisloan a
where m.memberid=a.memberid and a.officeid=m.officeid and  analyzeddate between '$date1' and '$date2'
$idx order by analyzeddate,m.MemberId";
                                    }
                                    $result = odbc_exec($connection, $qry);
                                    while ($res = odbc_fetch_array($result)) {
                                        ?>
                                        <tr class="text-sm">
                                            <td><?php echo $res['MemberId']; ?></td>
                                            <td><?php echo $res['membercode']; ?></td>
                                            <td><?php echo $res['MemberName']; ?></td>
                                            <td><?php echo $res['Loantype']; ?></td>
                                            <td><?php echo $res['AnalyzedLoan']; ?></td>
                                            <td><?php echo $res['AnalyzedDate']; ?></td>
                                            <td><?php echo $res['HouseNo']; ?></td>
                                            <td><?php echo $res['HouseValue']; ?></td>
                                            <td><?php echo $res['LandArea']; ?></td>
                                            <td><?php echo $res['LandValue']; ?></td>
                                            
                                            <td><?php echo $res['LiveStockNo']; ?></td>
                                            <td><?php echo $res['LivestockValue']; ?></td>
                                            <td><?php echo $res['FarmingAmount']; ?></td>
                                            <td><?php echo $res['BusinessAmount']; ?></td>
                                            <td><?php echo $res['CashAmount']; ?></td>
                                            <td><?php echo $res['JewelryAmount']; ?></td>
                                            <td><?php echo $res['OtherAmount']; ?></td>
                                            <td><?php echo $res['IBusiness']; ?></td>
                                            <td><?php echo $res['Ijob']; ?></td>
                                            <td><?php echo $res['IWages']; ?></td>
                                            
                                            <td><?php echo $res['IFarming']; ?></td>
                                            <td><?php echo $res['Iother']; ?></td>
                                            <td><?php echo $res['Ebusiness']; ?></td>
                                            <td><?php echo $res['EHealth']; ?></td>
                                            <td><?php echo $res['EFood']; ?></td>
                                            <td><?php echo $res['ERepair']; ?></td>
                                            <td><?php echo $res['EClothes']; ?></td>
                                            <td><?php echo $res['ECommunication']; ?></td>
                                            <td><?php echo $res['EEducation']; ?></td>
                                            <td><?php echo $res['ELoanPayment']; ?></td>
                                            
                                            <td><?php echo $res['Erent']; ?></td>
                                            <td><?php echo $res['Etravel']; ?></td>
                                            <td><?php echo $res['EWaterElec']; ?></td>
                                            <td><?php echo $res['Lorg1Loan']; ?></td>
                                            <td><?php echo $res['Lorg2Loan']; ?></td>
                                            <td><?php echo $res['Lorg3Loan']; ?></td>
                                            <td><?php echo $res['LorgOtherLoan']; ?></td>
                                            <td><?php echo $res['Curr_saving']; ?></td>
                                            <td><?php echo $res['Curr_Loan']; ?></td>
                                            <td><?php echo $res['Netcash']; ?></td>
                                            
                                            <td><?php echo $res['Networth']; ?></td>
											<td><?php echo $res['userid']; ?></td>
											<td><?php echo $res['approvedby']; ?></td>
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
