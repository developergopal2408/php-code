<?php
ob_start();
session_start();
require_once '../db.php';
if (!isset($_SESSION['StaffID'])) {
    header('Location:../login.php');
}
$sql = "SELECT * FROM OfficeDetail WHERE ID='" . $_SESSION['BranchID'] . "' ";
$res = odbc_exec($connection, $sql);
$row = odbc_fetch_array($res);
$branchName = $row['Name'];
$Code = $row['Code'];
$_SESSION['ID'] = $row['ID'];
include_once 'header.php';
require_once('../js/nepali_calendar.php');
require_once('../js/functions.php');
$cal = new Nepali_Calendar();
list($year, $month, $day) = explode('/', date('Y/m/d'));
$nepdate = $cal->eng_to_nep($year, $month, $day);
$ndate = $cal->eng_to_nep($year, $month, $day);
$sdate = $ndate['year'] . "/" . $ndate['month'] . "/" . '01';
$nyr = $nepdate['year'];
$nmonth = $nepdate['month'];
$nday = $nepdate['date'];
$cdate = $nyr . "/" . $nmonth . "/" . $nday;
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
                <small>LIC OPEN/CLOSE</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">LIC OPEN/CLOSE </li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-danger">
                        <div class="box-header with-border">
                            <h4 class="text-bold text-red">LIC OPEN/CLOSE</h4>
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
                                            <select name="id" id="id" class="form-control select2" required>
                                                <option value="">Select Status</option>
                                                <option value="open" >Open</option> 
                                                <option value="close" >Close</option>                                                   
                                            </select>
                                        </div>

                                        <div class="col-sm-2">
                                            <button type="submit" name="search" id="search-btn" class=" btn btn-flat bg-green"><i class="fa fa-search"></i></button>
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
                            <div class="box-title with-header text-bold text-center">
                                <?php
                                if (isset($_POST['id'])) {
                                    echo "( " . $_POST['date1'] . " - " . $_POST['date2'] . " ) " . $_POST['id'];
                                }
                                ?>
                            </div>
                            <table id="trial" class="table table-responsive table-bordered table-striped"  >
                                <thead class="bg-red text-sm">
                                    <?php
                                    if (isset($_POST['search'])) {
                                        $id = $_POST['id'];
                                        ?>
                                        <tr>
                                            <th>Code</th>
                                            <th>Name</th>
                                            <th>MemberCode</th>
                                            <th>MemberName</th>
                                            <?php
                                            if ($id == "open") {
                                                ?>
												<th>DOB</th>
                                                <th>StartDate</th>
                                                <th>InsuredAmount</th>
                                                <th>PolicyNo</th>
                                                <th>Finstamount</th>
                                                <th>Instamount</th>
                                                <?php
                                            } else if ($id == "close") {
                                                ?>
                                                <th>Status</th>
                                                <th>Closedate</th>
												<th>PolicyNo</th>
                                                <th>Startdate</th>
                                                <th>InsuredAmount</th>
                                                <th>Finstamount</th>
                                                <th>Instamount</th>
                                                <?php
                                            }
                                            ?>

                                        </tr>

                                        <?php
                                    }
                                    ?>
                                </thead>
                                <tbody>

                                    <?php
									if($_SESSION['ID'] == 1){
										$id = "";
									}else{
										$id = "and i.officeid = '" . $_SESSION['ID'] . "'";
									}
                                    if (isset($_POST['search'])) {
                                        $ID = $_POST['id'];
                                        $date1 = $_POST['date1'];
                                        $date2 = $_POST['date2'];
                                        if ($ID == 'open') {
                                            $qry = "select o.Code,o.Name, m.membercode,m.dob,m.firstname+' '+ m.lastname as MemberName,i.startdate,i.insuredamount,i.policyno, i.finstamount,i.Instamount 
                                                    from member m, insuranceaccount i,Officedetail o
                                                    where m.memberid=i.memberid and i.isactive='y' and m.status='active' and i.startdate>='$date1' and i.startdate<='$date2'
                                                    and o.id=m.officeid and o.id=i.officeid $id
                                                    order by i.startdate, o.Code";
                                        } else if ($ID == 'close') {

                                            $qry = "select o.Code,o.Name,m.memberid,m.membercode,m.firstname+' '+m.lastname as MemberName,M.status,i.CloseDate,i.policyno, i.Startdate,i.InsuredAmount,i.FinstAmount,i.InstAmount
                                                    from insuranceaccount i, member m, officedetail o
                                                    where m.memberid=i.memberid and o.id=i.officeid and m.officeid=o.id and i.isactive='N' and i.closedate between '$date1' and '$date2'
                                                    and m.officeid=o.id $id
                                                    order by i.closedate, o.name";
                                        }
                                        $result = odbc_exec($connection, $qry);
                                        while ($res = odbc_fetch_array($result)) {
                                            ?>
                                            <tr>
                                                <td><?php echo $res['Code']; ?></td>
                                                <td><?php echo $res['Name']; ?></td>
                                                <td><?php echo $res['membercode']; ?></td>
                                                <td><?php echo $res['MemberName']; ?></td>
                                                <?php
                                                if ($ID == 'open') {
                                                    ?>
													<td><?php echo $res['dob']; ?></td>
                                                    <td><?php echo $res['startdate']; ?></td>
                                                    <td><?php echo $res['insuredamount']; ?></td>
                                                    <td><?php echo $res['policyno']; ?></td>
                                                    <td><?php echo $res['finstamount']; ?></td>
                                                    <td><?php echo $res['Instamount']; ?></td>
                                                    <?php
                                                } else if ($ID == 'close') {
                                                    ?>
                                                    <td><?php echo $res['status']; ?></td>
                                                    <td><?php echo $res['CloseDate']; ?></td>
													<td><?php echo $res['policyno']; ?></td>
                                                    <td><?php echo $res['Startdate']; ?></td>
                                                    <td><?php echo $res['InsuredAmount']; ?></td>
                                                    <td><?php echo $res['FinstAmount']; ?></td>
                                                    <td><?php echo $res['InstAmount']; ?></td>

                                                    <?php
                                                }
                                                ?>
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
	

