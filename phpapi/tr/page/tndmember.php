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
                <small>Total New/Dropout Member's List</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Total New/Dropout Member's List</li>
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
                                            <select name="id" id="id" class="form-control select2" >
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
                                    <a  href="tndmember.php"  class="btn btn-flat bg-blue" data-toggle = "tooltip" title="All / Refresh"><i class="fa fa-refresh"></i></a>
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
                            <table id="ndrop" class="stripe row-border order-column" cellspacing="0" width="100%"> 
                                <thead class="bg-red text-sm" >
                                    <tr>
                                        <th>Office Code</th>
                                        <th>Office Name</th>
                                        <th>New Added Member</th>
                                        <th>Dropout Member</th>
                                        <th>Net Growth</th>

                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    <?php
                                    $tdm = 0;
                                    $tnm = 0;
                                    $total = 0;
                                    $ID = $_POST['id'];
                                    $date1 = $_POST['date1'];
                                    $date2 = $_POST['date2'];
                                    if (isset($_POST['search'])) {
                                        if ($ID == "all") {
                                            $qry = "select o.code,o.name,
                                                (select count(memberid) from member where regdate>='$date1' and regdate<='$date2' and officeid=o.id and status='ACTIVE')NewAddMember,
                                                (select count(memberid) from member where DropOutDate>='$date1' and DropOutDate<='$date2' and officeid=o.id and Status='DROPOUT')DropOutMember
                                                from  officedetail o
                                                where ID not in(1,51,52,53,54,55,57,61,80,81,82)
                                                group by o.code,o.name,o.id
                                                order by o.code";
                                        } else {
                                            $qry = "select o.code,o.name,
                                            (select count(memberid) from member where regdate>='$date1' and regdate<='$date2' and officeid=o.id and status='ACTIVE')NewAddMember,
                                            (select count(memberid) from member where DropOutDate>='$date1' and DropOutDate<='$date2' and officeid=o.id and Status='DROPOUT')DropOutMember
                                            from  officedetail o
                                            where ID not in(1,51,52,53,54,55,57,61,80,81,82) and ID = '$ID'
                                            group by o.code,o.name,o.id
                                            order by o.code";
                                        }
                                    }
                                    $result = sqlsrv_query($connection, $qry);
                                    while ($res = sqlsrv_fetch_array($result)) {
                                        $tdm += $res['DropOutMember'];
                                        $tnm += $res['NewAddMember'];
                                        $netmem = $res['NewAddMember'] - $res['DropOutMember'];
                                        $total += $netmem;
                                        ?>
                                        <tr>
                                            <td><?php echo $res['code']; ?></td>
                                            <td><?php echo $res['name']; ?></td>
                                            <td><?php echo $res['NewAddMember']; ?></td>
                                            <td><?php echo $res['DropOutMember']; ?></td>
                                            <td><?php echo $netmem; ?></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                                <tfoot  class="bg-red text-sm">
                                    <tr>
                                        <td colspan=2>Total</td>
                                        <td><?php echo $tnm; ?></td>
                                        <td><?php echo $tdm; ?></td>
                                        <td><?php echo $total; ?></td>
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
    $('#ndrop').DataTable({
        scrollY: "300px",
        scrollCollapse: true,
        paging: false,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                filename: 'New/Dropout Members List',
                title: 'Jeevan Bikas Samaj',
                messageTop: ' <?php
if (isset($_POST['search'])) {
    if ($_POST['id'] > 1) {
        echo $bname . ' ( '. $_POST['date1'] . ' - ' . $_POST['date2'] . ') - New/Dropout Members List';
    } else {
        echo 'All Branch ( ' . $_POST['date1'] . ' - ' . $_POST['date2'] . ') - New/Dropout Members List';
    }
} else {
    echo $branchName . ' ( ' . $cdate . ' ) - New/Dropout Members List';
}
?>',
            },
            {
                extend: 'print',
                filename: 'New/Dropout Members List',
                title: 'Jeevan Bikas Samaj',
                messageTop: ' <?php
if (isset($_POST['search'])) {
    if ($_POST['id'] > 1) {
        echo '<h5 class="text-bold text-center">' . $bname . '<br/> ( New/Dropout Members List -' . $_POST['date1'] . ' - ' . $_POST['date2'] . ' ) </h5>';
    } else {
        echo '<h5 class="text-bold text-center">All Branch <br/> ( New/Dropout Members List - ' . $_POST['date1'] . ' - ' . $_POST['date2'] . ' ) </h5>';
    }
} else {
    echo '<h5 class="text-bold text-center">' . $branchName . '<br/> ( New/Dropout Members List ' . $cdate . '  )</h5>';
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






