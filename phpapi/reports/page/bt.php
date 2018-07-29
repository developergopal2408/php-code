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

                <small>Branch Trial</small>
            </h1>


            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>

                <li class="active">Branch Trial</li>
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
                                            <input maxlength="10" type="text" name="date2" id="date2" class=" nepali-calendar form-control" placeholder="Select Date" value="<?php
                                            if (isset($_POST['date2'])) {
                                                echo $_POST['date2'];
                                            } else {
                                                echo $cdate;
                                            }
                                            ?>">
                                        </div>
                                        <?php
                                        if ($_SESSION['BranchID'] == 1) {
                                            ?>
                                            <div class="col-sm-3">
                                                <select name="id" id="id" class="form-control select2" required>
                                                    <option value="">Select Branch</option>
                                                    <?php
                                                    $query = "SELECT ID,Name,Code from OfficeDetail";
                                                    $sub = odbc_exec($connection, $query);
                                                    while ($p = odbc_fetch_array($sub)) {
                                                        ?>
                                                        <option value="<?php echo $p['ID']; ?>" <?php
                                                        if ($_POST['id'] == $p['ID']) {
                                                            echo "selected";
                                                        }
                                                        ?>><?php echo $p['Code'] . " - " . $p['Name']; ?></option>;
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
                            <?php
                            $bname = "";
                            if (isset($_POST['id'])) {
                                $query = "SELECT ID,Name,Code FROM OfficeDetail  WHERE  ID = '" . $_POST['id'] . "'";
                                $sub = odbc_exec($connection, $query);
                                $p = odbc_fetch_array($sub);
                                $bname = $p['Name'];
                                echo "<h5 class='text-center text-bold'>" . $bname . "</h5>";
                            } else {
                                echo "<h5 class='text-center text-bold'>" . $branchName . "</h5>";
                            }
                            ?>
                            <table id="malemember" class="table table-bordered table-striped" bordered="1"> 
                                <thead class="bg-red" >
                                    <tr>
                                        <th>LF</th>
                                        <th>NAME</th>
                                        <th>DEBIT</th>
                                        <th>CREDIT</th>

                                    </tr>
                                </thead>

                                <?php
                                $ID = $_POST['id'];
                                $date2 = $_POST['date2'];
                                if ($date2 < $f) {
                                    $fdate = $fyearn;
                                } else {
                                    $fdate = $f;
                                }
                                //echo $fdate;


                                if (isset($_POST['search']) AND $_SESSION['BranchID'] == 1) {
                                    $qry = "select a.id, a.lf,a.Name,
					case when sum(dramount-cramount)>0 then sum(dramount-cramount) end dr, 
					case when sum(cramount-dramount)>0 then sum(cramount-dramount) end cr
					from acctree a,ledger l
					where a.id=l.accountheadid and l.officeid='$ID'
					and ldate between '$fdate' and '$date2'
					group by a.lf,a.name ,a.groupid,a.id
					having sum(l.dramount-l.cramount)<>0
					order by a.lf,a.id";
                                } else if (isset($_POST['search'])) {
                                    $qry = "select a.id, a.lf,a.Name,
					case when sum(dramount-cramount)>0 then sum(dramount-cramount) end dr, 
					case when sum(cramount-dramount)>0 then sum(cramount-dramount) end cr
					from acctree a,ledger l
					where a.id=l.accountheadid and l.officeid='" . $_SESSION['BranchID'] . "'
					and ldate between '$fdate' and '$date2'
					group by a.lf,a.name ,a.groupid,a.id
					having sum(l.dramount-l.cramount)<>0
					order by a.lf,a.id";
                                }
                                $result = odbc_exec($connection, $qry);
                                $tcr = 0.0;
                                $tdr = 0.0;
                                ?>
                                <tbody>
                                    <?php
                                    while ($res = odbc_fetch_array($result)) {
                                        $tdr += $res['dr'];
                                        $tcr += $res['cr'];
                                        ?>
                                        <tr>
                                            <td><?php echo $res['lf']; ?></td>
                                            <td><?php echo $res['Name']; ?></td>
                                            <td><?php echo number_format($res['dr'], 2); ?></td>
                                            <td><?php echo number_format($res['cr'], 2); ?></td>
                                        </tr>																				
                                        <?php
                                    }
                                    ?>
                                </tbody>
                                <tfoot class="bg-red">
                                    <tr>
                                        <td colspan=2 class="text-bold">Total : </td>                                           
                                        <td class="text-bold"><?php echo number_format($tdr, 2); ?></td>
                                        <td class="text-bold"><?php echo number_format($tcr, 2); ?></td>

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

<script>
    $('#malemember').DataTable({
        scrollY: "300px",
        scrollCollapse: true,
        paging: false,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                filename: 'Trial Balance',
                title: 'Jeevan Bikas Samaj',
                messageTop: ' <?php
if (isset($_POST['search'])) {
    if ($_POST['id'] > 1) {
        echo $bname . ' ( ' . $_POST['date2'] . ') - Trial Balance';
    } else {
        echo 'All Branch ( ' . $_POST['date2'] . ') - Trial Balance';
    }
} else {
    echo $branchName . ' ( ' . $cdate . ' ) - Trial Balance';
}
?>',
            },
            {
                extend: 'print',
                filename: 'Trial Balance',
                title: 'Jeevan Bikas Samaj',
                messageTop: ' <?php
if (isset($_POST['search'])) {
    if ($_POST['id'] > 1) {
        echo '<h5 class="text-bold text-center">' . $bname . '<br/> ( Trial Balance - ' . $_POST['date2'] . ' ) </h5>';
    } else {
        echo '<h5 class="text-bold text-center">All Branch <br/> ( Trial Balance - ' . $_POST['date2'] . ' ) </h5>';
    }
} else {
    echo '<h5 class="text-bold text-center">' . $branchName . '<br/> ( Trial Balance ' . $cdate . '  )</h5>';
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
