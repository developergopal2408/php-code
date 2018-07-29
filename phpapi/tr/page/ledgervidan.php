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
                            if(isset($_POST['search'])){
                                echo "<h5 class='text-bold text-center'>".$branchName." - (".$_POST['id']." - ".$_POST['date2']." )</h5>";
                            }
                            ?>
                            <table id="ledger" class="table display table-condensed table-bordered table-striped" style="width: auto;">
                                <?php
                                if (isset($_POST['search'])) {
                                    ?>
                                    <thead class="bg-red text-sm">
                                        <tr>
                                            <th>ID</th>
                                            <?php
                                            if ($_POST['id'] == 'saving') {
                                                echo "<th>SavingType</th><th>Balance</th>";
                                            } else if ($_POST['id'] == 'loan') {
                                                echo "<th>LoanType</th><th>Balance</th>";
                                            }
                                            ?>
                                        </tr>
                                    </thead>
                                    <?php
                                }
                                ?>
                                <tbody class="text-sm">
                                    <?php
                                    $ID = $_POST['id'];
                                    $date2 = $_POST['date2'];
                                    if ($_SESSION['BranchID'] > 1) {
                                        $idx = "and s.officeid= '" . $_SESSION['BranchID'] . "'";
                                    }
                                    if (isset($_POST['search']) AND $_SESSION['BranchID'] > 1) {
                                        
                                        if ($ID === 'loan') {
                                            $qry = "select t.loantypeid,t.loantype,sum(s.loandr-s.loancr)balance
						from loantype t,loandetail s
						where t.loantypeid=s.loantypeid and savedate<='$date2' $idx 
						group by t.loantypeid,t.loantype
						order by t.loantypeid";
                                        } else if ($ID === 'saving') {
                                            $qry = "select t.savingtypeid,t.savingtype,sum(s.cramount-s.dramount)bal
						from savingtype t,savingdetail s
						where t.savingtypeid=s.savingtypeid and savedate<='$date2' $idx
						group by t.savingtypeid,t.savingtype
						order by t.savingtypeid";
                                        }
                                        $result = sqlsrv_query($connection, $qry);
                                        while ($res = sqlsrv_fetch_array($result)) {
                                            ?>
                                            <tr>
                                                <td><?php echo ++$counter; ?></td>
                                                <?php
                                                if ($ID == 'saving') {
                                                    ?>
                                                    <td><?php echo $res['savingtype']; ?></td>
                                                    <td><?php echo number_format($res['bal'], 2); ?></td>
                                                    <?php
                                                } else if ($ID == 'loan') {
                                                    ?>
                                                    <td><?php echo $res['loantype']; ?></td>
                                                    <td><?php echo number_format($res['balance'], 2); ?></td>
                                                    <?php
                                                }
                                                ?>
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
    $('#ledger').DataTable({
        
        scrollY: "300px",
        scrollCollapse: true,
        paging: false,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                filename: 'Ledger With Compile Report',
                title: 'Jeevan Bikas Samaj',
                messageTop: ' <?php if(isset($_POST['search'])){echo $branchName . ' ( ' .$_POST['id']. '-' . $_POST['date2'] . ' ) - Ledger With Compile Report';}else{echo $branchName . ' ( ' .$cdate. ' ) - Ledger With Compile Report';}?>',

            },
            {
                extend: 'pdf',
                filename: 'Ledger With Compile Report',
                title: 'Jeevan Bikas Samaj',
                messageTop: ' <?php if(isset($_POST['search'])){echo $branchName . ' ( ' .$_POST['id']. '-' . $_POST['date2'] . ' ) - Ledger With Compile Report';}else{echo $branchName . ' ( ' .$cdate. ' ) - Ledger With Compile Report';}?>',

            },
            {
                extend: 'print',
                filename: 'Ledger With Compile Report',
                title: 'Jeevan Bikas Samaj',
                messageTop: ' <?php if(isset($_POST['search'])){echo '<h5 class="text-bold text-center">' . $branchName . '<br/> ( Ledger With Compile Report - '  .$_POST['id']. '-'  . $_POST['date2'] . ' ) </h5>';}else{echo '<h5 class="text-bold text-center">' . $branchName . '<br/> ( Ledger With Compile Report ' .$cdate. ' )</h5>';}?>',
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


