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
                <small>Passive Member List</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Passive Member List</li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="box box-danger">
                <div class="box-header with-border">
                    <div class="col-sm-12">
                        <?php
                        if ($_SESSION['BranchID'] == 1) {
                            ?>
                            <!-- search form -->
                            <form  action="" method="post" class="form-horizontal" >
                                <div class=" form-group-sm">
                                    <div class="col-sm-3">
                                        <select name="id" id="id" class="form-control select2" required>
                                            <option value="">Select Branch</option>
                                            <option value="all">All Branch</option>
                                            <?php
                                            $query = "SELECT ID,Name,Code from OfficeDetail";
                                            $sub = sqlsrv_query($connection, $query);
                                            while ($p = sqlsrv_fetch_array($sub)) {
                                                ?>
                                                <option value="<?php echo $p['ID']; ?>" ><?php echo $p['Code'] . " - " . $p['Name']; ?></option>;
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <button type="submit" name="search" id="search-btn" class=" btn btn-flat bg-green" data-toggle = "tooltip" title="Search"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            </form>
                            <!-- /.search form -->
                            <?php
                        }
                        ?>
                        <div class="pull-right" >
                            <a  href="passive_member.php"  class="btn btn-flat bg-blue" data-toggle = "tooltip" title="All / Refresh"><i class="fa fa-refresh"></i></a>
                        </div>

                    </div>
                </div>
                <div class="box-body">

                    <?php
                    $bname = "";
                    if (isset($_POST['id'])) {
                        $query = "SELECT ID,Name,Code FROM OfficeDetail  WHERE  ID = '" . $_POST['id'] . "'";
                        $sub = sqlsrv_query($connection, $query);
                        $p = sqlsrv_fetch_array($sub);
                        $bname = $p['Name'];
                        echo "<h5 class='text-center text-bold'>" . $bname . " - Passive Member List</h5>";
                    }
                    ?>

                    <table id="passive" class="table table-bordered table-striped display" bordered="1" style="width:auto;"> 
                        <thead class="bg-red text-sm">
                            <tr>
                                <th>Member Code</th>
                                <th>Member Name</th>
                                <th>Reg Date</th>
                                <th>Compulsory</th>
                                <th>Last_transaction_date_of_Com</th>
                            </tr>
                        </thead>

                        <?php
                        if (isset($_POST['search'])) {
                            $ID = $_POST['id'];
                            if ($ID == "all") {
                                $qry = "select m.MemberCode,m.Firstname+' '+m.lastname as MemberName,m.Regdate,
                                                (select sum(cramount-dramount)from savingdetail where memberid=m.memberid  and savingtypeid=2 and m.officeid=officeid)Compulsory,
                                                (select max(savedate) from savingdetail where memberid=m.memberid  and savingtypeid=2 and cramount>0 
                                                and reftype<>'Interest' )Last_transaction_date_of_Com
                                                from member m
                                                where  m.status='PASSIVE'
                                                order by m.membercode";
                            } else {
                                $qry = "select m.MemberCode,m.Firstname+' '+m.lastname as MemberName,m.Regdate,
                                                (select sum(cramount-dramount)from savingdetail where memberid=m.memberid and officeid='$ID' and savingtypeid=2 and m.officeid=officeid)Compulsory,
                                                (select max(savedate) from savingdetail where memberid=m.memberid and officeid='$ID' and savingtypeid=2 and cramount>0 
                                                and reftype<>'Interest' )Last_transaction_date_of_Com
                                                from member m
                                                where m.officeid='$ID' and m.status='PASSIVE'
                                                order by m.membercode";
                            }
                            $result = sqlsrv_query($connection, $qry);
                            ?>
                            <tbody class="text-sm">
                                <?php
                                while ($res = sqlsrv_fetch_array($result)) {
                                    ?>
                                    <tr >
                                        <td><?php echo $res['MemberCode']; ?></td>
                                        <td><?php echo $res['MemberName']; ?></td>
                                        <td><?php echo $res['Regdate']; ?></td>
                                        <td><?php echo number_format($res['Compulsory'], 2); ?></td>
                                        <td><?php echo $res['Last_transaction_date_of_Com']; ?></td>

                                    </tr>																				
                                    <?php
                                }
                                ?>
                            </tbody>
                            <?php
                        } else if (empty($_POST) AND $_SESSION['BranchID'] > 1) {
                            $qry = "select m.MemberCode,m.Firstname+' '+m.lastname as MemberName,m.Regdate,
                                         (select sum(cramount-dramount)from savingdetail where memberid=m.memberid and officeid='" . $_SESSION['BranchID'] . "' and savingtypeid=2 and m.officeid=officeid)Compulsory,
                                          (select max(savedate) from savingdetail where memberid=m.memberid and officeid='" . $_SESSION['BranchID'] . "' and savingtypeid=2 and cramount>0 
                                          and reftype<>'Interest' )Last_transaction_date_of_Com
                                          from member m
                                           where m.officeid='" . $_SESSION['BranchID'] . "' and m.status='PASSIVE'
                                          order by m.membercode";
                            $result = sqlsrv_query($connection, $qry);
                            ?>
                            <tbody class="text-sm">
                                <?php
                                while ($res = sqlsrv_fetch_array($result)) {
                                    ?>
                                    <tr >
                                        <td><?php echo $res['MemberCode']; ?></td>
                                        <td><?php echo $res['MemberName']; ?></td>
                                        <td><?php echo $res['Regdate']; ?></td>
                                        <td><?php echo number_format($res['Compulsory'], 2); ?></td>
                                        <td><?php echo $res['Last_transaction_date_of_Com']; ?></td>

                                    </tr>																				
                                    <?php
                                }
                                ?>
                            </tbody>
                            <?php
                        }
                        ?>

                    </table>
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
    $('#passive').DataTable({
        //order:[0,'Asc'],
        scrollY: "300px",
        scrollCollapse: true,
        paging: false,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                filename: 'Passive Member List',
                title: 'Jeevan Bikas Samaj',
                messageTop: '<?php if (isset($_POST['search'])) {echo $bname . "- Passive Member Detail ";} else { echo $branchName . "- Passive Member Detail ";};?>',
            },
            {
                extend: 'pdf',
                filename: 'Passive Member List',
                title: 'Jeevan Bikas Samaj',
                messageTop: '<?php if (isset($_POST['search'])) {echo $bname . "- Passive Member Detail ";} else { echo $branchName . "- Passive Member Detail ";};?>',

            },
            {
                extend: 'print',
                filename: 'Passive Member List',
                title: 'Jeevan Bikas Samaj',
                messageTop: '<?php if (isset($_POST['search'])) {echo $bname . "<br/> Passive Member Detail ";} else {echo $branchName . "<br/> Passive Member Detail ";};?>',
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

