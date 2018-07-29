<?php
include_once 'top.php';
include_once 'header.php';
$mid = $_GET['memberid'];
?>
<!-- Site wrapper -->
<div class="wrapper">
    <style type="text/css">
        @media print {
            @page {
                margin: 0mm;
                padding: 0mm;
            }
            body { 
                margin: 1in;
            }

        </style>
        <?php
        include_once 'sidebar_header.php'; //Include Sidebar_header.php-->
        include_once 'sidebar.php'; //Include Sidebar.php-->
        $query = "select o.name,m.regno+' ( '+m.membercode+' ) '+m.Firstname+' '+m.Lastname as MemberName ,c.chequeNO
          from officedetail o, member m, chequedetail c
          where o.id=m.officeid and o.id=c.officeid and m.memberid=c.memberid and c.MasterID=(select MAX(MasterID) from ChequeDetail where c.OfficeID =OfficeID and
		  MemberID=c.MemberID)  and m.memberid='$mid' and c.status = 'U'
          and c.officeid = '" . $_SESSION['BranchID'] . "'
          group by o.name,m.membercode,m.firstname,m.lastname,c.chequeno,m.regno
          order by c.chequeno";
        $result = odbc_exec($connection, $query);
        //$rows = array();
        ?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    <i class="fa fa-building"></i> <?php echo $branchName; ?>
                    <small>Cheque Print</small>
                </h1>
                <ol class="breadcrumb">
                    <button type="button" class="btn btn-sm bg-blue" onClick="printDiv('printableArea')"  ><i class="glyphicon glyphicon-print"></i></button>
                </ol>
            </section>
            <!-- Main content -->
            <section class="content">
                <div class="box box-solid">
                    <div class="box-body" >
                        <div id="printableArea">
                            <?php
                            while ($rows = odbc_fetch_array($result)) {
                                ?>
                                <table class="no-border" style="margin-left:2.57in;" >
                                    <tr>
                                        <th><?php echo $rows['name']; ?></th>
                                    </tr>
                                </table>
                                <table class="no-border " style="margin-top:3cm;margin-left:150px;">
                                    <thead>
                                        <tr>
                                            <th><?php echo $rows['MemberName']; ?>
                                                <br/>
                                                <?php echo $rows['chequeNO']; ?></th>
                                        </tr>
                                    </thead>
                                </table>
                                <hr style='width:0px;height:0px;page-break-after:always;'>
                                <?php
                            }
                            ?> 
                        </div>
                    </div>

                </div>
            </section>

            <!-- /.content -->
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
      
        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }

    </script>