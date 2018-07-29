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
                <small>Ledger With Compile</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Ledger With Compile </li>
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
                                                <option value="">Select Ledger With Compile</option>
                                                <option value="loan" >Loan</option> 
                                                <option value="saving" >Saving</option>                                                   
                                            </select>
                                        </div>
                                        <div class="col-sm-2">
                                            <button type="submit" name="search" id="search-btn" class=" btn btn-flat bg-green"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                </form>
                                <!-- /.search form -->
                                <div class="box-tools pull-right" >
                                    <a  href="ledgervidan.php"  class="btn btn-flat bg-blue" data-toggle = "tooltip" title="All / Refresh"><i class="fa fa-refresh"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="box-body">
                            <?php
                            if (isset($_POST['search'])) {
                                echo "<h5 class='text-bold text-center'>" . $branchName . " - (" . $_POST['id'] . " - " . $_POST['date2'] . " )</h5>";
                            }
                            ?>
                            <table id="ledger" class="table display table-condensed table-bordered table-striped" style="width: auto;">
                                <?php
                                if (isset($_POST['search'])) {
                                    ?>
                                    <thead class="bg-red text-sm">
                                        <tr>
                                            <th>Code</th>
                                            <th>Name</th>
                                            <th>Main Compile Balance</th>
                                            <th>Trial Balance</th>
                                            <th>Difference</th>
                                        </tr>
                                    </thead>
                                    <?php
                                }
                                ?>
                                <tbody class="text-sm">
                                    <?php
                                    $ID = $_POST['id'];
                                    $date2 = $_POST['date2'];

                                    if (isset($_POST['search'])) {

                                        if ($ID === 'loan') {
                                            $qry = "select o.code,o.Name,
                                                    (select sum(Loandr-Loancr) from loandetail where o.id=officeid and savedate<='$date2')Mem_Compile_Bal,
                                                    sum(l.dramount-l.cramount) Trail_Bal
                                                    from ledger l,officedetail o
                                                    where o.id=l.officeid and l.ldate between'2075/04/01' and '$date2' and l.accountheadid in(select id from acctree where parentid=226)
                                                    group by o.code,o.name,o.id
                                                    order by o.code";
                                        } else if ($ID === 'saving') {
                                            $qry = "select o.code,o.Name,
                                                    (select sum(cramount-dramount) from savingdetail where o.id=officeid and savedate<='$date2' and savingtypeid in(1,2,3,4,5))Mem_Compile_Bal,
                                                    sum(l.cramount-l.dramount) Trail_Bal
                                                    from ledger l,officedetail o
                                                    where o.id=l.officeid and l.ldate between'2075/04/01' and '$date2' and l.accountheadid in(select id from acctree where parentid=313)
                                                    group by o.code,o.name,o.id
                                                    order by o.code";
                                        }
                                    }
                                    $result = odbc_exec($connection, $qry);
                                    while ($res = odbc_fetch_array($result)) {
                                        $net = $res['Mem_Compile_Bal'] - $res['Trail_Bal'];
                                        ?>
                                        <tr>
                                            <td><?php echo $res['code']; ?></td>
                                            <td><?php echo $res['Name']; ?></td>
                                            <td><?php echo $res['Mem_Compile_Bal']; ?></td>
                                            <td><?php echo $res['Trail_Bal']; ?></td>
                                            <td><?php echo $net; ?></td>
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
    $('#ledger').DataTable({
        scrollY: "300px",
        scrollCollapse: true,
        paging: false,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                filename: 'Compile Report',
                title: 'Jeevan Bikas Samaj',
                messageTop: ' <?php if (isset($_POST['search'])) {
    echo $branchName . ' ( ' . $_POST['id'] . '-' . $_POST['date2'] . ' ) - Compile Report';
} else {
    echo $branchName . ' ( ' . $cdate . ' ) - Compile Report';
} ?>',
            },
            {
                extend: 'pdf',
                filename: 'Compile Report',
                title: 'Jeevan Bikas Samaj',
                messageTop: ' <?php if (isset($_POST['search'])) {
    echo $branchName . ' ( ' . $_POST['id'] . '-' . $_POST['date2'] . ' ) - Compile Report';
} else {
    echo $branchName . ' ( ' . $cdate . ' ) - Compile Report';
} ?>',
            },
            {
                extend: 'print',
                filename: 'Ledger With Compile Report',
                title: 'Jeevan Bikas Samaj',
                messageTop: ' <?php if (isset($_POST['search'])) {
    echo '<h5 class="text-bold text-center">' . $branchName . '<br/> ( Compile Report - ' . $_POST['id'] . '-' . $_POST['date2'] . ' ) </h5>';
} else {
    echo '<h5 class="text-bold text-center">' . $branchName . '<br/> ( Compile Report ' . $cdate . ' )</h5>';
} ?>',
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


