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
                <i class="fa fa-dashboard"></i> <?php echo $branchName; ?>
                <small>Monthly Progress Report</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Monthly Progress Report</li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <div class="col-sm-12">
                            <?php
                            if ($_SESSION['BranchID'] == 1) {
                                ?>
                                <!-- search form -->
                                <form  action="" method="post" class="form-horizontal" >
                                    <div class=" form-group-sm">

                                        <div class="col-sm-3">
                                            <select name="id" id="id" class="form-control select2" required>
                                                <option value="">Select Branch</option>
                                                <?php
                                                $query = "SELECT ID,Name,Code from OfficeDetail";
                                                $sub = sqlsrv_query($connection, $query);
                                                while ($p = sqlsrv_fetch_array($sub)) {
                                                    ?>
                                                    <option value="<?php echo $p['ID']; ?>" <?php
                                                    if ($p['ID'] == $_POST['id']) {
                                                        echo "selected";
                                                    }
                                                    ?>><?php echo $p['Code'] . " - " . $p['Name']; ?></option>;
                                                            <?php
                                                        }
                                                        ?>
                                            </select>
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

                            <div class="box-tools pull-right" >
                                <a  href="mpreport.php"  class="btn btn-flat bg-blue" data-toggle = "tooltip" title="All / Refresh"><i class="fa fa-refresh"></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="box-body">
                        <table id="mpreport" class="display stripe row-border order-column table-bordered" cellspacing="0" width="100%">
                            <thead class="text-sm bg-red">
                                <tr>
                                    <th>S.No</th>
                                    <th>Particular</th>
                                    <th>Last Month</th>
                                    <th>This Month</th>
                                    <th>Till This Month</th>
                                </tr>
                            </thead>

                            <?php
                            if (isset($_POST['search']) AND $_SESSION['BranchID'] == 1) {
                                $idx = "branchid='" . $_POST['id'] . "'";
                                $idt = "s.branchid='" . $_POST['id'] . "'";
                                $oid = "officeid='" . $_POST['id'] . "'";
                                $ido = "o.id='" . $_POST['id'] . "'";
                            } else {
                                $idx = "branchid='" . $_SESSION['BranchID'] . "'";
                                $idt = "s.branchid='" . $_SESSION['BranchID'] . "'";
                                $oid = "officeid='" . $_SESSION['BranchID'] . "'";
                                $ido = "o.id='" . $_SESSION['BranchID'] . "'";
                            }
                            if (isset($_POST['search']) AND $_SESSION['BranchID'] == 1) {
                                if (sqlsrv_begin_transaction($connection) === false) {
                                    die(print_r(sqlsrv_errors(), true));
                                }
                                $q1 = "select o.id,
                                            (select count(staffid) from staffmain where statusid=1 and branchid=o.id and $idx and JoinDate<='$sdate')TPre,
                                            (select count(staffid) from staffmain where statusid=1 and branchid=o.id and $idx and JoinDate<='$cdate')Total,
                                            (select count(staffid) from staffmain where groupid =1 and branchid=o.id and $idx and statusid =1 and positionid in(8,9,10) and jobtypeid not in(3,6) and PermanentDate<='$sdate')FPre,
                                            (select count(staffid) from staffmain where groupid =1 and branchid=o.id and $idx and statusid =1 and positionid in(8,9,10) and jobtypeid not in(3,6) and PermanentDate<='$cdate')TPre,
                                            (select count(staffid) from staffmain where statusid=1 and branchid=o.id and $idx and groupid=2 and JoinDate<='$sdate')TrPre,
                                            (select count(staffid) from staffmain where statusid=1 and branchid=o.id and $idx and groupid=2 and JoinDate<='$cdate')TrTill,
                                            (select count(staffid) from staffmain where statusid=1 and branchid=o.id and $idx and positionid in(18,19,20)  and  JoinDate<='$sdate')OPre,
                                            (select count(staffid) from staffmain where statusid=1 and branchid=o.id and $idx and positionid in(18,19,20) and JoinDate<='$cdate')OTill
                                            from officedetail o,staffmain s
                                            where o.id=s.branchid and $idt
                                            group by o.id ";
                                $q2 = "select o.id,
                                        (select count(distinct Vdcid) from centermain where o.id=officeid and active='Y' and Formeddate<='$sdate'and vdcid in(select vdcid from vdc where ismun='4')and $oid)Rmunpre,
                                        (select count(distinct Vdcid) from centermain where o.id=officeid and active='Y' and Formeddate<='$cdate'and vdcid in(select vdcid from vdc where ismun='4')and $oid)Rmuntill,
                                        (select count(distinct Vdcid) from centermain where o.id=officeid and active='Y' and Formeddate<='$sdate'and vdcid in(select vdcid from vdc where ismun <>'4')and $oid)Munpre,
                                        (select count(distinct Vdcid) from centermain where o.id=officeid and active='Y' and Formeddate<='$cdate'and vdcid in(select vdcid from vdc where ismun <>'4')and $oid)MunTill,
                                        (select count( centerid) from centermain where o.id=officeid and active='Y' and Formeddate<='$cdate'and $oid)cenpre, 
                                        (select count( centerid) from centermain where o.id=officeid and active='Y' and Formeddate<='$cdate'and $oid)cenTill,
                                        (select count( memberid) from Member where o.id=officeid and status='Active' and Regdate<='$sdate'and $oid)actMempre,
                                        (select count( memberid) from Member where o.id=officeid and status='Dropout' and Regdate<='$sdate' and Dropoutdate>'$sdate'and $oid)act_pre_Mem,
                                        (select count( memberid) from member where o.id=officeid and status='Active' and Regdate<='$cdate'and $oid)MemTill,
                                        (select count( memberid) from Member where o.id=officeid and status='Dropout' and Regdate<='$cdate' and Dropoutdate>'$cdate'and $oid)act_till_Mem,
                                        (select count( memberid) from Member where o.id=officeid and status='active'and Gender='Male' and Regdate<='$sdate'and $oid)actmalepre,
                                        (select count( memberid) from Member where o.id=officeid and status='Dropout' and Gender='Male'and Regdate<='$sdate' and Dropoutdate>'$sdate'and $oid)act_pre_male,
                                        (select count( memberid) from member where o.id=officeid and status='active'and Gender='Male' and Regdate<='$cdate'and $oid)MaleTill,
                                        (select count( memberid) from Member where o.id=officeid and status='Dropout'and Gender='Male' and Regdate<='$cdate' and Dropoutdate>'$cdate'and $oid)act_till_Male,
                                        (select count( memberid) from member where o.id=officeid and status='Passive' and Regdate<='$sdate'and $oid)passPre,
                                        (select count( memberid) from member where o.id=officeid and status='Passive' and Regdate<='$cdate'and $oid)passtill,
                                        (select count( memberid) from Member where o.id=officeid and status='Dropout' and Regdate<='$sdate' and Dropoutdate<='$sdate'and $oid)dro_pre_Mem,
                                        (select count( memberid) from Member where o.id=officeid and status='Dropout' and Regdate<='$cdate' and Dropoutdate<='$cdate'and $oid)dro_till_Mem
                                        from officedetail o
                                        where $ido
                                        group by o.id";
                                $q3 = "select count(*)nob from(select count(distinct memberid)nos
                                        from loandetail 
                                        where $oid and savedate<='$sdate'
                                        group by memberid
                                        having sum(loandr-loancr)>0)nob";
                                $q4 = "select count(*)nobtill from(select count(distinct memberid)nob
                                        from loandetail 
                                        where $oid and savedate<='$cdate'
                                        group by memberid
                                        having sum(loandr-loancr)>0)nobtill";


                                $r1 = sqlsrv_query($connection, $q1)or die(print_r(sqlsrv_errors(), true));
                                $r2 = sqlsrv_query($connection, $q2) or die(print_r(sqlsrv_errors(), true));
                                $r3 = sqlsrv_query($connection, $q3)or die(print_r(sqlsrv_errors(), true));
                                $r4 = sqlsrv_query($connection, $q4)or die(print_r(sqlsrv_errors(), true));
                            } else {
                                if (sqlsrv_begin_transaction($connection) === false) {
                                    die(print_r(sqlsrv_errors(), true));
                                }
                                $q1 = "select o.id,
                                            (select count(staffid) from staffmain where statusid=1 and branchid=o.id and $idx and JoinDate<='$sdate')TPre,
                                            (select count(staffid) from staffmain where statusid=1 and branchid=o.id and $idx and JoinDate<='$cdate')Total,
                                            (select count(staffid) from staffmain where groupid =1 and branchid=o.id and $idx and statusid =1 and positionid in(8,9,10) and jobtypeid not in(3,6) and PermanentDate<='$sdate')FPre,
                                            (select count(staffid) from staffmain where groupid =1 and branchid=o.id and $idx and statusid =1 and positionid in(8,9,10) and jobtypeid not in(3,6) and PermanentDate<='$cdate')TPre,
                                            (select count(staffid) from staffmain where statusid=1 and branchid=o.id and $idx and groupid=2 and JoinDate<='$sdate')TrPre,
                                            (select count(staffid) from staffmain where statusid=1 and branchid=o.id and $idx and groupid=2 and JoinDate<='$cdate')TrTill,
                                            (select count(staffid) from staffmain where statusid=1 and branchid=o.id and $idx and positionid in(18,19,20)  and  JoinDate<='$sdate')OPre,
                                            (select count(staffid) from staffmain where statusid=1 and branchid=o.id and $idx and positionid in(18,19,20) and JoinDate<='$cdate')OTill
                                            from officedetail o,staffmain s
                                            where o.id=s.branchid and $idt
                                            group by o.id ";
                                $q2 = "select o.id,
                                        (select count(distinct Vdcid) from centermain where o.id=officeid and active='Y' and Formeddate<='$sdate'and vdcid in(select vdcid from vdc where ismun='4')and $oid)Rmunpre,
                                        (select count(distinct Vdcid) from centermain where o.id=officeid and active='Y' and Formeddate<='$cdate'and vdcid in(select vdcid from vdc where ismun='4')and $oid)Rmuntill,
                                        (select count(distinct Vdcid) from centermain where o.id=officeid and active='Y' and Formeddate<='$sdate'and vdcid in(select vdcid from vdc where ismun <>'4')and $oid)Munpre,
                                        (select count(distinct Vdcid) from centermain where o.id=officeid and active='Y' and Formeddate<='$cdate'and vdcid in(select vdcid from vdc where ismun <>'4')and $oid)MunTill,
                                        (select count( centerid) from centermain where o.id=officeid and active='Y' and Formeddate<='$cdate'and $oid)cenpre, 
                                        (select count( centerid) from centermain where o.id=officeid and active='Y' and Formeddate<='$cdate'and $oid)cenTill,
                                        (select count( memberid) from Member where o.id=officeid and status='Active' and Regdate<='$sdate'and $oid)actMempre,
                                        (select count( memberid) from Member where o.id=officeid and status='Dropout' and Regdate<='$sdate' and Dropoutdate>'$sdate'and $oid)act_pre_Mem,
                                        (select count( memberid) from member where o.id=officeid and status='Active' and Regdate<='$cdate'and $oid)MemTill,
                                        (select count( memberid) from Member where o.id=officeid and status='Dropout' and Regdate<='$cdate' and Dropoutdate>'$cdate'and $oid)act_till_Mem,
                                        (select count( memberid) from Member where o.id=officeid and status='active'and Gender='Male' and Regdate<='$sdate'and $oid)actmalepre,
                                        (select count( memberid) from Member where o.id=officeid and status='Dropout' and Gender='Male'and Regdate<='$sdate' and Dropoutdate>'$sdate'and $oid)act_pre_male,
                                        (select count( memberid) from member where o.id=officeid and status='active'and Gender='Male' and Regdate<='$cdate'and $oid)MaleTill,
                                        (select count( memberid) from Member where o.id=officeid and status='Dropout'and Gender='Male' and Regdate<='$cdate' and Dropoutdate>'$cdate'and $oid)act_till_Male,
                                        (select count( memberid) from member where o.id=officeid and status='Passive' and Regdate<='$sdate'and $oid)passPre,
                                        (select count( memberid) from member where o.id=officeid and status='Passive' and Regdate<='$cdate'and $oid)passtill,
                                        (select count( memberid) from Member where o.id=officeid and status='Dropout' and Regdate<='$sdate' and Dropoutdate<='$sdate'and $oid)dro_pre_Mem,
                                        (select count( memberid) from Member where o.id=officeid and status='Dropout' and Regdate<='$cdate' and Dropoutdate<='$cdate'and $oid)dro_till_Mem
                                        from officedetail o
                                        where $ido
                                        group by o.id";
                                $q3 = "select count(*)nob from(select count(distinct memberid)nos
                                        from loandetail 
                                        where $oid and savedate<='$sdate'
                                        group by memberid
                                        having sum(loandr-loancr)>0)nob";
                                $q4 = "select count(*)nobtill from(select count(distinct memberid)nob
                                        from loandetail 
                                        where $oid and savedate<='$cdate'
                                        group by memberid
                                        having sum(loandr-loancr)>0)nobtill";


                                $r1 = sqlsrv_query($connection, $q1)or die(print_r(sqlsrv_errors(), true));
                                $r2 = sqlsrv_query($connection, $q2) or die(print_r(sqlsrv_errors(), true));
                                $r3 = sqlsrv_query($connection, $q3)or die(print_r(sqlsrv_errors(), true));
                                $r4 = sqlsrv_query($connection, $q4)or die(print_r(sqlsrv_errors(), true));

                                
                            }

                            if ($r1 && $r2 && $r3 && $r4) {
                                $res = sqlsrv_fetch_array($r1);
                                $run = sqlsrv_fetch_array($r2);
                                $nob = sqlsrv_fetch_array($r3);
                                $nobtill = sqlsrv_fetch_array($r4);
                                sqlsrv_commit($connection);
                                echo "<script>alert('Generated Succesfully');</script>";
                            } else {
                                sqlsrv_rollback($connection);
                                echo "Query rolled back.<br />";
                            }
                            $thismonthstaff = abs($res['Total'] - $res['TPre']);
                            $thismonthfstaff = abs($res['TPre'] - $res['FPre']);
                            $trtill = abs($res['TrTill'] - $res['TrPre']);
                            $otill = abs($res['OTill'] - $res['OPre']);
                            
                            $totalrmun = abs($run['Rmuntill'] - $run['Rmunpre']);
                            $totalmun = abs($run['MunTill'] - $run['Munpre']);
                            $totalcen = abs($run['cenTill'] - $run['cenpre']);
                            
                            //$nobtill = sqlsrv_fetch_array($r4);
                            $nottotal = $nobtill['nobtill'] - $nob['nob'];
                            ?>
                            <tbody class="text-sm">
                                <tr class="bg-gray">
                                    <td class="text-bold">A</td>
                                    <td class="text-bold">Institutional Information</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>No. Of Total Staff</td>
                                    <td><?php echo $res['TPre']; ?></td>
                                    <td><?php echo $thismonthstaff; ?></td>
                                    <td><?php echo $res['Total']; ?></td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>No. Of Field Staff</td>
                                    <td><?php echo $res['FPre']; ?></td>
                                    <td><?php echo $thismonthfstaff; ?></td>
                                    <td><?php echo $res['TPre']; ?></td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>No. Of Trainees</td>
                                    <td><?php echo $res['TrTill']; ?></td>
                                    <td><?php echo $trtill; ?></td>
                                    <td><?php echo $res['TrPre']; ?></td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>No. Of Office Helper</td>
                                    <td><?php echo $res['OTill']; ?></td>
                                    <td><?php echo $otill; ?></td>
                                    <td><?php echo $res['OPre']; ?></td>
                                </tr>
                                <tr class = "bg-gray">
                                    <td class = "text-bold">B</td>
                                    <td class = "text-bold">Program Expansion</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>Rural Municipality</td>
                                    <td><?php echo $run['Rmunpre']; ?></td>
                                    <td><?php echo $totalrmun; ?></td>
                                    <td><?php echo $run['Rmuntill']; ?></td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Municipality</td>
                                    <td><?php echo $run['Munpre']; ?></td>
                                    <td><?php echo $totalmun; ?></td>
                                    <td><?php echo $run['MunTill']; ?></td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>No. Of Center</td>
                                    <td><?php echo $run['cenpre']; ?></td>
                                    <td><?php echo $totalcen; ?></td>
                                    <td><?php echo $run['cenTill']; ?></td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>No. Of Group</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>No. Of Total Member</td>
                                    <td><?php echo $run['act_pre_Mem']; ?></td>
                                    <td><?php echo $totalmem = abs($run['act_till_Mem'] - $run['act_pre_Mem']); ?></td>
                                    <td><?php echo $run['act_till_Mem']; ?></td>
                                </tr>
                                <tr>
                                    <td>6</td>
                                    <td>No. Of Total Active Member</td>
                                    <td><?php echo $run['actMempre']; ?></td>
                                    <td><?php echo $totalactmem = abs($run['MemTill'] - $run['actMempre']); ?></td>
                                    <td><?php echo $run['MemTill']; ?></td>
                                </tr>
                                <tr>
                                    <td>7</td>
                                    <td>No. Of Male Active Member</td>
                                    <td><?php echo $run['actmalepre']; ?></td>
                                    <td><?php echo $totalmalemem = abs($run['MaleTill'] - $run['actmalepre']); ?></td>
                                    <td><?php echo $run['MaleTill']; ?></td>
                                </tr>
                                <tr>
                                    <td>8</td>
                                    <td>No. Of Total Passive Member</td>
                                    <td><?php echo $run['act_pre_male']; ?></td>
                                    <td><?php echo $totalpassm = abs($run['act_till_Male'] - $run['act_pre_male']); ?></td>
                                    <td><?php echo $run['act_till_Male']; ?></td>
                                </tr>
                                <tr>
                                    <td>9</td>
                                    <td>No. Of Male Passive Member</td>
                                    <td><?php echo $run['passPre']; ?></td>
                                    <td><?php echo $totalpass = abs($run['passtill'] - $run['passPre']); ?></td>
                                    <td><?php echo $run['passtill']; ?></td>
                                </tr>
                                <tr>
                                    <td>10</td>
                                    <td>No. Of Borrowers</td>
                                    <td><?php echo $nob['nob']; ?></td>
                                    <td><?php echo $nottotal; ?></td>
                                    <td><?php echo $nobtill['nobtill']; ?></td>
                                </tr>
                                <tr>
                                    <td>11</td>
                                    <td>No. Of Dropout Member</td>
                                    <td><?php echo $run['dro_pre_Mem']; ?></td>
                                    <td><?php echo $totaldro = abs($run['dro_till_Mem'] - $run['dro_pre_Mem']); ?></td>
                                    <td><?php echo $run['dro_till_Mem']; ?></td>
                                </tr>

                                <tr class="bg-gray">
                                    <td class="text-bold">C</td>
                                    <td class="text-bold">Savings</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>Compulsory Saving</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Special Saving</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Pension Saving</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>Welfare Fund</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>Pension/Fixed Saving Interest Prov.Fund</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="text-bold">Total Saving Mobilization</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>

                                <tr class="bg-gray">
                                    <td class="text-bold">D</td>
                                    <td class="text-bold">Loan Transaction</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>Loan Disbursment (Cum.)</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Loan Recovered (Cum.)</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr class="text-bold">
                                    <td>3</td>
                                    <td>Loan Outstanding</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>

                                <tr class="bg-gray">
                                    <td class="text-bold">E</td>
                                    <td class="text-bold">Default Loan Summary</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>No. Of Default Borrowers</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Overdue Loan Amount</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>PAR Amount</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>Repayment Rate</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="text-bold">5</td>
                                    <td class="text-bold">No. Of Borrower With Renew Loan</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>5.1</td>
                                    <td>Renew Loan Amount</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>5.2</td>
                                    <td>Loan Outstanding</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="text-bold">6</td>
                                    <td class="text-bold">No. Of Borrower With Rescheduled Loan</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>6.1</td>
                                    <td>Rescheduled Loan Amount</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>6.2</td>
                                    <td>Loan Outstanding</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>

                                <tr class="bg-gray">
                                    <td class="text-bold">F</td>
                                    <td class="text-bold">Status Of Operation</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr class="text-bold">
                                    <td>1</td>
                                    <td>Total Income</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Interest Income</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Other Income</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr class="text-bold">
                                    <td>2</td>
                                    <td>Total Expenditure</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Personnel Expenses</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Administrative Expenses</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Interest Expenses</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td> <i class="glyphicon glyphicon-arrow-right"></i> On Saving</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td> <i class="glyphicon glyphicon-arrow-right"></i> On Borrowings</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Loan Loss Provision Expenses</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr class="text-bold">
                                    <td>3</td>
                                    <td>OSS(F1)/(F2)<i class="fa fa-close"></i>100</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>

                                <tr class="bg-gray">
                                    <td class="text-bold">G</td>
                                    <td class="text-bold">Other Information</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>No. of Full Center (40 or Above 40)</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>No. of Medium Incomplete Center (21 to 39)</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>No. of Incomplete Center (20 or Below 20)</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>No. of Center With Center House</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>No. of Center With Dress</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>6</td>
                                    <td>Center With Attendance Register</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>7</td>
                                    <td>Dalit Member</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>8</td>
                                    <td>Yield On  Portfolio</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td></td>
                                    <td class="text-left">_________________</td>
                                    <td class="text-center"><input type="text" value=""></td>
                                    <td></td>
                                    <td class="text-right">__________________</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td  class="text-left text-bold">Prepared By</td>
                                    <td class="text-center text-bold">Have To fill</td>
                                    <td></td>
                                    <td  class="text-right text-bold">Approved By</td>
                                </tr>
                            </tbody>
                        </table>
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
    $('#mpreport').DataTable({
        order: false,
        //scrollY: "300px",
        //scrollCollapse: true,
        paging: false,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                filename: 'Monthly Progress Report',
                title: 'Jeevan Bikas Samaj',
                messageTop: ' <?php
if (isset($_POST['search'])) {
    if ($_SESSION['BranchID'] == 1) {
        echo $bname . ' ( ' . 2074 / 11 / 31 . ' ) - Monthly Progress Report';
    } else {
        echo $branchName . ' ( ' . 2074 / 11 / 31 . ' ) - Monthly Progress Report';
    }
} else {
    echo $branchName . ' ( ' . 2074 / 11 / 31 . ' ) - Monthly Progress Report';
}
?>',
            },
            {
                extend: 'print',
                filename: 'Monthly Progress Report',
                title: 'Jeevan Bikas Samaj',
                messageTop: ' <?php
if ($_SESSION['BranchID'] == 1) {
    echo '<h5 class="text-bold text-center">' . $bname . '<br/> ( Monthly Progress Report ' . $sdate . ' - '. $cdate .'  ) </h5>';
} else {
    echo '<h5 class="text-bold text-center">' . $branchName . '<br/> ( Monthly Progress Report ' . $sdate . ' - '. $cdate .' ) </h5>';
}
?>',
                customize: function (win) {
                    $(win.document.body)
                            .css({
                                'font-size': '10pt',
                                'text-align': 'center'
                            });
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