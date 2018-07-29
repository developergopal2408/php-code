<?php
include_once 'top.php';
include_once 'header.php';
?>
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
                <i class="fa fa-building"></i> 
                <small>Loan OverDue</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Loan OverDue</li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="box box-danger">
                <div class="box-header with-border">
                    <div class="row">
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

                                    <?php
                                    if ($BranchID == 1) {
                                        ?>
                                        <div class="col-sm-3">
                                            <select name="oid" id="oid" class="form-control select2" >
                                                <option value="">Select Branch</option>
                                                <?php
                                                $sql1 = "SELECT * FROM OfficeDetail ORDER BY ID ASC ";
                                                $result = sqlsrv_query($connection, $sql1);

                                                while ($rows = sqlsrv_fetch_array($result)) {
                                                    ?>
                                                    <option value="<?php echo $rows['ID']; ?>" <?php if ($rows['ID'] == $_POST['oid']) {
                                                echo "selected";
                                            } ?> ><?php echo $rows['Code'] . " - " . $rows['Name']; ?></option>;
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
                            <div class=" pull-right" >
                                <a href="loandue.php"  class=" btn btn-flat bg-blue" title="Refresh"><i class="fa fa-refresh"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box-body">
                    <?php
                    if (isset($_POST['search'])) {
                        $sqls = "SELECT Name FROM OfficeDetail Where ID = '" . $_POST['oid'] . "' ";
                        $results = sqlsrv_query($connection, $sqls);
                        $reso = sqlsrv_fetch_array($results);
                        echo "<h5 class='text-center text-bold'>Loan OverDue - " . $reso['Name'] . "( " . $_POST['date1'] . "  )</h5>";
                    }
                    ?>
                    <table id="loandue" class="display" style="width:auto;"> 
                        <thead class="bg-red text-sm">
                            <tr>
                                <th>Name</th>
                                <th>MemberID</th>
                                <th>MemberCode</th>
                                <th>MemberName</th>
                                <th>LType</th>
                                <th>SaveDate</th>
                                <th>PriDue</th>
                                <th>IntDue</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            <?php
                            $id = $_POST['oid'];
                            $date1 = $_POST['date1'];
                            $pridue = 0.0;
                            $intdue = 0.0;
                            if ($_SESSION['BranchID'] == 1 AND $id == "") {
                                $idx = "";
                            } else if ($_SESSION['BranchID'] == 1 AND $id != 1) {
                                $idx = "and a.officeid = '$id'";
                            } else {
                                $idx = "and a.officeid = '" . $_SESSION['BranchID'] . "'";
                            }
                            if (isset($_POST['search'])) {
                                $qry = "select m.officeid,m.memberid, m.membercode, m.firstname+' '+m.LastName as MemberName, l.loantype,
                                                a.savedate, sum(a.pridue)pridue,sum(a.intdue)intdue,
                                                (select name from officedetail where ID = m.officeid)name
                                                from member m, loandetail a, loantype l
                                                where m.officeid=a.officeid and m.memberid=a.memberid  and a.loantypeid=l.loantypeid 
                                                and a.savedate = '$date1' $idx
                                                group by m.memberid, m.membercode,m.firstname,m.lastname,a.savedate, l.loantype,m.officeid 
                                                having sum(a.pridue+a.intdue)>0 order by a.savedate,m.membercode";
                                $result = sqlsrv_query($connection, $qry);
                                while ($res = sqlsrv_fetch_array($result)) {
                                    $pridue += $res['pridue'];
                                    $intdue += $res['intdue'];
                                    ?>
                                    <tr>
                                        <td><?php echo $res['name']; ?></td>
                                        <td><?php echo $res['memberid']; ?></td>
                                        <td><?php echo $res['membercode']; ?></td>
                                        <td><?php echo $res['MemberName']; ?></td>
                                        <td><?php echo $res['loantype']; ?></td>
                                        <td><?php echo $res['savedate']; ?></td>
                                        <td class="text-right"><?php echo number_format($res['pridue'],2); ?></td>
                                        <td class="text-right"> <?php echo number_format($res['intdue'],2); ?></td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                        </tbody>
                        <tfoot>

                            <tr class="bg-red text-bold">
                                <td colspan="6">Total</td>
                                <td class="text-right"><?php echo number_format($pridue, 2); ?></td>
                                <td class="text-right"><?php echo number_format($intdue, 2); ?></td>
                            </tr>

                        </tfoot>
                    </table>
                </div>
            </div>
        </section>
        <!--/.content -->
    </div>
    <!--/.content-wrapper -->
    <?php
    include_once 'copyright.php';
    ?>
</div>
<!-- ./wrapper -->
<?php
include_once 'footer.php';
?>
<script>

    $('#loandue').DataTable({
        scrollY: "300px",
        scrollCollapse: true,
        paging: false,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                title: 'Jeevan Bikas Samaj',
                messageTop: 'Loan Due Detail - ' + $("#date1").val(),
            },
            {
                extend: 'print',
                title: 'Jeevan Bikas Samaj',
                messageTop: 'Loan Due Detail - ' + $("#date1").val(),
                customize: function (win) {
                    $(win.document.body)
                            .css({
                                'font-size': '10pt',
                                'text-align': 'center'
                            })
                            .prepend(
                                    '<img src="http://www.jeevanbikas.org.np/wp-content/uploads/2017/12/Jeevan-Bikas-logo.png" style="position:absolute; top:0; left:0;" />'
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

