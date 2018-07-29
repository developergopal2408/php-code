<?php
include_once 'top.php'; //Include Sidebar_header.php-->
include_once 'header.php'; //Include Sidebar.php-->
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
                <small>Daily Cheque Transaction</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Daily Cheque Transaction</li>
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
                                        <?php
                                        if ($_SESSION['BranchID'] == 1) {
                                            ?>
                                            <div class="col-sm-3">
                                                <select name="id" id="id" class="form-control select2" required>
                                                    <option value="">Select Branch</option>
                                                    <?php
                                                    $sql1 = "SELECT * FROM OfficeDetail ORDER BY ID ASC ";
                                                    $result = sqlsrv_query($connection, $sql1);
                                                    while ($rows = sqlsrv_fetch_array($result)) {
                                                        ?>
                                                        <option value="<?php echo $rows['ID']; ?>"  <?php
                                                        if ($_POST['id'] == $rows['ID']) {
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
                                <div class="box-tools pull-right" >
                                    <a href="dailycheque.php"  class=" btn btn-flat bg-blue" title="Refresh"><i class="fa fa-refresh"></i></a>
                                </div>

                            </div>
                        </div>

                        <div class="box-body">
                            <?php
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
                            <table id="dc" class="stripe row-border order-column" cellspacing="0" width="100%">
                                <thead class="bg-red ">
                                    <tr class="text-sm">
                                        <th>MemberCode</th>
                                        <th>MemberName</th>
                                        <th>SavingType</th>
                                        <th>ChequeNo</th>
                                        <th>SaveDate</th>
                                        <th>WithdrawBalance</th>
                                        <th>WithdrawBy</th>
                                        <th>PaidBy</th>
                                        <th>Signature</th>
                                        <th>ApprovedBy</th>
                                        <th>Signatures</th>

                                    </tr>
                                </thead>
                                <tbody  class="text-sm">
                                    <?php
                                    $id = $_POST['id'];
                                    $date1 = $_POST['date1'];
                                    $date2 = $_POST['date2'];
                                    if ($_SESSION['BranchID'] == 1 AND $id != "") {
                                        $idx = "and m.officeid='$id' and s.officeid='$id' and n.branchid='$id' and d.officeid='$id'";
                                    } else {
                                        $idx = "and m.officeid = '" . $_SESSION['BranchID'] . "' and s.officeid='" . $_SESSION['BranchID'] . "' and n.branchid='" . $_SESSION['BranchID'] . "' and d.officeid='" . $_SESSION['BranchID'] . "'";
                                    }
                                    if (isset($_POST['search'])) {
                                        $qry = "select m.MemberCode,m.Firstname+' '+m.lastname as MemberName,t.SavingType,
                                                d.ChequeNo,s.SaveDate,(s.Dramount)WithdrawBalance,(d.Remarks)WithdrawBY,
                                                n.firstname+' '+n.Lastname as PaidBy,(' ')Signature,(' ')ApprovedBy,(' ')Signatures
                                                from member m, savingtype t,savingdetail s,staffmain n, chequedetail d
                                                where m.memberid=s.memberid and s.savingtypeid=t.savingtypeid and n.staffid=s.userid
                                                and  d.status='P' and s.chequeno=d.chequeno 
                                                $idx
                                                and savedate between '$date1' AND '$date2'
                                                order by m.membercode";
                                    } else {
                                        $qry = "select m.MemberCode,m.Firstname+' '+m.lastname as MemberName,t.SavingType,
                                                d.ChequeNo,s.SaveDate,(s.Dramount)WithdrawBalance,(d.Remarks)WithdrawBY,
                                                n.firstname+' '+n.Lastname as PaidBy,(' ')Signature,(' ')ApprovedBy,(' ')Signatures
                                                from member m, savingtype t,savingdetail s,staffmain n, chequedetail d
                                                where m.memberid=s.memberid and s.savingtypeid=t.savingtypeid and n.staffid=s.userid
                                                and  d.status='P' and s.chequeno=d.chequeno 
                                                $idx
                                                and savedate between '$date1' AND '$date2'
                                                order by m.membercode";
                                    }
                                    $result = sqlsrv_query($connection, $qry);
                                    while ($res = sqlsrv_fetch_array($result)) {
                                        ?>
                                        <tr>
                                            <td><?php echo $res['MemberCode']; ?></td>
                                            <td><?php echo $res['MemberName']; ?></td>
                                            <td><?php echo $res['SavingType']; ?></td>
                                            <td><?php echo $res['ChequeNo']; ?></td>
                                            <td><?php echo $res['SaveDate']; ?></td>
                                            <td><?php echo $res['WithdrawBalance']; ?></td>
                                            <td><?php echo $res['WithdrawBY']; ?></td>
                                            <td><?php echo $res['PaidBy']; ?></td>
                                            <td><?php echo $res['Signature']; ?></td>
                                            <td><?php echo $res['ApprovedBy']; ?></td>
                                            <td><?php echo $res['Signatures']; ?></td>
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
    $('#dc').DataTable({
        scrollX: true,
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
                filename: 'Daily Cheque Detail',
                title: 'Jeevan Bikas Samaj',
                messageTop: ' <?php
if (isset($_POST['search'])) {
    if ($_SESSION['BranchID'] > 1) {
        echo $branchName . ' ( ' . $_POST['date1'] . ' - ' . $_POST['date2'] . ') - Daily Cheque Detail';
    } else {
        echo $bname . ' ( ' . $_POST['date1'] . ' - ' . $_POST['date2'] . ') - Daily Cheque Detail';
    }
} else {
    echo $branchName . ' ( ' . $cdate . ' ) - Daily Cheque Detail';
}
?>',
            },
            {
                extend: 'print',
                filename: 'Daily Cheque Detail',
                title: 'Jeevan Bikas Samaj',
                messageTop: ' <?php
if (isset($_POST['search'])) {
    if ($_SESSION['BranchID'] > 1) {
        echo '<h5 class="text-bold text-center">' . $branchName . '<br/> ( Daily Cheque Detail - ' . $_POST['date1'] . ' - ' . $_POST['date2'] . ' ) </h5>';
    } else {
        echo '<h5 class="text-bold text-center">' . $bname . '<br/> ( Daily Cheque Detail - ' . $_POST['date1'] . ' - ' . $_POST['date2'] . ' ) </h5>';
    }
} else {
    echo '<h5 class="text-bold text-center">' . $branchName . '<br/> ( Daily Cheque Detail ' . $cdate . '  )</h5>';
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


