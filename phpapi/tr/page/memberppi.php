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
                                                       echo $sdate;
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
                                                    $result = sqlsrv_query($connection, $sql1);
                                                    while ($rows = sqlsrv_fetch_array($result)) {
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
                                    <a  href="memberppi.php"  class="btn btn-flat bg-blue" data-toggle = "tooltip" title="All / Refresh"><i class="fa fa-refresh"></i></a>
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
                                if ($_POST['id']) {
                                    echo "<h5 class='text-bold text-center'>" . $bname . " ( " . $_POST['date1'] . " - " . $_POST['date2'] . " )</h5>";
                                } else {
                                    echo "<h5 class='text-bold text-center'>" . $branchName. "( " . $_POST['date1'] . " - " . $_POST['date2'] . " )</h5>";
                                }
                            }
                            ?>
                            <table id="memberppi" class="table display table-condensed table-bordered table-striped" style="width:auto;">
                                <thead class="bg-red text-sm">
                                    <tr>
                                        <th>MemberCode</th>
                                        <th>MemberName</th>
                                        <th>SaveDate</th>
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
                                        <th>StaffName</th>

                                    </tr>
                                </thead>

                                <tbody class="text-sm">
                                    <?php
                                    $id = $_POST['id'];
                                    $date1 = $_POST['date1'];
                                    $date2 = $_POST['date2'];
                                    if ($_SESSION['BranchID'] == 1 AND $id != "") {
                                        $idu = "and c.officeid = '$id'";
                                        $idv = "and s.branchid = '$id'";
                                        $idw = "and p.officeid = '$id'";
                                        $idx = "and m.officeid = '$id'";
                                    } else {
                                        $idu = "and c.officeid = '" . $_SESSION['BranchID'] . "'";
                                        $idv = "and s.branchid = '" . $_SESSION['BranchID'] . "'";
                                        $idw = "and p.officeid = '" . $_SESSION['BranchID'] . "'";
                                        $idx = "and m.officeid = '" . $_SESSION['BranchID'] . "'";
                                    }
                                    if (isset($_POST['search'])) {
                                        $qry = "select m.MemberCode,m.FirstName+' '+M.Lastname as MemberName,
                                            P.Savedate,p.CookingType,p.GuardianJob,P.HomeAppliance,
                                            P.House,P.IrrigationType,
                                            p.IsKitchen,p.LightSystem,P.NosRoom,P.Remarks,P.RoofType,
                                            P.TelePhone,P.ToiletType,P.VehicleType,P.WallType,
                                            s.firstname+' '+s.lastname as StaffName
                                            from Member m
                                            join centermain c on c.centerid=m.centerid $idu
                                            join staffmain s on s.staffid=c.staffid $idv
                                            left join MembersPPI p on M.memberid=p.Memberid $idw
                                            where savedate between '$date1' and '$date2' 
                                            $idx and m.status='active'
                                            order by m.membercode";
                                    } else {
                                        $qry = "select m.MemberCode,m.FirstName+' '+M.Lastname as MemberName,
                                            P.Savedate,p.CookingType,p.GuardianJob,P.HomeAppliance,
                                            P.House,P.IrrigationType,
                                            p.IsKitchen,p.LightSystem,P.NosRoom,P.Remarks,P.RoofType,
                                            P.TelePhone,P.ToiletType,P.VehicleType,P.WallType,
                                            s.firstname+' '+s.lastname as StaffName
                                            from Member m
                                            join centermain c on c.centerid=m.centerid $idu
                                            join staffmain s on s.staffid=c.staffid $idv
                                            left join MembersPPI p on M.memberid=p.Memberid $idw
                                            where savedate between '$date1' and '$date2' 
                                            $idx and m.status='active'
                                            order by m.membercode";
                                    }
                                    $result = sqlsrv_query($connection, $qry);
                                    while ($res = sqlsrv_fetch_array($result)) {
                                        ?>
                                        <tr class="text-sm">
                                            <td><?php echo $res['MemberCode']; ?></td>
                                            <td><?php echo $res['MemberName']; ?></td>
                                            <td><?php echo $res['Savedate']; ?></td>
                                            <td><?php echo $res['CookingType']; ?></td>
                                            <td><?php echo $res['GuardianJob']; ?></td>
                                            <td><?php echo $res['HomeAppliance']; ?></td>
                                            <td><?php echo $res['House']; ?></td>
                                            <td><?php echo $res['IrrigationType']; ?></td>
                                            <td><?php echo $res['IsKitchen']; ?></td>
                                            <td><?php echo $res['LightSystem']; ?></td>
                                            <td><?php echo $res['NosRoom']; ?></td>
                                            <td><?php echo $res['Remarks']; ?></td>
                                            <td><?php echo $res['RoofType']; ?></td>
                                            <td><?php echo $res['TelePhone']; ?></td>
                                            <td><?php echo $res['ToiletType']; ?></td>
                                            <td><?php echo $res['VehicleType']; ?></td>
                                            <td><?php echo $res['WallType']; ?></td>
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
    $('#memberppi').DataTable({
        scrollX:true,
        scrollY: "300px",
        scrollCollapse: true,
        paging: false,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                filename: 'Member PPI',
                title: 'Jeevan Bikas Samaj',
                messageTop: '<?php if (isset($_POST['search'])) {echo $bname . ' -  Member PPI ( ' . $_POST['date1'] . ' - ' . $_POST['date2'] . ')';} else { echo $branchName . "- Member PPI ";};?>',
            },
            {
                extend: 'pdf',
                filename: 'Member PPI',
                title: 'Jeevan Bikas Samaj',
                messageTop: '<?php if (isset($_POST['search'])) {echo $bname . "- Member PPI";} else { echo $branchName . "- Member PPI ";};?>',

            },
            {
                extend: 'print',
                filename: 'Member PPI',
                title: 'Jeevan Bikas Samaj',
                messageTop: '<?php if (isset($_POST['search'])) { echo '<h5 class="text-bold text-center"> ' . $bname . ' <br/> Member PPI <br/>  ' . $_POST['date1'] . ' - ' . $_POST['date2'] . ' </h5>';} else {echo $branchName . "<br/> Member PPI ";};?>',
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