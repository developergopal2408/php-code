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
                <small>Pension Card List</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Pension Card List</li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-danger">
                        <div class="box-header with-border">
                            <div class="col-sm-12">
                                <?php
                                if ($_SESSION['BranchID'] == 1) {
                                    ?>
                                    <!-- search form -->
                                    <form  action="" method="post" class="form-horizontal" >
                                        <div class=" form-group-sm">
                                            <div class="col-sm-3">
                                                <select name="id" id="id" class="form-control select2" required>
                                                    <option value="">Select Branch</option>
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
                                                <button type="submit" name="search" id="search-btn" class=" btn btn-flat bg-green" data-toggle="tool-tip" title="Search"><i class="fa fa-search"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                    <!-- /.search form -->
                                    <?php
                                }
                                ?>
                                <div class="box-tools pull-right" >
                                    <a href="pension.php" class="btn btn-flat bg-blue" data-toggle="tool-tip" title="Refresh"><i class="fa fa-refresh"></i></a>
                                </div>

                            </div>
                        </div>

                        <div class="box-body">
                            <div class="box-title with-header text-bold text-center">
                                <?php
                                if (isset($_POST['id'])) {
                                    $query = "SELECT ID,Name,Code FROM OfficeDetail  WHERE  ID = '" . $_POST['id'] . "'";

                                    $sub = sqlsrv_query($connection, $query);
                                    $p = sqlsrv_fetch_array($sub);
                                    echo $p['Name'];
                                } else {
                                    echo $branchName;
                                }
                                ?>
                            </div>
                            <table id="pension" class="stripe row-border order-column" cellspacing="0" width="100%"> 
                                <thead class="bg-red text-sm" >
                                    <tr>
                                        <th>Member Code</th>
                                        <th>Member NAME</th>
                                        <th>DOB</th>
                                        <th>CentreName</th>
                                        <th>Address</th>
                                        <th>RegDate</th>
                                        <th>Print</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    <?php
                                    $id = $_POST['id'];
                                    if ($_SESSION['BranchID'] == 1) {
                                        $idx = "and m.officeid='$id'";
                                    } else {
                                        $idx = "and m.officeid = '" . $_SESSION['BranchID'] . "'";
                                    }
                                    if (isset($_POST['search'])) {
                                        $qry = "select m.memberid,m.officeid,m.membercode,
                                                m.firstname+' '+m.Lastname as MemberName,m.DOB,
                                                (select centername from centermain where centerid=m.centerid and officeid=m.officeid)centername,m.spouseFather,m.Fatherinlaw,v.vdcname+' '+m.wardno as Address ,m.regdate,m.photo
                                                from member m, vdc v
                                                where m.vdcid=v.vdcid
                                                 $idx and m.status='active'
                                                group by m.memberid,m.membercode,m.firstname,m.Lastname ,
                                                m.DOB,m.spouseFather,m.Fatherinlaw,m.wardno ,m.regdate,m.photo,
                                                m.centerid,m.officeid,v.vdcname
                                                order by m.membercode";
                                    } else if ($_SESSION['BranchID'] > 1) {
                                        $qry = "select m.memberid,m.officeid,m.membercode,
                                                m.firstname+' '+m.Lastname as MemberName,m.DOB,
                                                (select centername from centermain where centerid=m.centerid and officeid=m.officeid)centername,m.spouseFather,m.Fatherinlaw,v.vdcname+' '+m.wardno as Address ,m.regdate,m.photo
                                                from member m, vdc v
                                                where m.vdcid=v.vdcid
                                                 $idx and m.status='active'
                                                group by m.memberid,m.membercode,m.firstname,m.Lastname ,
                                                m.DOB,m.spouseFather,m.Fatherinlaw,m.wardno ,m.regdate,m.photo,
                                                m.centerid,m.officeid,v.vdcname
                                                order by m.membercode";
                                    }
                                    $result = sqlsrv_query($connection, $qry);
                                    while ($res = sqlsrv_fetch_array($result)) {
                                        $id = $res['officeid'];
                                        $mid = $res['memberid'];
                                        ?>
                                        <tr>
                                            <td><?php echo $res['membercode']; ?></td>
                                            <td><?php echo $res['MemberName']; ?></td>
                                            <td><?php echo $res['DOB']; ?></td>
                                            <td><?php echo $res['centername']; ?></td>
                                            <td><?php echo $res['Address']; ?></td>
                                            <td><?php echo $res['regdate']; ?></td>
                                            <td>
                                                <a href="pensionpdf.php?mid=<?php echo $mid; ?>&officeid=<?php echo $id; ?>" target="_new" class="btn btn-flat bg-red"><i class="glyphicon glyphicon-print"></i></a>
                                            </td>
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
    $('#pension').DataTable({
        scrollY: "300px",
        scrollCollapse: true,
        paging: false
    });


</script>
