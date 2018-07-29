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
                <small>Branch Wise Saving</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Branch Wise Saving</li>
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
                                                <button type="submit" name="search" id="search-btn" class=" btn btn-flat bg-green"><i class="fa fa-search"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                    <?php
                                }
                                ?>

                                <!-- /.search form -->
                                <a href="branchwisesaving.php"  class=" btn btn-flat bg-blue pull-right" title="Refresh"><i class="fa fa-refresh"></i></a>
                            </div>
                        </div>

                        <div class="box-body">
                            <table id="eli" class="table display  text-sm" style="width:auto;"> 
                                <thead class="bg-red">
                                    <tr>
                                        
                                        <th>Code</th>
                                        <th>Office</th>
                                        <th>Loan Outstanding</th>
                                        <th>Saving</th>
                                        
                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    <?php
                                   
                                    $date1 = $_POST['date1'];

                                    if (isset($_POST['search'])) {
                                        $qry = "select o.code,o.Name ,
                                                (select sum(loandr-loancr) from loandetail where o.id=officeid and savedate<='$date1')LoanOutstanding,
                                                sum(s.cramount-s.dramount)SavingBalance
                                                from officedetail o,savingdetail s
                                                where o.id=s.officeid and savedate<='$date1' 
                                                group by o.code,o.name,o.id
                                                order by o.code";
                                    } 
                                    $result = odbc_exec($connection, $qry);
                                    while ($res = odbc_fetch_array($result)) {
                                    
                                        ?>
                                        <tr>
                                            
                                            <td><?php echo $res['code']; ?></td>
                                            <td><?php echo str_ireplace("Branch Office", " ", $res['Name']); ?></td>
                                            <td><?php echo $res['LoanOutstanding']; ?></td>
                                            <td><?php echo $res['SavingBalance']; ?></td>
                                           
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
    $('#eli').DataTable({
        //scrollX: true,
        scrollY: "300px",
        scrollCollapse: true,
        paging: false,
        dom: 'Bfrtip',
        
        //fixedColumns: {leftColumns: 4},
        buttons: [
            {
                extend: 'excel',
                filename: 'Branch Wise Saving',
                title: 'Jeevan Bikas Samaj',
                messageTop: 'Branch Wise Saving',
            },
            {
                extend: 'print',
                filename: 'Branch Wise Saving',
                title: 'Jeevan Bikas Samaj',
                messageTop: 'Branch Wise Saving',
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
