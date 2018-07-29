<?php
include_once 'top.php';
include_once 'header.php';
?>
<!-- Site wrapper -->
<div class="wrapper">
    <?php
    include_once 'sidebar_header.php';
    include_once 'sidebar.php';
    ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                <i class="fa fa-building"></i> <?php echo $branchName; ?>
                <small>Dropout Member's</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Dropout Members</li>
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
                                                <select name="oid" id="oid" class="form-control select2" >
                                                    <option value="">Select Branch</option>

                                                    <?php
                                                    $sql1 = "SELECT * FROM OfficeDetail ORDER BY ID ASC ";
                                                    $result = sqlsrv_query($connection, $sql1);

                                                    while ($rows = sqlsrv_fetch_array($result)) {
                                                        ?>
                                                        <option value="<?php echo $rows['ID']; ?>" <?php
                                                        if ($_POST['oid'] == $rows['ID']) {
                                                            echo "selected";
                                                        }
                                                        ?>><?php echo $rows['Code'] . " - " . $rows['Name']; ?></option>;
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
                                    <a href="dropout_member.php"  class=" btn btn-flat bg-blue" title="Refresh"><i class="fa fa-refresh"></i></a>
                                </div>

                            </div>
                        </div>

                        <div class="box-body">
                            <?php
                            $bname = "";
                            if (isset($_POST['oid'])) {
                                $query = "SELECT ID,Name,Code FROM OfficeDetail  WHERE  ID = '" . $_POST['oid'] . "'";
                                $sub = sqlsrv_query($connection, $query);
                                $p = sqlsrv_fetch_array($sub);
                                $bname = $p['Name'];
                                if ($_SESSION['BranchID'] == 1) {
                                    echo "<h5 class='text-bold text-center'>".$bname . " - " . $_POST['sid'] . " ( " . $_POST['date1'] . " - " . $_POST['date2'] . " ) </h5>";
                                } else {
                                    echo "<h5 class='text-bold text-center'>".$branchName . " - " . $_POST['sid'] . " ( " . $_POST['date1'] . " - " . $_POST['date2'] . " ) </h5>";
                                }
                            }
                            ?>
                            <table id="dmember" class="stripe row-border order-column" cellspacing="0" width="100%" >
                                <thead class="bg-red text-sm">
                                    <tr>
                                        <th>MemberCode</th>
                                        <th>MemberName</th>
                                        <th>DropoutDate</th>
                                        <th>DOB</th>
                                        <th>RegDate</th>
                                        <th>District</th>
                                        <th>VDC</th>
                                        <th>WardNo</th>
                                        <th>DropOutReason</th>
                                        <th>CompCr</th>
                                        <th>CompDr</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    <?php
                                    $date1 = $_POST['date1'];
                                    $date2 = $_POST['date2'];
                                    $id = $_POST['oid'];
                                    if ($_SESSION['BranchID'] == 1) {
                                        $idx = "and m.officeid='$id'";
                                        $idw = "and officeid = '$id'";
                                    } else {
                                        $idx = "and m.officeid='" . $_SESSION['BranchID'] . "'";
                                        $idw = "and officeid = '" . $_SESSION['BranchID'] . "'";
                                    }
                                    if (isset($_POST['search'])) {
                                        $qry = "select s.DropOutReason,(m.membercode)Code,m.Firstname+' '+M.lastname as MemberName,
                                                    m.regdate,(m.Dropoutdate)Dropoutdate,m.DOB,m.Spousefather,m.FatherInLaw,(z.Zonename)Zone,
                                                    (d.districtname)District,(v.vdcname)VDC,m.WardNo,
                                                    (select sum(cramount) from savingdetail where memberid = m.memberid and savingtypeid = 2 $idw)cramount,
                                                    (select sum(dramount) from savingdetail where memberid = m.memberid and savingtypeid = 2 $idw)dramount
                                                    from member m, Zone z,District d,Vdc v,DropOutReason s
                                                    where m.Zoneid=z.zoneid and m.districtid=d.districtid and 
                                                    v.vdcid=m.vdcid and m.Dropoutdate between '$date1' and '$date2'
                                                    $idx and s.id = m.dropoutreason
                                                     order by m.membercode";
                                    }
                                    $results = sqlsrv_query($connection, $qry);
                                    while ($r = sqlsrv_fetch_array($results)) {
                                        ?>
                                        <tr>
                                            <td><?php echo $r['Code']; ?></td>
                                            <td><?php echo $r['MemberName']; ?></td>
                                            <td><?php echo $r['Dropoutdate']; ?></td>
                                            <td><?php echo $r['DOB']; ?></td>
                                            <td><?php echo $r['regdate']; ?></td>
                                            <td><?php echo $r['District']; ?></td>
                                            <td><?php echo $r['VDC']; ?></td>
                                            <td><?php echo $r['WardNo']; ?></td>
                                            <td><?php echo $r['DropOutReason']; ?></td>
                                            <td><?php echo $r['cramount']; ?></td>
                                            <td><?php echo $r['dramount']; ?></td>
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
    $('#dmember').DataTable({
        //scrollX: true,
        scrollY: "300px",
        scrollCollapse: true,
        paging: false,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                filename: 'Dropout Member List',
                title: 'Jeevan Bikas Samaj',
                messageTop: ' <?php
if (isset($_POST['search'])) {
    if ($_SESSION['BranchID'] > 1) {
        echo $branchName . ' ( ' . $_POST['date1'] . ' - ' . $_POST['date2'] . ') - Dropout Member List';
    } else {
        echo $bname . ' ( ' . $_POST['date1'] . ' - ' . $_POST['date2'] . ') - Dropout Member List';
    }
} else {
    echo $branchName . ' ( ' . $cdate . ' ) - Dropout Member List';
}
?>',
            },
            {
                extend: 'print',
                filename: 'Registered Member List',
                title: 'Jeevan Bikas Samaj',
                messageTop: ' <?php
if (isset($_POST['search'])) {
    if ($_SESSION['BranchID'] > 1) {
        echo '<h5 class="text-bold text-center">' . $branchName . '<br/> ( Dropout Member List - ' . $_POST['date1'] . ' - ' . $_POST['date2'] . ' ) </h5>';
    } else {
        echo '<h5 class="text-bold text-center">' . $bname . '<br/> ( Dropout Member List - ' . $_POST['date1'] . ' - ' . $_POST['date2'] . ' ) </h5>';
    }
} else {
    echo '<h5 class="text-bold text-center">' . $branchName . '<br/> ( Dropout Member List ' . $cdate . '  )</h5>';
}
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


