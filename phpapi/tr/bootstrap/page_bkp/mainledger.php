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
                <small>Main Ledger</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Main Ledger </li>
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

                                        <div class="col-sm-3">
                                            <select name="id" id="id" class="form-control select2" required>
                                                <option value="">Select Head</option>
                                                <?php
                                                $query = "SELECT ID, ParentID, Name FROM AccTree WHERE State='1' AND  ID In(SELECT ParentID FROM AccTree ) AND ID != '1'";
                                                $sub = odbc_exec($connection, $query);
                                                while ($p = odbc_fetch_array($sub)) {
                                                    ?>
                                                    <option value="<?php echo $p['ID']; ?>" <?php
                                                    if ($p['ID'] == $_POST['id']) {
                                                        echo "selected";
                                                    }
                                                    ?>><?php echo $p['Name']; ?></option>;
                                                            <?php
                                                        }
                                                        ?>
                                            </select>
                                        </div>
                                        <?php
                                        if ($_SESSION['BranchID'] == 1) {
                                            ?>
                                            <div class="col-sm-3">
                                                <select name="branchid" id="branchid" class="form-control select2" required>
                                                    <option value="">Select Branch</option>
                                                    <?php
                                                    $query = "SELECT ID,Name FROM officedetail order by ID ASC";
                                                    $sub = odbc_exec($connection, $query);
                                                    while ($p = odbc_fetch_array($sub)) {
                                                        ?>
                                                        <option value="<?php echo $p['ID']; ?>" <?php
                                                        if ($p['ID'] == $_POST['branchid']) {
                                                            echo "selected";
                                                        }
                                                        ?> ><?php echo $p['Name']; ?></option>;
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
                                    <a href="mainledger.php"  class=" btn btn-flat bg-blue"><i class="fa fa-refresh"></i></a>
                                </div>

                            </div>
                        </div>
                        <div class="box-body">
                            <?php
                            if (isset($_POST['search'])) {
                                $query = "SELECT ID,Name FROM OfficeDetail  WHERE  ID = '" . $_POST['branchid'] . "'";
                                $sub = odbc_exec($connection, $query);
                                $p = odbc_fetch_array($sub);
                                $bname = $p['Name'];
                                if ($_POST['branchid']) {
                                    echo "<h5 class='text-bold text-center'>" . $bname . " ( " . $_POST['date1'] . " - " . $_POST['date2'] . " )</h5>";
                                } else {
                                    echo "<h5 class='text-bold text-center'>" . $branchName . "( " . $_POST['date1'] . " - " . $_POST['date2'] . " )</h5>";
                                }
                            } else {
                                echo "<h5 class='text-bold text-center'> " . $_POST['date1'] . "  " . $_POST['date2'] . "</h5>";
                            }
                            ?>
                            <table id="mainledger" class="stripe row-border order-column" cellspacing="0" width="100%">
                                <thead class="bg-red text-sm">
                                    <tr>
                                        <th>LF</th>
                                        <th>Name</th>
                                        <th>DrAmount</th>                             
                                        <th>CrAmount</th>
                                        <th>Balance</th>
                                        <th>Dr/Cr</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    <?php
                                    $bal = 0.0;
                                    $ID = $_POST['id'];
                                    $bid = $_POST['branchid'];
                                    $date1 = $_POST['date1'];
                                    $date2 = $_POST['date2'];
                                    if ($_SESSION['BranchID'] == 1 AND $bid != "") {
                                        $idx = "and l.officeid='$bid'";
                                        $idw = "and a.parentid = '$ID'";
                                    } else {
                                        $idx = "and l.officeid='" . $_SESSION['BranchID'] . "'";
                                        $idw = "and a.parentid = '$ID'";
                                    }
                                    if (isset($_POST['search'])) {
                                        $qry = "select a.LF, a.Name,sum(DrAmount) DrAmount,
                                                sum(CrAmount) CrAmount,sum(DrAmount - CrAmount) Balance
                                                FROM Ledger l ,Acctree a
                                                WHERE l.AccountHeadID=a.ID
                                                and ldate between '$date1' and '$date2' 
                                                $idw $idx
                                                GROUP BY a.LF, a.Name";
                                    }
                                    $result = odbc_exec($connection, $qry);
                                    while ($res = odbc_fetch_array($result)) {
                                        $bal = $res['Balance'];
                                        ?>
                                        <tr>
                                            <td><?php echo $res['LF']; ?></td>
                                            <td><?php echo $res['Name']; ?></td>
                                            <td><?php echo number_format($res['DrAmount'], 2); ?></td>
                                            <td><?php echo number_format($res['CrAmount'], 2); ?></td>
                                            <td><?php echo number_format(abs($bal), 2); ?></td>
                                            <td><?php
                                                if ($bal > 0) {
                                                    echo "Dr";
                                                } else {
                                                    echo "Cr";
                                                }
                                                ?></td>
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
    $('#mainledger').DataTable({
        scrollY: "300px",
        scrollCollapse: true,
        paging: false,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                filename: 'Main Ledger Detail',
                title: 'Jeevan Bikas Samaj',
                messageTop: '<?php
if (isset($_POST['search'])) {
    echo $bname . ' -  Main Ledger Detail ( ' . $_POST['date1'] . ' - ' . $_POST['date2'] . ')';
} else {
    echo $branchName . "- Main Ledger Detail ";
};
?>',
            },
            {
                extend: 'print',
                filename: 'Main Ledger Detail',
                title: 'Jeevan Bikas Samaj',
                messageTop: '<?php
if (isset($_POST['search'])) {
    if ($_SESSION['BranchID'] == 1) {
        echo '<h5 class="text-bold text-center"> ' . $bname . ' <br/> Main Ledger Detail <br/>  ' . $_POST['date1'] . ' - ' . $_POST['date2'] . ' </h5>';
    } else {
        echo '<h5 class="text-bold text-center"> ' . $branchName . ' <br/> Main Ledger Detail <br/>  ' . $_POST['date1'] . ' - ' . $_POST['date2'] . ' </h5>';
    }
} else {
    echo $branchName . "<br/> Main Ledger Detail ";
};
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