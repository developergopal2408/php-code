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

                <small>Member Personal</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Member Personal</li>
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
                                            <?php
                                        }
                                        ?>
                                        <div class="col-sm-2">
                                            <button type="submit" name="search" id="search-btn" class=" btn btn-flat bg-green"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                </form>
                                <!-- /.search form -->
                                <div class="pull-right" >
                                    <a  href="memberpersonal.php"  class="btn btn-flat bg-blue" data-toggle = "tooltip" title="All / Refresh"><i class="fa fa-refresh"></i></a>
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
                                    echo "<h5 class='text-bold text-center'>" . "( " . $_POST['date1'] . " - " . $_POST['date2'] . " )</h5>";
                                }
                            } else {
                                echo "<h5 class='text-bold text-center'>All Branch (" . $_POST['date1'] . " - " . $_POST['date2'] . ")</h5>";
                            }
                            ?>
                            <table id="memberpersonal" class="display table-condensed table-bordered table-striped" style="width:auto;">
                                <thead class="bg-red text-sm" style="font-size:10px;">
                                    <tr  >
                                        <th>MemberID</th>
                                        <th>MCode</th>
                                        <th>MName</th>
                                        <th>RegDate</th>
                                        <th>IsDisable</th>
                                        <th>CitizenshipNo</th>
                                        <th>IDType</th>
                                        <th>DOB</th>
                                        <th>Gender</th>
                                        <th>MemberCast</th>
                                        <th>District</th>
                                        <th>VDC</th>
                                        <th>WardNo</th>
                                        <th>Tole</th>
                                    </tr>
                                </thead>

                                <tbody class="text-sm">
                                    <?php
                                    $id = $_POST['id'];
                                    $date1 = $_POST['date1'];
                                    $date2 = $_POST['date2'];
                                    if ($_SESSION['BranchID'] == 1 AND $id == "") {
                                        $idx = "";
                                    } else if ($_SESSION['BranchID'] == 1 AND $id != "") {
                                        $idx = "and m.officeid = '$id'";
                                    } else {

                                        $idx = "and m.officeid = '" . $_SESSION['BranchID'] . "'";
                                    }
                                    if (isset($_POST['search'])) {
                                        $qry = "select m.memberid,m.membercode,m.firstname+' '+m.lastname as MemberName,
                                            m.Regdate,m.IsDisable,m.CitizenShipNo,m.gender,
                                            (select IdentityType from identitytype where id=m.idtypeid)IDType,m.DOB,c.MemberCast,
                                            (Select Districtname from district where districtid=m.districtid)District,
                                            (select vdcname from vdc where vdcid=m.vdcid) vdc,m.WardNo,m.Tole from member m,membercast c
                                            where m.castId=c.id and m.status='Active' and m.RegDate between '$date1' AND '$date2' 
                                            $idx
                                            Order by m.membercode";
                                    } /* else {
                                      $qry = "select m.memberid,m.membercode,m.firstname+' '+m.lastname as MemberName,
                                      m.Regdate,m.IsDisable,m.CitizenShipNo,
                                      (select IdentityType from identitytype where id=m.idtypeid)IDType,m.DOB,c.MemberCast,
                                      (Select Districtname from district where districtid=m.districtid)District,
                                      (select vdcname from vdc where vdcid=m.vdcid) vdc,m.WardNo,m.Tole from member m,membercast c
                                      where m.castId=c.id and m.status='Active' and m.RegDate between '$cdate' AND '$cdate'
                                      $idx
                                      Order by m.membercode";
                                      } */
                                    $result = sqlsrv_query($connection, $qry);
                                    while ($res = sqlsrv_fetch_array($result)) {
                                        ?>
                                        <tr>
                                            <td><?php echo $res['memberid']; ?></td>
                                            <td><?php echo $res['membercode']; ?></td>
                                            <td><?php echo $res['MemberName']; ?></td>
                                            <td><?php echo $res['Regdate']; ?></td>
                                            <td><?php echo $res['IsDisable']; ?></td>
                                            <td><?php echo $res['CitizenShipNo']; ?></td>
                                            <td><?php echo $res['IDType']; ?></td>
                                            <td><?php echo $res['DOB']; ?></td>
                                            <td><?php echo $res['gender']; ?></td>
                                            <td><?php echo $res['MemberCast']; ?></td>
                                            <td><?php echo $res['District']; ?></td>
                                            <td><?php echo $res['vdc']; ?></td>
                                            <td><?php echo $res['WardNo']; ?></td>
                                            <td><?php echo $res['Tole']; ?></td>
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
    $('#memberpersonal').DataTable({
        scrollX: true,
        scrollY: "300px",
        scrollCollapse: true,
        paging: false,
        dom: 'Bfrtip',
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