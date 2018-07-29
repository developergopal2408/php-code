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
                <small>Member's List</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Member's List</li>
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

                                        <div class="col-sm-3">
                                            <select name="cid" id="cid" class="form-control select2" >
                                                <option value="">Select Center</option>
                                                <?php
                                                $query = "SELECT CenterID,CenterName,CenterCode from centermain where officeid = '" . $_SESSION['BranchID'] . "'";
                                                $sub = odbc_exec($connection, $query);
                                                while ($p = odbc_fetch_array($sub)) {
                                                    ?>
                                                    <option value="<?php echo $p['CenterID']; ?>" <?php if ($p['CenterID'] == $_POST['cid']) {
                                                    echo "selected";
                                                } ?> ><?php echo $p['CenterCode'] . " - " . $p['CenterName']; ?></option>;
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-sm-2">
                                            <button type="submit" name="search" id="search-btn" class=" btn btn-flat bg-green" data-toggle = "tooltip" title="search"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                </form>
                                <!-- /.search form -->
                                <div class="box-tools pull-right" >
                                    <a  href="memberlist.php"  class="btn btn-flat bg-blue" data-toggle = "tooltip" title="All / Refresh"><i class="fa fa-refresh"></i></a>
                                </div>
                            </div>
                        </div>

                        <div class="box-body">

                            <table id="mlist" class="stripe row-border order-column" cellspacing="0" width="100%"> 
                                <thead class="bg-red text-sm" >
                                    <tr>
                                        <th>Member ID</th>
                                        <th>Member Code</th>
                                        <th>Member Name</th>
                                        <th>Gender</th>
                                        <th>DOB</th>
                                        <th>Action</th>

                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    <?php
                                    if (isset($_POST['search'])) {
                                        $cid = $_POST['cid'];
                                        $qry = "select OfficeID,CenterID,MemberID,MemberCode,FirstName +' '+ LastName as Mname,Gender,DOB from member where officeid = '" . $_SESSION['BranchID'] . "'  and centerid = '$cid' and Status = 'ACTIVE'";
                                        $res = odbc_exec($connection, $qry);
                                        while ($row = odbc_fetch_array($res)) {
                                            ?>
                                            <tr>
                                                <td><?php echo $row['MemberID']; ?></td>
                                                <td><?php echo $row['MemberCode']; ?></td>
                                                <td><?php echo $row['Mname']; ?></td>
                                                <td><?php echo $row['Gender']; ?></td>
                                                <td><?php echo $row['DOB']; ?></td>
                                                <td><a href="statement.php?oid=<?php echo $row['OfficeID']; ?>&cid=<?php echo $row['CenterID'] ?>&mid=<?php echo $row['MemberID'] ?>" class="btn btn-sm bg-red" target="_blank"><i class="fa fa-print" ></i></a></td>
                                            </tr>

                                            <?php
                                        }
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
    $('#mlist').DataTable({
        //scrollX: true,
        scrollY: "300px",
        scrollCollapse: true,
        paging: false,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                filename: 'Center Wise Member Statement Detail',
                title: 'Jeevan Bikas Samaj',
                messageTop: ' <?php
if (isset($_POST['search'])) {
    if ($_SESSION['BranchID'] > 1) {
        echo $branchName . ' ( Center Wise Member Statement Detail)';
    } else {
        echo $bname . ' ( Center Wise Member Statement Detail )';
    }
} else {
    echo $branchName . ' (  Center Wise Member Statement Detail )';
}
?>',
            },
            {
                extend: 'print',
                filename: 'Center Wise Member Statement Detail',
                title: 'Jeevan Bikas Samaj',
                messageTop: ' <?php
if (isset($_POST['search'])) {
    if ($_SESSION['BranchID'] > 1) {
        echo '<h5 class="text-bold text-center">' . $branchName . '<br/> ( Center Wise Member Statement Detail   ) </h5>';
    } else {
        echo '<h5 class="text-bold text-center">' . $bname . '<br/> ( Center Wise Member Statement Detail  ) </h5>';
    }
} else {
    echo '<h5 class="text-bold text-center">' . $branchName . '<br/> ( Center Wise Member Statement Detail )</h5>';
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






