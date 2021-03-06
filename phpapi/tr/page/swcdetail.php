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

                <small>Staff Wise Center Detail</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Staff Wise Center Detail</li>
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
                                    <a  href="swcdetail.php"  class="btn btn-flat bg-blue" data-toggle = "tooltip" title="All / Refresh"><i class="fa fa-refresh"></i></a>
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
                            <table id="swc" class="table table-condensed table-bordered table-striped display" style="width:auto;">
                                <thead class="bg-red text-sm" style="font-size:10px;">
                                    <tr>
                                        <th>StaffCode</th>
                                        <th>StaffName</th>
                                        <th>CenterNo</th>
                                        <th>Grade</th>
                                        <th>IsRural</th>
                                        <th>A.Member</th>
                                        <th>Borrower</th>
                                        <th>PenSaver</th>
                                        <th>LICSaver</th>
                                        <th>PhotoUpdate</th>
                                        <th>Msign</th>
                                        <th>MobileNoUp</th>
                                        <th>DuplicationMem</th>
                                        <th>CHouse</th>
                                        <th>CDress</th>
                                        <th>AttendanceReg</th>
                                        <th>VdcName</th>
                                        <th>WardNo</th>



                                    </tr>
                                </thead>

                                <tbody class="text-sm" style="font-size:10px;">
                                    <?php
                                    $totalmember = $totalborrower = $totalpension = $totallic = $totalphoto = 0;
                                    $totalmsign = $totalmnu = $totaldm = $totalch = $totalcd = $totalatt = 0;


                                    $id = $_POST['id'];
                                    if ($_SESSION['BranchID'] == 1 AND $id != "") {
                                        $idx = "and officeid = '$id'";
                                        $idt = "and s.branchid = '$id'";
                                    } else {
                                        $idx = "and officeid = '" . $_SESSION['BranchID'] . "'";
                                        $idt = "and s.branchid = '" . $_SESSION['BranchID'] . "'";
                                    }
                                    if (isset($_POST['search']) AND $_SESSION['BranchID'] == 1) {
                                        $qry = "select (s.Code)StaffCode,s.firstname+' '+s.lastname as StaffName,(c.centercode)CenterNo,
                                            c.grade,c.IsRural,
(select count(memberid) from member where status='active' and centerid=c.centerid $idx)TotalMember,
(select count(distinct memberid)from loanviewmember where loanbalance>0 and centerid=c.centerid $idx )Borrower,
(select count(distinct memberid)from savingaccount where isactive='Y' and centerid=c.centerid $idx) PensionSever,
(select count(memberid)from insuranceaccount where isactive='Y' and centerid=c.centerid $idx)LICSever,
(select count(memberid) from member where Photo<>'' and centerid=c.centerid and status='active' $idx)PhotoUpdate,
(select count(memberid) from member where mSign<>'' and centerid=c.centerid and status='active' $idx)mSign,
(select count(memberid) from member where centerid=c.centerid $idx and memberid in
(select memberid from member where status='active' $idx and centerid=c.centerid  group by mobileno ,memberid having len(mobileNo)=10)
 Group by centerid) MobileNoUpdate,
(select count(memberid) as nos from member where status='active' and centerid=c.centerid and catID=2 $idx)DuplicationMember,
(select count(centerid) from centermain where centerHouse='Y' and centerid=c.centerid $idx)CenterHouse,
(select count(centerid) from centermain where CenterDress='Y' and centerid=c.centerid $idx)CenterDress,
(select count(centerid) from centermain where AttendanceRegister='Y' and centerid=c.centerid $idx )AttendanceReg,
v.VDCName,c.WardNo
from staffmain s,centermain c,VDC v
where s.staffid=c.staffid and c.active='Y' and v.vdcid=c.vdcid $idt
group by s.code,s.firstname,s.lastname,c.centerid,c.centercode,c.IsRural,v.VDCName,c.WardNo,c.grade
order by s.code,c.centercode";
                                    } else if ($_SESSION['BranchID'] > 1) {
                                        $qry = "select (s.Code)StaffCode,s.firstname+' '+s.lastname as StaffName,(c.centercode)CenterNo,c.IsRural,
(select count(memberid) from member where status='active' and centerid=c.centerid $idx)TotalMember,
(select count(distinct memberid)from loanviewmember where loanbalance>0 and centerid=c.centerid $idx )Borrower,
(select count(distinct memberid)from savingaccount where isactive='Y' and centerid=c.centerid $idx) PensionSever,
(select count(memberid)from insuranceaccount where isactive='Y' and centerid=c.centerid $idx)LICSever,
(select count(memberid) from member where Photo<>'' and centerid=c.centerid and status='active' $idx)PhotoUpdate,
(select count(memberid) from member where mSign<>'' and centerid=c.centerid and status='active' $idx)mSign,
(select count(memberid) from member where centerid=c.centerid $idx and memberid in
(select memberid from member where status='active' $idx and centerid=c.centerid  group by mobileno ,memberid having len(mobileNo)=10)
 Group by centerid) MobileNoUpdate,
(select count(memberid) as nos from member where status='active' and centerid=c.centerid and catID=2 $idx)DuplicationMember,
(select count(centerid) from centermain where centerHouse='Y' and centerid=c.centerid $idx)CenterHouse,
(select count(centerid) from centermain where CenterDress='Y' and centerid=c.centerid $idx)CenterDress,
(select count(centerid) from centermain where AttendanceRegister='Y' and centerid=c.centerid $idx )AttendanceReg,
v.VDCName,c.WardNo
from staffmain s,centermain c,VDC v
where s.staffid=c.staffid and c.active='Y' and v.vdcid=c.vdcid $idt
group by s.code,s.firstname,s.lastname,c.centerid,c.centercode,c.IsRural,v.VDCName,c.WardNo
order by s.code,c.centercode";
                                    }
                                    $result = sqlsrv_query($connection, $qry);
                                    while ($res = sqlsrv_fetch_array($result)) {

                                        $totalmember += $res['TotalMember'];
                                        $totalborrower += $res['Borrower'];
                                        $totalpension += $res['PensionSever'];
                                        $totallic += $res['LICSever'];
                                        $totalphoto += $res['PhotoUpdate'];
                                        $totalmsign += $res['mSign'];
                                        $totalmnu += $res['MobileNoUpdate'];
                                        $totaldm += $res['DuplicationMember'];
                                        $totalch += $res['CenterHouse'];
                                        $totalcd += $res['CenterDress'];
                                        $totalatt += $res['AttendanceReg'];
                                        ?>
                                        <tr  >
                                            <td><?php echo $res['StaffCode']; ?></td>
                                            <td><?php echo $res['StaffName']; ?></td>
                                            <td><?php echo $res['CenterNo']; ?></td>
                                            <td><?php echo $res['grade']; ?></td>
                                            <td><?php
                                                if ($res['IsRural'] == 'Y') {
                                                    echo "Yes";
                                                } else {
                                                    echo "No";
                                                };
                                                ?></td>
                                            <td><?php echo $res['TotalMember']; ?></td>
                                            <td><?php echo $res['Borrower']; ?></td>
                                            <td><?php echo $res['PensionSever']; ?></td>
                                            <td><?php echo $res['LICSever']; ?></td>
                                            <td><?php echo $res['PhotoUpdate']; ?></td>
                                            <td><?php echo $res['mSign']; ?></td>
                                            <td><?php echo $res['MobileNoUpdate']; ?></td>
                                            <td><?php echo $res['DuplicationMember']; ?></td>
                                            <td><?php echo $res['CenterHouse']; ?></td>
                                            <td><?php echo $res['CenterDress']; ?></td>
                                            <td><?php echo $res['AttendanceReg']; ?></td>
                                            <td><?php echo $res['VDCName']; ?></td>
                                            <td><?php echo $res['WardNo']; ?></td>

                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>

                                <tfoot class="bg-red text-sm">
                                    <tr>
                                        <td colspan="5" class="text-bold">Total </td>
                                        <td><?php echo $totalmember; ?></td>
                                        <td><?php echo $totalborrower; ?></td>
                                        <td><?php echo $totalpension; ?></td>
                                        <td><?php echo $totallic; ?></td>
                                        <td><?php echo $totalphoto; ?></td>
                                        <td><?php echo $totalmsign; ?></td>
                                        <td><?php echo $totalmnu; ?></td>
                                        <td><?php echo $totaldm; ?></td>
                                        <td><?php echo $totalch; ?></td>
                                        <td><?php echo $totalcd; ?></td>
                                        <td><?php echo $totalatt; ?></td>
                                        <td></td>
                                        <td></td>


                                    </tr>
                                </tfoot>



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
    $('#swc').DataTable({
        //order:[0,'Asc'],
        scrollX: true,
        scrollY: "300px",
        scrollCollapse: true,
        paging: false,
        dom: 'Bfrtip',
		fixedColumns: {
            leftColumns: 3,
        },
        buttons: [
            {
                extend: 'excel',
                filename: 'StaffWise Center Detail',
                title: 'Jeevan Bikas Samaj',
                messageTop: '<?php if (isset($_POST['search'])) {
    echo $bname . "- StaffWise Center Detail ";
} else {
    echo $branchName . "- StaffWise Center Detail ";
}; ?>',
            },
            {
                extend: 'pdf',
                filename: 'StaffWise Center Detail',
                title: 'Jeevan Bikas Samaj',
                messageTop: '<?php if (isset($_POST['search'])) {
    echo $bname . "- StaffWise Center Detail ";
} else {
    echo $branchName . "- StaffWise Center Detail ";
}; ?>',
            },
            {
                extend: 'print',
                filename: 'StaffWise Center Detail',
                title: 'Jeevan Bikas Samaj',
                messageTop: '<?php if (isset($_POST['search'])) {
    echo $bname . "<br/> StaffWise Center Detail ";
} else {
    echo $branchName . "<br/> StaffWise Center Detail ";
}; ?>',
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

