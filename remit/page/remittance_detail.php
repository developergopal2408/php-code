<?php
include_once 'top.php';
include_once 'header.php';
?>
<style>
    #row:hover{
        background:#0063dc;
        color:#fff;
    }
</style>
<!-- Site wrapper -->
<div class="wrapper">
    <?php
    include_once 'sidebar_header.php';
    include_once 'sidebar.php';
    ?>
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-dashboard"></i> Remittance Detail
                <small>Remittance Detail</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Remittance Detail</li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">

            <div class="box box-solid">
                <div class="box-header with-border">
                    <div class="col-sm-12">
                        <!-- search form -->
                        <form  action="" method="post" class="form-horizontal" >
                            <div class=" form-group-sm">
                                <div class="col-sm-2">
                                    <input maxlength="10" type="text" name="fdate" id="fdate" class=" nepali-calendar form-control" placeholder="From Date" 
                                           value="<?php
                                           if (isset($_POST['fdate'])) {
                                               echo $_POST['fdate'];
                                           } else {
                                               echo "";
                                           }
                                           ?>" 
                                           >
                                </div>
                                <div class="col-sm-2">
                                    <input maxlength="10" type="text" name="tdate" id="tdate" class=" nepali-calendar form-control" placeholder="To Date" 
                                           value="<?php
                                           if (isset($_POST['tdate'])) {
                                               echo $_POST['tdate'];
                                           } else {
                                               echo "";
                                           }
                                           ?>" 
                                           >
                                </div>

                                <div class="col-sm-2">
                                    <button type="submit" name="search" id="search-btn" class=" btn btn-flat bg-green"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </form>
                        <!-- /.search form -->
                        <div class="box-tools pull-right">
                            <span class="pull-right"><?php
                                if (isset($_POST['search'])) {
                                    echo "<span class='text-red'>You Have search between " . $_POST['fdate'] . " and " . $_POST['tdate'] . "</span>";
                                    //print_r($fdate);
                                }
                                ?></span>
                        </div>
                    </div>
                </div>
                <div class="box-body">
                    <table id="remitdetail" class="table table-striped table-condensed" >
                        <thead class="text-sm  bg-red" style="width: auto;">
                            <tr>
                                <th>TDATE</th>
                                <th>RemitNo</th>
                                <th>RCompany</th>
                                <th>FrBranch</th>
                                <th>RName</th>
                                <!--<th>IDTYPE</th>
                                <th>IDNO</th>
                                <th>DOB</th>
                                <th>RecNo</th>-->
                                <th>SenderName</th>
                                <!--<th>Country</th>
                                <th>SRelation</th>-->
                                <th>ExpAmount</th>
                                <th>PDATE</th>
                                <th>PAMOUNT</th>
                                <th>Status</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            include_once 'db2.php';
                            $branchCode = $_SESSION['Code'];
                            //print $branchCode;
                            if (empty($_POST)) {
                                if ($branchid == '1') {
                                    $query = "SELECT * FROM remittance WHERE TDATE = '" . date('Y-m-d') . "' OR STATUS BETWEEN 'PENDING' AND 'REJECTED' ";
                                    $run = mysqli_query($conn, $query);
                                } else {
                                    $query = "SELECT * FROM remittance WHERE BRANCHNAME = '$branchName' ORDER BY TDATE desc";
                                    $run = mysqli_query($conn, $query);
                                }
                            } else if (isset($_POST['search'])) {
                                $date1 = $_POST['fdate'];
                                if ($date1 == true) {
                                    $date1 = $_POST['fdate'];
                                } else {
                                    $date1 = '0000-00-00';
                                }
                                list($yr1, $mn1, $dy1) = explode("-", $date1);
                                $npdate = $cal->nep_to_eng($yr1, $mn1, $dy1);
                                $yr = $npdate['year'];
                                $mn = $npdate['month'];
                                $dy = $npdate['date'];
                                $fdate = $yr . "-" . $mn . "-" . $dy;
                                //print_r($fdate);
                                $date2 = $_POST['tdate'];
                                if ($date2 == true) {
                                    $date2 = $_POST['tdate'];
                                } else {
                                    $date2 = '0000-00-00';
                                }
                                list($yr2, $mn2, $dy2) = explode("-", $date2);
                                $npdates = $cal->nep_to_eng($yr2, $mn2, $dy2);
                                $yrs = $npdates['year'];
                                $mns = $npdates['month'];
                                $dys = $npdates['date'];
                                $tdate = $yrs . "-" . $mns . "-" . $dys;
                                //print_r($tdate);
                                if ($branchid == 1) {
                                    $query = "SELECT * FROM remittance WHERE TDATE BETWEEN '$fdate' AND '$tdate'";
                                    $run = mysqli_query($conn, $query);
                                } else {
                                    $query = "SELECT * FROM remittance WHERE BRANCHNAME = '$branchName' AND TDATE BETWEEN '$fdate' AND '$tdate' ";
                                    $run = mysqli_query($conn, $query);
                                }
                            }
                            while ($row = mysqli_fetch_array($run)) {

                                if ($row['PAIDDATE'] == true) {
                                    $pdate = $row['PAIDDATE'];
                                } else {
                                    $pdate = '0000-00-00';
                                }
                                list($pyear, $pmonth, $pday) = explode('-', $pdate);
                                $npdate = $cal->eng_to_nep($pyear, $pmonth, $pday);
                                $npyr = $npdate['year'];
                                $npmonth = $npdate['month'];
                                $npday = $npdate['date'];
                                $cpdate = $npyr . "-" . $npmonth . "-" . $npday;
                                $date = $row['TDATE'];
                                list($year, $month, $day) = explode('-', $date);
                                $nepdate = $cal->eng_to_nep($year, $month, $day);
                                $nyr = $nepdate['year'];
                                $nmonth = $nepdate['month'];
                                $nday = $nepdate['date'];
                                $cdate = $nyr . "-" . $nmonth . "-" . $nday;
                                ?>
                                <tr  id="row" class="clickable-row text-sm" style="cursor:pointer;" data-href="remit_view_detail.php?msg_id=<?php echo $row['MSGID']; ?>">
                                    <td><?php echo $cdate; ?></td>
                                    <td><?php echo $row['REMITNO']; ?></td>
                                    <td><?php echo $row['REMITCOMPANY']; ?></td>
                                    <td><?php echo $row['BRANCHNAME']; ?></td>
                                    <td><?php echo $row['RECEIVERNAME']; ?></td>
                                    <!--<td><?php// echo $row['RECEIVERIDTYPE']; ?></td>
                                    <td><?php //echo $row['RECEIVERIDNO']; ?></td>
                                    <td><?php //echo $row['RECEIVERDOB']; ?></td>
                                    <td><?php// echo $row['RECEIVERCONTACTNO']; ?></td>-->
                                    <td><?php echo $row['SENDERNAME']; ?></td>
                                   <!-- <td><?php// echo $row['SENDERCOUNTRY']; ?></td>
                                    <td><?php //echo $row['SENDERRELATION']; ?></td>-->
                                    <td><?php echo number_format($row['EXPECTEDAMT'],2); ?></td>
                                    <td><?php echo $cpdate; ?></td>
                                    <td><?php echo number_format($row['PAIDAMT'], 2); ?></td>
                                    <td>
                                        <?php
                                        if ($row['STATUS'] == "PENDING") {
                                            echo "<span class='label label-danger text-bold'>Pending</span>";
                                        } else if ($row['STATUS'] == "REJECTED") {
                                            echo "<span class='label label-primary'>Rejected</span>";
                                        } else {
                                            echo "<span class='label label-success'>Approved</span>";
                                        }
                                        ?>
                                    </td>
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
    $('#remitdetail').DataTable({
        "order": [[0, "desc"]],
        "scrollY": "275px",
        "paging": false,
        dom: 'Bfrtip',
        buttons: [
            {
                filename: 'Remttance Detail',
                extend: 'excel',
                text: '<i class="fa fa-file-excel-o"></i>',
                title: 'Jeevan Bikas Samaj',
                message: 'Remittance Detail',
                className: 'btn btn-primary btn-xs'
            },
            {
                filename: 'Remittance Detail',
                extend: 'print',
                text: '<i class="fa fa-print"></i>',
                title: 'Jeevan Bikas Samaj',
                message: 'Remittance Detail',
                //messageTop: 'Fund Due Detail - ' + $("#date1").val(),
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
                            .addClass('compact')
                            .css({
                                'font-size': '9pt',
                                'padding': '10pt'
                            });
                }

            }
        ]
    });


</script>