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
                                            <input maxlength="10" type="text" name="date1" id="date1" class=" nepali-calendar form-control" placeholder="Select Date" value="<?php
                                            if (isset($_POST['date1'])) {
                                                echo $_POST['date1'];
                                            } else {
                                                echo $cdate;
                                            }
                                            ?>">
                                        </div>
                                        <?php
                                        if ($BranchID == 1) {
                                            ?>
                                            <div class="col-sm-3">
                                                <select name="oid" id="oid" class="form-control select2" >
                                                    <option value="">Select Branch</option>
                                                    <?php
                                                    $sql1 = "SELECT * FROM OfficeDetail ORDER BY ID ASC ";
                                                    $result = sqlsrv_query($connection, $sql1);

                                                    while ($rows = sqlsrv_fetch_array($result)) {
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
                                <div class="pull-right" >
                                    <a href="branchtrial.php"  class=" btn btn-flat bg-blue" title="Refresh"><i class="fa fa-refresh"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="box-body">
                            <?php
                            if (isset($_POST['search'])) {
                                $sqls = "SELECT Name FROM OfficeDetail Where ID = '" . $_POST['oid'] . "' ";
                                $results = sqlsrv_query($connection, $sqls);
                                $reso = sqlsrv_fetch_array($results);
                                echo "<h5 class='text-center text-bold'>Branch Trial - " . $reso['Name'] . "( " . $fyearn . " - ".  $_POST['date1'] . "  )</h5>";
                            }
                            ?>
                            <table id="btrial" class="display"> 
                                <thead class="bg-red text-sm" >
                                    <tr>
                                        <th>LF</th>
                                        <th>NAME</th>
                                        <th>DEBIT</th>
                                        <th>CREDIT</th>

                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    <?php
                                    $id = $_POST['oid'];
                                    $date1 = $_POST['date1'];
                                    if ($_SESSION['BranchID'] == 1 AND $id == "") {
                                        $idx = "";
                                    } else if ($_SESSION['BranchID'] == 1 AND $id != 1) {
                                        $idx = "and officeid = '$id'";
                                    } else {
                                        $idx = "and officeid = '" . $_SESSION['BranchID'] . "'";
                                    }
                                    $tcr = 0.0;
                                    $tdr = 0.0;
                                    if (isset($_POST['search'])) {
                                        $qry = "select a.LF,a.Name,'',
                                                (select sum(dramount-cramount)from ledger where ldate between '$fyearn' and '$date1' and a.groupid in(3,4)and accountheadid=a.id $idx)Dr,
                                                (select sum(cramount-dramount)from ledger where ldate between '$fyearn' and '$date1' and a.groupid in(2,5)and accountheadid=a.id $idx)Cr
                                                from acctree a,ledger l
                                                where a.id=l.accountheadid 
                                                group by a.lf,a.name ,a.groupid,a.id
                                                order by a.groupid,a.lf";

                                        $result = sqlsrv_query($connection, $qry);
                                        while ($res = sqlsrv_fetch_array($result)) {
                                            $tdr += $res['Dr'];
                                            $tcr += $res['Cr'];
                                            if (!empty($res['Cr']) or ! empty($res['Dr'])) {
                                                ?>
                                                <tr>
                                                    <td><?php echo $res['LF']; ?></td>
                                                    <td><?php echo $res['Name']; ?></td>
                                                    <td><?php echo number_format($res['Dr'], 2); ?></td>
                                                    <td><?php echo number_format($res['Cr'], 2); ?></td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                    }
                                    ?>
                                </tbody>
                                <tfoot>

                                    <tr class="bg-red text-bold">
                                        <td colspan="2" class="text-bold">Total - </td>                                           
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
    $('#btrial').DataTable({
        scrollY: "300px",
        scrollCollapse: true,
        paging: false,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                title: 'Jeevan Bikas Samaj',
                messageTop: 'Branch Trial - ' + $("#date1").val(),
            },
            {
                extend: 'pdf',
                title: 'Jeevan Bikas Samaj',
                messageTop: 'Branch Trial - ' + $("#date1").val(),
            },
            {
                extend: 'print',
                title: 'Jeevan Bikas Samaj',
                messageTop: 'Branch Trial - ' + $("#date1").val(),
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

