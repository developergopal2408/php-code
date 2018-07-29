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
                <small>Center Wise Compulsory Reg Amount</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Center Wise Compulsory Reg Amount</li>
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
                                echo "<h5 class='text-bold text-center'>" . $bname . " </h5>";
                            } else {
                                echo "<h5 class='text-bold text-center'>" . $branchName . " </h5>";
                            }
                            ?>
                            <table id="cwc" class="table display table-condensed table-bordered table-striped" style="width:auto;">
                                <thead class="bg-red text-sm">
                                    <tr>
                                        <th>CenterCode</th>
                                        <th>CenterName</th>
                                        <th>CenterMeetingType</th>
                                        <th>Com_SetUp_Amt</th>
                                        <th>StaffCode</th>
                                        <th>StaffName</th>
                                    </tr>
                                </thead>

                                <tbody  class="text-sm">
                                    <?php
                                    $id = $_POST['id'];
                                    if ($_SESSION['BranchID'] == 1 AND $id == "") {
                                        $idx = "";
                                    } else if ($_SESSION['BranchID'] == 1 AND $id != "") {
                                        $idx = "and s.officeid='$id' and c.officeid='$id' and a.branchid='$id'";
                                    } else {
                                        $idx = "and s.officeid = '" . $_SESSION['BranchID'] . "' and c.officeid='" . $_SESSION['BranchID'] . "' and a.branchid='" . $_SESSION['BranchID'] . "' ";
                                    }
                                    if (isset($_POST['search']) AND $_SESSION['BranchID'] == 1) {
                                        $qry = "select c.CenterCode,c.CenterName,(m.intcroption)CenterMeetingType,
                                                (s.Amount)Reg_compulsory_Amt,(a.Code)StaffCode,a.firstname+' '+a.lastname as StaffName
                                                from centersetting s,centermain c,staffmain a,intcroptionloan m
                                                where c.centerid=s.centerid and c.active='Y' and s.typeid=2 and s.Type='SAVING'
                                                and c.staffid=a.staffid and c.meetingtype=m.intcroptionid 
                                                $idx 
                                                order by c.centercode";
                                    } else if ($_SESSION['BranchID'] > 1) {
                                        $qry = "select c.CenterCode,c.CenterName,(m.intcroption)CenterMeetingType,
                                                (s.Amount)Reg_compulsory_Amt,(a.Code)StaffCode,a.firstname+' '+a.lastname as StaffName
                                                from centersetting s,centermain c,staffmain a,intcroptionloan m
                                                where c.centerid=s.centerid and c.active='Y' and s.typeid=2 and s.Type='SAVING'
                                                and c.staffid=a.staffid and c.meetingtype=m.intcroptionid 
                                                $idx 
                                                order by c.centercode";
                                    }
                                    $result = sqlsrv_query($connection, $qry);
                                    while ($res = sqlsrv_fetch_array($result)) {
                                        ?>
                                        <tr>
                                            <td><?php echo $res['CenterCode']; ?></td>
                                            <td><?php echo $res['CenterName']; ?></td>
                                            <td><?php echo $res['CenterMeetingType']; ?></td>
                                            <td><?php echo $res['Reg_compulsory_Amt']; ?></td>
                                            <td><?php echo $res['StaffCode']; ?></td>
                                            <td><?php echo $res['StaffName']; ?></td>
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
    $('#cwc').DataTable({
        scrollY: "300px",
        scrollCollapse: true,
        paging: false,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                filename: 'Centerwise Comp Saving SetUp',
                title: 'Jeevan Bikas Samaj',
                messageTop: '<?php
if (isset($_POST['search'])) {
    echo $bname . "- Centerwise Comp Saving SetUp ";
} else {
    echo $branchName . "- Centerwise Comp Saving SetUp ";
};
?>',
            },
            {
                extend: 'pdf',
                filename: 'Centerwise Comp Saving SetUp',
                title: 'Jeevan Bikas Samaj',
                messageTop: '<?php
if (isset($_POST['search'])) {
    echo $bname . "- Centerwise Comp Saving SetUp";
} else {
    echo $branchName . "- Centerwise Comp Saving SetUp ";
};
?>',
            },
            {
                extend: 'print',
                filename: 'Centerwise Comp Saving SetUp',
                title: 'Jeevan Bikas Samaj',
                messageTop: '<?php
if (isset($_POST['search'])) {
    echo $bname . "<br/> Centerwise Comp Saving SetUp ";
} else {
    echo $branchName . "<br/> Centerwise Comp Saving SetUp ";
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