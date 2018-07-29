<?php
include_once 'top.php'; //Include Sidebar_header.php-->
include_once 'header.php'; //Include Sidebar.php-->
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
                <small>Member Pension</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Member Pension</li>
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
                                        if ($_SESSION['BranchID'] == 1) {
                                            ?>
                                            <div class="col-sm-3">
                                                <select name="id" id="id" class="form-control select2" required>
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
                                    <a  href="pdetail.php"  class="btn btn-flat bg-blue" data-toggle = "tooltip" title="All / Refresh"><i class="fa fa-refresh"></i></a>
                                </div>

                            </div>
                        </div>
                        <div class="box-body">
                            <?php
                            
                            if (isset($_POST['search'])) {
                                $query = "SELECT ID,Name FROM OfficeDetail  WHERE  ID = '" . $_POST['id'] . "'";
                                $sub = odbc_exec($connection, $query);
                                $p = odbc_fetch_array($sub);
                                $bname = $p['Name'];
                                if ($_POST['id']) {
                                    echo "<h5 class='text-bold text-center'>" . $bname . " ( " . $_POST['date1'] . " - " . $_POST['date2'] . " )</h5>";
                                } else {
                                    echo "<h5 class='text-bold text-center'>" . $branchName . "( " . $_POST['date1'] . " - " . $_POST['date2'] . " )</h5>";
                                }
                            } else {
                                echo "<h5 class='text-bold text-center'> " . $_POST['date1'] . "  " . $_POST['date2'] . "</h5>";
                            }
                            ?>
                            <table id="pdetail" class="table table-condensed table-bordered table-striped display" style="width:auto;">
                                <thead class="bg-red text-sm">
                                    <tr>
                                        <th>MemberId</th>
                                        <th>MemberCode</th>
                                        <th>MemberName</th>
                                        <th>Status</th>
                                        <th>AccountNo</th>
                                        <th>RegAmt</th>
                                        <th>IsActive</th>
                                        <th>Period</th>
                                        <th>StartDate</th>
                                        <th>Amount</th>
                                        <th>IntProvision</th>

                                    </tr>
                                </thead>

                                <tbody class="text-sm">
                                    <?php
                                    $date1 = $_POST['date1'];
                                    $date2 = $_POST['date2'];
                                    $id = $_POST['id'];
                                    if ($_SESSION['BranchID'] == 1 AND $id != "") {
                                        $idx = "and o.id = '$id'";
                                    } else {
                                        $idx = "and o.id = '" . $_SESSION['BranchID'] . "'";
                                    }
                                    if (isset($_POST['search']) AND $_SESSION['BranchID'] == 1) {
                                        $qry = "select m.Memberid,m.Membercode,m.firstname+' '+m.lastname as MemberName,m.Status,s.AccountNo,(t.Amount)RegAmt,t.isactive,t.period,t.Startdate,
                                                sum(cramount-dramount)as Amount,sum(s.intcr-s.intdr) IntProvision
                                                from officedetail o, savingdetail s,savingaccount t,Member m
                                                where o.id=s.officeid and o.id=t.officeid and s.memberid=t.memberid and s.accountno=t.accountno and s.savingtypeid=t.savingtypeid
                                                and o.id=m.officeid and m.memberid=t.memberid and m.officeid=t.officeid and m.memberid=s.memberid and m.officeid=s.officeid
                                                $idx and t.Startdate between '$date1' and '$date2'
                                                group by o.code,o.name,s.accountno,t.amount,t.period,t.startdate,m.firstname,m.lastname,m.membercode,m.memberid,m.status,t.isactive
                                                having sum(cramount-dramount)>0
                                                order by o.code,m.memberid";
                                    } else if ($_SESSION['BranchID'] > 1) {
                                        $qry = "select m.Memberid,m.Membercode,m.firstname+' '+m.lastname as MemberName,
                                            m.Status,s.AccountNo,(t.Amount)RegAmt,t.isactive,t.period,t.Startdate,
                                                sum(cramount-dramount)as Amount,sum(s.intcr-s.intdr) IntProvision
                                                from officedetail o, savingdetail s,savingaccount t,Member m
                                                where o.id=s.officeid and o.id=t.officeid and s.memberid=t.memberid and s.accountno=t.accountno and s.savingtypeid=t.savingtypeid
                                                and o.id=m.officeid and m.memberid=t.memberid and m.officeid=t.officeid and m.memberid=s.memberid and m.officeid=s.officeid
                                                $idx and t.Startdate between '$date1' and '$date2'
                                                group by o.code,o.name,s.accountno,t.amount,t.period,t.startdate,m.firstname,m.lastname,m.membercode,m.memberid,m.status,t.isactive
                                                having sum(cramount-dramount)>0
                                                order by o.code,m.memberid";
                                    }
                                    $result = odbc_exec($connection, $qry);
                                    while ($res = odbc_fetch_array($result)) {
                                        ?>
                                        <tr>
                                            <td><?php echo $res['Memberid']; ?></td>
                                            <td><?php echo $res['Membercode']; ?></td>
                                            <td><?php echo $res['MemberName']; ?></td>
                                            <td><?php echo $res['Status']; ?></td>
                                            <td><?php echo $res['AccountNo']; ?></td>
                                            <td><?php echo $res['RegAmt']; ?></td>
                                            <td><?php
                                                if ($res['isactive'] == 'Y') {
                                                    echo "Yes";
                                                } else {
                                                    echo "No";
                                                };
                                                ?></td>
                                            <td><?php echo $res['period']; ?></td>
                                            <td><?php echo $res['Startdate']; ?></td>
                                            <td><?php echo $res['Amount']; ?></td>
                                            <td><?php echo $res['IntProvision']; ?></td>

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
    $('#pdetail').DataTable({
        scrollY: "300px",
        scrollCollapse: true,
        paging: false,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                filename: 'Pension Detail',
                title: 'Jeevan Bikas Samaj',
                messageTop: '<?php if (isset($_POST['search'])) {echo $bname . ' -  Pension Detail ( ' . $_POST['date1'] . ' - ' . $_POST['date2'] . ')';} else { echo $branchName . "- Pension Detail ";};?>',
            },
            {
                extend: 'pdf',
                filename: 'Pension Detail',
                title: 'Jeevan Bikas Samaj',
                messageTop: '<?php if (isset($_POST['search'])) {echo $bname . "- Pension Detail ";} else { echo $branchName . "- Pension Detail ";};?>',

            },
            {
                extend: 'print',
                filename: 'Pension Detail',
                title: 'Jeevan Bikas Samaj',
                messageTop: '<?php if (isset($_POST['search'])) { echo '<h5 class="text-bold text-center"> ' . $bname . ' <br/> Pension Detail <br/>  ' . $_POST['date1'] . ' - ' . $_POST['date2'] . ' </h5>';} else {echo $branchName . "<br/> Pension Detail ";};?>',
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