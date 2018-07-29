<?php
include_once 'top.php';
include_once 'header.php';
?>
<div class="wrapper">
    <?php
    include_once 'sidebar_header.php';
    include_once 'sidebar.php';

    function arrayContainsDuplicate($array) {
        return count($array) != count(array_unique($array));
    }
    ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                <i class="fa fa-building"></i> 
                <small>Loan OverDue</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Loan OverDue</li>
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
                                                       echo $cdate;
                                                   }
                                                   ?>"
                                                   >
                                        </div>

                                        <?php
                                        if ($BranchID == 1) {
                                            ?>
                                            <div class="col-sm-4">
                                                <select name="oid" id="oid" class="form-control select2" required>
                                                    <option value="">Select Branch</option>
                                                    <option value="all">All Branch</option>
                                                    <?php
                                                    $sql1 = "SELECT * FROM OfficeDetail where ID > 1 ORDER BY ID ASC ";
                                                    $result = odbc_exec($connection, $sql1);

                                                    while ($rows = odbc_fetch_array($result)) {
                                                        ?>
                                                        <option value="<?php echo $rows['ID']; ?>" <?php
                                                        if ($_POST['oid'] == $rows['ID']) {
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
                                <!-- /.search form -->
                                <a href="loandue1.php"  class=" btn btn-flat bg-blue pull-right" title="Refresh"><i class="fa fa-refresh"></i></a>


                            </div>
                        </div>

                        <div class="box-body">
                            <?php
                            if (isset($_POST['search'])) {
                                $sqls = "SELECT Name FROM OfficeDetail Where ID = '" . $_POST['oid'] . "' ";
                                $results = odbc_exec($connection, $sqls);
                                $reso = odbc_fetch_array($results);
                                echo "<h5 class='text-bold text-center'>Loan OverDue - " . $reso['Name'] . "( " . $_POST['date1'] . "  )</h5>";
                            }
                            ?>
                            <table id="loandue1" class="table stripe row-border order-column  text-sm" cellspacing="0" width="100%"  style="font-size:10.6px;"> 
                                <thead class="bg-red">
                                    <tr>
                                        <th>Off. Name</th>
                                        <th>MemberId</th>
                                        <th>Mem.Code</th>
                                        <th>Mem.Name</th>
                                        <th>SaveDate</th>
                                        <th>L.Type</th>
                                        <th>LoanNo</th>
                                        <th>L.Heading</th>
                                        <th>Pridue</th>
                                        <th>Intdue</th>
                                        <th>PAR</th>
                                        <th>StaffCode</th>
                                        <th>Special</th>
                                        <th>Personal</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $id = $_POST['oid'];
                                    $date1 = $_POST['date1'];
                                    $pridue = 0.0;
                                    $intdue = 0.0;
                                    $par = 0.0;
                                    $resu = 0;
                                    $new_array = array();
                                    if ($BranchID == 1) {
                                        $idx = "and o.id='$id'";
                                    } else {
                                        $idx = "and o.id='$BranchID'";
                                    }
                                    if (isset($_POST['search'])) {
                                        if ($id == "all" AND $BranchID == 1) {
                                            $qry = "select  m.regno,m.memberid,o.code,o.name,m.membercode,m.firstname+' '+m.lastname as MemberName,l.savedate,t.Loantype,l.loanNo,sum(l.pridue)pridue,sum(l.intdue)intdue,h.LoanHeading,
												(select sum(loandr-loancr) from loandetail where l.officeid=officeid  and loanmainid=l.loanmainid and l.savedate<='$date1')PAR,
                                                                                                    (select sum(cramount-dramount) from savingdetail where officeid=l.officeid and memberid=l.memberid and savingtypeid=4 and l.savedate<='$date1')special,
(select sum(cramount-dramount) from savingdetail where officeid=l.officeid and memberid=l.memberid and savingtypeid=3 and l.savedate<='$date1')personal,
												(select code from staffmain where l.officeid=branchid and staffid=c.staffid)StaffCode
												from loandetail l ,member m,loantype t,officedetail o,loanheading h,centermain c
												where m.memberid=l.memberid and m.officeid=l.officeid and t.loantypeid=l.loantypeid  and l.savedate=
												(select max(savedate) from loandetail where  savedate<='$date1' and officeid=l.officeid  and loanmainid=l.loanmainid) and c.officeid=l.officeid and l.centerid=c.centerid and l.officeid=o.id and o.id=m.officeid and l.loanheadingid = h.loanheadingid
												group by m.regno,m.memberid,c.staffid, m.membercode,m.firstname,lastname,l.loantypeid,l.savedate,l.officeid,t.loantype,l.loanno,l.loanmainid,o.code,o.name,h.LoanHeading,l.memberid
												having sum(pridue+intdue)>0  
												order by o.code, m.membercode";
                                        } else {
                                            $qry = "select  m.regno,m.memberid,o.code,o.name,m.membercode,m.firstname+' '+m.lastname as MemberName,l.savedate,t.Loantype,l.loanNo,sum(l.pridue)pridue,sum(l.intdue)intdue,h.LoanHeading,
												(select sum(loandr-loancr) from loandetail where l.officeid=officeid  and loanmainid=l.loanmainid and l.savedate<='$date1')PAR,
                                                                                                    (select sum(cramount-dramount) from savingdetail where officeid=l.officeid and memberid=l.memberid and savingtypeid=4 and l.savedate<='$date1')special,
(select sum(cramount-dramount) from savingdetail where officeid=l.officeid and memberid=l.memberid and savingtypeid=3 and l.savedate<='$date1')personal,
												(select code from staffmain where l.officeid=branchid and staffid=c.staffid)StaffCode
												from loandetail l ,member m,loantype t,officedetail o,loanheading h,centermain c
												where m.memberid=l.memberid and m.officeid=l.officeid and t.loantypeid=l.loantypeid $idx and l.savedate=
												(select max(savedate) from loandetail where  savedate<='$date1' and officeid=l.officeid  and loanmainid=l.loanmainid) and c.officeid=l.officeid and l.centerid=c.centerid and l.officeid=o.id and o.id=m.officeid and l.loanheadingid = h.loanheadingid
												group by m.regno,m.memberid,c.staffid, m.membercode,m.firstname,lastname,l.loantypeid,l.savedate,l.officeid,t.loantype,l.loanno,l.loanmainid,o.code,o.name,h.LoanHeading,l.memberid
												having sum(pridue+intdue)>0  
												order by o.code, m.membercode";
                                        }
                                    }

                                    $result = odbc_exec($connection, $qry);

                                    while ($res = odbc_fetch_array($result)) {
                                        if ($res['PAR'] > 0) {
                                            $pridue += $res['pridue'];
                                            $intdue += $res['intdue'];
                                            $par += $res['PAR'];

                                            $sql2 = odbc_exec($connection, "select DISTINCT regno "
                                                    . "from member "
                                                    . "where regno = '" . $res['regno'] . "'   ");
                                            $count = odbc_fetch_array($sql2);
                                            foreach ($count as $key => $value) {
                                                if (isset($new_array[$value]))
                                                    $new_array[$value] += 1;
                                                else
                                                    $new_array[$value] = 1;
                                            }
                                            ?>
                                            <tr>
                                                <td><?php echo str_ireplace('Branch Office', '', $res['name']); ?></td>
                                                <td><?php echo $res['memberid']; ?></td>
                                                <td><?php echo $res['membercode']; ?></td>
                                                <td><?php echo $res['MemberName']; ?></td>
                                                <td><?php echo $res['savedate']; ?></td>
                                                <td><?php echo $res['Loantype']; ?></td>
                                                <td><?php echo $res['loanNo']; ?></td>
                                                <td><?php echo $res['LoanHeading']; ?></td>
                                                <td><?php echo number_format($res['pridue'], 2); ?></td>
                                                <td><?php echo number_format($res['intdue'], 2); ?></td>
                                                <td><?php echo number_format($res['PAR'], 2); ?></td>
                                                <td><?php echo $res['StaffCode']; ?></td>
                                                <td><?php echo $res['special']; ?></td>
                                                <td><?php echo $res['personal']; ?></td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                                <tfoot class="bg-red">
                                    <tr>
                                        <td colspan=2>Total</td>
                                        <td><?php print_r(count($new_array)); ?></td>
                                        <td colspan="5"></td>
                                        <td><?php echo number_format($pridue, 2); ?></td>
                                        <td><?php echo number_format($intdue, 2); ?></td>
                                        <td><?php echo number_format($par, 2); ?></td>
                                        <td colspan="3"></td>
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
    $('#loandue1').DataTable({
        scrollX: true,
        scrollY: "300px",
        scrollCollapse: true,
        paging: false,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                filename: 'Loan overdue List',
                title: 'Jeevan Bikas Samaj',
                messageTop: ' <?php
if (isset($_POST['search'])) {
    if ($_SESSION['BranchID'] > 1) {
        echo $branchName . ' ( ' . $_POST['date1'] . ') - Loan overdue List';
    } else {
        echo $bname . ' ( ' . $_POST['date1'] . ') - Loan Overdue List';
    }
} else {
    echo $branchName . ' ( ' . $cdate . ' ) - Loan Overdue List';
}
?>',
            },
            {
                extend: 'print',
                filename: 'Loan Overdue List',
                title: 'Jeevan Bikas Samaj',
                messageTop: ' <?php
if (isset($_POST['search'])) {
    if ($_SESSION['BranchID'] > 1) {
        echo '<h5 class="text-bold text-center">' . $branchName . '<br/> ( Loan Overdue List - ' . $_POST['date1'] . ' ) </h5>';
    } else {
        echo '<h5 class="text-bold text-center">' . $bname . '<br/> ( Loan Overdue List - ' . $_POST['date1'] . ' ) </h5>';
    }
} else {
    echo '<h5 class="text-bold text-center">' . $branchName . '<br/> ( Loan Overdue List ' . $cdate . '  )</h5>';
}
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





