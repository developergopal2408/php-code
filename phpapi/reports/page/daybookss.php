<?php
include_once 'top.php';
include_once 'header.php';
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
                            <div class="col-sm-12">
                                <!-- search form -->
                                <form  action="" method="post" class="form-horizontal" >
                                    <div class=" form-group-sm">
                                        <div class="col-sm-2">
                                            <input maxlength="10" type="text" name="date1" id="date1" class=" nepali-calendar form-control" placeholder="Select Date" value="<?php
                                            if (isset($_POST['date1'])) {
                                                echo $_POST['date1'];
                                            } else {
                                                echo $cdate;
                                            }
                                            ?>">
                                        </div>
                                        <div class="col-sm-2">
                                            <button type="submit" name="search" id="search-btn" class=" btn btn-flat bg-green"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                </form>
                                <!-- /.search form -->
                                <div class="box-tools pull-right" >
                                    <a  href="daybookss.php"  class="btn btn-flat bg-blue" data-toggle = "tooltip" title="All / Refresh"><i class="fa fa-refresh"></i></a>
                                </div>
                            </div>
                        </div>

                        <div class="box-body">
                                <?php
                                if (isset($_POST['date1'])) {
                                    echo "<h5 class='text-bold text-center'>" . $branchName . " - " . $_POST['date1'] . "</h5>";
                                }
                                ?>
                            
                            <table  id="daybookss" class="table stripe row-border order-column" cellspacing="0" width="100%">
                                <thead class="bg-red text-sm">
                                    <tr>
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
										<th>AGII</th>
                                        <th>PasCr</th>
                                        <th>Cheq</th>
                                        <th>Servch</th>
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
                                        
                                        <th>EngDr</th>
                                        <th>EngCr</th>
                                        <th>EngInt</th>
                                        
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
                                        <th>TWithdraw</th>
                                        <th>Net</th>
                                    </tr>

                                </thead>
                                <tbody class="text-sm">
                                    <?php
                                    if (isset($_POST['search'])) {
                                        $ID = $_SESSION['BranchID'];
                                        $date1 = $_POST['date1'];
                                        $query = "select c.centercode,
(select sum(cramount)from savingdetail where savedate = '$date1' and c.centerid=centerid and savingtypeid=1 and officeid=c.officeid and TransType <> 'Interest' and RefType <> 'Interest')welCr,
(select sum(dramount + IntDr)from savingdetail where savedate = '$date1' and c.centerid=centerid and savingtypeid=1 and officeid=c.officeid and TransType <> 'Interest' and RefType <> 'Interest')welDr,
(select sum(cramount)from savingdetail where savedate = '$date1' and c.centerid=centerid and savingtypeid=2 and officeid=c.officeid and TransType <> 'Interest' and RefType <> 'Interest')ComCr,
(select sum(dramount + IntDr)from savingdetail where savedate = '$date1' and c.centerid=centerid and savingtypeid=2 and officeid=c.officeid and TransType <> 'Interest' and RefType <> 'Interest')ComDr,
(select sum(cramount)from savingdetail where savedate = '$date1' and c.centerid=centerid and savingtypeid=3 and officeid=c.officeid and TransType <> 'Interest' and RefType <> 'Interest')PerCr,
(select sum(dramount + IntDr)from savingdetail where savedate = '$date1' and c.centerid=centerid and savingtypeid=3 and officeid=c.officeid and TransType <> 'Interest' and RefType <> 'Interest')PerDr,
(select sum(cramount)from savingdetail where savedate = '$date1' and c.centerid=centerid and savingtypeid=4 and officeid=c.officeid and TransType <> 'Interest' and RefType <> 'Interest')SpeCr,
(select sum(dramount + IntDr)from savingdetail where savedate = '$date1' and c.centerid=centerid and savingtypeid=4 and officeid=c.officeid and TransType <> 'Interest' and RefType <> 'Interest')SpeDr,
(select sum(cramount)from savingdetail where savedate = '$date1' and c.centerid=centerid and savingtypeid=5 and officeid=c.officeid and TransType <> 'Interest' and RefType <> 'Interest')PenCr,
(select sum(dramount + IntDr)from savingdetail where savedate = '$date1' and c.centerid=centerid and savingtypeid=5 and officeid=c.officeid and contraid = '301')PenDr,
(select sum(dramount + IntDr)from savingdetail where savedate = '$date1' and c.centerid=centerid and savingtypeid=5 and officeid=c.officeid and contraid = '339')Pension,
(select sum(loancr) from loandetail where savedate = '$date1' and c.centerid=centerid and loantypeid=1 and officeid=c.officeid)GenCr,
(select sum(intcr) from loandetail where savedate = '$date1' and c.centerid=centerid and loantypeid=1 and officeid=c.officeid)GenInt,
(select sum(loandr) from loandetail where savedate = '$date1' and c.centerid=centerid and loantypeid=1 and officeid=c.officeid)GenDr,
(select sum(loancr) from loandetail where savedate = '$date1' and c.centerid=centerid and loantypeid=2 and officeid=c.officeid)EmeCr,
(select sum(intcr) from loandetail where savedate = '$date1' and c.centerid=centerid and loantypeid=2 and officeid=c.officeid)EmeInt,
(select sum(loandr) from loandetail where savedate = '$date1' and c.centerid=centerid and loantypeid=2 and officeid=c.officeid)EmeDr,
(select sum(loancr) from loandetail where savedate = '$date1' and c.centerid=centerid and loantypeid=3 and officeid=c.officeid)HouCr,
(select sum(intcr) from loandetail where savedate = '$date1' and c.centerid=centerid and loantypeid=3 and officeid=c.officeid)HouInt,
(select sum(loandr) from loandetail where savedate = '$date1' and c.centerid=centerid and loantypeid=3 and officeid=c.officeid)HouDr,   
(select sum(loancr) from loandetail where savedate = '$date1' and c.centerid=centerid and loantypeid=4 and officeid=c.officeid)engcr,
(select sum(intcr) from loandetail where savedate = '$date1' and c.centerid=centerid and loantypeid=4 and officeid=c.officeid)engint,
(select sum(loandr) from loandetail where savedate = '$date1' and c.centerid=centerid and loantypeid=4 and officeid=c.officeid)engdr,   
(select sum(loancr) from loandetail where savedate = '$date1' and c.centerid=centerid and loantypeid=7 and officeid=c.officeid)DseCr,
(select sum(intcr) from loandetail where savedate = '$date1' and c.centerid=centerid and loantypeid=7 and officeid=c.officeid)DseInt,
(select sum(loandr) from loandetail where savedate = '$date1' and c.centerid=centerid and loantypeid=7 and officeid=c.officeid)DseDr,
(select sum(loancr) from loandetail where savedate = '$date1' and c.centerid=centerid and loantypeid=9 and officeid=c.officeid)EduCr,
(select sum(intcr) from loandetail where savedate = '$date1' and c.centerid=centerid and loantypeid=9 and officeid=c.officeid)EduInt,
(select sum(loandr) from loandetail where savedate = '$date1' and c.centerid=centerid and loantypeid=9 and officeid=c.officeid)EduDr,
(select sum(loancr) from loandetail where savedate = '$date1' and c.centerid=centerid and loantypeid=10 and officeid=c.officeid)AGICr,
(select sum(intcr) from loandetail where savedate = '$date1' and c.centerid=centerid and loantypeid=10 and officeid=c.officeid)AGIInt,
(select sum(loandr) from loandetail where savedate = '$date1' and c.centerid=centerid and loantypeid=10 and officeid=c.officeid)AGIDr,
(select sum(cramount)from insurancedetail where savedate = '$date1' and c.centerid=centerid and insurancetypeid=1 and officeid=c.officeid)EFCr,
(select sum(Dramount)from insurancedetail where savedate = '$date1' and c.centerid=centerid and insurancetypeid=1 and officeid=c.officeid)EFDr,
(select sum(cramount)from insurancedetail where savedate = '$date1' and c.centerid=centerid and insurancetypeid=2 and officeid=c.officeid)Catt,
(select sum(cramount)from insurancedetail where savedate = '$date1' and c.centerid=centerid and insurancetypeid=3 and officeid=c.officeid)LIc,
(select sum(cramount)from insurancedetail where savedate = '$date1' and c.centerid=centerid and insurancetypeid=4 and officeid=c.officeid)AGI,
(select sum(cramount)from incomedetail where savedate = '$date1' and c.centerid=centerid and incometypeid=1 and officeid=c.officeid)Passbook,
(select sum(cramount)from incomedetail where savedate = '$date1' and c.centerid=centerid and incometypeid=2 and officeid=c.officeid)Att,
(select sum(cramount)from incomedetail where savedate = '$date1' and c.centerid=centerid and incometypeid=5 and officeid=c.officeid)Cheq,
(select sum(cramount)from incomedetail where savedate='$date1' and c.centerid=centerid and incometypeid=4 and officeid=c.officeid)Servch,
(select sum(cramount)from incomedetail where savedate = '$date1' and c.centerid=centerid and incometypeid=7 and officeid=c.officeid)Other,
(select sum(dramount)from savingdetail where particulars<>'Withdraw for Installement and Saving' and c.centerid=centerid and officeid=c.officeid and savedate='$date1')cashsw
from centermain c where c.officeid='$ID'  group by c.centercode,c.centerid,c.officeid order by c.centercode";
                                        $results = odbc_exec($connection, $query);
                                        $engcr = $engdr = $engint = $WelCr = $WelDr = $ComDr = $ComCr = $PerCr = $PerDr = $PenCr = $EFDr = $EFCr = $PenDr = $LIc = $GenCr = $GenDr = $GenInt = $EmeCr = $EmeDr = $EmeInt = $Catt = $Passbook = $Att = $Cheq = $EduCr = $EduDr = $EduInt = $Other = $AGIDr = $AGICr = $AGIInt = $Servch = $AGI = 0;
                                        $DseCr = $DseDr = $DseInt = $Cheq = $SpeCr = $SpeDr = $HouCr = $HouDr = $HouInt = $treceipt = $tdisbursed = $twithdraw = $cashsw = $netamt = 0;
                                        while ($r = odbc_fetch_array($results)) {
                                            $totalreceipt = $r['welCr'] + $r['ComCr'] + $r['PerCr'] + $r['SpeCr'] + $r['PenCr'] + $r['EFCr'] + $r['LIc'] + $r['GenCr'] + $r['EmeCr'] + $r['AGI'] + $r['HouCr'] + $r['EduCr'] + $r['Catt'] + $r['Passbook'] + $r['Att'] + $r['Cheq'] + $r['Other'] + $r['DseCr'] + $r['GenInt'] + $r['engcr'] + $r['engint'] + $r['HouInt'] + $r['EmeInt'] + $r['EduInt'] + $r['DseInt'] + $r['AGICr'] + $r['AGIInt'] + $r['Servch'];
                                            $totaldisbursed = $r['GenDr'] + $r['EmeDr'] + $r['HouDr'] + $r['DseDr'] + $r['EduDr'] + $r['AGIDr'] + $r['engdr'];
                                            $totalwithdraw = $r['EFDr'] + $r['PenDr'] + $r['SpeDr'] + $r['PerDr'] + $r['ComDr'] + $r['welDr'] + $r['Pension'];
                                            $net = $totalreceipt - $totalwithdraw;
                                            if ($net == true or ! empty($totalreceipt or $totalwithdraw)) {
                                                $WelCr = $WelCr + $r['welCr'];
                                                $WelDr = $WelDr + $r['welDr'];
                                                $ComDr = $ComDr + $r['ComDr'];
                                                $ComCr = $ComCr + $r['ComCr'];
                                                $PerCr = $PerCr + $r['PerCr'];
                                                $PerDr = $PerDr + $r['PerDr'];
                                                $Servch = $Servch + $r['Servch'];
                                                $SpeCr = $SpeCr + $r['SpeCr'];
                                                $SpeDr = $SpeDr + $r['SpeDr'];
                                                $PenCr = $PenCr + $r['PenCr'];
                                                $PenDr = $PenDr + $r['PenDr'];
                                                $EFCr = $EFCr + $r['EFCr'];
                                                $EFDr = $EFDr + $r['EFDr'];
                                                $LIc = $LIc + $r['LIc'];
												$AGI = $AGI + $r['AGI'];
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
                                                
                                                $engdr = $engdr + $r['engdr'];
                                                $engcr = $engcr + $r['engcr'];
                                                $engint = $engint + $r['engint'];
                                                
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

                                                <tr>
                                                    <td class="text-bold"><?php echo $r['centercode']; ?></td>
                                                    <td><?php echo $r['welCr']; ?></td>
                                                    <td><?php echo $r['welDr']; ?></td>
                                                    <td><?php echo $r['ComCr']; ?></td>
                                                    <td><?php echo $r['ComDr']; ?></td>
                                                    <td><?php echo $r['PerCr']; ?></td>
                                                    <td><?php echo $r['PerDr']; ?></td>
                                                    <td><?php echo $r['SpeCr']; ?></td>
                                                    <td><?php echo $r['SpeDr']; ?></td>
                                                    <td><?php echo $r['PenCr']; ?></td>
                                                    <td><?php echo $r['PenDr']; ?></td>
                                                    <td><?php echo $r['EFCr']; ?></td>
                                                    <td><?php echo $r['EFDr']; ?></td>
                                                    <td><?php echo $r['LIc']; ?></td>
													<td><?php echo $r['AGI']; ?></td>
                                                    <td><?php echo $r['Passbook']; ?></td>
                                                    <td><?php echo $r['Cheq']; ?></td>
                                                    <td><?php echo $r['Servch']; ?></td>
                                                    <td><?php echo $r['Other']; ?></td>
                                                    <td><?php echo $r['GenCr']; ?></td>
                                                    <td><?php echo $r['GenDr']; ?></td>
                                                    <td><?php echo $r['GenInt']; ?></td>
                                                    <td><?php echo $r['EmeDr']; ?></td>
                                                    <td><?php echo $r['EmeCr']; ?></td>
                                                    <td><?php echo $r['EmeInt']; ?></td>
                                                    <td><?php echo $r['HouDr']; ?></td>
                                                    <td><?php echo $r['HouCr']; ?></td>
                                                    <td><?php echo $r['HouInt']; ?></td>
                                                    
                                                    <td><?php echo $r['engdr']; ?></td>
                                                    <td><?php echo $r['engcr']; ?></td>
                                                    <td><?php echo $r['engint']; ?></td>
                                                    
                                                    <td><?php echo $r['DseDr']; ?></td>
                                                    <td><?php echo $r['DseCr']; ?></td>
                                                    <td><?php echo $r['DseInt']; ?></td>
                                                    <td><?php echo $r['EduDr']; ?></td>
                                                    <td><?php echo $r['EduCr']; ?></td>
                                                    <td><?php echo $r['EduInt']; ?></td>
                                                    <td><?php echo $r['AGIDr']; ?></td>
                                                    <td><?php echo $r['AGICr']; ?></td>
                                                    <td><?php echo $r['AGIInt']; ?></td>
                                                    <td><?php echo $totaldisbursed; ?></td>
                                                    <td><?php echo $totalreceipt; ?></td>
													 <td><?php echo $r['cashsw']; ?></td>
                                                    <td><?php echo $totalwithdraw; ?></td>
                                                   
                                                    <td><?php echo $net; ?></td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                        ?>
                                    <tfoot class="bg-red text-sm">
                                        <tr>
                                            <td>Total</td>
                                            <td><?php echo $WelCr; ?></td>
                                            <td><?php echo $WelDr; ?></td>
                                            <td><?php echo $ComCr; ?></td>
                                            <td><?php echo $ComDr; ?></td>
                                            <td><?php echo $PerCr; ?></td>
                                            <td><?php echo $PerDr; ?></td>
                                            <td><?php echo $SpeCr; ?></td>
                                            <td><?php echo $SpeDr; ?></td>
                                            <td><?php echo $PenCr; ?></td>
                                            <td><?php echo $PenDr; ?></td>
                                            <td><?php echo $EFCr; ?></td>
                                            <td><?php echo $EFDr; ?></td>
                                            <td><?php echo $LIc; ?></td>
											<td><?php echo $AGI;?></td>
                                            <td><?php echo $Passbook; ?></td>
                                            <td><?php echo $Cheq; ?></td>
                                            <td><?php echo $Servch; ?></td>
                                            <td><?php echo $Other; ?></td>
                                            <td><?php echo $GenCr; ?></td>
                                            <td><?php echo $GenDr; ?></td>
                                            <td><?php echo $GenInt; ?></td>
                                            <td><?php echo $EmeDr; ?></td>
                                            <td><?php echo $EmeCr; ?></td>
                                            <td><?php echo $EmeInt; ?></td>
                                            <td><?php echo $HouDr; ?></td>
                                            <td><?php echo $HouCr; ?></td>
                                            <td><?php echo $HouInt; ?></td>
                                            
                                            <td><?php echo $engdr; ?></td>
                                            <td><?php echo $engcr; ?></td>
                                            <td><?php echo $engint; ?></td>
                                            
                                            <td><?php echo $DseDr; ?></td>
                                            <td><?php echo $DseCr; ?></td>
                                            <td><?php echo $DseInt; ?></td>
                                            <td><?php echo $EduDr; ?></td>
                                            <td><?php echo $EduCr; ?></td>
                                            <td><?php echo $EduInt; ?></td>
                                            <td><?php echo $AGIDr; ?></td>
                                            <td><?php echo $AGICr; ?></td>
                                            <td><?php echo $AGIInt; ?></td>
                                            <td><?php echo $tdisbursed; ?></td>
                                            <td><?php echo $treceipt; ?></td>
											<td><?php echo $cashsw; ?></td>
                                            <td><?php echo $twithdraw; ?></td>
                                            
                                            <td><?php echo $netamt; ?></td>
                                        </tr>
                                    </tfoot>
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
<script>
    $('#daybookss').DataTable({
        scrollX: true,
        scrollY: "300px",
        scrollCollapse: true,
        paging: false,
        fixedColumns: {
            leftColumns: 1,
        },
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                filename: 'Daybook',
                title: 'Jeevan Bikas Samaj',
                messageTop: ' <?php if (isset($_POST['search'])) {
    echo $branchName . ' ( ' . $_POST['date1'] . ' ) - Daybook';
} else {
    echo $branchName . ' ( ' . $cdate . ' ) - Daybook';
} ?>',
            },
            {
                extend: 'pdf',
                filename: 'Daybook',
                title: 'Jeevan Bikas Samaj',
                messageTop: ' <?php if (isset($_POST['search'])) {
    echo $branchName . ' ( ' . $_POST['date1'] . ' ) - Daybook';
} else {
    echo $branchName . ' ( ' . $cdate . ' ) - Daybook';
} ?>',
            },
            {
                extend: 'print',
                filename: 'Daybook',
                title: 'Jeevan Bikas Samaj',
                messageTop: ' <?php if (isset($_POST['search'])) {
    echo '<h5 class="text-bold text-center">' . $branchName . '<br/> ( Daybook - ' . $_POST['date1'] . ' ) </h5>';
} else {
    echo '<h5 class="text-bold text-center">' . $branchName . '<br/> ( Daybook ' . $cdate . ' )</h5>';
} ?>',
                customize: function (win) {
                    $(win.document.body)
                            .css({
                                'font-size': '10pt',
                                'text-align': 'center'
                            })
                            .prepend(
                                    '<img src="http://datatables.net/media/images/logo-fade.png" style="position:absolute; top:0; left:0;" />'
                                    );
                    $(win.document.body).find('table')
                            .addClass('display')
                            .css({
                                'padding': '5pt',
                                'font-size': '10pt',
                                'margin': '1px'
                            });
                }

            }
        ]
    });


</script>

