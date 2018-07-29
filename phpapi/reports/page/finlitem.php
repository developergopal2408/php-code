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

                <small>Finlite-M Logged In Member</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Finlite-M Logged In Member</li>
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
                                            <div class="col-sm-2">
                                                <button type="submit" name="search" id="search-btn" class=" btn btn-flat bg-green"><i class="fa fa-search"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                    <?php
                                }
                                ?>
                                <!-- /.search form -->
                            </div>
                        </div>

                        <div class="box-body">
                            <?php
                            $bn = "";
                            if (isset($_POST['search'])) {
                                $query = "SELECT ID,Name FROM OfficeDetail  WHERE  ID = '" . $_POST['id'] . "'";
                                $sub = odbc_exec($connection, $query);
                                $p = odbc_fetch_array($sub);
                                $bn = $p['Name'];
                                echo "<h5 class='text-bold text-center'>" . $bn . " </h5>";
                            } else {
                                $branchName = $branchName;
                            }
                            ?>
                            <table id="fm" class="table display  text-sm">
                                <thead class="bg-red">
                                    <tr class="text-sm">
                                        <th>O.Code</th>
                                        <th>O.Name</th>
                                        <th class="text-center">Total Member</th>
                                        <?php if(isset($_POST['search'])){?>
                                        <th class="text-center">Action</th>
                                        <?php } ?>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $total = 0;
                                    $oid = "";
                                    $brname = "";
                                    $id = $_POST['id'];
                                    if (isset($_POST['search'])) {
                                        $qry = "select OfficeID,count(memberid)Total from member where PinCode LIKE '%[1,9]%' and OfficeID = '$id'  group by OfficeID order by OfficeID";
                                    } else {
                                        $qry = "select OfficeID,count(memberid)Total from member where PinCode LIKE '%[1,9]%'  group by OfficeID order by OfficeID";
                                    }
                                    $result = odbc_exec($connection, $qry);
                                    while ($res = odbc_fetch_array($result)) {
                                        $total += $res['Total'];
                                        $qy = odbc_exec($connection, "select * from officedetail where ID = '" . $res['OfficeID'] . "'");
                                        $bname = odbc_fetch_array($qy);
                                        $oid = $res['OfficeID'];
                                        $brname = $bname['Name'];
                                        ?>
                                        <tr>
                                            <td><?php echo $bname['Code']; ?></td>
                                            <td><?php echo $bname['Name']; ?></td>
                                            <td class="text-center"><?php echo $res['Total']; ?></td>
                                           <?php if(isset($_POST['search'])){?>
                                            <td class="text-center"><a href="#view<?php echo $res['OfficeID']; ?>" class="btn btn-red btn-sm" data-toggle="modal" data-target="#view<?php echo $res['OfficeID']; ?>"><i class="fa fa-download"></i></a></td>
                                           <?php } ?>
                                        </tr>


                                        <?php
                                    }
                                    ?>
                                </tbody>
                                <tfoot class="bg-red text-bold">
                                    <tr>
                                        <td>Total</td>
                                        <td colspan="2" class="text-center"><?php echo $total; ?></td>
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
    <!-- Modal -->
    <div class="modal fade" id="view<?php echo $oid; ?>" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header bg-red">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><?php echo $brname; ?></h4>
                </div>
                <div class="modal-body">
                    <table id="fms" class="table  table-bordered table-condensed table-hover" style="width:100%">
                        <thead class="bg-red">
                            <tr>
                                <th>MemberCode</th>
                                <th>MemberName</th>
                                <th>OTP</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = odbc_exec($connection, "select FirstName,LastName,MemberCode,PinCode from member where PinCode LIKE '%[1,9]%' and OfficeID = '$oid' order by OfficeID");
                            while ($row = odbc_fetch_array($query)) {
                                $mname = $row['FirstName'] . " " . $row['LastName'];
                                ?>
                                <tr>
                                    <td><?php echo $row['MemberCode']; ?></td>
                                    <td><?php echo $mname; ?></td>
                                    <td><?php echo $row['PinCode']; ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer bg-red">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>



    <?php
    include_once 'copyright.php';
    ?>

</div>
<!-- ./wrapper -->

<?php
include_once 'footer.php';
?>

<script>
    $('#fm').DataTable({
        scrollX: true,
        scrollY: "300px",
        scrollCollapse: true,
        paging: false,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                filename: 'FinliteM Detail',
                title: 'Jeevan Bikas Samaj',
                messageTop: '<?php
if (isset($_POST['search'])) {
    echo $bn . "- FinliteM Detail ";
} else {
    echo $branchName . "- FinliteM Detail ";
};
?>',
            },
            {
                extend: 'print',
                filename: 'FinliteM Detail',
                title: 'Jeevan Bikas Samaj',
                messageTop: '<?php
if (isset($_POST['search'])) {
    echo $bn . "<br/> FinliteM Detail ";
} else {
    echo $branchName . "<br/> FinliteM Detail ";
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


    $(document).ready(function () {
        $('#fms').DataTable({
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excel',
                    filename: 'FinliteM Detail',
                    title: 'Jeevan Bikas Samaj',
                    messageTop: '<?php
if (isset($_POST['search'])) {
    echo $bn . "- FinliteM Detail ";
} else {
    echo $branchName . "- FinliteM Detail ";
};
?>',
                },
                {
                    extend: 'print',
                    filename: 'FinliteM Detail',
                    title: 'Jeevan Bikas Samaj',
                    messageTop: '<?php
if (isset($_POST['search'])) {
    echo $bn . "<br/> FinliteM Detail ";
} else {
    echo $branchName . "<br/> FinliteM Detail ";
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
            ],
            responsive: {
                details: {
                    display: $.fn.dataTable.Responsive.display.modal({
                        header: function (row) {
                            var data = row.data();
                            return 'Details for ' + data[0] + ' ' + data[1];
                        }
                    }),
                    renderer: $.fn.dataTable.Responsive.renderer.tableAll({
                        tableClass: 'table'
                    })
                }
            }

        });
    });

</script>
