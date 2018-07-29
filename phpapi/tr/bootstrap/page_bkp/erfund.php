<?php
include_once 'top.php';
include_once 'header.php';
?>
<div class="wrapper">
    <?php
    include_once 'sidebar_header.php';
    include_once 'sidebar.php';
    ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                <i class="fa fa-building"></i> <?php echo $branchName; ?>
                <small>Emergency Relief Fund</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Emergency Relief Fund</li>
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
                                        <div class="col-sm-2">
                                            <button type="submit" name="search" id="search-btn" class=" btn btn-flat bg-green"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                </form>
                                <!-- /.search form -->
                                <div class="box-tools pull-right" >
                                    <a href="erfund.php"  class=" btn btn-flat bg-blue" title="Refresh"><i class="fa fa-refresh"></i></a>
                                </div>

                            </div>
                        </div>

                        <div class="box-body">
                            <?php
                            if (isset($_POST['search'])) {
                                echo "<h5 class='text-bold text-center'>Emergency Relief Fund - " . $branchName . "( " . $_POST['date1'] . " - " . $_POST['date2'] . "  )</h5>";
                            }
                            ?>
                            <table id="er" > 
                                <thead class="bg-red text-sm">
                                    <tr>
                                        <th>Code</th>
                                        <th>Name</th>
                                        <th>MemberCode</th>
                                        <th>MemberName</th>
                                        <th>SaveDate</th>
                                        <th>InsuaranceType</th>
                                        <th>CattleInsurance</th>
                                        <th>InsuranceHead</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    <?php
                                    $date2 = $_POST['date2'];
                                    $date1 = $_POST['date1'];
                                    $total = 0.0;
                                    if ($_SESSION['BranchID'] == 1) {
                                        $idx = "";
                                    } else {
                                        $idx = "and d.officeid = '" . $_SESSION['BranchID'] . "'";
                                    }
                                    if (isset($_POST['search'])) {
                                        $qry = "select o.Code,o.Name,M.MemberCode,M.Firstname+' '+m.Lastname as MemberName ,d.savedate,T.insurancetype,
                                                (d.dramount)Cattleinsurance,h.InsuranceHead
                                                from officedetail o, member m, insurancetype t, insurancedetail d,insurancehead h
                                                where o.id=m.officeid and o.id=d.officeid $idx and m.memberid=d.memberid and t.insurancetypeid=d.insurancetypeid 
                                                and d.insurancetypeid=1 and d.savedate between '$date1' and '$date2' and d.dramount>0 
                                                and h.id=d.insuranceheadid
                                                group by o.code,o.name,m.firstname,m.lastname,d.savedate,d.dramount,m.membercode,T.insurancetype,h.InsuranceHead
                                                order by o.code,m.membercode";
                                    }
                                        $result = odbc_exec($connection, $qry);
                                        while ($res = odbc_fetch_array($result)) {
                                            $total += $res['Cattleinsurance'];
                                            ?>
                                            <tr>
                                                <td><?php echo $res['Code']; ?></td>
                                                <td><?php echo $res['Name']; ?></td>
                                                <td><?php echo $res['MemberCode']; ?></td>
                                                <td><?php echo $res['MemberName']; ?></td>
                                                <td><?php echo $res['savedate']; ?></td>
                                                <td><?php echo $res['insurancetype']; ?></td>
                                                <td><?php echo $res['Cattleinsurance']; ?></td>
                                                <td><?php echo $res['InsuranceHead']; ?></td>
                                            </tr>
                                            <?php
                                        }
                                    ?>
                                </tbody>
                                <tfoot>

                                    <tr class="bg-red text-sm">
                                        <td colspan="6">Total</td>
                                        <td><?php echo $total; ?></td>
                                        <td></td>
                                    </tr>

                                </tfoot>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </section>
        <!--/.content -->
    </div>
    <!--/.content-wrapper -->
    <?php
    include_once 'copyright.php';
    ?>
</div>
<!-- ./wrapper -->
<?php
include_once 'footer.php';
?>

<script>
    $('#er').DataTable({
        //scrollX: true,
        scrollY: "300px",
        scrollCollapse: true,
        paging: false,
        dom: 'Bfrtip',
        fixedColumns: {
            leftColumns: 1,
        },
        buttons: [
            {
                extend: 'excel',
                filename: 'Emergency Relief Fund',
                title: 'Jeevan Bikas Samaj',
                messageTop: ' <?php
if (isset($_POST['search'])) {
    if ($_SESSION['BranchID'] > 1) {
        echo $branchName . ' ( ' . $_POST['date1'] . ' - ' . $_POST['date2'] . ') - Emergency Relief Fund';
    } else {
        echo $bname . ' ( ' . $_POST['date1'] . ' - ' . $_POST['date2'] . ') - Emergency Relief Fund';
    }
} else {
    echo $branchName . ' ( ' . $cdate . ' ) - Emergency Relief Fund';
}
?>',
            },
            {
                extend: 'print',
                filename: 'Emergency Relief Fund',
                title: 'Jeevan Bikas Samaj',
                messageTop: ' <?php
if (isset($_POST['search'])) {
    if ($_SESSION['BranchID'] > 1) {
        echo '<h5 class="text-bold text-center">' . $branchName . '<br/> ( Emergency Relief Fund - ' . $_POST['date1'] . ' - ' . $_POST['date2'] . ' ) </h5>';
    } else {
        echo '<h5 class="text-bold text-center">' . $bname . '<br/> ( Emergency Relief Fund - ' . $_POST['date1'] . ' - ' . $_POST['date2'] . ' ) </h5>';
    }
} else {
    echo '<h5 class="text-bold text-center">' . $branchName . '<br/> ( Emergency Relief Fund ' . $cdate . '  )</h5>';
}
?>',
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
