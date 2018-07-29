<?php
include_once 'top.php';
include_once 'header.php';
include_once 'db2.php';
if (isset($_POST['update'])) {
    $did = $_GET['detailid'];
    $upremitno = $_POST['upremitno'];
    $upremitco = $_POST['upremitco'];
    $upAmount = $_POST['upamount'];
    if (empty($upremitno) or empty($upAmount)) {
        echo "<script>alert('Please Fill up all the field');location.href = 'pay_remit.php?detailid=$did';</script>";
    } else {
        $usql = "UPDATE remittancedetail SET REMITNO = '$upremitno', RemitID = '$upremitco', Amount = '$upAmount' WHERE Detailid = '$did'";
        $run = mysqli_query($conn, $usql);
        echo "<script>alert('Details Successfully Updated');location.href = 'pay_remit.php';</script>";
    }
}
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
                <i class="fa fa-dashboard text-red"></i> <?php echo strtoupper($branchName); ?> 
                <small>Pay Remit</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>

                <li class="active">Pay Remit</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">

            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">
                        <div class="box-header with-border">
                            <?php
                            if ($_SESSION['BranchID'] > 1 AND empty($_GET)) {
                                ?>
                                <div class="col-sm-12">
                                    <form class="form-inline" role="form" action="payprocess.php" method="post">
                                        <div class="form-group-sm">
                                            <label class="control-label">Remit No</label>
                                            &nbsp;<input type="text" class="form-control " name="remitno" id="remitno" required style="width:120px;">&nbsp;&nbsp;&nbsp;
                                            <label class="control-label ">Remit Company</label>
                                            &nbsp;<select name="remitco" id="remitco" class="form-control select2" required>
                                                <option value="select">Select Remit Company</option>
                                                <?php
                                                $sql = odbc_exec($connection, "SELECT * FROM RemittanceList WHERE IsActive = 'Y'");
                                                while ($ro = odbc_fetch_array($sql)) {
                                                    ?>
                                                    <option value="<?php echo $ro['RemitID']; ?>"><?php echo $ro['RemitName']; ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>&nbsp;&nbsp;&nbsp;
                                            <label class="control-label ">Amount</label>
                                            &nbsp;<input type="text" class="form-control" name="amount" id="amount" required style="width:100px;">
                                            &nbsp;&nbsp;&nbsp;
                                            <button type="submit" name="submit" class="btn btn-sm bg-green pull-right">Pay Remit</button>
                                        </div>
                                    </form>
                                </div>

                                <?php
                            } else if (isset($_GET['detailid'])) {
                                $updatesql = mysqli_query($conn, "select * from remittancedetail where Detailid = '" . $_GET['detailid'] . "' ");
                                $up = mysqli_fetch_array($updatesql);
                                ?>
                                <div class="col-sm-12">
                                    <form class="form-inline" role="form" action="" method="POST">
                                        <div class="form-group-sm">
                                            <label class="control-label">Remit No</label>
                                            &nbsp;<input type="text" class="form-control " name="upremitno" id="upremitno" required style="width:120px;" value="<?php echo $up['REMITNO']; ?>">&nbsp;&nbsp;&nbsp;
                                            <label class="control-label ">Remit Company</label>
                                            &nbsp;<select name="upremitco" id="upremitco" class="form-control select2" required style="width:200px;">
                                                <option value="select">Select Remit Company</option>
                                                <?php
                                                $sql = odbc_exec($connection, "SELECT * FROM RemittanceList WHERE IsActive = 'Y'");
                                                while ($ro = odbc_fetch_array($sql)) {
                                                    ?>
                                                    <option value="<?php echo $ro['RemitID']; ?>" <?php
                                                    if ($up['RemitID'] == $ro['RemitID']) {
                                                        echo 'selected';
                                                    }
                                                    ?>><?php echo $ro['RemitName']; ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                            </select>&nbsp;&nbsp;&nbsp;
                                            <label class="control-label ">Amount</label>
                                            &nbsp;<input type="text" class="form-control" name="upamount" id="upamount" required style="width:100px;" value="<?php echo $up['Amount']; ?>">
                                            &nbsp;&nbsp;&nbsp;
                                            <button type="submit" name="update" class="btn btn-sm bg-green ">Update Pay Remit</button>
                                            &nbsp;&nbsp;&nbsp;
                                            <a href="pay_remit.php"  class="btn btn-sm bg-red pull-right">Cancel</a>

                                        </div>
                                    </form>
                                </div>
                                <?php
                            } else {
                                ?>
                                <span class="text-bold pull-left">Remittance Paid By Branches</span>
                                <!--<a class="btn pull-right bg-red btn-xs" href="postremittofinlite.php">Post To FinliteX</a>-->
                                <?php
                            }
                            ?>
                        </div>

                        <div class="box-body">
                            <?php
                            if ($_SESSION['BranchID'] > 1) {
                                ?>
                                <h5 class="text-bold">Remittance Paid By Branch
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <?php
                                    if (($_SESSION['JobTypeID'] == 2 or $_SESSION['JobTypeID'] == 3) AND $_SESSION['BranchID'] > 1) {
                                      ?>
                                      <a id="Update" class="btn btn-xs bg-red-gradient pull-right" href="update_pay_remit.php?branchId=<?php echo $_SESSION['BranchID']; ?>">Approve All Remit</a>
                                      <?php
                                      } 
                                    ?>
                                </h5>
                                <?php
                            }
                            ?>
                            <table id="payremit" class="table table-condensed table-hover table-bordered">
                                <thead class="text-sm bg-red">
                                    <tr>
                                        <th>Pay Date</th>
                                        <th>Remit No</th>
                                        <th>Remit Company</th>
                                        <th>Branch Name</th>
                                        <th>Staff Name</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($_SESSION['BranchID'] == 1) {
                                        $sql = mysqli_query($conn, "select * from remittancedetail  ORDER BY Detailid DESC");
                                    } else if (($_SESSION['JobTypeID'] == 2 or $_SESSION['JobTypeID'] == 3) AND $_SESSION['BranchID'] > 1) {
                                        $sql = mysqli_query($conn, "select * from remittancedetail where branchId = '" . $_SESSION['BranchID'] . "'  ORDER BY Savedate DESC ");
                                    } else {
                                        $sql = mysqli_query($conn, "select * from remittancedetail where  STAFFID = '" . $_SESSION['StaffID'] . "'  ");
                                    }
                                    while ($rows = mysqli_fetch_array($sql)) {
                                        $detailid = $rows['Detailid'];
                                        $sqlo = "SELECT * FROM OfficeDetail WHERE ID='" . $rows['branchId'] . "' ";
                                        $reso = odbc_exec($connection, $sqlo);
                                        $rowo = odbc_fetch_array($reso);
                                        $branch = $rowo['Name'];
                                        $sqls = "SELECT * FROM staffmain WHERE StaffID='" . $rows['STAFFID'] . "' ";
                                        $res = odbc_exec($connection, $sqls);
                                        $row = odbc_fetch_array($res);
                                        $sname = $row['FirstName'] . " " . $row['LastName'];
                                        $jid = $row['JobTypeID'];
                                        $sql1 = odbc_exec($connection, "SELECT * FROM RemittanceList WHERE IsActive = 'Y' and RemitID = '" . $rows['RemitID'] . "'");
                                        $sid = odbc_fetch_array($sql1);
                                        $sid['RemitName'];
                                        ?>
                                        <tr>
                                            <td><?php echo $rows['Savedate']; ?></td>
                                            <td><?php echo $rows['REMITNO']; ?></td>
                                            <td><?php echo $sid['RemitName']; ?></td>
                                            <td><?php echo $branch; ?></td>
                                            <td><?php echo $sname; ?></td>
                                            <td><?php echo $rows['Amount']; ?></td>
                                            <td><?php
                                                if ($rows['STATUS'] == 0) {
                                                    echo "<span class='label label-danger text-bold'>Pending</span>";
                                                } else {
                                                    echo "<span class='label label-success text-bold'>Checked</span>";
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                if (($_SESSION['JobTypeID'] == 2 or $_SESSION['JobTypeID'] == 3) AND $_SESSION['BranchID'] > 1) {
                                                    if ($rows['STATUS'] == 0) {
                                                        echo "<a  class='btn btn-xs bg-blue' href='update_pay_remit.php?detailid=$detailid'>UPDATE</a>";
                                                    } else {
                                                        echo "<a  class='btn btn-xs bg-navy' >Processed To HO</a>";
                                                    }
                                                } else if ($_SESSION['BranchID'] > 1 AND ( $jid != 3 or $jid != 2)) {
                                                    if ($rows['STATUS'] == 0) {
                                                        echo "<a  class='btn btn-xs btn-primary'  href='pay_remit.php?detailid=$detailid'>Edit</a>";
                                                    } else {
                                                        echo "<button  class='btn btn-xs btn-primary' disabled>Checked By BI</button>";
                                                    }
                                                } else if ($_SESSION['BranchID'] == 1 AND $rows['STATUS'] == 1) {
                                                    echo "<button class='btn btn-xs btn-primary' >Checked By BI</button>";
                                                } else {
                                                    echo "<button class='btn btn-xs btn-primary' >Reviewing..</button>";
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
    $('#payremit').DataTable({
        "order": [[0, "desc"]],
        "scrollY": "275px",
        "paging": false,
        dom: 'Bfrtip',
        buttons: [
            {
                filename: 'Pay Remit Detail',
                extend: 'excel',
                text: '<i class="fa fa-file-excel-o"></i>',
                title: 'Jeevan Bikas Samaj',
                message: 'Pay Remit Detail',
                className: 'btn btn-primary btn-xs'
            },
            {
                filename: 'Pay Remit Detail',
                extend: 'print',
                text: '<i class="fa fa-print"></i>',
                title: 'Jeevan Bikas Samaj',
                message: 'Pay Remit Detail',
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