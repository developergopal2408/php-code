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
                <small>Error Loan Installment</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Error Loan Installment</li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">

                    <div class="box box-danger">

                        <div class="box-header with-border">

                            <div class="col-sm-12">
                               

                                <!-- /.search form -->
                                <a href="eli.php"  class=" btn btn-flat bg-blue pull-right" title="Refresh"><i class="fa fa-refresh"></i></a>
                            </div>
                        </div>

                        <div class="box-body">
                            <table id="eli" class="table display  text-sm" style="width:auto;"> 
                                <thead class="bg-red">
                                    <tr>
                                        <th>LoanMainID</th>
                                        <th>Code</th>
                                        <th>Off.Name</th>
                                        <th>MemberID</th>
                                        <th>MCode</th>
                                        <th>MName</th>
                                        <th>LoanType</th>
                                        <th>MeetingType</th>
                                        <th>IssueDate</th>
                                        <th>L.Period</th>
                                        <th>L.Amt</th>
                                        <th>InstAmt</th>
                                        
                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    <?php
                                    

                                    
                                        $qry = "select l.memberid,o.code,o.name,m.membercode,m.firstname+' '+m.lastname as MemberName,l.LoanMainID,
t.loantype,l.Loanperiod,(i.intcroption)Meetingtype,l.issuedate,l.loanamount,l.instamount
from loanmain l ,loanviewmember v ,officedetail o, member m, loantype t,intcroptionloan i
where   l.officeid=v.officeid and v.loanmainid=l.loanmainid and v.loanbalance>0
and l.instamount not in(select top 1 instamt from memberloanschedule where l.officeid=officeid 
and l.loanmainid=loanmainid and priamt>0 order by instno)
and l.officeid=o.id and l.officeid=m.officeid and l.memberid=m.memberid and l.loantypeid=t.loantypeid 
and i.intcroptionid=l.intcroptionid 
order by o.code,m.membercode";
                                     
                                    $result = odbc_exec($connection, $qry);
                                    while ($res = odbc_fetch_array($result)) {
                             
                                        ?>
                                        <tr>
                                            <td><?php echo $res['LoanMainID']; ?></td>
                                            <td><?php echo $res['code']; ?></td>
                                            <td><?php echo str_ireplace("Branch Office", " ", $res['name']); ?></td>
                                            <td><?php echo $res['memberid']; ?></td>
                                            <td><?php echo $res['membercode']; ?></td>
                                            <td><?php echo $res['MemberName']; ?></td>
                                            <td><?php echo $res['loantype']; ?></td>
                                            <td><?php echo $res['Meetingtype']?></td>
                                            <td><?php echo $res['issuedate']; ?></td>
                                            <td><?php echo $res['Loanperiod']; ?></td>
                                            <td><?php echo $res['loanamount']; ?></td>
                                            <td><?php echo $res['instamount']; ?></td>
                                            
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
        scrollX: true,
        scrollY: "300px",
        scrollCollapse: true,
        paging: false,
        dom: 'Bfrtip',
        columnDefs: [
            {"width": "3%", "targets": [0]},
            {"width": "5%", "targets": [1, 3]},
            {"width": "5%", "targets": [2]},
            {"width": "13%", "targets": [4, 5, 6]}
        ],
        //fixedColumns: {leftColumns: 4},
        buttons: [
            {
                extend: 'excel',
                filename: 'Error Loan Installment',
                title: 'Jeevan Bikas Samaj',
                messageTop: 'Error Loan Installment',
            },
            {
                extend: 'print',
                filename: 'Error Loan Installment',
                title: 'Jeevan Bikas Samaj',
                messageTop: 'Error Loan Installment',
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
