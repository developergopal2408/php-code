<?php
ob_start();
session_start();
require_once '../db.php';
if (!isset($_SESSION['StaffID'])) {
    header('Location:../login.php');
}
$sql = "SELECT * FROM OfficeDetail WHERE ID='" . $_SESSION['BranchID'] . "' ";
$res = sqlsrv_query($connection, $sql);
$row = sqlsrv_fetch_array($res);
$branchName = $row['Name'];
$Code = $row['Code'];
$_SESSION['ID'] = $row['ID'];
include_once 'header.php';
require_once('../js/nepali_calendar.php');
require_once('../js/functions.php');
$cal = new Nepali_Calendar();
list($year, $month, $day) = explode('/', date('Y/m/d'));
$nepdate = $cal->eng_to_nep($year, $month, $day);
$ndate = $cal->eng_to_nep($year, $month, $day);
$sdate = $ndate['year'] . "/" . $ndate['month'] . "/" . '01';
$nyr = $nepdate['year'];
$nmonth = $nepdate['month'];
$nday = $nepdate['date'];
$cdate = $nyr . "/" . $nmonth . "/" . $nday;

$cid = $_GET['cid'];
$mid = $_GET['mid'];
$oid = $_GET['oid'];
$qry = "select OfficeID,CenterID,MemberID,MemberCode,FirstName +' '+ LastName as Mname from member where officeid = '$oid'  and centerid = '$cid' and MemberID = '$mid'";
$reso = sqlsrv_query($connection, $qry);
$rows = sqlsrv_fetch_array($reso);
?>
<style>
    .body{margin: 0pt;font-size: 0.25em;}
    @page 
    {
		
        size: landscape;   /* auto is the initial value */
        margin: 0mm;  /* this affects the margin in the printer settings */
    }
	
	
</style>
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
                <small>Member Statement</small>
            </h1>
            <ol class="breadcrumb">
                <a  href="javascript:window.print()" ><i class="glyphicon glyphicon-print"></i></a>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">

            <div class="box box-solid">
                <div class="box-header with-border no-print">
                    <span class="text-bold text-red pull-right"> NOTE : Please Use Date Range only upto 3 Months</span>
                    <div class="col-sm-12">
                        <!-- search form -->
                        <form  action="" method="post" class="form-horizontal" >
                            <div class=" form-group-sm">
                                <div class="col-sm-2">
                                    <input maxlength="10" type="text" name="date1" id="date1" class=" nepali-calendar form-control" placeholder="Select Date" value="<?php
                                    if (isset($_POST['date1'])) {
                                        echo $_POST['date1'];
                                    } else {
                                        echo $sdate;
                                    }
                                    ?>">
                                </div>
                                <div class="col-sm-2">
                                    <input maxlength="10" type="text" name="date2" id="date2" class=" nepali-calendar form-control" placeholder="Select Date" value="<?php
                                    if (isset($_POST['date2'])) {
                                        echo $_POST['date2'];
                                    } else {
                                        echo $cdate;
                                    }
                                    ?>">
                                </div>

                                <div class="col-sm-2">
                                    <button type="submit" name="search" id="search-btn" class=" btn btn-flat bg-green" data-toggle = "tooltip" title="search"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </form>
                        <!-- /.search form -->



                    </div>
                </div>
                <div class="box-body ">
                    <div class="box-title">
                        <h5 class="text-bold text-center text-uppercase">Jeevan Bikas Samaj</h5>
                        <h6 class="text-bold text-center text-uppercase"><?php echo $branchName; ?></h6>
                    </div>
                    <div class="box-title text-bold text-red no-print">
                        बचत कारोवार 
                    </div>
                    <table class="table table-bordered text-center text-sm" style="font-size:9px;" >
                        <thead>

                        <caption class="text-bold text-black">नाम : <?php echo $rows['MemberCode'] . " - " . $rows['Mname']; ?> <span class="pull-right">Date : <?php if(isset($_POST['search'])){echo $_POST['date1'] . " to " . $_POST['date2'];}else{echo $sdate . " to " . $cdate;} ?></span></caption>

                        <tr class="bg-red">
                            <th rowspan="2">मिति</th>
                            <th colspan="3">उपकार बचत</th>
                            <th colspan="3">अनिवार्य बचत</th>
                            <th colspan="3">व्यक्तिगत बचत</th>
                            <th colspan="3">विषेस बचत</th>
                            <th colspan="3">पेन्सन बचत</th>

                        </tr>
                        <tr class="bg-red">

                            <th>राखेको</th>
                            <th>झिकेको</th>
                            <th>बाँकी</th>
                            <th>राखेको</th>
                            <th>झिकेको</th>
                            <th>बाँकी</th>
                            <th>राखेको</th>
                            <th>झिकेको</th>
                            <th>बाँकी</th>
                            <th>राखेको</th>
                            <th>झिकेको</th>
                            <th>बाँकी</th>
                            <th>राखेको</th>
                            <th>झिकेको</th>
                            <th>बाँकी</th>

                        </tr>
                        </thead>

                        <tbody>
                            <?php
                            if (empty($_POST)) {
                                $newbal = 0.0;
                                $query = "select savedate from savingdetail where memberid = '$mid' and officeid = '$oid' and (dramount > 0 or cramount > 0) and 
							savedate between '$sdate' and '$cdate' group by savedate order by savedate";
                                $result = sqlsrv_query($connection, $query);
                                //print_r($query);
                                while ($res = sqlsrv_fetch_array($result)) {
                                    $savedate = $res['savedate'];
                                    ?>
                                    <tr style="height:5px;" >
                                        <td><?php echo $savedate; ?></td>
                                        <?php
                                        $type = "select SavingTypeID from savingtype where SavingTypeID < 6 ";
                                        $r1 = sqlsrv_query($connection, $type);
                                        //print_r($type);
                                        while ($rows = sqlsrv_fetch_array($r1)) {
                                            $sql = "select sum(cramount)cr,sum(dramount)dr from savingdetail 
												where officeid = '$oid' and memberid = '$mid' 
                                                and savedate = '$savedate' and savingtypeid = '" . $rows['SavingTypeID'] . "'";
                                            $run1 = sqlsrv_query($connection, $sql);
                                            //print_r($sql);
                                            while ($rowres = sqlsrv_fetch_array($run1)) {
                                                
												$balance = "select sum(cramount-dramount)bal from savingdetail where memberid = '$mid' and officeid = '$oid' and savedate < '$sdate' and savingtypeid = '" . $rows['SavingTypeID'] . "'";
												$balrun = sqlsrv_query($connection, $balance);
												$runbal = sqlsrv_fetch_array($balrun);
                                                ?>
                                                <td><?php echo $rowres['cr']; ?></td>
                                                <td><?php echo $rowres['dr']; ?></td>
                                                <td><?php echo $newbal; ?></td>

                                                <?php
                                            }
                                        }
                                        ?>
                                    </tr>
                                    <?php
                                }
                            } else if (isset($_POST['search'])) {
                                $date1 = $_POST['date1'];
                                $date2 = $_POST['date2'];

                                $query = "select savedate from savingdetail where memberid = '$mid' and officeid = '$oid' and (dramount > 0 or cramount > 0) and 
							savedate between '$date1' and '$date2' group by savedate order by savedate";
                                $result = sqlsrv_query($connection, $query);
                                //print_r($query);
                                while ($res = sqlsrv_fetch_array($result)) {
                                    $savedate = $res['savedate'];
                                    ?>
                                    <tr  style="height:5px;" >
                                        <td><?php echo $savedate; ?></td>
                                        <?php
                                        $type = "select SavingTypeID from savingtype where SavingTypeID < 6 ";
                                        $r1 = sqlsrv_query($connection, $type);
                                       
                                        while ($rows = sqlsrv_fetch_array($r1)) {
                                           
                                            $savingtypeid = $rows['SavingTypeID'];
                                           
                                            $sql = "select sum(cramount)cr,sum(dramount)dr from savingdetail 
												where officeid = '$oid' and memberid = '$mid' 
                                                and savedate = '$savedate' and savingtypeid = '" . $rows['SavingTypeID'] . "' ";
                                            $run1 = sqlsrv_query($connection, $sql);
                                            
                                            while ($rowres = sqlsrv_fetch_array($run1)) {
												 $balance = "select sum(cramount-dramount)bal from savingdetail where memberid = '$mid' and officeid = '$oid' and savedate <= '$savedate' and savingtypeid = '" . $rows['SavingTypeID'] . "'";
                                                 $balrun = sqlsrv_query($connection, $balance);
                                                 $runbal = sqlsrv_fetch_array($balrun);
											
                                               
                                                ?>
                                                <td><?php echo $rowres['cr']; ?></td>
                                                <td><?php echo $rowres['dr']; ?></td>
                                                <td><?php echo $runbal['bal']; ?></td>

                                                <?php
                                            }
                                        }
                                        ?>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>

                        </tbody>

                    </table>
                    <hr class="no-print">
                    <div class="box-title text-bold text-red no-print">
                        ऋण कारोवार 
                    </div>
                    <table class="table table-bordered text-center text-sm" style="font-size:9px;">
                        <thead>
                            <tr class="bg-red">
                                <th rowspan="2">मिति</th>
                                <th colspan="4">साधारण ऋण</th>
                                <th colspan="4">आकस्मिक ऋण</th>
                                <th colspan="4">आवास ऋण</th>
                                <th colspan="4">स्वदेसी रोजगार ऋण</th>
                                <th colspan="4">सिक्षा ऋण</th>
								<th colspan="4">कृषि ऋण</th>
                            </tr>
                            <tr class="bg-red">

                                <th>लगानी</th>
                                <th>साँवा</th>
                                <th>व्याज</th>
                                <th>बाँकी</th>
                                <th>लगानी</th>
                                <th>साँवा</th>
                                <th>व्याज</th>
                                <th>बाँकी</th>
                                <th>लगानी</th>
                                <th>साँवा</th>
                                <th>व्याज</th>
                                <th>बाँकी</th>
                                <th>लगानी</th>
                                <th>साँवा</th>
                                <th>व्याज</th>
                                <th>बाँकी</th>
                                <th>लगानी</th>
                                <th>साँवा</th>
                                <th>व्याज</th>
                                <th>बाँकी</th>
								<th>लगानी</th>
                                <th>साँवा</th>
                                <th>व्याज</th>
                                <th>बाँकी</th>


                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            if (empty($_POST)) {
                                $newbal = 0.0;
                                $query = "select savedate from loandetail where memberid = '$mid' and officeid = '$oid' and (loandr > 0 or loancr > 0 or intcr > 0) and 
							savedate between '$sdate' and '$cdate' group by savedate order by savedate";
                                $result = sqlsrv_query($connection, $query);
                                //print_r($query);
                                while ($res = sqlsrv_fetch_array($result)) {
                                    $savedate = $res['savedate'];
                                    ?>
                                    <tr style="height:5px;" >
                                        <td><?php echo $savedate; ?></td>
                                        <?php
                                        $type = "select LoanTypeID from loantype where LoanTypeID IN(1,2,3,7,9,10)";
                                        $r1 = sqlsrv_query($connection, $type);
                                        //print_r($type);
                                        while ($rows = sqlsrv_fetch_array($r1)) {
                                            $sql = "select sum(loancr)cr,sum(loandr)dr,sum(intcr)int from loandetail 
												where officeid = '$oid' and memberid = '$mid' 
                                                and savedate = '$savedate' and LoanTypeID = '" . $rows['LoanTypeID'] . "'";
                                            $run1 = sqlsrv_query($connection, $sql);
                                            //print_r($sql);
                                            while ($rowres = sqlsrv_fetch_array($run1)) {
                                                $balance = "select sum(loandr-loancr)bal from loandetail where memberid = '$mid' and officeid = '$oid' and savedate < '$sdate' and LoanTypeID = '" . $rows['LoanTypeID'] . "'";
												$balrun = sqlsrv_query($connection, $balance);
												$runbal = sqlsrv_fetch_array($balrun);
                                                ?>
                                                <td><?php echo $rowres['dr']; ?></td>
                                                <td><?php echo $rowres['cr']; ?></td>
                                                <td><?php echo $rowres['int']; ?></td>
												<td><?php echo $runbal['bal']; ?></td>

                                                <?php
                                            }
                                        }
                                        ?>
                                    </tr>
                                    <?php
                                }
                            } else if (isset($_POST['search'])) {
                                $date1 = $_POST['date1'];
                                $date2 = $_POST['date2'];

                                $query = "select savedate from loandetail where memberid = '$mid' and officeid = '$oid' and (loandr > 0 or loancr > 0 or intcr > 0) and 
							savedate between '$date1' and '$date2' group by savedate order by savedate";
                                $result = sqlsrv_query($connection, $query);
                                //print_r($query);
                                while ($res = sqlsrv_fetch_array($result)) {
                                    $savedate = $res['savedate'];
                                    ?>
                                    <tr style="height:5px;" >
                                        <td><?php echo $savedate; ?></td>
                                        <?php
                                        $type = "select LoanTypeID from loantype where LoanTypeID IN(1,2,3,7,9,10) ";
                                        $r1 = sqlsrv_query($connection, $type);
                                       
                                        while ($rows = sqlsrv_fetch_array($r1)) {
                                            $sql = "select sum(loancr)cr,sum(loandr)dr,sum(intcr)int from loandetail 
												where officeid = '$oid' and memberid = '$mid' 
                                                and savedate = '$savedate' and LoanTypeID = '" . $rows['LoanTypeID'] . "'";
                                            $run1 = sqlsrv_query($connection, $sql);
                                            
                                            while ($rowres = sqlsrv_fetch_array($run1)) {
												 $balance = "select sum(loandr-loancr)bal from loandetail where memberid = '$mid' and officeid = '$oid' and savedate <= '$savedate' and LoanTypeID = '" . $rows['LoanTypeID'] . "'";
                                                 $balrun = sqlsrv_query($connection, $balance);
                                                 $runbal = sqlsrv_fetch_array($balrun);
											
                                               
                                                ?>
                                                <td><?php echo $rowres['dr']; ?></td>
                                                <td><?php echo $rowres['cr']; ?></td>
                                                <td><?php echo $rowres['int']; ?></td>
												<td><?php echo $runbal['bal']; ?></td>

                                                <?php
                                            }
                                        }
                                        ?>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>

                        </tbody>

                    </table>

                    <hr class="no-print">
                    <div class="box-title text-bold text-red no-print">
                        बिमा कारोवार
                    </div>
                    <table class="table table-bordered text-center text-sm" style="font-size:9px;">
                        <thead>
                            <tr class="bg-red">
                                <th rowspan="2">मिति</th>
                                <th colspan="2">आपतकालीन</th>
                                <th colspan="2">पशु बिमा</th>
                                <th colspan="2">जीवन बिमा</th>
								<th colspan="2">कृषि बिमा</th>

                            </tr>
                            <tr class="bg-red">

                                <th>राखेको</th>
                                <th>झिकेको</th>
                                <th>राखेको</th>
                                <th>झिकेको</th>
								<th>राखेको</th>
                                <th>झिकेको</th>
                                <th>राखेको</th>
                                <th>झिकेको</th>
                               


                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            if (empty($_POST)) {
                                $newbal = 0.0;
                                $query = "select savedate from insurancedetail where memberid = '$mid' and officeid = '$oid' and (cramount > 0 or dramount > 0 ) and 
								savedate between '$sdate' and '$cdate' group by savedate order by savedate";
                                $result = sqlsrv_query($connection, $query);
                                //print_r($query);
                                while ($res = sqlsrv_fetch_array($result)) {
                                    $savedate = $res['savedate'];
                                    ?>
                                    <tr style="height:5px;" >
                                        <td><?php echo $savedate; ?></td>
                                        <?php
                                        $type = "select InsuranceTypeID from insurancetype";
                                        $r1 = sqlsrv_query($connection, $type);
                                        //print_r($type);
                                        while ($rows = sqlsrv_fetch_array($r1)) {
                                            $sql = "select sum(cramount)cr,sum(dramount)dr from insurancedetail 
												where officeid = '$oid' and memberid = '$mid' 
                                                and savedate = '$savedate' and InsuranceTypeID = '" . $rows['InsuranceTypeID'] . "'";
                                            $run1 = sqlsrv_query($connection, $sql);
                                            //print_r($sql);
                                            while ($rowres = sqlsrv_fetch_array($run1)) {
                                                
                                                ?>
                                                <td><?php echo $rowres['cr']; ?></td>
                                                <td><?php echo $rowres['dr']; ?></td>
                                               

                                                <?php
                                            }
                                        }
                                        ?>
                                    </tr>
                                    <?php
                                }
                            } else if (isset($_POST['search'])) {
                                $date1 = $_POST['date1'];
                                $date2 = $_POST['date2'];

                                $query = "select savedate from insurancedetail where memberid = '$mid' and officeid = '$oid' and (cramount > 0 or dramount > 0 ) and 
							savedate between '$date1' and '$date2' group by savedate order by savedate";
                                $result = sqlsrv_query($connection, $query);
                                //print_r($query);
                                while ($res = sqlsrv_fetch_array($result)) {
                                    $savedate = $res['savedate'];
                                    ?>
                                    <tr style="height:5px;" >
                                        <td><?php echo $savedate; ?></td>
                                        <?php
                                        $type = "select InsuranceTypeID from insurancetype ";
                                        $r1 = sqlsrv_query($connection, $type);
                                       
                                        while ($rows = sqlsrv_fetch_array($r1)) {
                                            $sql = "select sum(cramount)cr,sum(dramount)dr from insurancedetail 
												where officeid = '$oid' and memberid = '$mid' 
                                                and savedate = '$savedate' and InsuranceTypeID = '" . $rows['InsuranceTypeID'] . "'";
                                            $run1 = sqlsrv_query($connection, $sql);
                                            
                                            while ($rowres = sqlsrv_fetch_array($run1)) {
												 
											
                                               
                                                ?>
                                                <td><?php echo $rowres['cr']; ?></td>
                                                <td><?php echo $rowres['dr']; ?></td>
                                                

                                                <?php
                                            }
                                        }
                                        ?>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>


                        </tbody>

                    </table>
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
    $('#depo').DataTable({
        scrollY: "300px",
        scrollCollapse: true,
        paging: false,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                title: 'Jeevan Bikas Samaj',
                messageTop: 'LIC/Open Close Detail List'
                        //messageBottom: "List of users accessing Agent Bank"
            },
            {
                extend: 'pdf',
                title: 'Jeevan Bikas Samaj',
                messageTop: 'LIC/Open Close Detail List'
                        //messageBottom: "List of users accessing Agent Bank"
            },
            {
                extend: 'print',
                title: 'Jeevan Bikas Samaj',
                messageTop: 'LIC/Open Close Detail List'
                        // messageBottom: "LIC/Open Close Detail List"
            }
        ]
    });
</script>


