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
                <small>Member's List For Cheque</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Members List</li>
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
                                        <div class="col-sm-4">
                                            <select name="id" id="id" class="form-control select2" required>
                                                <option value="">Select Center</option>
                                                <?php
                                                $sql1 = "select * from centermain where officeid = '" . $_SESSION['BranchID'] . "' ";
                                                $result = odbc_exec($connection, $sql1);
                                                while ($rows = odbc_fetch_array($result)) {
                                                    ?>
                                                    <option value="<?php echo $rows['CenterID']; ?>" ><?php echo $rows['CenterCode'] . " - " . $rows['CenterName']; ?></option>;
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="col-sm-2">
                                            <button type="submit" name="search" id="search-btn" class=" btn btn-flat bg-green"><i class="fa fa-search"></i></button>                                   
                                            <a href="MemberCheck.php" class="btn btn-flat bg-blue"><i class="fa fa-refresh"></i></a>
                                        </div>
                                    </div>
                                </form>
                                <!-- /.search form -->

                                <div class="box-tools pull-right" >
                                    <button id="excel" class="btn bg-blue" href="#" onClick ="$('#trial').tableExport({type: 'excel', escape: 'false'});">Export To Excel</button>
                                </div>
                            </div>
                        </div>
                        <div class="box-body">
                            <table id="trial" class="table table-bordered text-sm">
                                <thead class="bg-red">
                                    <tr>
                                        <th>Member ID</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Member Code</th>
                                        <th>Action</th>
                                    </tr>
                                <tbody>
                                    <?php
                                    if (isset($_POST['search'])) {
                                        $centerid = $_POST['id'];
                                        $query = "select * from member where status = 'ACTIVE' and centerid = '$centerid' and officeid='" . $_SESSION['BranchID'] . "' ";
                                        $results = odbc_exec($connection, $query);
                                        while ($r = odbc_fetch_array($results)) {
                                            ?>
                                            <tr>
                                                <td><?php echo $r['MemberID']; ?></td>
                                                <td><?php echo $r['FirstName']; ?></td>
                                                <td><?php echo $r['LastName']; ?></td>
                                                <td><?php echo $r['MemberCode']; ?></td>
                                                <td class="text-center">
                                                    <a href="cprint.php?memberid=<?php echo $r['MemberID']; ?>" target="_new" class="btn btn-sm bg-red-active"><i class="glyphicon glyphicon-print"></i></a>
                                                </td>                                               
                                            </tr>


                                            <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                                </thead>

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
