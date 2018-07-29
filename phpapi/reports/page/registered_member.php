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

                <small>Registered Member's</small>
            </h1>


            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>

                <li class="active">Registered Members</li>
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
                                            <input maxlength="10" type="text" name="date1" id="date1" class=" nepali-calendar form-control" placeholder="Select Date" >
                                        </div>
                                        <div class="col-sm-2">
                                            <input maxlength="10" type="text" name="date2" id="date2" class=" nepali-calendar form-control" placeholder="Select Date" >
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
                                                        <option value="<?php echo $rows['ID']; ?>" <?php
                                                        if ($_POST['id'] == $rows['ID']) {
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
                            </div>
                        </div>

                        <div class="box-body">
                            <?php
                            $bname = "";
                            if (isset($_POST['id'])) {
                                $query = "SELECT ID,Name,Code FROM OfficeDetail  WHERE  ID = '" . $_POST['id'] . "'";
                                $sub = odbc_exec($connection, $query);
                                $p = odbc_fetch_array($sub);
                                $bname = $p['Name'];
                                if ($_SESSION['BranchID'] == 1) {
                                    echo "<h5 class='text-bold text-center'>" . $bname . " - " . $_POST['sid'] . " ( " . $_POST['date1'] . " - " . $_POST['date2'] . " ) </h5>";
                                } else {
                                    echo "<h5 class='text-bold text-center'>" . $branchName . " - " . $_POST['oid'] . " ( " . $_POST['date1'] . " - " . $_POST['date2'] . " ) </h5>";
                                }
                            }
                            ?>
                            <table id="rmember" class="table stripe row-border order-column text-sm" cellspacing="0" width="100%">

                                <thead class="bg-red ">
                                    <tr>
                                        <th>S.No</th>
                                        <th>MemberCode</th>
                                        <th>MemberName</th>
                                        <th>RegDate</th>
                                        <th>DOB</th>
                                        <th>Spousefather</th>
                                        <th>FatherInlaw</th>
                                        <th>Zone</th>
                                        <th>District</th>
                                        <th>VDC</th>
                                        <th>Ward No</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $counter = 0;
                                    $ln = 0;
                                    $ID = $_POST['id'];
                                    $date1 = $_POST['date1'];
                                    $date2 = $_POST['date2'];
                                    if($_SESSION['BranchID'] == 1 ){
                                        $idx = "and m.officeid='$ID'";
                                    }else{
                                        $idx = " and m.officeid='". $_SESSION['BranchID'] ."' ";
                                    }
                                    if (isset($_POST['search'])) {

                                        $query = "select (m.membercode)Code,m.Firstname+' '+M.lastname as MemberName,
                                            (m.Regdate)Regdate,m.DOB,m.Spousefather,m.FatherInLaw,(z.Zonename)Zone,
                                            (d.districtname)District,(v.vdcname)VDC,m.WardNo
                                            from member m, Zone z,District d,Vdc v
                                            where m.Zoneid=z.zoneid and m.districtid=d.districtid and v.vdcid=m.vdcid 
                                            and m.regdate between '$date1' and '$date2' $idx
                                            order by m.membercode";
                                        $results = odbc_exec($connection, $query);
                                        while ($r = odbc_fetch_array($results)) {
                                            ?>
                                            <tr>
                                                <td><?php echo ++$counter; ?></td>
                                                <td><?php echo $r['Code']; ?></td>
                                                <td><?php echo $r['MemberName']; ?></td>
                                                <td><?php echo $r['Regdate']; ?></td>
                                                <td><?php echo $r['DOB']; ?></td>
                                                <td><?php echo $r['Spousefather']; ?></td>
                                                <td><?php echo $r['FatherInLaw']; ?></td>
                                                <td><?php echo $r['Zone']; ?></td>
                                                <td><?php echo $r['District']; ?></td>
                                                <td><?php echo $r['VDC']; ?></td>
                                                <td><?php echo $r['WardNo']; ?></td>

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
<script>
    $('#rmember').DataTable({
        //scrollX: true,
        scrollY: "300px",
        scrollCollapse: true,
        paging: false,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                filename: 'Registered Member List',
                title: 'Jeevan Bikas Samaj',
                messageTop: ' <?php
if (isset($_POST['search'])) {
    if ($_SESSION['BranchID'] > 1) {
        echo $branchName . ' ( ' . $_POST['date1'] . ' - ' . $_POST['date2'] . ') - Registered Member List';
    } else {
        echo $bname . ' ( ' . $_POST['date1'] . ' - ' . $_POST['date2'] . ') - Registered Member List';
    }
} else {
    echo $branchName . ' ( ' . $cdate . ' ) - Registered Member List';
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
        echo '<h5 class="text-bold text-center">' . $branchName . '<br/> ( Registered Member List - ' . $_POST['date1'] . ' - ' . $_POST['date2'] . ' ) </h5>';
    } else {
        echo '<h5 class="text-bold text-center">' . $bname . '<br/> ( Registered Member List - ' . $_POST['date1'] . ' - ' . $_POST['date2'] . ' ) </h5>';
    }
} else {
    echo '<h5 class="text-bold text-center">' . $branchName . '<br/> ( Registered Member List ' . $cdate . '  )</h5>';
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
