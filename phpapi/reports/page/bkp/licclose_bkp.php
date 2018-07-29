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
                <small>LIC CLOSE</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">LIC CLOSE </li>
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
                                        <div class="col-sm-2">
                                            <button type="submit" name="search" id="search-btn" class=" btn btn-flat bg-green"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                </form>
                                <!-- /.search form -->


                            </div>
                        </div>

                        <div class="box-body">
                            <div class="box-title with-header text-bold text-center">
                                <?php
                                if (isset($_POST['id'])) {
                                    echo "( " . $_POST['date1'] . " - " . $_POST['date2'] . " ) Close";
                                }
                                ?>
                            </div>
                            <table id="lic" class="table display stripe row-border order-column" cellspacing="0" width="100%"   >
                                <thead class="bg-red text-sm">
                                    <tr>
                                        <th>Code</th>
                                        <th>BranchName</th>
                                        <th>MemberCode</th>
                                        <th>MemberName</th>
                                        <th>Status</th>
                                        <th>Closedate</th>
                                        <th>PolicyNo</th>
                                        <th>Startdate</th>
                                        <th>InsuredAmount</th>
                                        <th>Finstamount</th>
                                        <th>Instamount</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                    if ($_SESSION['ID'] == 1) {
                                        $id = "";
                                    } else {
                                        $id = "and i.officeid = '" . $_SESSION['BranchID'] . "'";
                                    }
                                    if (isset($_POST['search'])) {
                                        $ID = $_POST['id'];
                                        $date1 = $_POST['date1'];
                                        $date2 = $_POST['date2'];

                                        $qry = "select o.Code,o.Name,m.memberid,m.membercode,m.firstname+' '+m.lastname as MemberName,M.status,i.CloseDate,i.policyno, i.Startdate,i.InsuredAmount,i.FinstAmount,i.InstAmount
                                                    from insuranceaccount i, member m, officedetail o
                                                    where m.memberid=i.memberid and o.id=i.officeid and m.officeid=o.id and i.isactive='N' and i.closedate between '$date1' and '$date2'
                                                    and m.officeid=o.id $id
                                                    order by i.closedate, o.name";

                                        $result = odbc_exec($connection, $qry);
                                        while ($res = odbc_fetch_array($result)) {
                                            ?>
                                            <tr>
                                                <td><?php echo $res['Code']; ?></td>
                                                <td><?php echo $res['Name']; ?></td>
                                                <td><?php echo $res['membercode']; ?></td>
                                                <td><?php echo $res['MemberName']; ?></td>
                                                <td><?php echo $res['status']; ?></td>
                                                <td><?php echo $res['CloseDate']; ?></td>
                                                <td><?php echo $res['policyno']; ?></td>
                                                <td><?php echo $res['Startdate']; ?></td>
                                                <td><?php echo $res['InsuredAmount']; ?></td>
                                                <td><?php echo $res['FinstAmount']; ?></td>
                                                <td><?php echo $res['InstAmount']; ?></td>
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
    $('#lic').DataTable({
        scrollX: true,
        scrollY: "300px",
        scrollCollapse: true,
        paging: false,
        fixedColumns: {
            leftColumns: 1,
        },
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                filename: 'Lic Close List',
                title: 'Jeevan Bikas Samaj',
                messageTop: ' <?php if (isset($_POST['search'])) {
    echo $branchName . ' ( ' . $_POST['date1'] . ' -  ' . $_POST['date2'] . ' ) - Lic Close List';
} else {
    echo $branchName . ' ( ' . $_POST['date1'] . ' -  ' . $_POST['date2'] . ' ) - Lic Close List';
} ?>',
            },
           
            {
                extend: 'print',
                filename: 'Lic Close List',
                title: 'Jeevan Bikas Samaj',
                messageTop: ' <?php if (isset($_POST['search'])) {
    echo '<h5 class="text-bold text-center">' . $branchName . '<br/> ( Lic Close List - ' . $_POST['date1'] . ' -  ' . $_POST['date2'] . ' ) </h5>';
} else {
    echo '<h5 class="text-bold text-center">' . $branchName . '<br/> ( Lic Close List ' . $_POST['date1'] . ' -  ' . $_POST['date2'] . ' )</h5>';
} ?>',
                customize: function (win) {
                    $(win.document.body)
                            .css({
                                'font-size': '10pt',
                                'text-align': 'center'
                            });
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

