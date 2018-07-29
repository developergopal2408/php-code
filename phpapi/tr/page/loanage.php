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
                <small>Loan Disburse According To Age</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Loan Disburse According To Age</li>
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
                                                <select name="id" id="id" class="form-control select2" >
                                                    <option value="">Select Branch</option>
                                                    <?php
                                                    $sql1 = "SELECT * FROM OfficeDetail ORDER BY ID ASC ";
                                                    $result = sqlsrv_query($connection, $sql1);
                                                    while ($rows = sqlsrv_fetch_array($result)) {
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
                                <div class="box-tools pull-right" >
                                    <a  href="loanage.php"  class="btn btn-flat bg-blue" data-toggle = "tooltip" title="All / Refresh"><i class="fa fa-refresh"></i></a>
                                </div>

                            </div>
                        </div>

                        <div class="box-body">
                            <?php
                            if (isset($_POST['search'])) {
                                $query = "SELECT ID,Name FROM OfficeDetail  WHERE  ID = '" . $_POST['id'] . "'";
                                $sub = sqlsrv_query($connection, $query);
                                $p = sqlsrv_fetch_array($sub);
                                $bname = $p['Name'];
                                if ($_POST['id']) {
                                    echo "<h5 class='text-bold text-center'>" . $bname . " ( " . $_POST['date1'] . " - " . $_POST['date2'] . " )</h5>";
                                } else {
                                    echo "<h5 class='text-bold text-center'>" . $branchName . "( " . $_POST['date1'] . " - " . $_POST['date2'] . " )</h5>";
                                }
                            }
                            ?>
                            <table id="loanage" class="table display table-condensed table-bordered table-striped" style="widht:auto;">
                                <thead class="bg-red text-sm">
                                    <tr>
                                        <th>MemberCode</th>
                                        <th>MemberName</th>
                                        <th>DOB</th>
                                        <th>Age</th>
                                        <th>RegDate</th>
                                        <th>LoanType</th>
                                        <th>DisDate</th>
                                        <th>LoanHeading</th>
                                        <th>LoanNo</th>
                                        <th>LoanDis</th>
                                        <th>Outstanding</th>
                                        <th>DisStaff</th>
                                    </tr>
                                </thead>

                                <tbody class="text-sm">
                                    <?php
                                    $id = $_POST['id'];
                                    $date1 = $_POST['date1'];
                                    $date2 = $_POST['date2'];
                                    if ($_SESSION['BranchID'] == 1 AND $id != "") {
                                        $idx = "and o.id = '$id'";
                                    } else {
                                        $idx = "and o.id = '" . $_SESSION['BranchID'] . "'";
                                    }
                                    if (isset($_POST['search'])) {
                                        $qry = "Select m.MemberCode,m.firstname+' '+m.lastname MemberName,m.DOB,m.Age,m.regdate,
                                            t.LoanType,(l.issuedate)DisDate,h.LoanHeading,l.LoanNo,(l.loanamount)
LoanDis,sum(v.loandr-v.loancr) as Outstanding,s.firstname+' '+s.lastname as DisStaff
from officedetail o, loanmain l,loandetail v, member m,loantype t,loanheading h,staffmain s
where m.memberid=l.memberid and v.memberid=m.memberid and l.userid=s.staffid
and v.loanmainid=l.loanmainid and t.loantypeid=l.loantypeid
and o.id=l.officeid and o.id=v.officeid and m.officeid=o.id and h.loanheadingid=l.loanheadingid
and l.issuedate between '$date1' AND '$date2' $idx
group by m.membercode,m.firstname,m.lastname,m.DOB,t.loantype,l.issuedate,l.loanamount,h.loanheading,
s.firstname,s.lastname,l.LoanNo,m.age,m.regdate
having sum(v.loandr-v.loancr)<>0.0
order by h.loanheading";
                                    } else {
                                        $qry = "Select m.MemberCode,m.firstname+' '+m.lastname MemberName,m.DOB,m.Age,m.regdate,
                                            t.LoanType,(l.issuedate)DisDate,h.LoanHeading,l.LoanNo,(l.loanamount)
                                            LoanDis,sum(v.loandr-v.loancr) as Outstanding,s.firstname+' '+s.lastname as DisStaff
                                            from officedetail o, loanmain l,loandetail v, member m,loantype t,loanheading h,staffmain s
                                            where m.memberid=l.memberid and v.memberid=m.memberid and l.userid=s.staffid
                                            and v.loanmainid=l.loanmainid and t.loantypeid=l.loantypeid
                                            and o.id=l.officeid and o.id=v.officeid and m.officeid=o.id and h.loanheadingid=l.loanheadingid
                                            and l.issuedate between '$date1' AND '$date2' $idx
                                            group by m.membercode,m.firstname,m.lastname,m.DOB,
                                            t.loantype,l.issuedate,l.loanamount,h.loanheading,s.firstname,
                                            s.lastname,l.LoanNo,m.age,m.regdate
                                            having sum(v.loandr-v.loancr)<>0.0
                                            order by h.loanheading";
                                    }
                                    $result = sqlsrv_query($connection, $qry);
                                    while ($res = sqlsrv_fetch_array($result)) {
                                        ?>
                                        <tr>
                                            <td><?php echo $res['MemberCode']; ?></td>
                                            <td><?php echo $res['MemberName']; ?></td>
                                            <td><?php echo $res['DOB']; ?></td>
                                            <td><?php echo $res['Age']; ?></td>
                                            <td><?php echo $res['regdate']; ?></td>
                                            <td><?php echo $res['LoanType']; ?></td>
                                            <td><?php echo $res['DisDate']; ?></td>
                                            <td><?php echo $res['LoanHeading']; ?></td>
                                            <td><?php echo $res['LoanNo']; ?></td>
                                            <td><?php echo $res['LoanDis']; ?></td>
                                            <td><?php echo $res['Outstanding']; ?></td>
                                            <td><?php echo $res['DisStaff']; ?></td>
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
    $('#loanage').DataTable({
        scrollX:true,
        scrollY: "300px",
        scrollCollapse: true,
        paging: false,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                filename: 'Loan Dis. According To Age',
                title: 'Jeevan Bikas Samaj',
                messageTop: '<?php if (isset($_POST['search'])) {echo $bname . ' -  Loan Dis. According To Age ( ' . $_POST['date1'] . ' - ' . $_POST['date2'] . ')';} else { echo $branchName . "- Loan Dis. According To Age ";};?>',
            },
            {
                extend: 'pdf',
                filename: 'Loan Dis. According To Age',
                title: 'Jeevan Bikas Samaj',
                messageTop: '<?php if (isset($_POST['search'])) {echo $bname . "- Loan Dis. According To Age ";} else { echo $branchName . "- Loan Dis. According To Age ";};?>',

            },
            {
                extend: 'print',
                filename: 'Loan Dis. According To Age',
                title: 'Jeevan Bikas Samaj',
                messageTop: '<?php if (isset($_POST['search'])) { echo '<h5 class="text-bold text-center"> ' . $bname . ' <br/> Loan Dis. According To Age <br/>  ' . $_POST['date1'] . ' - ' . $_POST['date2'] . ' </h5>';} else {echo $branchName . "<br/> Loan Dis. According To Age ";};?>',
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