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
										if($BranchID == 1){
										?>
                                        <div class="col-sm-3">
                                            <select name="oid" id="oid" class="form-control select2" required>
                                                <option value="">Select Branch</option>
												<option value="all">All Branch</option>
                                                <?php
                                                $sql1 = "SELECT * FROM OfficeDetail where ID > 1 ORDER BY ID ASC ";
                                                $result = odbc_exec($connection, $sql1);

                                                while ($rows = odbc_fetch_array($result)) {
                                                    ?>
                                                    <option value="<?php echo $rows['ID']; ?>" <?php if($_POST['oid']==$rows['ID']){echo "selected";}?>><?php echo $rows['Code'] . " - " . $rows['Name']; ?></option>;
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
                                <a href="loandue.php"  class=" btn btn-flat bg-blue" title="Refresh"><i class="fa fa-refresh"></i></a>

                                <div class="box-tools pull-right" >
                                    <button id="excel" class="btn bg-blue" href="#" onClick ="$('#trial').tableExport({type: 'excel', escape: 'false'});">Export To Excel</button>
                                </div>

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
                            <table id="trial" class="table table-bordered table-striped"  > 
                                <thead class="bg-red text-sm">
                                    <tr>
                                        <th>Off. Name</th>
                                        <th>MemberID</th>
                                        <th>MemberCode</th>
                                        <th>MemberName</th>
                                        <th>SaveDate</th>
                                        <th>LoanType</th>
                                        <th>LoanNo</th>
                                        <th>LoanHeading</th>
										<th>Pridue</th>
                                        <th>Intdue</th>
                                        <th>PAR</th>
                                    </tr>
                                </thead>
                               
											<tbody class="text-sm">
                                    <?php
                                    $id = $_POST['oid'];
                                    $date1 = $_POST['date1'];
                                    $pridue = 0.0;
                                    $intdue = 0.0;
									$par = 0.0;
									$resu = 0;
									
									if($BranchID == 1){
										$idx = "and o.id='$id'";
									}else{
										$idx = "and o.id='$BranchID'";
									}
                                   if (isset($_POST['search'])) {
									if($id == "all" AND $BranchID == 1){
                                        $qry = "select  m.memberid,o.code,o.name,m.membercode,m.firstname+' '+m.lastname as MemberName,l.savedate,t.Loantype,l.loanNo,l.pridue,l.intdue,h.LoanHeading,
												(select sum(loandr-loancr) from loandetail where l.officeid=officeid  and loanmainid=l.loanmainid and l.savedate<='$date1')PAR
												from loandetail l ,member m,loantype t,officedetail o,loanheading h
												where m.memberid=l.memberid and m.officeid=l.officeid and t.loantypeid=l.loantypeid  and l.savedate=
												(select max(savedate) from loandetail where  savedate<='$date1' and officeid=l.officeid  and loanmainid=l.loanmainid) and l.officeid=o.id and o.id=m.officeid and l.loanheadingid = h.loanheadingid
												group by m.memberid, m.membercode,m.firstname,lastname,l.loantypeid,l.pridue,l.intdue,l.savedate,l.officeid,t.loantype,l.loanno,l.loanmainid,o.code,o.name,h.LoanHeading
												having sum(pridue+intdue)>0  
												order by o.code, m.membercode";
												}else{
										$qry = "select  m.memberid,o.code,o.name,m.membercode,m.firstname+' '+m.lastname as MemberName,l.savedate,t.Loantype,l.loanNo,l.pridue,l.intdue,h.LoanHeading,
												(select sum(loandr-loancr) from loandetail where l.officeid=officeid  and loanmainid=l.loanmainid and l.savedate<='$date1')PAR
												from loandetail l ,member m,loantype t,officedetail o,loanheading h
												where m.memberid=l.memberid and m.officeid=l.officeid and t.loantypeid=l.loantypeid $idx and l.savedate=
												(select max(savedate) from loandetail where  savedate<='$date1' and officeid=l.officeid  and loanmainid=l.loanmainid) and l.officeid=o.id and o.id=m.officeid and l.loanheadingid = h.loanheadingid
												group by m.memberid, m.membercode,m.firstname,lastname,l.loantypeid,l.pridue,l.intdue,l.savedate,l.officeid,t.loantype,l.loanno,l.loanmainid,o.code,o.name,h.LoanHeading
												having sum(pridue+intdue)>0  
												order by o.code, m.membercode";
									}
								   }
									   
                                        $result = odbc_exec($connection, $qry)or die(print_r(odbc_errormsg($connection,true)));
										
                                        while ($res = odbc_fetch_array($result)) {
											
                                            $pridue += $res['pridue'];
                                            $intdue += $res['intdue'];
											$par += $res['PAR'];
											
											if($res['PAR'] > 0 ){
                                            ?>
                                            <tr>
                                                <td><?php echo str_ireplace('Branch Office','',$res['name']); ?></td>
                                                <td><?php echo $res['memberid']; ?></td>
                                                <td><?php echo $res['membercode']; ?></td>
                                                <td><?php echo $res['MemberName']; ?></td>
                                                <td><?php echo $res['savedate']; ?></td>
                                                <td><?php echo $res['Loantype']; ?></td>
                                                <td><?php echo $res['loanNo']; ?></td>
                                                <td><?php echo $res['LoanHeading']; ?></td>
												<td><?php echo $res['pridue']; ?></td>
                                                <td><?php echo $res['intdue']; ?></td>
                                                <td><?php echo $res['PAR']; ?></td>
                                            </tr>
                                            <?php
										}
                                        }
										
                                    ?>
                                </tbody>
                               <tfoot class="bg-red">
							   <tr>
							   <td colspan=8>Total</td>
							   
							   <td><?php echo $pridue;?></td>
							   <td><?php echo $intdue;?></td>
							   <td><?php echo $par;?></td>
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


