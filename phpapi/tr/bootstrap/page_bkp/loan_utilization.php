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
                <small>Loan Utilization List</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Loan Utilization List</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-danger">
                        <div class="box-header with-border">
                            <div class="col-sm-12">
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
                                            if ($_SESSION['BranchID'] == 1) {
                                                ?>
                                                <div class="col-sm-3">
                                                    <select name="oid" id="oid" class="form-control select2" >
                                                        <option value="">Select Branch</option>

                                                        <?php
                                                        $sql1 = "SELECT * FROM OfficeDetail ORDER BY ID ASC ";
                                                        $result = odbc_exec($connection, $sql1);

                                                        while ($rows = odbc_fetch_array($result)) {
                                                            ?>
                                                        <option value="<?php echo $rows['ID']; ?>" <?php if($_POST['oid']==$rows['ID']){echo "selected";} ?>><?php echo $rows['Code'] . " - " . $rows['Name']; ?></option>;
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <?php
                                            }
                                            ?>
                                            <div class="col-sm-2">
                                                <select name="sid" id="sid" class="form-control select2" >
                                                    <option value="">Select Type</option>
                                                    <option value="Fstaff" <?php if($_POST['sid']== "Fstaff"){echo "selected";} ?>>Field Staff</option>
                                                    <option value="Incharge" <?php if($_POST['sid']== "Incharge"){echo "selected";} ?>>Incharge</option>

                                                </select>
                                            </div>
                                            <div class="col-sm-2">
                                                <button type="submit" name="search" id="search-btn" class=" btn btn-flat bg-green"><i class="fa fa-search"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                    <!-- /.search form -->
                                    <div class="pull-right" >
                                        <a href="loan_utilization.php"  class=" btn btn-flat bg-blue" title="Refresh"><i class="fa fa-refresh"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="box-body">
                            <?php
                            $bname = "";
                            if (isset($_POST['oid'])) {
                                $query = "SELECT ID,Name,Code FROM OfficeDetail  WHERE  ID = '" . $_POST['oid'] . "'";
                                $sub = odbc_exec($connection, $query);
                                $p = odbc_fetch_array($sub);
                                $bname = $p['Name'];
                                if ($_SESSION['BranchID'] == 1) {
                                    echo "<h5 class='text-bold text-center'>".$bname . " - " . $_POST['sid'] . " ( " . $_POST['date1'] . " - " . $_POST['date2'] . " ) </h5>";
                                } else {
                                    echo "<h5 class='text-bold text-center'>".$branchName . " - " . $_POST['sid'] . " ( " . $_POST['date1'] . " - " . $_POST['date2'] . " ) </h5>";
                                }
                            }
                            ?>
                            <table id="lu" class="stripe row-border order-column" cellspacing="0" width="100%" > 
                                <thead class="bg-red text-sm" >
                                    <tr>
                                        <th>MemCode</th>
                                        <th>MembName</th>
                                        <th>LoanDis Date</th>
                                        <th>LoanType</th>
                                        <th>LoanDisAmt</th>
                                        <th>LoanHeading</th>   
                                        <th>UtilizeAmt</th>
                                        <th>OtherAmt</th>
                                        <th>MisUseAmt</th>
                                        <th>Remarks</th>
                                        <th>UtilizeDate</th>
                                        <th>StaffName</th>
                                    </tr>
                                </thead>

                                <?php
                                $id = $_POST['oid'];
                                $sid = $_POST['sid'];
                                $date1 = $_POST['date1'];
                                $date2 = $_POST['date2'];
                                if ($_SESSION['BranchID'] == 1 AND $sid == "Fstaff") {
                                    $id1 = "and l.officeid='$id'";
                                    $id2 = "and c.officeid='$id'";
                                    $id3 = "and s.branchid='$id'";
                                    $id4 = "and u.officeid='$id'";
                                    $id5 = "and m.officeid='$id'";
                                } else if ($_SESSION['BranchID'] == 1 AND $sid == "Incharge") {
                                    $idx = "and m.officeid='$id' and l.officeid='$id' and u.officeid='$id' and s.branchid='$id'";
                                }else if($_SESSION['BranchID'] > 1 AND $sid == "Fstaff"){
                                    $id1 = "and l.officeid='".$_SESSION['BranchID']."'";
                                    $id2 = "and c.officeid='".$_SESSION['BranchID']."'";
                                    $id3 = "and s.branchid='".$_SESSION['BranchID']."'";
                                    $id4 = "and u.officeid='".$_SESSION['BranchID']."'";
                                    $id5 = "and m.officeid='".$_SESSION['BranchID']."'";
                                }else if ($_SESSION['BranchID'] > 1 AND $sid == "Incharge") {
                                    $idx = "and m.officeid='".$_SESSION['BranchID']."' and l.officeid='".$_SESSION['BranchID']."' and u.officeid='".$_SESSION['BranchID']."' and s.branchid='".$_SESSION['BranchID']."'";
                                }
                                if (isset($_POST['search'])) {
                                    if($sid == "Fstaff"){
                                         $qry = "select m.membercode,m.firstname+' '+m.lastname as MemberName,
                                            (l.issuedate)LoanDisDate,t.loantype,(l.loanamount)LoanDisAmt,h.Loanheading,
                                            u.utilizeamt,u.Otheramt,u.misuseamt,u.remarks,(u.savedate)UtilizaDate,s.firstname+' '+s.lastname as StaffName
                                            from member m
                                            join loanmain l on m.memberid=l.memberid $id1
                                            join loantype t on t.loantypeid=l.loantypeid
                                            join centermain c on c.centerid=m.centerid $id2
                                            join staffmain s on s.staffid=c.staffid $id3
                                            join loanheading h on l.loanheadingid=h.loanheadingid 
                                            left join loanutilization u on u.loanmainid=l.loanmainid $id4
                                            where l.loantypeid<>2 and  l.loanheadingid not in(8,9,10,12,13,14,15,16,70,72,73,74,75,76,77)and
                                            l.issuedate between '$date1' and '$date2' $id5";
                                    }else if($sid == "Incharge"){
                                        $qry = "select m.membercode,m.firstname+' '+ m.lastname as MemberName,(l.issuedate)LoanDisDate,
                                                (select loantype from loantype where l.loantypeid=loantypeid)loantype,
                                                (l.loanamount)LoanDisAmt,
                                                (select loanheading from loanheading where l.loanheadingid=loanheadingid)Loanheading,
                                                u.utilizeamt,u.Otheramt,u.misuseamt,u.remarks,(u.savedate) as UtilizaDate
                                                ,s.Firstname+' '+s.lastname as StaffName
                                                from member m, loanmain l, loanutilization u,Staffmain s
                                                where m.memberid=l.memberid and l.loanmainid=u.loanmainid and s.staffid=u.userid 
                                                and u.savedate between '$date1' AND '$date2'
                                                and s.jobtypeid  In(3,6) $idx
                                                order by m.membercode";
                                    }
                                }
                                $result = odbc_exec($connection, $qry);
                                ?>
                                <tbody class="text-sm">
                                    <?php
                                    while ($res = odbc_fetch_array($result)) {
                                        ?>
                                        <tr>
                                            <td><?php echo $res['membercode']; ?></td>
                                                <td><?php echo $res['MemberName']; ?></td>
                                                <td><?php echo $res['LoanDisDate']; ?></td>
                                                <td><?php echo $res['loantype']; ?></td>
                                                <td><?php echo $res['LoanDisAmt']; ?></td>
                                                <td><?php echo $res['Loanheading']; ?></td>
                                                <td><?php echo $res['utilizeamt']; ?></td>
                                                <td><?php echo $res['Otheramt']; ?></td>
                                                <td><?php echo $res['misuseamt']; ?></td>
                                                <td><?php echo $res['remarks']; ?></td>
                                                <td><?php echo $res['UtilizaDate']; ?></td>
                                                <td><?php echo $res['StaffName']; ?></td>
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
    $('#lu').DataTable({
        scrollX: true,
        scrollY: "300px",
        scrollCollapse: true,
        paging: false,
        dom: 'Bfrtip',
        fixedColumns: {
            leftColumns: 1,
        },
        buttons: [
            {
                extend: 'excel',
                filename: 'Loan Utilization List',
                title: 'Jeevan Bikas Samaj',
                messageTop: ' <?php
if (isset($_POST['search'])) {
    if ($_SESSION['BranchID'] > 1) {
        echo $branchName . ' ( ' . $_POST['date1'] . ' - ' . $_POST['date2'] . ') - Loan Utilization List';
    } else {
        echo $bname . ' ( ' . $_POST['date1'] . ' - ' . $_POST['date2'] . ') - Loan Utilization List';
    }
} else {
    echo $branchName . ' ( ' . $cdate . ' ) - Loan Utilization List';
}
?>',
            },
            {
                extend: 'print',
                filename: 'Loan Utilization List',
                title: 'Jeevan Bikas Samaj',
                messageTop: ' <?php
if (isset($_POST['search'])) {
    if ($_SESSION['BranchID'] > 1) {
        echo '<h5 class="text-bold text-center">' . $branchName . '<br/> ( Loan Utilization List - ' . $_POST['date1'] . ' - ' . $_POST['date2'] . ' ) </h5>';
    } else {
        echo '<h5 class="text-bold text-center">' . $bname . '<br/> ( Loan Utilization List - ' . $_POST['date1'] . ' - ' . $_POST['date2'] . ' ) </h5>';
    }
} else {
    echo '<h5 class="text-bold text-center">' . $branchName . '<br/> ( Loan Utilization List ' . $cdate . '  )</h5>';
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


