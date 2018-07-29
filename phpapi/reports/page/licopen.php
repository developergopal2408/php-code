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
                <small>LIC OPEN</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">LIC OPEN </li>
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
                                            <button type="submit" name="search" id="search-btn" class=" btn btn-flat bg-green"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                </form>
                                <!-- /.search form -->


                            </div>
                        </div>

                        <div class="box-body">
                            <div class="box-title with-header text-bold text-center">
                                <?php
                                if (isset($_POST['id'])) {
                                    echo "( " . $_POST['date1'] . " - " . $_POST['date2'] . " ) " . $_POST['id'];
                                }
                                ?>
                            </div>
                            <table id="lic" class="table display stripe row-border order-column" cellspacing="0" width="100%"  >
                                <thead class="bg-red text-sm">

                                    <tr>
                                        <th>Code</th>
                                        <th>BranchName</th>
                                        <th>MemberCode</th>
                                        <th>MemberName</th>
                                        <th>InsuredPerson</th>
                                        <th>DOB</th>
                                        <th>Husband</th>
                                        <th>FatherName</th>
                                        <th>Gender</th>
                                        <th>InsuredAmt</th>
                                        <th>PolicyNo</th>
                                        <th>FirstInstAmt</th>
                                        <th>InstAmt</th>
                                        <th>StartDate</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                    if (isset($_POST['search'])) {
                                        $date1 = $_POST['date1'];
                                        $date2 = $_POST['date2'];
                                        if ($_SESSION['BranchID'] == 1) {
                                            /*$qry = "select o.Code,o.Name, m.membercode,m.firstname+' '+ m.lastname as MemberName,
                                                    m.firstname+' '+ m.lastname as InsuredPerson,m.DOB,i.Husband,i.FatherName,
                                                    i.Gender,i.insuredamount,i.policyno, i.finstamount,i.Instamount ,i.startdate,i.isSelf
                                                    from member m, insuranceaccount i,Officedetail o
                                                    where m.memberid=i.memberid and i.isactive='Y' and m.status='active' 
                                                    and i.startdate>='$date1' and i.startdate<='$date2'
                                                    and o.id=m.officeid and o.id=i.officeid 
                                                    order by  o.Code,i.startdate";*/
													
											$qry = "select o.Code,o.Name, m.membercode,m.firstname+' '+ m.lastname as MemberName,
													i.Name as InsuredPerson,m.DOB,i.Husband,i.FatherName,I.Gender,i.insuredamount,i.policyno, i.finstamount,i.Instamount ,i.startdate,i.isSelf
													from member m, insuranceaccount i,Officedetail o
													where m.memberid=i.memberid and i.isactive='Y' and m.status='active' and i.startdate>='$date1' and i.startdate<='$date2' 
													and o.id=m.officeid and o.id=i.officeid  and i.IsSelf =1
													Union all
													select o.Code,o.Name, m.membercode,m.firstname+' '+ m.lastname as MemberName,
													F.name as InsuredPerson,F.DOB,i.Husband,i.FatherName,i.Gender,i.insuredamount,i.policyno, i.finstamount,i.Instamount ,i.startdate,i.isSelf
													from member m, insuranceaccount i,Officedetail o,MemberFamilyDetail f
													where m.memberid=i.memberid and i.isactive='Y' and m.status='active' and i.startdate>='$date1' and i.startdate<='$date2' 
													and o.id=m.officeid and o.id=i.officeid and m.MemberID =i.MemberID and f.ID=i.InsuredPersonID and f.OfficeID =i.OfficeID 
													 and i.isself=0";
                                        } else{
                                            /*$qry = "select o.Code,o.Name, m.membercode,m.firstname+' '+ m.lastname as MemberName,
                                                    m.firstname+' '+ m.lastname as InsuredPerson,m.DOB,i.Husband,i.FatherName,
                                                    i.Gender,i.insuredamount,i.policyno, i.finstamount,i.Instamount ,i.startdate,i.isSelf
                                                    from member m, insuranceaccount i,Officedetail o
                                                    where m.memberid=i.memberid and i.isactive='Y' and m.status='active' 
                                                    and i.startdate>='$date1' and i.startdate<='$date2' and o.id = '".$_SESSION['BranchID']."'
                                                    and o.id=m.officeid and o.id=i.officeid 
                                                    order by  o.Code,i.startdate";*/
													
											$qry = "select o.Code,o.Name, m.membercode,m.firstname+' '+ m.lastname as MemberName,
													i.Name as InsuredPerson,m.DOB,i.Husband,i.FatherName,I.Gender,i.insuredamount,i.policyno, i.finstamount,i.Instamount ,i.startdate,i.isSelf
													from member m, insuranceaccount i,Officedetail o
													where m.memberid=i.memberid and i.isactive='Y' and m.status='active' and i.startdate>='$date1' and i.startdate<='$date2' 
													and o.id=m.officeid and o.id=i.officeid and o.ID='".$_SESSION['BranchID']."' and i.IsSelf =1
													Union all
													select o.Code,o.Name, m.membercode,m.firstname+' '+ m.lastname as MemberName,
													F.name as InsuredPerson,F.DOB,i.Husband,i.FatherName,i.Gender,i.insuredamount,i.policyno, i.finstamount,i.Instamount ,i.startdate,i.isSelf
													from member m, insuranceaccount i,Officedetail o,MemberFamilyDetail f
													where m.memberid=i.memberid and i.isactive='Y' and m.status='active' and i.startdate>='$date1' and i.startdate<='$date2' 
													and o.id=m.officeid and o.id=i.officeid and m.MemberID =i.MemberID and f.ID=i.InsuredPersonID and f.OfficeID =i.OfficeID 
													and o.ID='".$_SESSION['BranchID']."' and i.isself=0";		
                                        }

                                        $result = odbc_exec($connection, $qry);
                                        while ($res = odbc_fetch_array($result)) {
                                            ?>
                                            <tr>
                                                <td><?php echo $res['Code']; ?></td>
                                                <td><?php echo str_ireplace("Branch Office","",$res['Name']); ?></td>
                                                <td><?php echo $res['membercode']; ?></td>
                                                <td><?php echo $res['MemberName']; ?></td>
                                                <td><?php echo $res['InsuredPerson'];?></td>
                                                <td><?php echo $res['DOB']; ?></td>
                                                <td><?php echo $res['Husband']; ?></td>
                                                <td><?php echo $res['FatherName']; ?></td>
                                                <td><?php echo $res['Gender']; ?></td>
                                                <td><?php echo $res['insuredamount']; ?></td>
                                                <td><?php echo $res['policyno']; ?></td>
                                                <td><?php echo $res['finstamount']; ?></td>
                                                <td><?php echo $res['Instamount']; ?></td>
                                                <td><?php echo $res['startdate']; ?></td>
                                               

												</tr>																				
												<?php
											}
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
    $('#lic').DataTable({
        scrollX: true,
        scrollY: "300px",
        scrollCollapse: true,
        paging: false,
        fixedColumns: {
            leftColumns: 1,
        },
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                filename: 'Lic Open List',
                title: 'Jeevan Bikas Samaj',
                messageTop: ' <?php
if (isset($_POST['search'])) {
    echo $branchName . ' ( ' . $_POST['date1'] . ' -  ' . $_POST['date2'] . ' ) - Lic Open List';
} else {
    echo $branchName . ' ( ' . $_POST['date1'] . ' -  ' . $_POST['date2'] . ' ) - Lic Open List';
}
?>',
            },
            {
                extend: 'print',
                filename: 'Lic Open List',
                title: 'Jeevan Bikas Samaj',
                messageTop: ' <?php
if (isset($_POST['search'])) {
    echo '<h5 class="text-bold text-center">' . $branchName . '<br/> ( Lic Open List - ' . $_POST['date1'] . ' -  ' . $_POST['date2'] . ' ) </h5>';
} else {
    echo '<h5 class="text-bold text-center">' . $branchName . '<br/> ( Lic Open List ' . $_POST['date1'] . ' -  ' . $_POST['date2'] . ' )</h5>';
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



