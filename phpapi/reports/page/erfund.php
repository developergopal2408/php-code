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
require_once('../js/nepali_calendar.php');
require_once('../js/functions.php');
$cal = new Nepali_Calendar();
list($year, $month, $day) = explode('/', date('Y/m/d'));
$nepdate = $cal->eng_to_nep($year, $month, $day);
$ndate = $cal->eng_to_nep($year, $month, 01);
$sdate = $ndate['year'] . "/" . $ndate['month'] . "/" . '01';
$nyr = $nepdate['year'];
$nmonth = $nepdate['month'];
$nday = $nepdate['date'];
$cdate = $nyr . "/" . $nmonth . "/" . $nday;
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
                <small>Emergency Relief Fund</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Emergency Relief Fund</li>
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
										if($_SESSION['BranchID'] == 1){
											?>
											<div class="col-sm-3">
                                                <select name="id" id="id" class="form-control select2" required>
                                                    <option value="">Select Branch</option>
                                                   
                                                    <?php
                                                    $query = "SELECT ID,Name,Code from OfficeDetail";
                                                    $sub = odbc_exec($connection, $query);
                                                    while ($p = odbc_fetch_array($sub)) {
                                                        ?>
                                                        <option value="<?php echo $p['ID']; ?>" <?php if($_POST['id'] == $p['ID']){echo "selected";} ?>><?php echo $p['Code'] . " - " . $p['Name']; ?></option>;
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
                                <a href="erfund.php"  class=" btn btn-flat bg-blue" title="Refresh"><i class="fa fa-refresh"></i></a>

                                <div class="box-tools pull-right" >
                                    <button id="excel" class="btn bg-blue" href="#" onClick ="$('#trial').tableExport({type: 'excel', escape: 'false' });">Export To Excel</button>
                                </div>

                            </div>
                        </div>

                        <div class="box-body">
                            <?php
                            if (isset($_POST['search'])) {
                                echo "<span class='text-bold text-center'>Emergency Relief Fund - " . $branchName . "( " . $_POST['date1'] . " - " . $_POST['date2'] . "  )</span>";
                            }
                            ?>
                            <table id="trial" class="table table-bordered table-striped" bordered="1" style="width:auto;"> 
                                <thead class="bg-red text-sm">
                                    <tr>
                                        <th>Code</th>
                                        <th>Name</th>
                                        <th>MemberCode</th>
                                        <th>MemberName</th>
                                        <th>SaveDate</th>
                                        <th>InsuaranceType</th>
                                        <th>FundWithdraw</th>
                                        <th>InsuranceHead</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    <?php
                                    $date2 = $_POST['date2'];
                                    $date1 = $_POST['date1'];
									$id = $_POST['id'];
                                    $total = 0.0;
                                    if(isset($_POST['search'])AND $_SESSION['BranchID'] == 1){
                                        $idx = "and d.officeid = '$id'";
                                    }else{
                                        $idx = "and d.officeid = '".$_SESSION['BranchID']."'";
                                    }
                                    if (empty($_POST)) {
                                        $qry = "select o.Code,o.Name,M.MemberCode,M.Firstname+' '+m.Lastname as MemberName ,d.savedate,T.insurancetype,
                                                (d.dramount)Cattleinsurance,h.InsuranceHead
                                                from officedetail o, member m, insurancetype t, insurancedetail d,insurancehead h
                                                where o.id=m.officeid and o.id=d.officeid $idx and m.memberid=d.memberid and t.insurancetypeid=d.insurancetypeid 
                                                and d.insurancetypeid=1 and d.savedate between '$sdate' and '$cdate' and d.dramount>0 
                                                and h.id=d.insuranceheadid
                                                group by o.code,o.name,m.firstname,m.lastname,d.savedate,d.dramount,m.membercode,T.insurancetype,h.InsuranceHead
                                                order by o.code,m.membercode";
                                        $result = odbc_exec($connection, $qry);
                                        while ($res = odbc_fetch_array($result)) {
                                            $total += $res['Cattleinsurance'];
                                            ?>
                                            <tr>
                                                <td><?php echo $res['Code']; ?></td>
                                                <td><?php echo $res['Name']; ?></td>
                                                <td><?php echo $res['MemberCode']; ?></td>
                                                <td><?php echo $res['MemberName']; ?></td>
                                                <td><?php echo $res['savedate']; ?></td>
                                                <td><?php echo $res['insurancetype']; ?></td>
                                                <td><?php echo $res['Cattleinsurance']; ?></td>
                                                <td><?php echo $res['InsuranceHead']; ?></td>
                                            </tr>
                                            <?php
                                        }
                                    } else if (isset($_POST['search'])) {
                                        $qry = "select o.Code,o.Name,M.MemberCode,M.Firstname+' '+m.Lastname as MemberName ,d.savedate,T.insurancetype,
                                                (d.dramount)Cattleinsurance,h.InsuranceHead
                                                from officedetail o, member m, insurancetype t, insurancedetail d,insurancehead h
                                                where o.id=m.officeid and o.id=d.officeid $idx and m.memberid=d.memberid and t.insurancetypeid=d.insurancetypeid 
                                                and d.insurancetypeid=1 and d.savedate between '$date1' and '$date2' and d.dramount>0 
                                                and h.id=d.insuranceheadid
                                                group by o.code,o.name,m.firstname,m.lastname,d.savedate,d.dramount,m.membercode,T.insurancetype,h.InsuranceHead
                                                order by o.code,m.membercode";
                                        $result = odbc_exec($connection, $qry);
                                        while ($res = odbc_fetch_array($result)) {
                                            $total += $res['Cattleinsurance'];
                                            ?>
                                            <tr>
                                                <td><?php echo $res['Code']; ?></td>
                                                <td><?php echo $res['Name']; ?></td>
                                                <td><?php echo $res['MemberCode']; ?></td>
                                                <td><?php echo $res['MemberName']; ?></td>
                                                <td><?php echo $res['savedate']; ?></td>
                                                <td><?php echo $res['insurancetype']; ?></td>
                                                <td><?php echo $res['Cattleinsurance']; ?></td>
                                                <td><?php echo $res['InsuranceHead']; ?></td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                                <tfoot>

                                    <tr class="bg-red text-bold">
                                        <td colspan="5">Total</td>
                                        <td><?php echo $total; ?></td>
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
$("trial").tableExport({
    headings: true,                    // (Boolean), display table headings (th/td elements) in the <thead>
    footers: true,                     // (Boolean), display table footers (th/td elements) in the <tfoot>
    formats: ["xls", "csv", "txt"],    // (String[]), filetypes for the export
    fileName: "id",                    // (id, String), filename for the downloaded file
    bootstrap: true,                   // (Boolean), style buttons using bootstrap
    position: "bottom"                 // (top, bottom), position of the caption element relative to table
    ignoreRows: null,                  // (Number, Number[]), row indices to exclude from the exported file(s)
    ignoreCols: null,                  // (Number, Number[]), column indices to exclude from the exported file(s)
    ignoreCSS: ".tableexport-ignore",  // (selector, selector[]), selector(s) to exclude from the exported file(s)
    emptyCSS: ".tableexport-empty",    // (selector, selector[]), selector(s) to replace cells with an empty string in the exported file(s)
    trimWhitespace: false              // (Boolean), remove all leading/trailing newlines, spaces, and tabs from cell text in the exported file(s)
});
</script>

