<?php
include_once 'top.php';
include_once 'header.php';
?>
<div class="wrapper">
    <?php
    include_once 'sidebar_header.php';
    include_once 'sidebar.php';
    ?>
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-money"></i> 
                <small>Error Installment Receipt</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Error Installment Receipt</li>
            </ol>
        </section>
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
                                                    $result = odbc_exec($connection, $sql1);

                                                    while ($rows = odbc_fetch_array($result)) {
                                                        ?>
                                                        <option value="<?php echo $rows['ID']; ?>" <?php
                                                        if ($rows['ID'] == $_POST['oid']) {
                                                            echo "selected";
                                                        }
                                                        ?>><?php echo $rows['Code'] . " - " . $rows['Name']; ?></option>;
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
                                <div class="pull-right">
                                    <a href="einstreceipt.php"  class=" btn btn-flat bg-blue" title="Refresh"><i class="fa fa-refresh"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="box-body"> 
                            <?php
                            if (isset($_POST['search'])) {
                                $sqls = "SELECT Name FROM OfficeDetail Where ID = '" . $_POST['oid'] . "' ";
                                $results = odbc_exec($connection, $sqls);
                                $reso = odbc_fetch_array($results);
                                echo "<h5 class='text-center text-bold'>Error Installement Receipt - " . $reso['Name'] . "( " . $_POST['date1'] . "  )</h5>";
                            }
                            ?>
                            <table id="err" class="table table-condensed " > 
                                <thead class="bg-red text-sm">
                                    <tr>
                                        <th>O.Code</th>
                                        <th>O.Name</th>
                                        <th>Mem.ID</th>
                                        <th>M.Code</th>
                                        <th>M.Name</th>
                                        <th>LoanMainID</th>
                                        <th>Principal</th>
                                        <th>Interest</th>
                                        <th>PriDue</th>
                                        <th>Intdue</th>
                                        <th>FirstName</th>
                                        <th>LastName</th>
                                        <th>Mobile</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    <?php
                                    $id = $_POST['oid'];
                                    $date1 = $_POST['date1'];
                                    if ($_SESSION['BranchID'] == 1 AND $id == "") {
                                        $idx = "";
                                    } else if ($_SESSION['BranchID'] == 1 AND $id != 1) {
                                        $idx = "and o.id = '$id'";
                                    } else {
                                        $idx = "and o.id = '" . $_SESSION['BranchID'] . "'";
                                    }
                                    //$totalbank = 0.0;
                                    //$CashBalance = 0.0;
                                    if (isset($_POST['search'])) {
                                        $qry = "select o.code,o.name,l.memberid,m.membercode,m.firstname+' '+m.lastname as MemberName,
                                                l.loanmainid,
						(select SUM(loancr) from loandetail where loanmainid=n.loanmainid and officeid=n.officeid and savedate = '$date1')Principal,
						(select SUM(intcr) from loandetail where loanmainid=n.loanmainid and officeid=n.officeid and savedate = '$date1')interest,
						(select SUM(pridue) from loandetail where loanmainid = l.loanmainid and officeid = l.officeid and savedate = '$date1')pridue,
						(select SUM(intdue) from loandetail where loanmainid=n.loanmainid and officeid=n.officeid and savedate = '$date1')intdue,
                                                (select firstname from staffmain where staffid=l.UserID)firstname,
                                                (select Lastname from staffmain where staffid=l.UserID)Lastname,
                                                (select Mobile from staffmain where staffid=l.UserID)Mobile
                                                from LoanDetail l,officedetail o,member m,loanmain n
                                                where  l.SaveDate= '$date1' and o.id=l.officeid $idx
                                                and o.id=m.officeid and l.memberid=m.memberid and n.loanmainid=l.loanmainid and n.officeid=l.officeid
                                                group by l.MemberID,l.loanmainid,o.code,o.name,m.membercode,
                                                m.firstname,m.lastname,l.userid,l.intcr,l.intdue,l.officeid,n.loanmainid,n.officeid
                                                having sum(l.pridue)>0";
                                        $result = odbc_exec($connection, $qry);
                                        while ($res = odbc_fetch_array($result)) {
                                            //$totalbank += $res['Bank'];
                                            //$CashBalance += $res['CashBalance'];
                                            if ($res['Principal'] > 0) {
                                                ?>
                                                <tr>
                                                    <td><?php echo $res['code']; ?></td>
                                                    <td><?php echo str_ireplace("Branch Office", "", $res['name']); ?></td>
                                                    <td><?php echo $res['memberid']; ?></td>
                                                    <td><?php echo $res['membercode']; ?></td>
                                                    <td><?php echo $res['MemberName']; ?></td>
                                                    <td><?php echo $res['loanmainid']; ?></td>
                                                    <td><?php echo $res['Principal']; ?></td>
                                                    <td><?php echo $res['interest']; ?></td>
                                                    <td><?php echo $res['pridue']; ?></td>
                                                    <td><?php echo $res['intdue']; ?></td>
                                                    <td><?php echo $res['firstname']; ?></td>
                                                    <td><?php echo $res['Lastname']; ?></td>
                                                    <td><?php echo $res['Mobile']; ?></td>

                                                </tr>
                                                <?php
                                            }
                                        }
                                    }
                                    ?>
                                </tbody>
                                <tfoot>

                                    <tr class="bg-red text-bold">
                                        <td colspan="3">Total</td>
                                        <td><?php echo $CashBalance; ?></td>
                                        <td><?php echo $totalbank; ?></td>
                                        <td></td>
                                    </tr>

                                </tfoot>
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
    /* $(document).ready(function() {
     $('#funddue').DataTable();
     } );*/
    $('#err').DataTable({
        scrollY: "300px",
        scrollCollapse: true,
        paging: false,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                title: 'Jeevan Bikas Samaj',
                messageTop: 'Error Installement Receipt - ' + $("#date1").val(),
            },
            {
                extend: 'print',
                title: 'Jeevan Bikas Samaj',
                messageTop: 'Error Installement Receipt - ' + $("#date1").val(),
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

