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

                <small>Member Queries</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Member Queries</li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-danger">
                        <div class="box-header with-border">
                            <span class="text-bold text-red">Member Queries</span>
                        </div>

                        <div class="box-body">

                            <table id="memberpersonal" class="table table-responsive table-bordered table-striped text-sm" >
                                <thead class="bg-red">
                                    <tr  >
                                        
                                        <th>Message</th>
                                        <th>Member</th>
                                        <th>Office</th>
                                        <th>PostedDate</th>

                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $qry = "select * from MemberQueries ORDER BY ID DESC";
                                    $result = odbc_exec($connection, $qry);
                                    while ($res = odbc_fetch_array($result)) {
                                        
                                        $osql = odbc_exec($connection,"select Name from OfficeDetail where ID = '".$res['OfficeID']."'");
                                        $run = odbc_fetch_array($osql);
                                        $msql = odbc_exec($connection,"select FirstName+' '+LastName as MemberName from member where MemberID = '".$res['MemberID']."'");
                                        $runm = odbc_fetch_array($msql);
                                         
                                        ?>
                                        <tr class="text-sm">

                                            
                                            <td><?php echo $res['Message']; ?></td>
                                            <td><?php echo $runm['MemberName']; ?></td>
                                            <td><?php echo $run['Name']; ?></td>
                                            <td><?php echo $res['PostDateTime']; ?></td>

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
                filename: 'Member Queries Detail',
                title: 'Jeevan Bikas Samaj',
                messageTop: '<?php
if (isset($_POST['search'])) {
    echo $bname . "- Member Queries Detail ";
} else {
    echo $branchName . "- Member Queries Detail ";
};
?>',
            },
            {
                extend: 'print',
                filename: 'Member Queries Detail',
                title: 'Jeevan Bikas Samaj',
                messageTop: '<?php
if (isset($_POST['search'])) {
    echo $bname . "<br/> Member Queries Detail ";
} else {
    echo $branchName . "<br/> Member Queries Detail ";
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
