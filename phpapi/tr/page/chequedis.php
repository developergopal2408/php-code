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
                <small>Cheque Disburse</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Cheque Disburse</li>
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
                                     <a  href="chequedis.php"  class="btn btn-flat bg-blue" data-toggle = "tooltip" title="All / Refresh"><i class="fa fa-refresh"></i></a>
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
                            <table id="cdis" class="table display table-condensed table-bordered table-striped" style="width:auto;">
                                <thead class="bg-red text-sm">
                                    <tr>
                                        <th>MemberCode</th>
                                        <th>MemberName</th>
                                        <th>ChequeIssueDate</th>
                                        <th>FromChqNo</th>
                                        <th>ToChqNo</th>
                                        <th>Qty</th>
                                        
                                    </tr>
                                </thead>

                                <tbody  class="text-sm">
                                    <?php
                                    $id = $_POST['id'];
                                    $date1 = $_POST['date1'];
                                    $date2 = $_POST['date2'];
                                     if ($_SESSION['BranchID'] == 1 AND $id != "") {
                                        $idx = "and m.officeid='$id'";
                                    } else {
                                        $idx = "and m.officeid = '" . $_SESSION['BranchID'] . "'";
                                    }
                                    if (isset($_POST['search'])) {
                                        $qry = "select m.MemberCode,M.Firstname+' '+m.LastName as MemberName,(c.SaveDate)ChequeIssueDate,(c.FromCheck)FromChequeNo,(c.ToCheck)ToChequeNo,C.Qty
                                                from Member m, ChequeMaster c
                                                where M.memberid=c.memberid $idx  and c.officeid=m.officeid and 
                                                c.savedate between '$date1' AND '$date2'
                                                order by C.SaveDate,m.MemberCode";
                                    } else {
                                        $qry = "select m.MemberCode,M.Firstname+' '+m.LastName as MemberName,
                                                (c.SaveDate)ChequeIssueDate,(c.FromCheck)FromChequeNo,(c.ToCheck)ToChequeNo,C.Qty
                                                from Member m, ChequeMaster c
                                                where M.memberid=c.memberid $idx and c.officeid=m.officeid and 
                                                c.savedate between '$sdate' AND '$cdate'
                                                order by C.SaveDate,m.MemberCode";
                                    }
                                    $result = sqlsrv_query($connection, $qry);
                                    while ($res = sqlsrv_fetch_array($result)) {
                                        ?>
                                        <tr>
                                            <td><?php echo $res['MemberCode']; ?></td>
                                            <td><?php echo $res['MemberName']; ?></td>
                                            <td><?php echo $res['ChequeIssueDate']; ?></td>
                                            <td><?php echo $res['FromChequeNo']; ?></td>
                                            <td><?php echo $res['ToChequeNo']; ?></td>
                                            <td><?php echo $res['Qty']; ?></td>
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
    $('#cdis').DataTable({
        //scrollX:true,
        scrollY: "300px",
        scrollCollapse: true,
        paging: false,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                filename: 'Cheque Issue',
                title: 'Jeevan Bikas Samaj',
                messageTop: '<?php if (isset($_POST['search'])) {echo $bname . ' -  Cheque Issue ( ' . $_POST['date1'] . ' - ' . $_POST['date2'] . ')';} else { echo $branchName . "- Cheque Issue ";};?>',
            },
            {
                extend: 'pdf',
                filename: 'Cheque Issue',
                title: 'Jeevan Bikas Samaj',
                messageTop: '<?php if (isset($_POST['search'])) {echo $bname . "- Cheque Issue ";} else { echo $branchName . "- Cheque Issue ";};?>',

            },
            {
                extend: 'print',
                filename: 'Cheque Issue',
                title: 'Jeevan Bikas Samaj',
                messageTop: '<?php if (isset($_POST['search'])) { echo '<h5 class="text-bold text-center"> ' . $bname . ' <br/> Cheque Issue <br/>  ' . $_POST['date1'] . ' - ' . $_POST['date2'] . ' </h5>';} else {echo $branchName . "<br/> Cheque Issue ";};?>',
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