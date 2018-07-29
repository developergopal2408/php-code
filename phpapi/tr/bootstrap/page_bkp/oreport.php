<?php
include_once 'top.php';
include_once 'header.php';

/* function NoOfDays($currentdate, $prevdate) {
  list($yr1, $mn1, $dy1) = explode("/", $currentdate);
  $npdate = $cal->nep_to_eng($yr1, $mn1, $dy1);
  $yr = $npdate['year'];
  $mn = $npdate['month'];
  $dy = $npdate['date'];
  $fdate = $yr . "/" . $mn . "/" . $dy;
  list($yr2, $mn2, $dy2) = explode("/", $prevdate);
  $npdates = $cal->nep_to_eng($yr2, $mn2, $dy2);
  $yrs = $npdates['year'];
  $mns = $npdates['month'];
  $dys = $npdates['date'];
  $tdate = $yrs . "-" . $mns . "-" . $dys;
  $start = strtotime($fdate);
  $end = strtotime($tdate);
  $diff = ceil(abs($start - $end) / 86400);
  print_r($diff);
  } */
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
                <i class="fa fa-dashboard"></i> <?php echo $branchName; ?>
                <small>Overdue & Ageing Report</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Dashboard</li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
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
                                        <select name="id" id="id" class="form-control select2" required>
                                            <option value="">Select Branch</option>
                                            <?php
                                            $sql1 = "SELECT * FROM OfficeDetail ORDER BY ID ASC ";
                                            $result = odbc_exec($connection, $sql1);
                                            while ($rows = odbc_fetch_array($result)) {
                                                ?>
                                                <option value="<?php echo $rows['ID']; ?>" <?php
                                                if ($rows['ID'] == $_POST['id']) {
                                                    echo "selected";
                                                }
                                                ?>><?php echo $rows['Code'] . " - " . $rows['Name']; ?></option>;
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
                        <div class="pull-right">
                            <a href="oreport.php"  class=" btn btn-flat bg-blue" title="Refresh"><i class="fa fa-refresh"></i></a>
                        </div>
                    </div>
                </div>
                <div class="box-body">
                    <table id="od" class="table table-condensed table-bordered display">
                        <thead class="bg-red text-sm">
                            <tr>

                                <th>BorrowerName</th>
                                <th>Mcode</th>
                                <th>LType</th>
                                <th>LHeading</th>
                                <th>ODate B.S</th>
                                <th colspan="4" class="text-center">OverdueAmt</th>
                                <th>Par Amt</th>
                                <th>Age CC</th>

                            </tr>
                            <tr>
                                <th colspan="5"></th>
                                <th>No. Install</th>
                                <th>Principal</th>
                                <th>int</th>
                                <th>Total</th>
                                <th colspan="2"></th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            <?php
                            $date1 = $_POST['date1'];
                            $id = $_POST['id'];
                            if (isset($_POST['search'])) {
                                $qry = "select MemberID,LoanMainID,LoanTypeID,LoanHeadingID,OfficeID,min(savedate)date from loandetail where (pridue>0 or IntDue>0)
                                and officeid = '$id' and savedate<='$date1'
                                group by MemberID,LoanMainID,OfficeID,LoanTypeID,LoanHeadingID
                                order by OfficeID, MemberID";
                            }
                            $result = odbc_exec($connection, $qry);
                            while ($row = odbc_fetch_array($result)) {
                                $mid = $row['MemberID'];
                                $lid = $row['LoanMainID'];
                                $ltid = $row['LoanTypeID'];
                                $lhid = $row['LoanHeadingID'];
                                $ldate = $row['date'];
                                $sql = odbc_exec($connection, "select * from member where officeid = '$id' and memberid = '$mid'");
                                $mrow = odbc_fetch_array($sql);
                                $mname = $mrow['FirstName'] . " " . $mrow['LastName'];
                                $mcode = $mrow['MemberCode'];
                                $sql1 = odbc_exec($connection, "select LoanType from LoanType where LoanTypeID = '$ltid'");
                                $lrow = odbc_fetch_array($sql1);
                                $LoanType = $lrow['LoanType'];
                                $sql2 = odbc_exec($connection, "select LoanHeading from LoanHeading where LoanHeadingID = '$lhid'");
                                $lhrow = odbc_fetch_array($sql2);
                                $LoanHeading = $lhrow['LoanHeading'];
                                $sql3 = odbc_exec($connection, "SELECT * FROM LoanDetail WHERE 
                                                                SaveDate =(SELECT Max (SaveDate) FROM LoanDetail WHERE OfficeID='$id'
                                                                AND LoanMainID='$lid'
                                                                AND SaveDate<='$date1')
                                                                AND LoanMainID='$lid'
                                                               AND OfficeID='$id' AND (pridue>0 OR IntDue>0)");
                                $ldrow = odbc_fetch_array($sql3);
                                $PriDue = $ldrow['PriDue'];
                                $IntDue = $ldrow['IntDue'];
                                $total = $PriDue + $IntDue;

                                $sql4 = odbc_exec($connection, "SELECT sum(loandr-loancr)par FROM LoanDetail WHERE 
                                                                LoanMainID='$lid' AND OfficeID='$id' AND savedate <= '$date1'");
                                $par = odbc_fetch_array($sql4);
                                $sql5 = odbc_exec($connection, "SELECT count(*)instno FROM LoanDetail WHERE (pridue>0 OR IntDue>0)
                                                                AND LoanMainID='$lid' AND OfficeID='$id' AND savedate <= '$date1'");
                                $intno = odbc_fetch_array($sql5);



                                list($yr1, $mn1, $dy1) = explode("/", $cdate);
                                $npdate = $cal->nep_to_eng($yr1, $mn1, $dy1);
                                $yr = $npdate['year'];
                                $mn = $npdate['month'];
                                $dy = $npdate['date'];
                                $fdate = $yr . "/" . $mn . "/" . $dy;
                                list($yr2, $mn2, $dy2) = explode("/", $ldate);
                                $npdates = $cal->nep_to_eng($yr2, $mn2, $dy2);
                                $yrs = $npdates['year'];
                                $mns = $npdates['month'];
                                $dys = $npdates['date'];
                                $tdate = $yrs . "-" . $mns . "-" . $dys;
                                $start = strtotime($fdate);
                                $end = strtotime($tdate);
                                $age = ceil(abs($end - $start) / 86400);
                                ?>

                                <tr>
                                    <td><?php echo $mname; ?></td>
                                    <td><?php echo $mcode; ?></td>
                                    <td><?php echo $LoanType; ?></td>
                                    <td><?php echo $LoanHeading; ?></td>
                                    <td><?php echo $ldate; ?></td>
                                    <td><?php echo $intno['instno']; ?></td>
                                    <td><?php echo number_format($PriDue, 2); ?></td>
                                    <td><?php echo number_format($IntDue, 2); ?></td>
                                    <td><?php echo number_format($total, 2); ?></td>
                                    <td><?php echo number_format($par['par'], 2); ?></td>
                                    <td><?php echo $age; ?></td>

                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
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
    $('#od').DataTable({
        //scrollX: true,
        scrollY: "300px",
        scrollCollapse: true,
        paging: false,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                title: 'Jeevan Bikas Samaj',
                messageTop: 'Over Due Report - ' + $("#date1").val(),
            },
            {
                extend: 'print',
                title: 'Jeevan Bikas Samaj',
                messageTop: 'Over Due Report - ' + $("#date1").val(),
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
