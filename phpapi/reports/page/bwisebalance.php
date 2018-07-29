<?php
include_once 'top.php';
include_once 'header.php';
?>
<!-- Site wrapper -->
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
                <i class="fa fa-building"></i> <?php echo $branchName; ?>

                <small>Branch Wise Balance</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Branch Wise Balance</li>
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


                                        <div class="col-sm-2">
                                            <button type="submit" name="search" id="search-btn" class=" btn btn-flat bg-green"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                </form>
                                <!-- /.search form -->
                                <div class="box-tools pull-right" >
                                    <button id="excel" class="btn bg-blue" href="#" onClick ="$('#daybook2').tableExport({type: 'excel', escape: 'false'});">Export To Excel</button>
                                </div>

                            </div>
                        </div>

                        <div class="box-body">

                            <table id="daybook2" class="table table-responsive table-bordered table-striped">
                                <thead class="bg-red ">
                                    <tr class="text-sm">
                                        <th>Code</th>
                                        <th>Office</th>
                                        <th>Welfare</th>
                                        <th>Compulsory</th>
                                        <th>Personal</th>
                                        <th>Special</th>
                                        <th>Pension</th>
                                        <th>General</th>
                                        <th>Emergency</th>
                                        <th>Housing</th>
                                        <th>Energy</th>
                                        <th>DSE</th>
                                        <th>Education</th>
                                        <th>AgiLoan</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $date1 = $_POST['date1'];
                                    if (isset($_POST['search'])) {
                                        $qry = "select o.code,o.Name,o.id,
                                    (select sum(cramount-dramount) as bal from savingdetail where savedate<='$date1'
                                    and savingtypeid=1 and officeid=o.id )welfare,
                                    (select sum(cramount-dramount) as bal from savingdetail where savedate<='$date1'
                                    and savingtypeid=2 and officeid=o.id)Compulsory,
                                    (select sum(cramount-dramount) as bal from savingdetail where savedate<='$date1'
                                    and savingtypeid=3 and officeid=o.id)Personal,
                                    (select sum(cramount-dramount) as bal from savingdetail where savedate<='$date1'
                                    and savingtypeid=4 and officeid=o.id)special,
                                    (select sum(cramount-dramount) as bal from savingdetail where savedate<='$date1'
                                    and savingtypeid=5 and officeid=o.id)Pension,
                                    (select sum(loandr-loancr) as bal from loandetail where savedate<='$date1'
                                     and loantypeid=1 and officeid=o.id)General,
                                    (select sum(loandr-loancr) as bal from loandetail where savedate<='$date1'
                                    and loantypeid=2 and officeid=o.id)Emergency,
                                    (select sum(loandr-loancr) as bal from loandetail where savedate<='$date1'
                                    and loantypeid=3 and officeid=o.id)Housing,
                                    (select sum(loandr-loancr) as bal from loandetail where savedate<='$date1'
                                    and loantypeid=4 and officeid=o.id)Energy,
                                    (select sum(loandr-loancr) as bal from loandetail where savedate<='$date1'
                                     and loantypeid=7 and officeid=o.id)DSE,
                                    (select sum(loandr-loancr) as bal from loandetail where savedate<='$date1'
                                     and loantypeid=9 and officeid=o.id)Education,
                                     (select sum(loandr-loancr) as bal from loandetail where savedate<='$date1'
                                     and loantypeid=10 and officeid=o.id)Agi
                                     from officedetail o 
                                    Group by  o.code,o.Name,o.id
                                    order by o.code";
                                    }

                                    $result = odbc_exec($connection, $qry);
                                    //print_r($qry);
                                    while ($res = odbc_fetch_array($result)) {
                                        $totalwelfare += $res['welfare'];
                                        $totalcomp += $res['Compulsory'];
                                        $totalpersonal += $res['Personal'];
                                        $totalspecial += $res['special'];
                                        $totalpension += $res['Pension'];
                                        $totalgeneral += $res['General'];
                                        $totaleme += $res['Emergency'];
                                        $totalhousing += $res['Housing'];
                                        $totalenergy += $res['Energy'];
                                        $totaldse += $res['DSE'];
                                        $totaledu += $res['Education'];
                                        $totalagi += $res['Agi'];
                                        ?>
                                        <tr class="text-sm">
                                            <td><?php echo $res['code']; ?></td>
                                            <td><?php echo str_ireplace("Branch Office", "", $res['Name']); ?></td>
                                            <td><?php echo $res['welfare']; ?></td>
                                            <td><?php echo $res['Compulsory']; ?></td>
                                            <td><?php echo $res['Personal']; ?></td>
                                            <td><?php echo $res['special']; ?></td>
                                            <td><?php echo $res['Pension']; ?></td>
                                            <td><?php echo $res['General']; ?></td>
                                            <td><?php echo $res['Emergency']; ?></td>
                                            <td><?php echo $res['Housing']; ?></td>
                                            <td><?php echo $res['Energy']; ?></td>
                                            <td><?php echo $res['DSE']; ?></td>
                                            <td><?php echo $res['Education']; ?></td>
                                            <td><?php echo $res['Agi']; ?></td>

                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                                <tfoot class="text-sm bg-red">
                                    <tr>
                                        <td colspan="2" >Total</td>
                                        <td><?php echo $totalwelfare; ?></td>
                                        <td><?php echo $totalcomp; ?></td>
                                        <td><?php echo $totalpersonal; ?></td>
                                        <td><?php echo $totalspecial; ?></td>
                                        <td><?php echo $totalpension; ?></td>
                                        <td><?php echo $totalgeneral; ?></td>
                                        <td><?php echo $totaleme; ?></td>
                                        <td><?php echo $totalhousing; ?></td>
                                        <td><?php echo $totalenergy; ?></td>
                                        <td><?php echo $totaldse; ?></td>
                                        <td><?php echo $totaledu; ?></td>
                                        <td><?php echo $totalagi; ?></td>
                                    </tr>
                                </tfoot>


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
