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
                                                    $result = sqlsrv_query($connection, $sql1);
                                                    while ($rows = sqlsrv_fetch_array($result)) {
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
                                    <a  href="malemember.php"  class="btn btn-flat bg-blue" data-toggle = "tooltip" title="All / Refresh"><i class="fa fa-refresh"></i></a>
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
                                echo "<h5 class='text-bold text-center'>" . $bname . " </h5>";
                            } else {
                                echo "<h5 class='text-bold text-center'>" . $branchName. " </h5>";
                            }
                            ?>
                            <table id="malemember" class="table display table-condensed table-bordered table-striped" style="width:auto;">
                                <thead class="bg-red text-sm">
                                    <tr>
                                        <th>BranchCode</th>
                                        <th>Office</th>
                                        <th>MemberCode</th>
                                        <th>MemberName</th>
                                        <th>RegDate</th>
                                        <th>Gender</th>
                                        <th>Status</th>

                                    </tr>
                                </thead>

                                <tbody class="text-sm">
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
                                    } else if ($_SESSION['BranchID'] > 1) {
                                        $qry = "select o.Code,o.Name,m.Membercode,m.firstname+' '+m.lastname as MemberName,m.regdate,
                                                m.Gender,M.Status
                                                from member m ,officedetail o 
                                               where m.status='active' and m.gender='Male'and o.id=m.officeid $idx
                                               order by o.code, m.membercode";
                                    }
                                    $result = sqlsrv_query($connection, $qry);
                                    while ($res = sqlsrv_fetch_array($result)) {
                                        ?>
                                        <tr>
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
        scrollY: "300px",
        scrollCollapse: true,
        paging: false,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                filename: 'Male Member Detail',
                title: 'Jeevan Bikas Samaj',
                messageTop: '<?php
if (isset($_POST['search'])) {
    echo $bname . "- Male Member Detail ";
} else {
    echo $branchName . "- Male Member Detail ";
};
?>',
            },
            {
                extend: 'pdf',
                filename: 'Male Member Detail',
                title: 'Jeevan Bikas Samaj',
                messageTop: '<?php
if (isset($_POST['search'])) {
    echo $bname . "- Male Member Detail ";
} else {
    echo $branchName . "- Male Member Detail ";
};
?>',
            },
            {
                extend: 'print',
                filename: 'Male Member Detail',
                title: 'Jeevan Bikas Samaj',
                messageTop: '<?php
if (isset($_POST['search'])) {
    echo $bname . "<br/> Male Member Detail ";
} else {
    echo $branchName . "<br/> Male Member Detail ";
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