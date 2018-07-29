<?php
ob_start();
session_start();
require_once '../db.php';
if (!isset($_SESSION['StaffID'])) {
    header('Location:../login.php');
}
include_once 'sidebar_header.php'; //Include Sidebar_header.php-->
include_once 'sidebar.php'; //Include Sidebar.php-->
$sql = "SELECT * FROM OfficeDetail WHERE ID='" . $_SESSION['BranchID'] . "' ";
$res = odbc_exec($connection, $sql);
$row = odbc_fetch_array($res);
$branchName = $row['Name'];
$Code = $row['Code'];
$_SESSION['ID'] = $row['ID'];
//echo $branchName;
include_once 'header.php';
require_once('../js/nepali_calendar.php');
require_once('../js/functions.php');
$cal = new Nepali_Calendar();
list($year, $month, $day) = explode('/', date('Y/m/d'));
$nepdate = $cal->eng_to_nep($year, $month, $day);
$ndate = $cal->eng_to_nep($year, $month, 01);
$sdate = $ndate['year'] . "/" . $ndate['month'] . "/" . '01';
$nyr = $nepdate['year'];
$nmonth = $nepdate['month'];
$nday = $nepdate['date'];
$cdate = $nyr . "/" . $nmonth . "/" . $nday;
//$cdate = "2074/06/24";
?>
<!-- Site wrapper -->
<div class="wrapper">
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                <i class="fa fa-building"></i> <?php echo $branchName; ?>

                <small>Member PPI</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Member PPI</li>
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
                                            <input maxlength="10" type="text" name="date2" id="date2" class=" nepali-calendar form-control" placeholder="Select Date" 
                                                   value="<?php
                                                   if (isset($_POST['date2'])) {
                                                       echo $_POST['date2'];
                                                   } else {
                                                       echo $cdate;
                                                   }
                                                   ?>"
                                                   >
                                        </div>
                                        <?php
                                        if ($_SESSION['BranchID'] == 1) {
                                            ?>
                                            <div class="col-sm-3">
                                                <select name="id" id="id" class="form-control select2" required>
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
                                            <?php
                                        }
                                        ?>
                                        <div class="col-sm-2">
                                            <button type="submit" name="search" id="search-btn" class=" btn btn-flat bg-green"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                </form>
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
                                if ($_POST['id']) {
                                    echo "<span class='text-bold'>" . $bname . " ( " . $_POST['date1'] . " - " . $_POST['date2'] . " )</span>";
                                } else {
                                    echo "<span class='text-bold'>" . "( " . $_POST['date1'] . " - " . $_POST['date2'] . " )</span>";
                                }
                            }
                            ?>
                            <table id="daybook2" class="table table-responsive table-bordered table-striped">
                                <thead class="bg-red ">
                                    <tr class="text-sm">
                                        <th>MemberCode</th>
                                        <th>MemberName</th>
                                        <th>VDC</th>
                                        <th>WardNo</th>
                                        
                                        <th>CookingType</th>
                                        <th>GuardianJob</th>
                                        <th>HomeAppliance</th>
                                        <th>House</th>
                                        <th>IrrigationType</th>
                                        <th>IsKitchen</th>
                                        <th>LightSystem</th>
                                        <th>NosRoom</th>
                                        <th>Remarks</th>
                                        <th>RoofType</th>
                                        <th>TelePhone</th>
                                        <th>ToiletType</th>
                                        <th>VehicleType</th>
                                        <th>WallType</th>
										<th>SaveDate</th>
                                        <th>PreparedBy</th>
										<th>Res. Staff</th>

                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $id = $_POST['id'];
                                    $date1 = $_POST['date1'];
                                    $date2 = $_POST['date2'];
                                    $id = $_POST['id'];
                                    if (isset($_POST['search']) AND $_SESSION['BranchID'] == 1) {
                                        /*$qry = "select m.MemberCode,m.firstname+' '+m.lastname as MemberName,v.VdcName,m.WardNo,i.Savedate,i.CookingType,i.GuardianJob,i.HomeAppliance,i.House,i.IrrigationType,i.IsKitchen,
                                                i.LightSystem,i.NosRoom,i.RoofType,i.TelePhone,i.ToiletType,i.VehicleType,i.WallType,i.Remarks,
                                                (select firstname+' '+Lastname from staffmain where staffid=i.preparedBy)StaffName
                                                from Member m
                                                join VDC v on (v.VdcID =m.VdcID )
                                                left join MembersPPI  i on (m.MemberID=i.MemberID and m.OfficeID =i.officeid and i.SaveDate between '$date1' and '$date2' )
                                                where m.officeid = '$id'  and m.Status='ACTIVE'  
                                                order by m.MemberCode";*/
												
										$qry = "select m.membercode,m.firstname+' '+m.lastname as MemberName,v.vdcname,m.wardno,i.savedate,i.cookingtype,i.guardianjob,i.homeappliance,i.House,i.irrigationtype,i.iskitchen,i.lightsystem,
										i.nosroom,i.Remarks,i.rooftype,i.telephone,i.toilettype,i.vehicletype,i.walltype,
											(select firstname+' '+Lastname from staffmain where staffid=i.preparedBy)PreparedBy,s.firstname+' '+s.lastname as Res_staff
											from Member m
											join VDC v on (v.VdcID =m.VdcID )
											join centermain c on c.centerid=m.centerid and m.officeid=c.officeid
											join staffmain s on s.staffid=c.staffid and s.branchid=c.officeid
											left join MembersPPI  i on (m.MemberID=i.MemberID and m.OfficeID =i.officeid and i.SaveDate =(select max(savedate )from membersppi where memberid=i.memberid and 
											officeid=i.officeid and savedate>='$date1' and savedate<='$date2'))
											where m.officeid='$id'  and m.Status='ACTIVE'
											order by m.membercode";		
                                    } else if (isset($_POST['search']) AND $_SESSION['BranchID'] > 1) {
                                       $qry = "select m.membercode,m.firstname+' '+m.lastname as MemberName,v.vdcname,m.wardno,i.savedate,i.cookingtype,i.guardianjob,i.homeappliance,i.House,i.irrigationtype,i.iskitchen,i.lightsystem,
										i.nosroom,i.Remarks,i.rooftype,i.telephone,i.toilettype,i.vehicletype,i.walltype,
											(select firstname+' '+Lastname from staffmain where staffid=i.preparedBy)PreparedBy,s.firstname+' '+s.lastname as Res_staff
											from Member m
											join VDC v on (v.VdcID =m.VdcID )
											join centermain c on c.centerid=m.centerid and m.officeid=c.officeid
											join staffmain s on s.staffid=c.staffid and s.branchid=c.officeid
											left join MembersPPI  i on (m.MemberID=i.MemberID and m.OfficeID =i.officeid and i.SaveDate =(select max(savedate )from membersppi where memberid=i.memberid and 
											officeid=i.officeid and savedate>='$date1' and savedate<='$date2'))
											where m.officeid='".$_SESSION['BranchID']."'  and m.Status='ACTIVE'
											order by m.membercode";
											
                                    }
                                    $result = odbc_exec($connection, $qry);
                                    while ($res = odbc_fetch_array($result)) {
                                        ?>
                                        <tr class="text-sm">
                                            <td><?php echo $res['membercode']; ?></td>
                                            <td><?php echo $res['MemberName']; ?></td>
                                            <td><?php echo $res['vdcname']; ?></td>
                                            <td><?php echo $res['wardno']; ?></td>
                                            
                                            <td><?php echo $res['cookingtype']; ?></td>
                                            <td><?php echo $res['guardianjob']; ?></td>
                                            <td><?php echo $res['homeappliance']; ?></td>
                                            <td><?php echo $res['House']; ?></td>
                                            <td><?php echo $res['irrigationtype']; ?></td>
                                            <td><?php echo $res['iskitchen']; ?></td>
                                            <td><?php echo $res['lightsystem']; ?></td>
                                            <td><?php echo $res['nosroom']; ?></td>
                                            <td><?php echo $res['Remarks']; ?></td>
                                            <td><?php echo $res['rooftype']; ?></td>
                                            <td><?php echo $res['telephone']; ?></td>
                                            <td><?php echo $res['toilettype']; ?></td>
                                            <td><?php echo $res['vehicletype']; ?></td>
                                            <td><?php echo $res['walltype']; ?></td>
											<td><?php echo $res['savedate']; ?></td>
                                            <td><?php echo $res['PreparedBy']; ?></td>
											<td><?php echo $res['Res_staff']; ?></td>
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
