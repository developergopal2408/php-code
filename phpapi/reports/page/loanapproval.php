<?php
/*
  select m.membercode,m.firstname+' '+ m. Lastname as MemberName,m.SpouseFather,t.loantype,h.loanheading,a.LoanNo,(d.savedate)DemandDate,
  a.DemandLoan,max(analyzedDate)AnalysisDate,sum(a.IBusiness+a.Ijob+a.iWages+a.IFarming+a.Iother)YrIncome,sum(a.EBusiness+a.EHealth+a.EFood+a.ERepair+a.Eother)YrExp,
  (select code from staffmain where a.userid=staffid and branchid=a.officeid)Submited,''Signature,''ApploanAmt,''Signature
  from member m, analysisloan a,loantype t,loanheading h,DemandLoan d
  where m.memberid=a.memberid and m.officeid=a.officeid and t.loantypeid=a.loantypeid and h.loanheadingid=a.loanheadingid and d.demandloanid=a.demandloanid
  and d.savedate between '2074/08/01' and '2074/08/11' and t.loantypeid<>2 and a.analysisloanid not in(select analysisloanid from loanmain where officeid=a.officeid)
  and d.officeid=4
  group by m.membercode,m.firstname,m. Lastname ,m.SpouseFather,t.loantype,h.loanheading,a.LoanNo,a.DemandLoan,a.analyzedDate,d.savedate
  ,d.memberid,d.officeid,a.userid,a.officeid
  order by m.membercode
 */

include_once 'top.php';
include_once 'header.php';

?>
<style>
    @media print {
        body {
            font: 10pt Georgia, "Times New Roman", Times, serif;
            line-height: 1;
            margin:0px;
            
        }
       page {size: A4 landscape;max-height:100%; max-width:100% }

    }
</style>

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

                <small>Loan Approval</small>
            </h1>


            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>

                <li class="active">Loan Approval</li>
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
                                                echo $sdate;
                                            }
                                            ?>">
                                        </div>

                                        <div class="col-sm-2">
                                            <input maxlength="10" type="text" name="date2" id="date2" class=" nepali-calendar form-control" placeholder="Select Date" value="<?php
                                            if (isset($_POST['date2'])) {
                                                echo $_POST['date2'];
                                            } else {
                                                echo $cdate;
                                            }
                                            ?>">
                                        </div>
										<?php
										if($_SESSION['BranchID'] == 1){
										?>
                                        <div class="col-sm-3">
                                            <select name="id" id="id" class="form-control select2" >
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
										<?php
										}
										?>

                                        <div class="col-sm-2">
                                            <button type="submit" name="search" id="search-btn" class=" btn btn-flat bg-green"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                </form>
                                <!-- /.search form -->

                                

                            </div>
                        </div>

                        <div class="box-body">
                            <div class="box-title with-header text-bold text-center">
                               <?php
                            $bname = "";
                            if (isset($_POST['search'])) {
                                $query = "SELECT ID,Name FROM OfficeDetail  WHERE  ID = '" . $_POST['id'] . "'";
                                $sub = sqlsrv_query($connection, $query);
                                $p = sqlsrv_fetch_array($sub);
                                $bname = $p['Name'];
                                if ($_POST['id']) {
                                    echo "<h5 class='text-bold text-center'>" . $bname . " ( " . $_POST['date1'] . " - " . $_POST['date2'] . " )</h5>";
                                } else {
                                    echo "<h5 class='text-bold text-center'>" . $branchName . "( " . $_POST['date1'] . " - " . $_POST['date2'] . " )</h5>";
                                }
                            }
                            ?>
                            </div>
                            <table id="loanl" class="table table-bordered stripe row-border order-column display" cellspacing="0" > 
                                <thead class="bg-red text-sm" >
                                    <tr>
										
                                        <th>MCode</th>
                                        <th>MNAME</th>
										<th>Name</th>
                                        <th>Father</th>
										<th>GFatherName</th>
                                        <th>Loantype</th>
                                        <th>Loanheading</th>
										<th>M.Age</th>
                                        <th>LoanNo</th>
                                        <th>DemandDate</th>
                                        <th>DemandLoan</th>
                                        <th>AnalysisDate</th>
                                        <th>NetCash</th>
                                        <th>NetWorth</th>
                                        <th>Submitted By</th>
                                        <th>Signature</th>
                                        <th>ApploanAmt</th>
										<th>Approved By</th>
                                        <th>Signature</th>


                                    </tr>
                                </thead>

                                <?php
								
								if($_SESSION['BranchID'] == 1){
                                    $id = "";
                                }else{
                                    $id = "and d.officeid='" . $_SESSION['BranchID'] . "'";
                                }
								
                                if (empty($_POST)) {

                                    $qry = "select (select Name from officedetail where id = a.officeid)Name,m.membercode,m.DOB,m.firstname+' '+ m. Lastname as MemberName,m.FatherName,m.GrandFatherName,t.loantype,h.loanheading,a.LoanNo,(d.savedate)DemandDate,
                                            a.DemandLoan,max(analyzedDate)AnalysisDate,a.NetCash,a.NetWorth,
                                            (select code from staffmain where d.userid=staffid and branchid=d.officeid)Submited,(select code from staffmain where a.ApprovedBy=staffid and branchid=a.officeid)ApprovedBy,''Signature,''ApploanAmt,''Signature
                                            from member m, analysisloan a,loantype t,loanheading h,DemandLoan d
                                            where m.memberid=a.memberid  and m.officeid=a.officeid and m.officeid=d.officeid and t.loantypeid=a.loantypeid and h.loanheadingid=a.loanheadingid and d.demandloanid=a.demandloanid
                                            and d.savedate between '$sdate' and '$cdate' and t.loantypeid<>2 and a.analysisloanid not in(select analysisloanid from loanmain where officeid=a.officeid)
                                            $id 
                                            group by m.membercode,m.firstname,m. Lastname ,m.DOB,m.FatherName,m.GrandFatherName,t.loantype,h.loanheading,a.LoanNo,a.DemandLoan,a.analyzedDate,d.savedate,a.ApprovedBy
                                            ,d.memberid,d.officeid,d.userid,a.officeid,a.NetCash,a.NetWorth
                                            order by m.membercode";
                                    $result = odbc_exec($connection, $qry);
									
									  
                                    $demand = $net = $nw = 0;
                                    ?>
                                    <tbody class="text-sm">
                                        <?php
                                        if (odbc_num_rows($result) > 0) {
                                            while ($res = odbc_fetch_array($result)) {
												$dob = $res['DOB'];
											  list($yr1, $mn1, $dy1) = explode("/", $cdate);
											  $npdate = $cal->nep_to_eng($yr1, $mn1, $dy1);
											  $yr = $npdate['year'];
											  $mn = $npdate['month'];
											  $dy = $npdate['date'];
											  $fdate = $yr . "/" . $mn . "/" . $dy;
											  list($yr2, $mn2, $dy2) = explode("/", $dob);
											  $npdates = $cal->nep_to_eng($yr2, $mn2, $dy2);
											  $yrs = $npdates['year'];
											  $mns = $npdates['month'];
											  $dys = $npdates['date'];
											  $tdate = $yrs . "/" . $mns . "/" . $dys;
											  $start = strtotime($fdate);
											  $end = strtotime($tdate);
											  $diff = ceil(abs($start - $end) / 86400);
											  //print_r($diff);
											  $age = ceil(abs($diff/365));
												
                                                $demand += $res['DemandLoan'];
                                                $net += $res['NetCash'];
                                                $nw += $res['NetWorth'];
                                                ?>
                                                <tr class="text-sm">
													
                                                    <td><?php echo $res['membercode']; ?></td>
                                                    <td><?php echo $res['MemberName']; ?></td>
													<td><?php echo str_ireplace("Branch Office"," ",$res['Name']);  ?></td>
                                                    <td><?php echo $res['FatherName']; ?></td>
													<td><?php echo $res['GrandFatherName']; ?></td>
                                                    <td><?php echo $res['loantype']; ?></td>
                                                    <td><?php echo $res['loanheading']; ?></td>
													<td><?php echo $age; ?></td>
                                                    <td><?php echo $res['LoanNo']; ?></td>
                                                    <td><?php echo $res['DemandDate']; ?></td>
                                                    <td><?php echo number_format($res['DemandLoan'], 2); ?></td>
                                                    <td><?php echo $res['AnalysisDate']; ?></td>
                                                    <td><?php echo number_format($res['NetCash'], 2); ?></td>
                                                    <td><?php echo number_format($res['NetWorth'], 2); ?></td>
                                                    <td><?php echo $res['Submited']; ?></td>
                                                    <td><?php echo $res['Signature']; ?></td>
                                                    <td><?php echo $res['ApploanAmt']; ?></td>
													<td><?php echo $res['ApprovedBy']; ?></td>
                                                    <td><?php echo $res['Signature']; ?></td>

                                                </tr>
                                                <?php
                                            }
                                            ?>
                                        </tbody>
                                        <tfoot class="text-sm bg-red">
                                            <tr>
                                                <td colspan=10>Total</td>
                                                <td><?php echo number_format($demand, 2); ?></td>
												<td></td>
                                                <td><?php echo number_format($net, 2); ?></td>
                                                <td><?php echo number_format($nw, 2); ?></td>
                                                <td colspan=5></td>
                                            </tr>
                                        </tfoot>
                                        <?php
                                    }
                                } else if (isset($_POST['search'])) {
									
                                    $demand = $net = $nw = 0;
                                    $ID = $_POST['id'];
                                    $date1 = $_POST['date1'];
                                    $date2 = $_POST['date2'];
									
									if($_SESSION['BranchID'] == 1 and empty($ID)){
										$idx = "";
										$changedate = "and d.savedate between '$date1' and '$date2'"; 
									}else if($_SESSION['BranchID'] == 1){
										$idx = "and d.officeid='$ID'";
										$changedate = "and d.savedate between '$date1' and '$date2'"; 
									}else{
										$idx = "and d.officeid='" . $_SESSION['BranchID'] . "'";
										$changedate = "and d.savedate between '$date1' and '$date2'"; 
									}
									
                                    $qry = "select (select Name from officedetail where id = a.officeid)Name,m.membercode,m.DOB,m.firstname+' '+ m. Lastname as MemberName,m.FatherName,m.GrandFatherName,t.loantype,h.loanheading,a.LoanNo,(d.savedate)DemandDate,
                                            a.DemandLoan,max(analyzedDate)AnalysisDate,a.NetCash,a.NetWorth,
                                            (select code from staffmain where d.userid=staffid and branchid=d.officeid)Submited,(select code from staffmain where a.ApprovedBy=staffid and branchid=a.officeid)ApprovedBy,''Signature,''ApploanAmt,''Signature
                                            from member m, analysisloan a,loantype t,loanheading h,DemandLoan d
                                            where m.memberid=a.memberid and m.officeid=a.officeid and m.officeid=d.officeid and t.loantypeid=a.loantypeid and h.loanheadingid=a.loanheadingid and d.demandloanid=a.demandloanid
                                            $changedate and t.loantypeid<>2 and a.analysisloanid not in(select analysisloanid from loanmain where officeid=a.officeid)
                                            $idx
                                            group by m.membercode,m.firstname,m.DOB,m. Lastname ,m.FatherName,m.GrandFatherName,t.loantype,h.loanheading,a.LoanNo,a.DemandLoan,a.analyzedDate,d.savedate,a.ApprovedBy
                                            ,d.memberid,d.officeid,d.userid,a.officeid,a.NetCash,a.NetWorth
                                            order by m.membercode";
                                    $result = odbc_exec($connection, $qry);
                                    ?>
                                    <tbody class="text-sm">
                                        <?php
                                        while ($res = odbc_fetch_array($result)) {
                                            $demand += $res['DemandLoan'];
                                            $net += $res['NetCash'];
                                            $nw += $res['NetWorth'];
											
											$dob = $res['DOB'];
											  list($yr1, $mn1, $dy1) = explode("/", $cdate);
											  $npdate = $cal->nep_to_eng($yr1, $mn1, $dy1);
											  $yr = $npdate['year'];
											  $mn = $npdate['month'];
											  $dy = $npdate['date'];
											  $fdate = $yr . "/" . $mn . "/" . $dy;
											  list($yr2, $mn2, $dy2) = explode("/", $dob);
											  $npdates = $cal->nep_to_eng($yr2, $mn2, $dy2);
											  $yrs = $npdates['year'];
											  $mns = $npdates['month'];
											  $dys = $npdates['date'];
											  $tdate = $yrs . "/" . $mns . "/" . $dys;
											  $start = strtotime($fdate);
											  $end = strtotime($tdate);
											  $diff = ceil(abs($start - $end) / 86400);
											  //print_r($diff);
											  $age = ceil(abs($diff/365));
											
                                            ?>
                                            <tr class="text-sm">
											
                                                <td><?php echo $res['membercode']; ?></td>
                                                <td><?php echo $res['MemberName']; ?></td>
												<td><?php echo str_ireplace("Branch Office"," ",$res['Name']); ?></td>
                                                <td><?php echo $res['FatherName']; ?></td>
												<td><?php echo $res['GrandFatherName']; ?></td>
                                                <td><?php echo $res['loantype']; ?></td>
                                                <td><?php echo $res['loanheading']; ?></td>
												<td><?php echo $age; ?></td>
                                                <td><?php echo $res['LoanNo']; ?></td>
                                                <td><?php echo $res['DemandDate']; ?></td>
                                                <td><?php echo number_format($res['DemandLoan'], 2); ?></td>
                                                <td><?php echo $res['AnalysisDate']; ?></td>
                                                <td><?php echo number_format($res['NetCash'], 2); ?></td>
                                                <td><?php echo number_format($res['NetWorth'], 2); ?></td>
                                                <td><?php echo $res['Submited']; ?></td>
                                                <td><?php echo $res['Signature']; ?></td>
                                                <td><?php echo $res['ApploanAmt']; ?></td>
												<td><?php echo $res['ApprovedBy']; ?></td>
                                                <td><?php echo $res['Signature']; ?></td>
                                            </tr>																				
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                    <tfoot class="text-sm bg-red">
                                        <tr>
                                            <td colspan=10>Total</td>
                                            <td><?php echo number_format($demand, 2); ?></td>
											<td></td>
                                            <td><?php echo number_format($net, 2); ?></td>
                                            <td><?php echo number_format($nw, 2); ?></td>
                                            <td colspan=5></td>
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

<script>
    $('#loanl').removeAttr('width').DataTable({
        scrollX: true,
        scrollY: "300px",
        scrollCollapse: true,
        paging: false,
        dom: 'Bfrtip',
        columnDefs: [
            {width: 150, targets: [1, 2, 3, 4]}
			
        ],
        fixedColumns: true,
        buttons: [
            {
                extend: 'excel',
                filename: 'Loan Approval',
                title: 'Jeevan Bikas Samaj',
                messageTop: ' <?php
if (isset($_POST['search'])) {
    if ($_SESSION['BranchID'] > 1) {
        echo $branchName . ' ( ' . $_POST['date1'] . ' - ' . $_POST['date2'] . ') - Loan Approval';
    } else {
        echo $bname . ' ( ' . $_POST['date1'] . ' - ' . $_POST['date2'] . ') - Loan Approval';
    }
} else {
    echo $branchName . ' ( ' . $cdate . ' ) - Loan Approval';
}
?>',
            },
            {
                extend: 'print',
                filename: 'Loan Approval',
                title: 'Jeevan Bikas Samaj',
                messageTop: ' <?php
if (isset($_POST['search'])) {
    if ($_SESSION['BranchID'] > 1) {
        echo '<h5 class="text-bold text-center">' . $branchName . '<br/> ( Loan Approval - ' . $_POST['date1'] . ' - ' . $_POST['date2'] . ' ) </h5>';
    } else {
        echo '<h5 class="text-bold text-center">' . $bname . '<br/> ( Loan Approval - ' . $_POST['date1'] . ' - ' . $_POST['date2'] . ' ) </h5>';
    }
} else {
    echo '<h5 class="text-bold text-center">' . $branchName . '<br/> ( Loan Approval ' . $cdate . '  )</h5>';
}
?>',
                customize: function (win) {
                    $(win.document.body)
                            .css({
                                'font-size': '7pt',
                                'text-align': 'center'
                            })
                            .prepend(
                                    '<img src="http://datatables.net/media/images/logo-fade.png" style="position:absolute; top:0; left:0;" />'
                                    );
                    $(win.document.body).find( 'table' )
                        .addClass( 'compact' )
                        .css( 'font-size', 'inherit' );
                }

            }
        ]
    });


</script>
