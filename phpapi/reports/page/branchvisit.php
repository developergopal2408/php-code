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

                <small>Field Visit</small>
            </h1>


            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>

                <li class="active">Branch Visit</li>
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


                                        <div class="col-sm-2">
                                            <button type="submit" name="search" id="search-btn" class=" btn btn-flat bg-green"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                </form>
                                <!-- /.search form -->



                            </div>
                        </div>

                        <div class="box-body">
                            
                            <table id="field" class="table table-bordered stripe row-border order-column display text-sm" cellspacing="0" > 
                                <thead class="bg-red" >
                                    <tr>
                                        <th>Staff Name</th>
                                        <th>From Office</th>
                                        <th>Visited Office</th>
                                        <th>Visit Type</th>
                                        <th>Visit Date</th>
                                        <th>Visit Time</th>
                                        <th>Visit End Time</th>
                                        <th>Visit End Date</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $date1 = $_POST['date1'];
                                $date2 = $_POST['date2'];
                                if (isset($_POST['search'])) {
                                    $qry = "select (select FirstName+''+LastName as StaffName from StaffMain where StaffID = StaffBranchVisit.StaffID)StaffName,
                                            (select VisitType from visittype where visittypeid = StaffBranchVisit.VisitTypeID)VisitType,
                                            (select Name from officedetail where ID = StaffBranchVisit.OfficeID)FromOffice,
                                            (select Name from officedetail where ID = StaffBranchVisit.VisitedOfficeID)VisitedOffice,
                                            VisitDate,VisitTime,VisitEndTime,VisitEndDate,Remarks
                                            from StaffBranchVisit
                                            where VisitDate between '$date1' and '$date2'
                                            order by VisitDate DESC";

                                    $result = odbc_exec($connection, $qry) or die(print_r(odbc_error($connection)));
                                    while ($res = odbc_fetch_array($result)) {
                                        ?>
                                        <tr>
                                            <td><?php echo $res['StaffName']; ?></td>
                                            <td><?php echo str_ireplace("Branch Office", " ",$res['FromOffice']); ?></td>
                                            <td><?php echo str_ireplace("Branch Office", " ", $res['VisitedOffice']); ?></td>
                                            <td><?php echo $res['VisitType']; ?></td>
                                            <td><?php echo $res['VisitDate']; ?></td>
                                            <td><?php echo $res['VisitTime']; ?></td>
                                            <td><?php echo $res['VisitEndTime']; ?></td>
                                            <td><?php echo $res['VisitEndDate']; ?></td>
                                            <td><?php echo $res['Remarks']; ?></td>
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
    $('#field').removeAttr('width').DataTable({
        scrollX: true,
        scrollY: "350px",
        scrollCollapse: true,
        paging: false,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                filename: 'Branch Visit',
                title: 'Jeevan Bikas Samaj',
                messageTop: ' <?php
if (isset($_POST['search'])) {
    if ($_SESSION['BranchID'] > 1) {
        echo $branchName . ' ( ' . $_POST['date1'] . ' - ' . $_POST['date2'] . ') - Branch Visit';
    } else {
        echo $bname . ' ( ' . $_POST['date1'] . ' - ' . $_POST['date2'] . ') - Branch Visit';
    }
} else {
    echo $branchName . ' ( ' . $cdate . ' ) - Branch Visit';
}
?>',
            },
            {
                extend: 'print',
                filename: 'Branch Visit',
                title: 'Jeevan Bikas Samaj',
                messageTop: ' <?php
if (isset($_POST['search'])) {
    if ($_SESSION['BranchID'] > 1) {
        echo '<h5 class="text-bold text-center">' . $branchName . '<br/> ( Branch Visit - ' . $_POST['date1'] . ' - ' . $_POST['date2'] . ' ) </h5>';
    } else {
        echo '<h5 class="text-bold text-center">' . $bname . '<br/> ( Branch Visit - ' . $_POST['date1'] . ' - ' . $_POST['date2'] . ' ) </h5>';
    }
} else {
    echo '<h5 class="text-bold text-center">' . $branchName . '<br/> ( Branch Visit ' . $cdate . '  )</h5>';
}
?>',
                customize: function (win) {
                    $(win.document.body)
                            .css({
                                'font-size': '7pt',
                                'text-align': 'center'
                            })
                            .prepend(
                                    '<img src="http://datatables.net/media/images/logo-fade.png" style="position:absolute; top:0; left:0;" />'
                                    );
                    $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit');
                }

            }
        ]
    });


</script>
