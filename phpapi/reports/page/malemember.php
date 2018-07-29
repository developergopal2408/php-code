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

                <small>Male Member</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Male Member</li>
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
                                <?php
                                if ($_SESSION['BranchID'] == 1) {
                                    ?>
                                    <form  action="" method="post" class="form-horizontal" >
                                        <div class=" form-group-sm">


                                            <div class="col-sm-3">
                                                <select name="id" id="id" class="form-control select2" >
                                                    <option value="">Select Branch</option>
                                                    <?php
                                                    $sql1 = "SELECT * FROM OfficeDetail ORDER BY ID ASC ";
                                                    $result = odbc_exec($connection, $sql1);
                                                    while ($rows = odbc_fetch_array($result)) {
                                                        ?>
                                                        <option value="<?php echo $rows['ID']; ?>" ><?php echo $rows['Code'] . " - " . $rows['Name']; ?></option>;
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-sm-2">
                                                <button type="submit" name="search" id="search-btn" class=" btn btn-flat bg-green"><i class="fa fa-search"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                    <?php
                                }
                                ?>
                                <!-- /.search form -->
                                <div class="box-tools pull-right" >
                                    <button id="excel" class="btn bg-blue" href="#" onClick ="$('#daybook2').tableExport({type: 'excel', escape: 'false'});">Export To Excel</button>
                                </div>

                            </div>
                        </div>

                        <div class="box-body">
                            <?php
                            if (isset($_POST['search'])) {
                                $query = "SELECT ID,Name FROM OfficeDetail  WHERE  ID = '" . $_POST['id'] . "'";
                                $sub = odbc_exec($connection, $query);
                                $p = odbc_fetch_array($sub);
                                $bname = $p['Name'];
                                echo "<h5 class='text-bold text-center'>" . $bname . " </h5>";
                            } else {
                                echo $branchName;
                            }
                            ?>
                            <table id="malemember" class="table table-responsive table-bordered table-striped">
                                <thead class="bg-red text-sm">
                                    <tr class="text-sm">
                                        <th>B.Code</th>
                                        <th>Office</th>
                                        <th>M.Code</th>
                                        <th>M.Name</th>
                                        <th>RegDate</th>
                                        <th>Gender</th>
                                        <th>Status</th>

                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $id = $_POST['id'];
                                    if ($_SESSION['BranchID'] == 1 AND $id == "") {
                                        $idx = "";
                                    } else if ($_SESSION['BranchID'] == 1 AND $id != "") {
                                        $idx = "and o.id = '$id'";
                                    } else {
                                        $idx = "and o.id = '" . $_SESSION['BranchID'] . "'";
                                    }
                                    if (isset($_POST['search']) AND $_SESSION['BranchID'] == 1) {
                                        $qry = "select o.Code,o.Name,m.Membercode,m.firstname+' '+m.lastname as MemberName,m.regdate,
                                                m.Gender,M.Status
                                                from member m ,officedetail o 
                                               where m.status='active' and m.gender='Male'and o.id=m.officeid $idx
                                               order by o.code, m.membercode";
                                    } else if($_SESSION['BranchID'] > 1){
                                        $qry = "select o.Code,o.Name,m.Membercode,m.firstname+' '+m.lastname as MemberName,m.regdate,
                                                m.Gender,M.Status
                                                from member m ,officedetail o 
                                               where m.status='active' and m.gender='Male'and o.id=m.officeid $idx
                                               order by o.code, m.membercode";
                                    }
                                    $result = odbc_exec($connection, $qry);
                                    while ($res = odbc_fetch_array($result)) {
                                        ?>
                                        <tr class="text-sm">
                                            <td><?php echo $res['Code']; ?></td>
                                            <td><?php echo $res['Name']; ?></td>
                                            <td><?php echo $res['Membercode']; ?></td>
                                            <td><?php echo $res['MemberName']; ?></td>
                                            <td><?php echo $res['regdate']; ?></td>
                                            <td><?php echo $res['Gender']; ?></td>
                                            <td><?php echo $res['Status']; ?></td>

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
    $('#malemember').DataTable({
        scrollX: true,
        scrollY: "300px",
        scrollCollapse: true,
        paging: false,
        dom: 'Bfrtip', 
		columnDefs: [
		{ "width": "5%", "targets": [0] },
		{ "width": "12%", "targets": [1,3] },
		{ "width": "5%", "targets": [2] },
		{ "width": "13%", "targets": [4,5,6] }
		],
		fixedColumns: {leftColumns: 4},
        buttons: [
            {
                extend: 'excel',
                filename: 'Member Personal Detail',
                title: 'Jeevan Bikas Samaj',
                messageTop: '<?php
if (isset($_POST['search'])) {
    echo $bname . "- Member Personal Detail ";
} else {
    echo $branchName . "- Member Personal Detail ";
};
?>',
            },
            {
                extend: 'pdf',
                filename: 'Member Personal Detail',
                title: 'Jeevan Bikas Samaj',
                messageTop: '<?php
if (isset($_POST['search'])) {
    echo $bname . "- Member Personal Detail ";
} else {
    echo $branchName . "- Member Personal Detail ";
};
?>',
            },
            {
                extend: 'print',
                filename: 'Member Personal Detail',
                title: 'Jeevan Bikas Samaj',
                messageTop: '<?php
if (isset($_POST['search'])) {
    echo $bname . "<br/> Member Personal Detail ";
} else {
    echo $branchName . "<br/> Member Personal Detail ";
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
