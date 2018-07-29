<?php
ob_start();
session_start();
//require_once '../db.php';
$connection = odbc_connect("Driver={SQL Server};Server=JBS-SERVER\JBS;Database=FinliteXV2;", "", "");
if (!isset($_SESSION['StaffID'])) {
    header('Location:../login.php');
}
   
    include_once 'sidebar_header.php'; //Include Sidebar_header.php-->
    include_once 'sidebar.php'; //Include Sidebar.php-->
	
	$sql = "SELECT * FROM OfficeDetail WHERE ID='".$_SESSION['BranchID']."' ";
    $res = odbc_exec($connection,$sql);
	
	$row = odbc_fetch_array($res);
	$branchName = $row['Name'];
	
	$Code = $row['Code'];
$_SESSION['ID'] = $row['ID'];
//echo $branchName;
include_once 'header.php';

$d = date_create();
$startdate = date_create($d->format('Y-m-1'))->format('Y-m-d');

/* $lastdate = date('Y/m/d', strtotime('last day of previous month')) . "<br/>";
  $cfirstday = date('Y/m/d', strtotime("first day of this month")) . "<br/>";
  $clastday = date('Y/m/d', strtotime("last day of this month")); */

require_once('../js/nepali_calendar.php');
require_once('../js/functions.php');
$cal = new Nepali_Calendar();
list($year, $month, $day) = explode('/', date('Y/m/d'));

$nepdate = $cal->eng_to_nep($year, $month, $day);
$ndate = $cal->eng_to_nep($year, $month, 01);
$sdate = $ndate['year']."/".$ndate['month']."/".'01';

$nyr = $nepdate['year'];
$nmonth = $nepdate['month'];
$nday = $nepdate['date'];

$cdate = $nyr . "/" . $nmonth . "/" . $nday;
//$cdate = "2074/06/24";
?>
<style>

.headcol{
	position:absolute;
	border-top-width:8px; /*only relevant for first row*/
    margin-top:-1px; /*compensate for top border*/
	background:grey;
	color:#FFF;
	text-align:center;
	width:51px;
	

}
.headcol:before {content: '';}

</style>

<!-- Site wrapper -->
<div class="wrapper">
   <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
               <i class="fa fa-building"></i> <?php echo $branchName;?>
				
                <small>DayBook</small>
            </h1>
			
			
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>

                <li class="active">DayBook</li>
            </ol>
        </section>

         <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">

                    <div class="box box-danger">

                        <div class="box-header with-border">
                            <h4 class="text-bold text-red">DayBook</h4>
                            <div class="col-sm-12">
                                <!-- search form -->
                                <form  action="" method="post" class="form-horizontal" >
                                    <div class=" form-group-sm">




                                        <div class="col-sm-2">
                                            <input maxlength="10" type="text" name="date1" id="date1" class=" nepali-calendar form-control" placeholder="Select Date" >
                                        </div>
                                        <div class="col-sm-2">
                                            <input maxlength="10" type="text" name="date2" id="date2" class=" nepali-calendar form-control" placeholder="Select Date" >
                                        </div>

                                        <div class="col-sm-3">
                                            <select name="id" id="id" class="form-control select2" required>
                                                <option value="">Select Branch</option>
                                                <?php
                                                $sql1 = "SELECT * FROM OfficeDetail ORDER BY ID ASC ";
												$result = odbc_exec($connection,$sql1);
												
                                                while ($rows = odbc_fetch_array($result)) {
                                                   
                                                    ?>
                                                    <option value="<?php echo $rows['ID']; ?>" ><?php echo $rows['Code'] . " - " .$rows['Name'];?></option>;
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
                                    <button id="excel" class="btn bg-blue" href="#" onClick ="$('#daybook').tableExport({type: 'excel', escape: 'false'});">Export To Excel</button>
                                </div>

                            </div>
                        </div>

                        <div class="box-body">
                            <div class="box-title with-header text-bold">
                                DayBook ( <?php if(isset($_POST['date1']) AND ($_POST['date2'])){ echo $_POST['date1']. " - " .$_POST['date2'];}else{echo $cdate;}?> ): 
                                <?php
                                if (!empty($_POST['id'])) {
                                    $query = "SELECT ID,Name FROM OfficeDetail  WHERE  ID = '" . $_POST['id'] . "'";

                                    $sub = odbc_exec($connection, $query);
                                    $p = odbc_fetch_array($sub);
                                    echo $p['Name'];
                                } else {
                                    echo $branchName;
                                }
                                ?>
                            </div>
							
                            <table  id="daybook" class="table table-responsive table-bordered table-striped">
                                <thead class="bg-red">
                                    <tr class="text-sm">

                                        <th >CODE</th>
                                        <th>WelCr</th>
                                        <th>WelDr</th>
                                        <th>ComCr</th>
                                        <th>ComDr</th>
                                        <th>PerCr</th>
                                        <th>PerDr</th>
                                        <th>SpeCr</th>
                                        <th>SpeDr</th>
										<th>PenCr</th>
										<th>PenDr</th>
										<th>EmeCr</th>
										<th>EmeDr</th>
										<th>MemCr</th>
										
										<th>PasCr</th>
										<th>Cheq</th>
										<th>OthCr</th>
										<th>GenCr</th>
										<th>GenDr</th>
										
										<th>GenInt</th>
										<th>EmeDr</th>
										<th>EmeCr</th>
										<th>EmeInt</th>
										<th>HouDr</th>
										<th>HouCr</th>
										<th>HouInt</th>
										<th>DseDr</th>
										<th>DseCr</th>
										<th>DseInt</th>
										
										
										<th>EduDr</th>
										<th>EduCr</th>
										<th>EduInt</th>
										
										<th>AGIDr</th>
										<th>AGICr</th>
										<th>AGIInt</th>
										
										<th>Disbursed</th>
										<th>Receipt</th>
										<th>CashSW</th>
										<th>Withdraw</th>
										<!--<th>CashSD</th>-->
										
										<th>Net</th>
										
                                    </tr>

                                </thead>

                                <tbody>

                                    <?php
                                    if (empty($_POST)) {
                                        

                                        $query = "SELECT ID,Name FROM OfficeDetail  WHERE NAME like '%$branchName%'";
                                        $sub = odbc_exec($connection, $query);
                                        $p = odbc_fetch_array($sub);
										$pid = $p['ID']; 
                                        

                                        $qry = "select c.centercode,
(select sum(cramount)from savingdetail where savedate='$cdate'  and c.centerid=centerid and savingtypeid=1 and officeid=c.officeid)welCr,
(select sum(dramount)from savingdetail where savedate='$cdate' and c.centerid=centerid and savingtypeid=1 and officeid=c.officeid)welDr,
(select sum(cramount)from savingdetail where savedate='$cdate' and c.centerid=centerid and savingtypeid=2 and officeid=c.officeid)ComCr,
(select sum(dramount)from savingdetail where savedate='$cdate' and c.centerid=centerid and savingtypeid=2 and officeid=c.officeid)ComDr,
(select sum(cramount)from savingdetail where savedate='$cdate' and c.centerid=centerid and savingtypeid=3 and officeid=c.officeid)PerCr,
(select sum(dramount)from savingdetail where savedate='$cdate' and c.centerid=centerid and savingtypeid=3 and officeid=c.officeid)PerDr,
(select sum(cramount)from savingdetail where savedate='$cdate' and c.centerid=centerid and savingtypeid=4 and officeid=c.officeid )SpeCr,
(select sum(dramount)from savingdetail where savedate='$cdate' and c.centerid=centerid and savingtypeid=4 and officeid=c.officeid )SpeDr,
(select sum(cramount)from savingdetail where savedate='$cdate' and c.centerid=centerid and savingtypeid=5 and officeid=c.officeid)PenCr,
(select sum(dramount + IntDr)from savingdetail where savedate='$cdate' and c.centerid=centerid and savingtypeid=5 and officeid=c.officeid and contraid = '301')PenDr,
(select sum(loancr) from loandetail where savedate='$cdate' and c.centerid=centerid and loantypeid=1 and officeid=c.officeid)GenCr,
(select sum(intcr) from loandetail where savedate='$cdate' and c.centerid=centerid and loantypeid=1 and officeid=c.officeid)GenInt,
(select sum(loandr) from loandetail where savedate='$cdate' and c.centerid=centerid and loantypeid=1 and officeid=c.officeid)GenDr,
(select sum(loancr) from loandetail where savedate='$cdate' and c.centerid=centerid and loantypeid=2 and officeid=c.officeid)EmeCr,
(select sum(intcr) from loandetail where savedate='$cdate' and c.centerid=centerid and loantypeid=2 and officeid=c.officeid)EmeInt,
(select sum(loandr) from loandetail where savedate='$cdate' and c.centerid=centerid and loantypeid=2 and officeid=c.officeid)EmeDr,
(select sum(loancr) from loandetail where savedate='$cdate' and c.centerid=centerid and loantypeid=3 and officeid=c.officeid)HouCr,
(select sum(intcr) from loandetail where savedate='$cdate' and c.centerid=centerid and loantypeid=3 and officeid=c.officeid)HouInt,
(select sum(loandr) from loandetail where savedate='$cdate' and c.centerid=centerid and loantypeid=3 and officeid=c.officeid)HouDr,
(select sum(loancr) from loandetail where savedate='$cdate' and c.centerid=centerid and loantypeid=7 and officeid=c.officeid)DseCr,
(select sum(intcr) from loandetail where savedate='$cdate' and c.centerid=centerid and loantypeid=7 and officeid=c.officeid)DseInt,
(select sum(loandr) from loandetail where savedate='$cdate' and c.centerid=centerid and loantypeid=7 and officeid=c.officeid)DseDr,
(select sum(loancr) from loandetail where savedate='$cdate' and c.centerid=centerid and loantypeid=9 and officeid=c.officeid)EduCr,
(select sum(intcr) from loandetail where savedate='$cdate' and c.centerid=centerid and loantypeid=9 and officeid=c.officeid)EduInt,
(select sum(loandr) from loandetail where savedate='$cdate' and c.centerid=centerid and loantypeid=9 and officeid=c.officeid)EduDr,

(select sum(loancr) from loandetail where savedate='$cdate' and c.centerid=centerid and loantypeid=10 and officeid=c.officeid)AGICr,
(select sum(intcr) from loandetail where savedate='$cdate' and c.centerid=centerid and loantypeid=10 and officeid=c.officeid)AGIInt,
(select sum(loandr) from loandetail where savedate='$cdate' and c.centerid=centerid and loantypeid=10 and officeid=c.officeid)AGIDr,

(select sum(cramount)from insurancedetail where savedate='$cdate' and c.centerid=centerid and insurancetypeid=1 and officeid=c.officeid)EFCr,
(select sum(Dramount)from insurancedetail where savedate='$cdate' and c.centerid=centerid and insurancetypeid=1 and officeid=c.officeid)EFDr,
(select sum(cramount)from insurancedetail where savedate='$cdate' and c.centerid=centerid and insurancetypeid=2 and officeid=c.officeid)Catt,
(select sum(cramount)from insurancedetail where savedate='$cdate' and c.centerid=centerid and insurancetypeid=3 and officeid=c.officeid)LIc,
(select sum(cramount)from incomedetail where savedate='$cdate' and c.centerid=centerid and incometypeid=1 and officeid=c.officeid)Passbook,
(select sum(cramount)from incomedetail where savedate='$cdate' and c.centerid=centerid and incometypeid=2 and officeid=c.officeid)Att,
(select sum(cramount)from incomedetail where savedate='$cdate' and c.centerid=centerid and incometypeid=5 and officeid=c.officeid)Cheq,
(select sum(cramount)from incomedetail where savedate='$cdate' and c.centerid=centerid and incometypeid=7 and officeid=c.officeid)Other,
(select sum(dramount)from savingdetail where particulars<>'Withdraw for Installement and Saving' and c.centerid=centerid and officeid=c.officeid and savedate='$cdate')cashsw
from centermain c where c.officeid='$pid'  group by c.centercode,c.centerid,c.officeid order by c.centercode";
                                        $result = odbc_exec($connection, $qry);
										$WelCr=$WelDr=$ComDr=$ComCr=$PerCr=$PerDr=$PenCr=$EFDr=$EFCr=$PenDr=$LIc=$GenCr=$GenDr=$GenInt=$EmeCr=$EmeDr=$EmeInt=$Catt=$Passbook=$Att=$Cheq=$EduCr=$EduDr=$EduInt=$Other=$AGICr=$AGIDr=$AGIInt = 0;
										$DseCr=$DseDr=$DseInt=$Cheq=$SpeCr=$SpeDr=$HouCr=$HouDr=$HouInt=$treceipt=$tdisbursed=$twithdraw=$cashsw = $netamt = 0;
								  while ($r = odbc_fetch_array($result)) {
											$totalreceipt = $r['welCr']+$r['ComCr']+ $r['PerCr'] + $r['SpeCr'] + $r['PenCr'] + $r['EFCr'] + $r['LIc'] + $r['GenCr'] + $r['EmeCr'] + $r['HouCr'] + $r['EduCr'] + $r['Catt'] + $r['Passbook'] + $r['Att'] + $r['Cheq'] + $r['Other'] + $r['DseCr'] + $r['GenInt'] + $r['HouInt'] + $r['EmeInt'] + $r['EduInt'] + $r['DseInt'] + $r['AGICr'] + $r['AGIInt'];
											$totaldisbursed = $r['GenDr'] + $r['EmeDr'] + $r['HouDr'] + $r['DseDr'] + $r['EduDr'] + $r['AGIDr'];
											$totalwithdraw = $r['EFDr'] + $r['PenDr'] + $r['SpeDr'] + $r['PerDr'] + $r['ComDr'] + $r['welDr'] ;
                                            $net = $totalreceipt - $totalwithdraw;
											
											
											if($net == true){
												$WelCr = $WelCr + $r['welCr'];
												$WelDr = $WelDr + $r['welDr'];
												$ComDr = $ComDr + $r['ComDr'];
												$ComCr = $ComCr + $r['ComCr'];
												$PerCr = $PerCr + $r['PerCr'];
												$PerDr = $PerDr + $r['PerDr'];
												
												$SpeCr = $SpeCr + $r['SpeCr'];
												$SpeDr = $SpeDr + $r['SpeDr'];
												$PenCr = $PenCr + $r['PenCr'];
												$PenDr = $PenDr + $r['PenDr'];
												$EFCr = $EFCr + $r['EFCr'];
												$EFDr = $EFDr + $r['EFDr'];
												
												$LIc = $LIc + $r['LIc'];
												$Passbook = $Passbook + $r['Passbook'];
												$Cheq = $Cheq + $r['Cheq'];
												$Other = $Other + $r['Other'];
												$GenCr = $GenCr + $r['GenCr'];
												$GenDr = $GenDr + $r['GenDr'];
												$GenInt = $GenInt + $r['GenInt'];
												
												$EmeDr = $EmeDr + $r['EmeDr'];
												$EmeCr = $EmeCr + $r['EmeCr'];
												$EmeInt = $EmeInt + $r['EmeInt'];
												$HouDr = $HouDr + $r['HouDr'];
												$HouCr = $HouCr + $r['HouCr'];
												$HouInt = $HouInt + $r['HouInt'];
												
												$DseDr = $DseDr + $r['DseDr'];
												$DseCr = $DseCr + $r['DseCr'];
												$DseInt = $DseInt + $r['DseInt'];
												
												$EduDr = $EduDr + $r['EduDr'];
												$EduCr = $EduCr + $r['EduCr'];
												$EduInt = $EduInt + $r['EduInt'];
												
												$AGIDr = $AGIDr + $r['AGIDr'];
												$AGICr = $AGICr + $r['AGICr'];
												$AGIInt = $AGIInt + $r['AGIInt'];
												
												$tdisbursed = $tdisbursed + $totaldisbursed;
												$treceipt = $treceipt + $totalreceipt;
												$twithdraw = $twithdraw + $totalwithdraw;
												$cashsw = $cashsw + $r['cashsw'];
												$netamt = $netamt + $net;
												
											?>
                                            <tr class="text-sm">
											<td class="text-bold headcol"><?php echo $r['centercode'];?></td>
											<td><?php echo $r['welCr'];?></td>
											<td><?php echo $r['welDr'];?></td>
											<td><?php echo $r['ComCr'];?></td>
											<td><?php echo $r['ComDr'];?></td>
											<td><?php echo $r['PerCr'];?></td>
											<td><?php echo $r['PerDr'];?></td>
											<td><?php echo $r['SpeCr'];?></td>
											<td><?php echo $r['SpeDr'];?></td>
											<td><?php echo $r['PenCr'];?></td>
											<td><?php echo $r['PenDr'];?></td>
											<td><?php echo $r['EFCr'];?></td>
											<td><?php echo $r['EFDr'];?></td>
											<td><?php echo $r['LIc'];?></td>
											<td><?php echo $r['Passbook'];?></td>
											<td><?php echo $r['Cheq'];?></td>
											<td><?php echo $r['Other'];?></td>
											<td><?php echo $r['GenCr'];?></td>
											<td><?php echo $r['GenDr'];?></td>
											<td><?php echo $r['GenInt'];?></td>
											<td><?php echo $r['EmeDr'];?></td>
											<td><?php echo $r['EmeCr'];?></td>
											<td><?php echo $r['EmeInt'];?></td>
											<td><?php echo $r['HouDr'];?></td>
											<td><?php echo $r['HouCr'];?></td>
											<td><?php echo $r['HouInt'];?></td>
											
											<td><?php echo $r['DseDr'];?></td>
											<td><?php echo $r['DseCr'];?></td>
											<td><?php echo $r['DseInt'];?></td>
											
											<td><?php echo $r['EduDr'];?></td>
											<td><?php echo $r['EduCr'];?></td>
											<td><?php echo $r['EduInt'];?></td>
											
											<td><?php echo $r['AGIDr'];?></td>
											<td><?php echo $r['AGICr'];?></td>
											<td><?php echo $r['AGIInt'];?></td>
											
											<td><?php echo $totaldisbursed;?></td>
											<td><?php echo $totalreceipt;?></td>
											<td><?php echo $totalwithdraw;?></td>
											<td><?php echo $r['cashsw'];?></td>
											<td><?php echo $net;?></td>
											
											</tr>
											
											
                                            <?php
											}	
											
										}
										
										?>
										<tr>
											<td class="text-bold text-red">Total : </td>
											<td><?php echo $WelCr;?></td>
											<td><?php echo $WelDr;?></td>
											<td><?php echo $ComCr;?></td>
											<td><?php echo $ComDr;?></td>
											<td><?php echo $PerCr;?></td>
											<td><?php echo $PerDr;?></td>
											<td><?php echo $SpeCr;?></td>
											<td><?php echo $SpeDr;?></td>
											<td><?php echo $PenCr;?></td>
											<td><?php echo $PenDr;?></td>
											<td><?php echo $EFCr;?></td>
											<td><?php echo $EFDr;?></td>
											<td><?php echo $LIc;?></td>
											<td><?php echo $Passbook;?></td>
											<td><?php echo $Cheq;?></td>
											<td><?php echo $Other;?></td>
											<td><?php echo $GenCr;?></td>
											<td><?php echo $GenDr;?></td>
											<td><?php echo $GenInt;?></td>
											<td><?php echo $EmeDr;?></td>
											<td><?php echo $EmeCr;?></td>
											<td><?php echo $EmeInt;?></td>
											<td><?php echo $HouDr;?></td>
											<td><?php echo $HouCr;?></td>
											<td><?php echo $HouInt;?></td>
											
											<td><?php echo $DseDr;?></td>
											<td><?php echo $DseCr;?></td>
											<td><?php echo $DseInt;?></td>
											
											<td><?php echo $EduDr;?></td>
											<td><?php echo $EduCr;?></td>
											<td><?php echo $EduInt;?></td>
											
											<td><?php echo $AGIDr;?></td>
											<td><?php echo $AGICr;?></td>
											<td><?php echo $AGIInt;?></td>
											
											<td><?php echo $tdisbursed;?></td>
											<td><?php echo $treceipt;?></td>
											<td><?php echo $twithdraw;?></td>
											<td><?php echo $cashsw;?></td>
											<td><?php echo $netamt;?></td>
											
											</tr>
											
										<?php
                                    }else if (isset($_POST['search'])) {
                                        $ID = $_POST['id'];
                                        $date1 = $_POST['date1'];
                                        $date2 = $_POST['date2'];
                                        

                                       
                                        $query = "select c.centercode,
(select sum(cramount)from savingdetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and savingtypeid=1 and officeid=c.officeid)welCr,
(select sum(dramount)from savingdetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and savingtypeid=1 and officeid=c.officeid)welDr,
(select sum(cramount)from savingdetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and savingtypeid=2 and officeid=c.officeid)ComCr,
(select sum(dramount)from savingdetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and savingtypeid=2 and officeid=c.officeid)ComDr,
(select sum(cramount)from savingdetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and savingtypeid=3 and officeid=c.officeid)PerCr,
(select sum(dramount)from savingdetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and savingtypeid=3 and officeid=c.officeid)PerDr,
(select sum(cramount)from savingdetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and savingtypeid=4 and officeid=c.officeid )SpeCr,
(select sum(dramount)from savingdetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and savingtypeid=4 and officeid=c.officeid )SpeDr,
(select sum(cramount)from savingdetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and savingtypeid=5 and officeid=c.officeid)PenCr,
(select sum(dramount + IntDr)from savingdetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and savingtypeid=5 and officeid=c.officeid and contraid = '301')PenDr,
(select sum(loancr) from loandetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and loantypeid=1 and officeid=c.officeid)GenCr,
(select sum(intcr) from loandetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and loantypeid=1 and officeid=c.officeid)GenInt,
(select sum(loandr) from loandetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and loantypeid=1 and officeid=c.officeid)GenDr,
(select sum(loancr) from loandetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and loantypeid=2 and officeid=c.officeid)EmeCr,
(select sum(intcr) from loandetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and loantypeid=2 and officeid=c.officeid)EmeInt,
(select sum(loandr) from loandetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and loantypeid=2 and officeid=c.officeid)EmeDr,
(select sum(loancr) from loandetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and loantypeid=3 and officeid=c.officeid)HouCr,
(select sum(intcr) from loandetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and loantypeid=3 and officeid=c.officeid)HouInt,
(select sum(loandr) from loandetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and loantypeid=3 and officeid=c.officeid)HouDr,
(select sum(loancr) from loandetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and loantypeid=7 and officeid=c.officeid)DseCr,
(select sum(intcr) from loandetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and loantypeid=7 and officeid=c.officeid)DseInt,
(select sum(loandr) from loandetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and loantypeid=7 and officeid=c.officeid)DseDr,
(select sum(loancr) from loandetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and loantypeid=9 and officeid=c.officeid)EduCr,
(select sum(intcr) from loandetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and loantypeid=9 and officeid=c.officeid)EduInt,
(select sum(loandr) from loandetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and loantypeid=9 and officeid=c.officeid)EduDr,


(select sum(loancr) from loandetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and loantypeid=10 and officeid=c.officeid)AGICr,
(select sum(intcr) from loandetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and loantypeid=10 and officeid=c.officeid)AGIInt,
(select sum(loandr) from loandetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and loantypeid=10 and officeid=c.officeid)AGIDr,

(select sum(cramount)from insurancedetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and insurancetypeid=1 and officeid=c.officeid)EFCr,
(select sum(Dramount)from insurancedetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and insurancetypeid=1 and officeid=c.officeid)EFDr,
(select sum(cramount)from insurancedetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and insurancetypeid=2 and officeid=c.officeid)Catt,
(select sum(cramount)from insurancedetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and insurancetypeid=3 and officeid=c.officeid)LIc,
(select sum(cramount)from incomedetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and incometypeid=1 and officeid=c.officeid)Passbook,
(select sum(cramount)from incomedetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and incometypeid=2 and officeid=c.officeid)Att,
(select sum(cramount)from incomedetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and incometypeid=5 and officeid=c.officeid)Cheq,
(select sum(cramount)from incomedetail where savedate BETWEEN '$date1' and '$date2' and c.centerid=centerid and incometypeid=7 and officeid=c.officeid)Other,
(select sum(dramount)from savingdetail where particulars<>'Withdraw for Installement and Saving' and c.centerid=centerid and officeid=c.officeid and savedate='$cdate')cashsw
from centermain c where c.officeid='$ID'  group by c.centercode,c.centerid,c.officeid order by c.centercode";
                                        $results = odbc_exec($connection, $query);
										$WelCr=$WelDr=$ComDr=$ComCr=$PerCr=$PerDr=$PenCr=$EFDr=$EFCr=$PenDr=$LIc=$GenCr=$GenDr=$GenInt=$EmeCr=$EmeDr=$EmeInt=$Catt=$Passbook=$Att=$Cheq=$EduCr=$EduDr=$EduInt=$Other = $AGIDr = $AGICr = $AGIInt =  0;
										$DseCr=$DseDr=$DseInt=$Cheq = $SpeCr=$SpeDr=$HouCr=$HouDr=$HouInt=$treceipt=$tdisbursed=$twithdraw=$cashsw = $netamt = 0;
										
                                        while ($r = odbc_fetch_array($results)){
											$totalreceipt = $r['welCr']+$r['ComCr']+ $r['PerCr'] + $r['SpeCr'] + $r['PenCr'] + $r['EFCr'] + $r['LIc'] + $r['GenCr'] + $r['EmeCr'] + $r['HouCr'] + $r['EduCr'] + $r['Catt'] + $r['Passbook'] + $r['Att'] + $r['Cheq'] + $r['Other'] + $r['DseCr'] + $r['GenInt'] + $r['HouInt'] + $r['EmeInt'] + $r['EduInt'] + $r['DseInt'] + $r['AGICr'] + $r['AGIInt'];
											$totaldisbursed = $r['GenDr'] + $r['EmeDr'] + $r['HouDr'] + $r['DseDr'] + $r['EduDr'] + $r['AGIDr'];
											$totalwithdraw = $r['EFDr'] + $r['PenDr'] + $r['SpeDr'] + $r['PerDr'] + $r['ComDr'] + $r['welDr'] ;
                                            $net = $totalreceipt - $totalwithdraw;
											if($net == true){
												$WelCr = $WelCr + $r['welCr'];
												$WelDr = $WelDr + $r['welDr'];
												$ComDr = $ComDr + $r['ComDr'];
												$ComCr = $ComCr + $r['ComCr'];
												$PerCr = $PerCr + $r['PerCr'];
												$PerDr = $PerDr + $r['PerDr'];
												
												$SpeCr = $SpeCr + $r['SpeCr'];
												$SpeDr = $SpeDr + $r['SpeDr'];
												$PenCr = $PenCr + $r['PenCr'];
												$PenDr = $PenDr + $r['PenDr'];
												$EFCr = $EFCr + $r['EFCr'];
												$EFDr = $EFDr + $r['EFDr'];
												
												$LIc = $LIc + $r['LIc'];
												$Passbook = $Passbook + $r['Passbook'];
												$Cheq = $Cheq + $r['Cheq'];
												$Other = $Other + $r['Other'];
												$GenCr = $GenCr + $r['GenCr'];
												$GenDr = $GenDr + $r['GenDr'];
												$GenInt = $GenInt + $r['GenInt'];
												
												$EmeDr = $EmeDr + $r['EmeDr'];
												$EmeCr = $EmeCr + $r['EmeCr'];
												$EmeInt = $EmeInt + $r['EmeInt'];
												$HouDr = $HouDr + $r['HouDr'];
												$HouCr = $HouCr + $r['HouCr'];
												$HouInt = $HouInt + $r['HouInt'];
												
												$DseDr = $DseDr + $r['DseDr'];
												$DseCr = $DseCr + $r['DseCr'];
												$DseInt = $DseInt + $r['DseInt'];
												
												$EduDr = $EduDr + $r['EduDr'];
												$EduCr = $EduCr + $r['EduCr'];
												$EduInt = $EduInt + $r['EduInt'];
												
												$AGIDr = $AGIDr + $r['AGIDr'];
												$AGICr = $AGICr + $r['AGICr'];
												$AGIInt = $AGIInt + $r['AGIInt'];
												
												$tdisbursed = $tdisbursed + $totaldisbursed;
												$treceipt = $treceipt + $totalreceipt;
												$twithdraw = $twithdraw + $totalwithdraw;
												$cashsw = $cashsw + $r['cashsw'];
												$netamt = $netamt + $net;
											
                                            ?>
                                           
											<tr class="text-sm">
											<td class="text-bold headcol"><?php echo $r['centercode'];?></td>
											<td><?php echo $r['welCr'];?></td>
											<td><?php echo $r['welDr'];?></td>
											<td><?php echo $r['ComCr'];?></td>
											<td><?php echo $r['ComDr'];?></td>
											<td><?php echo $r['PerCr'];?></td>
											<td><?php echo $r['PerDr'];?></td>
											<td><?php echo $r['SpeCr'];?></td>
											<td><?php echo $r['SpeDr'];?></td>
											<td><?php echo $r['PenCr'];?></td>
											<td><?php echo $r['PenDr'];?></td>
											<td><?php echo $r['EFCr'];?></td>
											<td><?php echo $r['EFDr'];?></td>
											<td><?php echo $r['LIc'];?></td>
											
											<td><?php echo $r['Passbook'];?></td>
											<td><?php echo $r['Cheq'];?></td>
											<td><?php echo $r['Other'];?></td>
											<td><?php echo $r['GenCr'];?></td>
											<td><?php echo $r['GenDr'];?></td>
											<td><?php echo $r['GenInt'];?></td>
											<td><?php echo $r['EmeDr'];?></td>
											<td><?php echo $r['EmeCr'];?></td>
											<td><?php echo $r['EmeInt'];?></td>
											<td><?php echo $r['HouDr'];?></td>
											<td><?php echo $r['HouCr'];?></td>
											<td><?php echo $r['HouInt'];?></td>
											
											<td><?php echo $r['DseDr'];?></td>
											<td><?php echo $r['DseCr'];?></td>
											<td><?php echo $r['DseInt'];?></td>
											<td><?php echo $r['EduDr'];?></td>
											<td><?php echo $r['EduCr'];?></td>
											<td><?php echo $r['EduInt'];?></td>
											
											<td><?php echo $r['AGIDr'];?></td>
											<td><?php echo $r['AGICr'];?></td>
											<td><?php echo $r['AGIInt'];?></td>
											
											<td><?php echo $totaldisbursed;?></td>
											<td><?php echo $totalreceipt;?></td>
											<td><?php echo $totalwithdraw;?></td>
											
											<td><?php echo $r['cashsw'];?></td>
											<td><?php echo $net;?></td>
											
											</tr>
											
											
                                            <?php
											}
											
                                        }?>
											<tr class="text-sm">
											<td class="text-bold text-red">Total : </td>
											<td><?php echo $WelCr;?></td>
											<td><?php echo $WelDr;?></td>
											<td><?php echo $ComCr;?></td>
											<td><?php echo $ComDr;?></td>
											<td><?php echo $PerCr;?></td>
											<td><?php echo $PerDr;?></td>
											<td><?php echo $SpeCr;?></td>
											<td><?php echo $SpeDr;?></td>
											<td><?php echo $PenCr;?></td>
											<td><?php echo $PenDr;?></td>
											<td><?php echo $EFCr;?></td>
											<td><?php echo $EFDr;?></td>
											<td><?php echo $LIc;?></td>
											<td><?php echo $Passbook;?></td>
											<td><?php echo $Cheq;?></td>
											<td><?php echo $Other;?></td>
											<td><?php echo $GenCr;?></td>
											<td><?php echo $GenDr;?></td>
											<td><?php echo $GenInt;?></td>
											<td><?php echo $EmeDr;?></td>
											<td><?php echo $EmeCr;?></td>
											<td><?php echo $EmeInt;?></td>
											<td><?php echo $HouDr;?></td>
											<td><?php echo $HouCr;?></td>
											<td><?php echo $HouInt;?></td>
											
											<td><?php echo $DseDr;?></td>
											<td><?php echo $DseCr;?></td>
											<td><?php echo $DseInt;?></td>
											
											<td><?php echo $EduDr;?></td>
											<td><?php echo $EduCr;?></td>
											<td><?php echo $EduInt;?></td>
											
											<td><?php echo $AGIDr;?></td>
											<td><?php echo $AGICr;?></td>
											<td><?php echo $AGIInt;?></td>
											
											<td><?php echo $tdisbursed;?></td>
											<td><?php echo $treceipt;?></td>
											<td><?php echo $twithdraw;?></td>
											<td><?php echo $cashsw;?></td>
											<td><?php echo $netamt;?></td>
											
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
