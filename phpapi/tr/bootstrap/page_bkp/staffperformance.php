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
                <small>Staffwise Performance Detail</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Staffwise Performance Detail</li>
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
                                            <input maxlength="10" type="text" name="date1" id="date1" class=" nepali-calendar form-control" placeholder="Select Date" 
                                                   value="<?php
                                                   if (isset($_POST['date1'])) {
                                                       echo $_POST['date1'];
                                                   } else {
                                                       echo $sdate;
                                                   }
                                                   ?>"
                                                   >
                                        </div>
                                        <div class="col-sm-2">
                                            <input maxlength="10" type="text" name="date2" id="date2" class=" nepali-calendar form-control" placeholder="Select Date" 
                                                   value="<?php
                                                   if (isset($_POST['date2'])) {
                                                       echo $_POST['date2'];
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
                                                        <option value="<?php echo $rows['ID']; ?>" ><?php echo $rows['Code'] . " - " . $rows['Name']; ?></option>;
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
                                <div class="pull-right" >
                                    <a href="staffperformance.php"  class=" btn btn-flat bg-blue" title="Refresh"><i class="fa fa-refresh"></i></a>
                                </div>
                            </div>
                        </div>

                        <div class="box-body">
                            <?php
                            $tmember = 0;
                            $tborrower = 0;
                            $tloan = 0;
                            $tsaving = 0;
                            $net = 0;
                            $center = 0;
                            $PenNomi = 0;
                            $trefresh = 0;
                            $totalmember = 0;
                            $branchName = "";
                            if (isset($_POST['search'])) {
                                $sqls = "SELECT Name FROM OfficeDetail Where ID = '" . $_POST['oid'] . "' ";
                                $results = odbc_exec($connection, $sqls);
                                $reso = odbc_fetch_array($results);
                                $branchName = $reso['Name'];
                                echo "<h5 class='text-bold text-center'> Staff Performance Detail - " . $branchName . "( " . $_POST['date1'] . " - " . $_POST['date2'] . "  )</h5>";
                            }
                            ?>
                            <table id="sperformance" class="display" style="font-size:10px;"> 
                                <thead class="bg-red text-sm" >
                                    <tr>
                                        <th>Code</th>
                                        <th>StaffName</th>
                                        <th>Responsibility</th>
                                        <th>Center</th>
                                        <th>Act.Member</th>
                                        <th>Borrower</th>
                                        <th>Loanoutstanding</th>
                                        <th>Saving</th>
                                        <th>PenNominee</th>
                                        <th>NetMem</th>
                                        <th>Refresher</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $id = $_POST['oid'];
                                    $date1 = $_POST['date1'];
                                    $date2 = $_POST['date2'];
                                    if ($_SESSION['BranchID'] == 1 AND $id == "") {
                                        $idx = "";
                                        $dx = "";
                                    } else if ($_SESSION['BranchID'] == 1 AND $id != 1) {
                                        $idx = "and officeid = '$id'";
                                        $dx = "and s.branchid = '$id'";
                                    } else {
                                        $idx = "and officeid = '" . $_SESSION['BranchID'] . "'";
                                        $dx = "and s.branchid = '" . $_SESSION['BranchID'] . "'";
                                    }
                                    if (isset($_POST['search'])) {
                                        $qry = "select s.code,s.firstname+' '+s.lastname as StaffName,(j.jobtype)Responsibility,
(select count(centerid)from centermain where active='Y' and staffid=s.staffid and formeddate <='$date2' $idx)center,
(select count(memberid)from member where status='ACTIVE' and centerid in(select centerid from centermain where s.staffid=staffid and  s.branchid = officeid ) $idx 
and regdate<='$date2')member,
(select count(memberid)from member where status='DROPOUT' and centerid in(select centerid from centermain where s.staffid=staffid and s.branchid = officeid) $idx 
and DropOutdate >'$date2')drmember,
(select count(distinct memberid)from loanviewmember where  centerid in(select centerid from centermain where s.staffid=staffid and s.branchid = officeid)and loanbalance > 0 $idx)borrower,
(select sum(loandr-loancr)from loandetail where  centerid in(select centerid from centermain where s.staffid=staffid and s.branchid = officeid) $idx
 and savedate<='$date2')loanout,
(select sum(cramount-dramount)from savingdetail where  centerid in(select centerid from centermain where s.staffid=staffid and s.branchid = officeid) $idx 
and savedate<='$date2')saving,

(select sum(intcr-intdr)from savingdetail where savingtypeid = 5 and  centerid in(select centerid from centermain where s.staffid=staffid and s.branchid = officeid) $idx 
and savedate <= '$date2')PenNomi,

(select count(memberid)from member where status='ACTIVE' and centerid in(select centerid from centermain where s.staffid=staffid and s.branchid = officeid) $idx 
and regdate>='$date1' and regdate<='$date2')NewAddMem,

(select count(memberid)from member where status='DROPOUT' and centerid in(select centerid from centermain where s.staffid=staffid and s.branchid = officeid) $idx 
and DropOutdate>='$date1' and DropOutdate<='$date2')DropMem,
(select count(centerid)from centertraining where  centerid in(select centerid from centermain where s.staffid=staffid and s.branchid = officeid) $idx
and FromDate>='$date1' and ToDate<='$date2')Refresher
from staffmain s,centermain c,jobtype j
where s.staffid=c.staffid and s.branchid=c.officeid $dx and s.statusid=1 
and j.jobtypeid=s.jobtypeid
group by s.code,s.firstname,s.lastname,s.staffid,j.jobtype,s.branchid
order by s.code";
                                        $result = odbc_exec($connection, $qry);
                                        while ($res = odbc_fetch_array($result)) {
                                            $tborrower += $res['borrower'];
                                            $tloan += $res['loanout'];
                                            $tsaving += $res['saving'];
                                            $center += $res['center'];
                                            $trefresh += $res['Refresher'];
                                            $netmember = $res['NewAddMem'] - $res['DropMem'];
                                            $net += $netmember;
                                            $totalmember = $res['member'] + $res['drmember'];
                                            $tmember += $totalmember;
                                            $PenNomi += $res['PenNomi'];
                                            ?>
                                            <tr class="text-sm">
                                                <td><?php echo $res['code']; ?></td>
                                                <td><?php echo $res['StaffName']; ?></td>
                                                <td><?php echo $res['Responsibility']; ?></td>
                                                <td><?php echo $res['center']; ?></td>
                                                <td><?php echo $totalmember; ?></td>
                                                <td><?php echo $res['borrower']; ?></td>
                                                <td><?php echo $res['loanout']; ?></td>
                                                <td><?php echo $res['saving']; ?></td>
                                                <td><?php echo $res['PenNomi']; ?></td>
                                                <td><?php echo $netmember; ?></td> 
                                                <td><?php echo $res['Refresher']; ?></td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                                <tfoot>

                                    <tr class="bg-red text-bold text-sm">
                                        <td colspan="3">Total</td>
                                        <td ><?php echo $center; ?></td>
                                        <td><?php echo $tmember; ?></td>
                                        <td><?php echo $tborrower; ?></td>
                                        <td><?php echo $tloan; ?></td>
                                        <td><?php echo $tsaving; ?></td>
                                        <td><?php echo $PenNomi; ?></td>
                                        <td><?php echo $net; ?></td>
                                        <td><?php echo $trefresh; ?></td>
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
    $('#sperformance').DataTable({
        scrollY: "300px",
        scrollCollapse: true,
        paging: false,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                filename: 'StaffPerformanceDetail',
                title: 'Jeevan Bikas Samaj',
                messageTop: 'Staff performance Detail  <?php echo $branchName; ?> ' + $("#date1").val() + " - " + $("#date2").val()
            },
            {
                extend: 'pdf',
                filename: 'StaffPerformanceDetail',
                title: 'Jeevan Bikas Samaj',
                messageTop: 'Staff performance Detail  <?php echo $branchName; ?>  ' + $("#date1").val() + " - " + $("#date2").val()
            },
            {
                extend: 'print',
                filename: 'StaffPerformanceDetail',
                title: 'Jeevan Bikas Samaj',
                messageTop: 'Staff performance Detail  <?php echo "<br/>" . $branchName . "<br/>"; ?> ' + $("#date1").val() + " - " + $("#date2").val(),
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



