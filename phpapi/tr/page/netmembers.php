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
                <small>Net Members List</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Net Members List</li>
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
                                        <div class="col-sm-2">Till Date </div>
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
                                                <option value="">Select Branch</option>
                                                <option value="all">All Branch</option>
                                                <?php
                                                $query = "SELECT ID,Name,Code from OfficeDetail";
                                                $sub = sqlsrv_query($connection, $query);
                                                while ($p = sqlsrv_fetch_array($sub)) {
                                                    ?>
                                                    <option value="<?php echo $p['ID']; ?>" <?php
                                                    if ($p['ID'] == $_POST['id']) {
                                                        echo "selected";
                                                    }
                                                    ?> ><?php echo $p['Code'] . " - " . $p['Name']; ?></option>;
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
                                    <a  href="netmembers.php"  class="btn btn-flat bg-blue" data-toggle = "tooltip" title="All / Refresh"><i class="fa fa-refresh"></i></a>
                                </div>

                            </div>
                        </div>

                        <div class="box-body">
                            <?php
                            $bname = "";
                            if (isset($_POST['id'])) {
                                $query = "SELECT ID,Name,Code FROM OfficeDetail  WHERE  ID = '" . $_POST['id'] . "'";
                                $sub = sqlsrv_query($connection, $query);
                                $p = sqlsrv_fetch_array($sub);
                                $bname = $p['Name'];
                                echo "<h5 class='text-center text-bold'>" . $bname . "</h5>";
                            } else {
                                echo "<h5 class='text-center text-bold'>" . $branchName . "</h5>";
                            }
                            ?>
                            <table id="net" class="stripe row-border order-column" cellspacing="0" width="100%"> 
                                <thead class="bg-red text-sm" >
                                    <tr>
                                        <th>Office Code</th>
                                        <th>Office Name</th>
                                        <th>Active Member</th>
                                        <th>Passive Member</th>
                                        <th>Dropout Member</th>
                                        <th>Net Member</th>
                                    </tr>
                                </thead>

                                <?php
                                if (isset($_POST['search'])) {
                                    $active = $passive = $drop = $net = 0;
                                    $ID = $_POST['id'];
                                    $date2 = $_POST['date2'];
                                    if ($ID == "all") {
                                        $qry = "select o.code,o.name,
                                                (select count(memberid) from member where  regdate<='$date2' and officeid=o.id and status='ACTIVE')ActiveMember,
                                                (select count(memberid) from member where  regdate<='$date2' and officeid=o.id and status='PASSIVE')PassiveMember,
                                                (select count(memberid) from member where DropOutDate<='$date2'  and officeid=o.id and Status='DROPOUT')DropOutMember,
                                                (select count(memberid) from member where  regdate<='$date2' and officeid=o.id and status IN ('ACTIVE','PASSIVE'))NetMember
                                                from  officedetail o
                                                where o.CatagoryID = 3
                                                group by o.code,o.name,o.id
                                                order by o.code";
                                    } else {
                                        $qry = "select o.code,o.name,
                                                (select count(memberid) from member where  regdate<='$date2' and officeid=o.id and status='ACTIVE')ActiveMember,
                                                (select count(memberid) from member where  regdate<='$date2' and officeid=o.id and status='PASSIVE')PassiveMember,
                                                (select count(memberid) from member where DropOutDate<='$date2'  and officeid=o.id and Status='DROPOUT')DropOutMember,
                                                (select count(memberid) from member where  regdate<='$date2' and officeid=o.id and status IN ('ACTIVE','PASSIVE'))NetMember
                                                from  officedetail o
                                                where o.CatagoryID = 3 and o.id = '$ID'
                                                group by o.code,o.name,o.id
                                                order by o.code";
                                    }
                                }
                                $result = sqlsrv_query($connection, $qry);
                                ?>
                                <tbody>
                                    <?php
                                    while ($res = sqlsrv_fetch_array($result)) {
                                        $active +=$res['ActiveMember'];
                                        $passive +=$res['PassiveMember'];
                                        $drop +=$res['DropOutMember'];
                                        $net +=$res['NetMember'];
                                        ?>
                                        <tr>
                                            <td><?php echo $res['code']; ?></td>
                                            <td><?php echo $res['name']; ?></td>
                                            <td><?php echo $res['ActiveMember']; ?></td>
                                            <td><?php echo $res['PassiveMember']; ?></td>
                                            <td><?php echo $res['DropOutMember']; ?></td>
                                            <td><?php echo $res['NetMember']; ?></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody> 
                                <tfoot class="bg-red text-bold">
                                    <tr>
                                        <td colspan=2>Total</td>
                                        <td><?php echo $active; ?></td>
                                        <td><?php echo $passive; ?></td>
                                        <td><?php echo $drop; ?></td>
                                        <td><?php echo $net; ?></td>
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
    $('#net').DataTable({
        scrollY: "300px",
        scrollCollapse: true,
        paging: false,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                filename: 'Net Members List',
                title: 'Jeevan Bikas Samaj',
                messageTop: ' <?php
if (isset($_POST['search'])) {
    if ($_POST['id'] > 1) {
        echo $bname . ' ( ' . $_POST['date2'] . ') - Net Members List';
    } else {
        echo 'All Branch ( ' . $_POST['date2'] . ') - Net Members List';
    }
} else {
    echo $branchName . ' ( ' . $cdate . ' ) - Net Members List';
}
?>',
            },
            {
                extend: 'print',
                filename: 'Net Members List',
                title: 'Jeevan Bikas Samaj',
                messageTop: ' <?php
if (isset($_POST['search'])) {
    if ($_POST['id'] > 1) {
        echo '<h5 class="text-bold text-center">' . $bname . '<br/> ( Net Members List - ' . $_POST['date2'] . ' ) </h5>';
    } else {
        echo '<h5 class="text-bold text-center">All Branch <br/> ( Net Members List - ' . $_POST['date2'] . ' ) </h5>';
    }
} else {
    echo '<h5 class="text-bold text-center">' . $branchName . '<br/> ( Net Members List ' . $cdate . '  )</h5>';
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




