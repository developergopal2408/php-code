<?php
ini_set('session.gc_maxlifetime', 180);
session_set_cookie_params(180);
ini_set('max_execution_time', 300);
ob_start();
session_start();
require_once '../db.php';
if (!isset($_SESSION['StaffID'])) {
    header('Location:../login.php');
}
$BranchID = $_SESSION['BranchID'];
include_once 'header.php';
?>

<style>

    .headcol{
        position:absolute;
        border-top-width:8px; /*only relevant for first row*/
        margin-top:-1px; /*compensate for top border*/
        background:black;
        color:#FFF;
        text-align:center;
        width:51px;


    }
    .headcol:before {content: '';}

</style>
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
                <small>Loan Main List</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Loan Main List</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">

                    <div class="box box-danger">

                        <div class="box-header with-border">
                            <span class="text-bold">Loan Main List</span>
                        </div>

                        <div class="box-body">
                            <table id="daybook" class="table table-bordered table-striped" bordered="1" > 
                                <thead class="bg-red text-sm">
                                    <tr >
                                        <th>OfficeCode</th>
                                        <th>MemberID</th>
                                        <th>MemberCode</th>
                                        <th>LoanNo</th>
                                        <th>LoanAmount</th>
                                        <th>InstAmount</th>
                                        <th>LoanTypeID</th>
                                        <th>IntCrOptID</th>
                                        <th>LoanPeriod</th>
                                        <th>InstallementNo</th>
                                        <th>IssueDate</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $qry = "select o.Code,m.memberid,m.membercode,m.FirstName +' '+ m.LastName as MemberName,l.loanno,l.loanamount,l.instamount,l.loantypeid,l.intcroptionid,l.loanperiod,l.installementno,l.issuedate
                                            from loanmain l,officedetail o,Member m
                                            where  l.MemberID = m.MemberID and l.OfficeID = o.ID and l.instamount=0 and o.id=m.officeid
                                            order by o.name,m.membercode";
                                    $result = odbc_exec($connection, $qry);
                                    while ($res = odbc_fetch_array($result)) {
                                        ?>
                                    <tr class="text-sm">
                                            <td class="headcol"><?php echo $res['Code']; ?></td>
                                            <td><?php echo $res['memberid']; ?></td>
                                            <td><?php echo $res['membercode']; ?></td>
                                            <td><?php echo $res['loanno']; ?></td>
                                            <td><?php echo $res['loanamount']; ?></td>
                                            <td><?php echo $res['instamount']; ?></td>
                                            <td><?php echo $res['loantypeid']; ?></td>
                                            <td><?php echo $res['intcroptionid']; ?></td>
                                            <td><?php echo $res['loanperiod']; ?></td>
                                            <td><?php echo $res['installementno']; ?></td>
                                            <td><?php echo $res['issuedate']; ?></td>
     
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


