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
                <small>Member Verify</small>
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
                                <form  action="view_member.php" method="post" class="form-horizontal" >
                                    <div class=" form-group-sm">
                                        <div class="col-sm-4">
                                            <select name="id" id="id" class="form-control select2" required>
                                                <option value="">Select Center</option>
                                                <?php
                                                $sql1 = "select * from centermain where officeid = '" . $_SESSION['BranchID'] . "' ";
                                                $result = odbc_exec($connection, $sql1);
                                                while ($rows = odbc_fetch_array($result)) {
                                                    ?>
                                                <option value="<?php echo $rows['CenterID']; ?>" <?php
                                                    if (($rows['CenterID']) == ($_POST['id'])) {
                                                        echo "selected";
                                                    }else if($rows['CenterID'] == $_GET['id']){
                                                        echo "selected";
                                                    }
                                                    ?> ><?php echo $rows['CenterName']; ?></option>;
                                                            <?php
                                                        }
                                                        ?>
                                            </select>
                                        </div>

                                        <div class="col-sm-2">
                                            <button type="submit" name="search" id="search-btn" class=" btn btn-flat bg-green"><i class="fa fa-search"></i></button>                                   
                                            <a href="view_member.php" class="btn btn-flat bg-blue"><i class="fa fa-refresh"></i></a>
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
                            <div id="alert_message"></div>
                            <table id="trial" class="table display table-bordered text-sm">
                                <thead class="bg-red">
                                    <tr>
                                        <th>Member Name</th>
                                        <th>CitizenType</th>
                                        <th>CitizenNo</th>
                                        <th>IssueDate</th>
                                        <th>District</th>
                                        <th>FatherName</th>
                                        <th>GFatherName</th>
                                        <th>Action</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (isset($_POST['search'])) {
                                        $centerid = $_POST['id'];
                                        $query = "select MemberID,OfficeID,FirstName,LastName,CitizenShipNo,cDistrictID,IdTypeID,cIssueDate,FatherName,GrandFatherName,"
                                                . "(select IdentityType from IdentityType where ID = member.IdTypeID)IDType,"
                                                . "(select DistrictName from District where DistrictID = member.cDistrictID)District  from member "
                                                . "where status = 'ACTIVE' and centerid = '$centerid' "
                                                . "and officeid='" . $_SESSION['BranchID'] . "' ";
                                        $results = odbc_exec($connection, $query) or die(print_r(odbc_errormsg(), true));
                                    }else{
                                        $query = "select MemberID,OfficeID,FirstName,LastName,CitizenShipNo,cDistrictID,IdTypeID,cIssueDate,FatherName,GrandFatherName,"
                                                . "(select IdentityType from IdentityType where ID = member.IdTypeID)IDType,"
                                                . "(select DistrictName from District where DistrictID = member.cDistrictID)District  from member "
                                                . "where status = 'ACTIVE' and centerid = '".$_GET['id']."' "
                                                . "and officeid='" . $_SESSION['BranchID'] . "' ";
                                        $results = odbc_exec($connection, $query) or die(print_r(odbc_errormsg(), true));
                                    }
                                        while ($r = odbc_fetch_array($results)) {
                                            $memid = $r['MemberID'];
                                            $mname = $r['FirstName'] . " " . $r['LastName'];
                                            ?>
                                            <tr>
                                                <td><?php echo $r['FirstName'] . " " . $r['LastName']; ?></td>
                                                <td><?php echo $r['IDType']; ?></td>
                                                <td><?php echo $r['CitizenShipNo']; ?></td>
                                                <td><?php echo $r['cIssueDate']; ?></td>
                                                <td><?php echo $r['District']; ?></td>
                                                <td><?php echo ucfirst($r['FatherName']); ?></td>
                                                <td><?php echo ucfirst($r['GrandFatherName']); ?></td>
                                                <td class="text-center">
                                                    <a href="#updatedetail<?php echo $memid; ?>" data-target="#updatedetail<?php echo $memid; ?>" data-toggle="modal" style="color:#fff;" class="btn btn-flat btn-default"><i class="glyphicon glyphicon-edit text-blue"></i></a>
                                                </td>
                                            </tr>

                                        <div id="updatedetail<?php echo $memid; ?>" class="modal fade " tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
                                            <div class="modal-dialog">
                                                <div class="modal-content" style="height:auto">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">×</span></button>
                                                        <h4 class="modal-title text-red">Update Members Details - सदस्यको नागरिता हेरी जारिगर्ने जिल्ला, बुवाको नाम, ठेगाना,जन्ममिति र नागरिता जरिगर्ने मिति समेत भर्नुहोस् |</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form class="form-inline" role="form" action="update_member_detail.php" method="post">
                                                            <input type="hidden"  id="member_id" name="member_id" value="<?php echo $memid; ?>" required><br/>
                                                            <input type="hidden"  id="cid" name="cid" value="<?php if($_GET['id']){echo $_GET['id'];}else{echo $centerid;} ?>" required><br/>

                                                            <div class="form-group-sm">
                                                                <label class="control-label col-xs-6" for="MemberFName">First Name</label>
                                                                <div class="col-xs-6">
                                                                    <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo $r['FirstName']; ?>" required><br/>
                                                                </div>
                                                            </div>

                                                            <div class="form-group-sm">
                                                                <label class="control-label col-xs-6" for="MemberLName">Last Name</label>
                                                                <div class="col-xs-6">
                                                                    <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo $r['LastName']; ?>" required><br/>
                                                                </div>
                                                            </div>

                                                            <div class="form-group-sm">
                                                                <label class="control-label col-xs-6" for="CitiType">CitizenShip Type</label>
                                                                <div class="col-xs-6">
                                                                    <select name="ctypeid" id="ctypeid" class="form-control select2" required style="width:250px;">
																	<option value="">Select ID Type</option>
                                                                        <?php
                                                                        $qry = odbc_exec($connection, "select * from IdentityType");
                                                                        while ($res = odbc_fetch_array($qry)) {
                                                                            ?>
                                                                            <option value="<?php echo $res['ID']; ?>" <?php
                                                                            if ($res['ID'] == $r['IdTypeID']) {
                                                                                echo "selected";
                                                                            }
                                                                            ?>><?php echo $res['IdentityType']; ?></option>
                                                                                    <?php
                                                                                }
                                                                                ?>
                                                                    </select>
                                                                </div><br/><br/><br/><br/><br/><br/><br/>
                                                            </div>

                                                            <div class="form-group-sm">
                                                                <label class="control-label col-xs-6" for="CitiNo">CitizenShip No</label>
                                                                <div class="col-xs-6">
                                                                    <input type="text" class="form-control" id="citino" name="citino" value="<?php echo $r['CitizenShipNo']; ?>" required><br/>
                                                                </div>
                                                            </div>

                                                            <div class="form-group-sm">
                                                                <label class="control-label col-xs-6" for="Date">Issue Date</label>
                                                                <div class="col-xs-6">
                                                                    <input type="text" class="nepali-calendar form-control" id="date1" name="date1" placeholder="0000/00/00" value="<?php
                                                                    if ($r['cIssueDate']) {
                                                                        echo $r['cIssueDate'];
                                                                    }
                                                                    ?>" required><br/>
                                                                </div>
                                                            </div>

                                                            <div class="form-group-sm">
                                                                <label class="control-label col-xs-6" for="CitiType">District</label>
                                                                <div class="col-xs-6">
                                                                    <select name="did" id="did" class="form-control select2" required style="width:250px;">
																	<option value="">Select District</option>
                                                                        <?php
                                                                        $qr = odbc_exec($connection, "select * from District");
                                                                        while ($reso = odbc_fetch_array($qr)) {
                                                                            ?>
                                                                            <option value="<?php echo $reso['DistrictID']; ?>" <?php
                                                                            if ($reso['DistrictID'] == $r['cDistrictID']) {
                                                                                echo "selected";
                                                                            }
                                                                            ?>><?php echo $reso['DistrictName']; ?></option>
                                                                                    <?php
                                                                                }
                                                                                ?>
                                                                    </select>
                                                                </div><br/><br/><br/><br/><br/><br/><br/>
                                                            </div>

                                                            <div class="form-group-sm">
                                                                <label class="control-label col-xs-6" for="FatheName">Father Name</label>
                                                                <div class="col-xs-6">
                                                                    <input type="text" class="form-control" id="father_name" name="father_name" value="<?php echo $r['FatherName']; ?>" required><br/>
                                                                </div>

                                                            </div>
                                                            <div class="form-group-sm">
                                                                <label class="control-label col-xs-6" for="GFatheName">Grand Father Name</label>
                                                                <div class="col-xs-6">
                                                                    <input type="text" class="form-control" id="gfather_name" name="gfather_name" value="<?php echo $r['GrandFatherName']; ?>" ><br/>
                                                                </div>
                                                            </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary pull-left">Save changes</button>
                                                        <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Close</button>
                                                    </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <!--end of modal-->                    
                                        </div><!-- /.box-body -->
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
<script type="text/javascript" language="javascript" >
    $(document).ready(function () {
        $('.modal #date1').nepaliDatePicker();
    });
</script>