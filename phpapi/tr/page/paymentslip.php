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
                <small>Payment Slip</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Payment Slip</li>
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
                                        if ($_SESSION['BranchID'] == 1) {
                                            ?>
                                            <div class="col-sm-3">
                                                <select name="id" id="id" class="form-control select2" required>
                                                    <option value="">Select Branch</option>
                                                    <?php
                                                    $query = "SELECT ID,Name,Code from OfficeDetail";
                                                    $sub = sqlsrv_query($connection, $query);
                                                    while ($p = sqlsrv_fetch_array($sub)) {
                                                        ?>
                                                        <option value="<?php echo $p['ID']; ?>" <?php
                                                        if ($p['ID'] == $_POST['id']) {
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

                                <div class="box-tools pull-right" >
                                    <a  href="paymentslip.php"  class="btn btn-flat bg-blue" data-toggle = "tooltip" title="All / Refresh"><i class="fa fa-refresh"></i></a>
                                </div>

                            </div>
                        </div>

                        <div class="box-body">
                            <?php
                            $bname = "";
                            if (isset($_POST['search'])) {
                                $query = "SELECT ID,Name FROM OfficeDetail  WHERE  ID = '" . $_POST['id'] . "'";
                                $sub = sqlsrv_query($connection, $query);
                                $p = sqlsrv_fetch_array($sub);
                                $bname = $p['Name'];
                                if ($_SESSION['BranchID'] == 1) {
                                    echo "<h5 class='text-bold text-center'>" . $bname . " - " . $_POST['date1'] . "</h5>";
                                } else {
                                    echo "<h5 class='text-bold text-center'>" . $branchName . " - " . $_POST['date1'] . "</h5>";
                                }
                            } else {
                                echo "<h5 class='text-bold text-center'>" . $branchName . " - " . $_POST['date1'] . "</h5>";
                            }
                            ?>
                            <table id="payslip" class="stripe row-border order-column" cellspacing="0" width="100%"> 
                                <thead class="bg-red text-sm" >
                                    <tr>
                                        <th>Member Code</th>
                                        <th>Member NAME</th>
                                        <th>Welfare</th>
                                        <th>Compulsory</th>
                                        <th>Personal</th>
                                        <th>Special</th>
                                        <th>Pension</th>
                                        <th>PenNominee</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    <?php
                                    $welfare = 0.0;
                                    $compulsory = 0.0;
                                    $personal = 0.0;
                                    $special = 0.0;
                                    $pension = 0.0;
                                    $pennomi = 0.0;
                                    $id = $_POST['id'];
                                    $date2 = $_POST['date1'];
                                    if ($_SESSION['BranchID'] == 1 AND $id != "") {
                                        $idx = "m.officeid='$id'";
                                    } else {
                                        $idx = "m.officeid = '" . $_SESSION['BranchID'] . "'";
                                    }
                                    if (isset($_POST['search'])) {
                                        $qry = "select m.membercode,m.firstname+' '+m.lastname as MemberName,
(select sum(dramount + intdr) from savingdetail where  contraid=301 and memberid=m.memberid and savingtypeid=1 and savedate='$date2' and officeid = m.officeid and particulars <> 'Installment Deducted For Loan')welfare,
(select sum(dramount + intdr) from savingdetail where  contraid=301 and memberid=m.memberid and savingtypeid=2 and savedate='$date2' and officeid = m.officeid and particulars <> 'Installment Deducted For Loan')Compulsory,
(select sum(dramount + intdr) from savingdetail where  contraid=301 and memberid=m.memberid and savingtypeid=3 and savedate='$date2' and officeid = m.officeid and particulars <> 'Installment Deducted For Loan') Personal,
(select sum(dramount + intdr) from savingdetail where  contraid=301 and memberid=m.memberid and savingtypeid=4 and savedate='$date2' and officeid = m.officeid and particulars <> 'Installment Deducted For Loan') Special,
(select sum(dramount + intdr) from savingdetail where  contraid=301 and memberid=m.memberid and savingtypeid=5 and savedate='$date2' and officeid = m.officeid and particulars <> 'Installment Deducted For Loan')Pension,
(select sum(intdr) from savingdetail where  contraid=301 and memberid=m.memberid and savingtypeid=5 and savedate='$date2' and officeid = m.officeid and particulars <> 'Installment Deducted For Loan')PenNomi
from member m,savingdetail s
where $idx and m.officeid = s.officeid and m.memberid = s.memberid and s.particulars <> 'Installment Deducted For Loan'
group by m.membercode,m.firstname, m.lastname,m.memberid,m.officeid
order by m.membercode";
                                    }
                                    $result = sqlsrv_query($connection, $qry);
                                    while ($res = sqlsrv_fetch_array($result)) {
                                        if (!empty($res['welfare'] AND $res['welfare'] > 0) or ! empty($res['Compulsory'] AND $res['Compulsory'] > 0) or ! empty($res['Personal'] AND $res['Personal'] > 0) or ! empty($res['Special'] AND $res['Special'] > 0) or ! empty($res['Pension'] AND $res['Pension'] > 0)) {
                                            $welfare += $res['welfare'];
                                            $compulsory += $res['Compulsory'];
                                            $personal += $res['Personal'];
                                            $special += $res['Special'];
                                            $pension += $res['Pension'];
                                            $pennomi += $res['PenNomi'];
                                            ?>
                                            <tr>
                                                <td><?php echo $res['membercode']; ?></td>
                                                <td><?php echo $res['MemberName']; ?></td>
                                                <td><?php echo number_format($res['welfare'], 2); ?></td>
                                                <td><?php echo number_format($res['Compulsory'], 2); ?></td>
                                                <td><?php echo number_format($res['Personal'], 2); ?></td>
                                                <td><?php echo number_format($res['Special'], 2); ?></td>
                                                <td><?php echo number_format($res['Pension'], 2); ?></td>
                                                <td><?php echo number_format($res['PenNomi'], 2); ?></td>
                                            </tr>																				
                                            <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                                <tfoot class="bg-red text-sm">
                                    <tr>
                                        <td colspan=2>Total</td>
                                        <td><?php echo number_format($welfare, 2); ?></td>
                                        <td><?php echo number_format($compulsory, 2); ?></td>
                                        <td><?php echo number_format($personal, 2); ?></td>
                                        <td><?php echo number_format($special, 2); ?></td>
                                        <td><?php echo number_format($pension, 2); ?></td>
                                        <td><?php echo number_format($pennomi, 2); ?></td>
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
    $('#payslip').DataTable({
        scrollY: "300px",
        scrollCollapse: true,
        paging: false,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                filename: 'Payment Slip',
                title: 'Jeevan Bikas Samaj',
                messageTop: ' <?php
if (isset($_POST['search'])) {
    echo $bname . ' ( ' . $_POST['date1'] . ' ) - Payment Slip';
} else {
    echo $branchName . ' ( ' . $cdate . ' ) - Payment Slip';
}
?>',
            },
            {
                extend: 'print',
                filename: 'Payment Slip',
                title: 'Jeevan Bikas Samaj',
                messageTop: ' <?php
if (isset($_POST['search'])) {
    if ($_SESSION['BranchID'] == 1) {
        echo '<h5 class="text-bold text-center">' . $bname . '<br/> ( Payment Slip - ' . $_POST['date1'] . ' ) </h5>';
    } else {
        echo '<h5 class="text-bold text-center">' . $branchName . '<br/> ( Payment Slip - ' . $_POST['date1'] . ' ) </h5>';
    }
} else {
    echo '<h5 class="text-bold text-center">' . $branchName . '<br/> ( Payment Slip ' . $cdate . ' )</h5>';
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



