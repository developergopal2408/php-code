<?php
include_once 'top.php';
include_once 'header.php';
?>
<div class="wrapper">
    <?php
    include_once 'sidebar_header.php';
    include_once 'sidebar.php';
    ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                <i class="fa fa-building"></i> 
                <small>Center Meeting Status</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Center Meeting Status</li>
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
                                                       echo $cdate;
                                                   }
                                                   ?>"
                                                   >
                                        </div>
                                        <div class="col-sm-2">
                                            <button type="submit" name="search" id="search-btn" class=" btn btn-flat bg-green"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                </form>
                                <!-- /.search form -->
                                <div class="box-tools pull-right" >
                                    <a href="collstatus.php"  class=" btn btn-flat bg-blue" title="Refresh"><i class="fa fa-refresh"></i></a>
                                </div>

                            </div>
                        </div>

                        <div class="box-body">
                            <?php
                            if (isset($_POST['search'])) {
                                echo "<h5 class='text-bold text-center'>All Branch - Center Meeting Status - ( " . $_POST['date1'] . "  )</h5>";
                            }
                            ?>
                            <table id="collstatus" class="stripe row-border order-column" cellspacing="0" width="100%"> 
                                <thead class="bg-red text-sm">
                                    <tr>
                                        <th>Code</th>
                                        <th>Name</th>
                                        <th>CenterCode</th>
                                        <th>MeetingDate</th>
                                        <th>IsGenerated</th>
                                        <th>IsDownloaded</th>
                                        <th>IsUploaded</th>
                                        <th>IsPosted</th>
                                        <th>StaffCode</th>
                                        <th>StaffName</th>
                                        <th>Mobile</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    <?php
                                    $date1 = $_POST['date1'];
                                    if ($_SESSION['BranchID'] == 1) {
                                        $id = "";
                                    } else {
                                        $id = "and d.officeid = '" . $_SESSION['ID'] . "'";
                                    }
                                     if (isset($_POST['search'])) {
                                        $qry = "select (o.Code)BranchCode,(o.Name)BranchName,c.CenterCode,M.MeetingDate,m.IsGenerated,(m.isDown)isDownLoad,(m.IsUp)IsUploaded,(m.IsPosted)IsPosted,
                                                (s.Code)StaffCode,s.firstname+' '+s.Lastname as StaffName,s.Mobile
                                                from officedetail o, centermain c,collmaster m,staffMain s
                                                where o.id=c.officeid and o.id=m.officeid and c.centerid=m.centerid and M.meetingDate = '$date1' 
                                                and s.branchid=o.id and m.officeid=s.branchid and s.staffid=m.staffid
                                                order by o.code,c.centercode";
                                        $result = sqlsrv_query($connection, $qry);
                                        while ($res = sqlsrv_fetch_array($result)) {
                                            ?>
                                            <tr>
                                                <td><?php echo $res['BranchCode']; ?></td>
                                                <td><?php echo $res['BranchName']; ?></td>
                                                <td><?php echo $res['CenterCode']; ?></td>
                                                <td><?php echo $res['MeetingDate']; ?></td>
                                                <td><?php echo $res['IsGenerated']; ?></td>
                                                <td><?php echo $res['isDownLoad']; ?></td>
                                                <td><?php echo $res['IsUploaded']; ?></td>
                                                <td><?php echo $res['IsPosted']; ?></td>
                                                <td><?php echo $res['StaffCode']; ?></td>
                                                <td><?php echo $res['StaffName']; ?></td>
                                                <td><?php echo $res['Mobile']; ?></td>
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
    $('#collstatus').removeAttr('width').DataTable({
        scrollX: true,
        scrollY: "300px",
        scrollCollapse: true,
        paging: false,
        columnDefs: [
            { width: 150, targets: [1,2,3,4]}
        ],
        fixedColumns: true,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                filename: 'Center Meeting Status',
                title: 'Jeevan Bikas Samaj',
                messageTop: '<?php
if (isset($_POST['search'])) {
    echo  " All Branch - Center Meeting Status ";
} else {
    echo $branchName . "- Center Meeting Status ";
};
?>',
            },
            {
                extend: 'print',
                filename: 'Center Meeting Status',
                title: 'Jeevan Bikas Samaj',
                messageTop: '<?php
if (isset($_POST['search'])) {
    echo  "All Branch<br/> Center Meeting Status ";
} else {
    echo $branchName . "<br/> Center Meeting Status ";
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

