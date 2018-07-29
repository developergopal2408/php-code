<?php
include_once 'top.php'; //Include Sidebar_header.php-->
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
                <small>LIC CLOSE</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">LIC CLOSE </li>
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
                                <div class="box-tools pull-right" >
                                    <a href="licclose.php"  class=" btn btn-flat bg-blue" title="Refresh"><i class="fa fa-refresh"></i></a>
                                </div>
                            </div>
                        </div>

                        <div class="box-body">
                            <?php
                            if (isset($_POST['search'])) {
                            $bname = $_POST['id'];
                            if ($_POST['id']) {
                            echo "<h5 class='text-bold text-center'>" . $bname . " ( " . $_POST['date1'] . " - " . $_POST['date2'] . " )</h5>";
                            } else {
                            echo "<h5 class='text-bold text-center'>" . $branchName . "( " . $_POST['date1'] . " - " . $_POST['date2'] . " )</h5>";
                            }
                            }
                            ?>
                            <table id="lic" class="table table-condensed text-sm">
                                <thead class="bg-red "> 
                                    <tr>
                                        <th>Code</th>
                                        <th>Name</th>
                                        <th>MemberCode</th>
                                        <th>MemberName</th>
										 <th>InsuredPerson</th>
                                        <th>Status</th>
                                        <th>Closedate</th>
                                        <th>PolicyNo</th>
                                        <th>Startdate</th>
                                        <th>InsuredAmount</th>
                                        <th>Finstamount</th>
                                        <th>Instamount</th>
										<th>IsActive</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $ID = $_POST['id'];
                                    $date1 = $_POST['date1'];
                                    $date2 = $_POST['date2'];
                                    if ($_SESSION['BranchID'] == 1 ) {
                                    $id = "";
                                    } else {
                                    $id = "and o.id = '" . $_SESSION['BranchID'] . "'";
                                    }
                                    if (isset($_POST['search'])) {
                                  
                                   /* $qry = "select o.Code,o.Name,m.memberid,m.membercode,m.firstname+' '+m.lastname as MemberName,M.status,i.CloseDate,i.policyno, i.Startdate,i.InsuredAmount,i.FinstAmount,i.InstAmount,i.isactive
                                                    from insuranceaccount i, member m, officedetail o
                                                    where m.memberid=i.memberid and o.id=i.officeid and m.officeid=o.id and i.isactive='N' and i.closedate between '$date1' and '$date2'
                                                    and m.officeid=o.id $id
                                                    order by i.closedate, o.name";*/
													
									$qry = "select o.Code,o.Name, m.membercode,m.firstname+' '+ m.lastname as MemberName,m.status,
											i.Name as InsuredPerson,m.DOB,i.Husband,i.FatherName,I.Gender,i.insuredamount,i.policyno,i.Instamount , i.finstamount,
											i.startdate,i.CloseDate,i.IsActive 
											from member m, insuranceaccount i,Officedetail o
											where m.memberid=i.memberid   and i.CloseDate>='$date1' and i.CloseDate<='$date2' 
											and o.id=m.officeid and o.id=i.officeid $id and i.IsSelf =1
											Union all
											select o.Code,o.Name, m.membercode,m.firstname+' '+ m.lastname as MemberName,m.status,
											F.name as InsuredPerson,F.DOB,i.Husband,i.FatherName,i.Gender,i.insuredamount,i.policyno, i.finstamount,i.Instamount ,
											i.startdate,i.CloseDate,i.IsActive
											from member m, insuranceaccount i,Officedetail o,MemberFamilyDetail f
											where m.memberid=i.memberid   and i.CloseDate>='$date1' and i.CloseDate<='$date2' 
											and o.id=m.officeid and o.id=i.officeid and m.MemberID =i.MemberID and f.ID=i.InsuredPersonID and f.OfficeID =i.OfficeID 
											$id and i.isself=0";
                                    
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
                                        <td><?php echo $res['status']; ?></td>
                                        <td><?php echo $res['CloseDate']; ?></td>
                                        <td><?php echo $res['policyno']; ?></td>
                                        <td><?php echo $res['startdate']; ?></td>
                                        <td><?php echo $res['insuredamount']; ?></td>
                                        <td><?php echo $res['finstamount']; ?></td>
                                        <td><?php echo $res['Instamount']; ?></td>
										<td><?php if($res['IsActive'] == 'N'){echo "<span class='label label-danger'>Closed</span>";}else if($res['IsActive'] == 'R'){echo "<span class='label label-primary'>Return & Closed</span>";}else{echo "<span class='label label-success'>Active</span>";} ?></td>

                                       
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
    $('#lic').DataTable({
        scrollX: true,
        scrollY: "300px",
        scrollCollapse: true,
        paging: false,
        dom: 'Bfrtip',
        columnDefs: [
			{ "width": "15%", "targets": [1] }
		  ],
        buttons: [
            {
                extend: 'excel',
                filename: 'LIC CLOSE Detail',
                title: 'Jeevan Bikas Samaj',
                messageTop: ' <?php
if (isset($_POST['search'])) {
    if ($_SESSION['BranchID'] > 1) {
        echo $branchName . ' ( ' . $_POST['date1'] . ' - ' . $_POST['date2'] . ') - LIC CLOSE Detail';
    } else {
        echo $bname . ' ( ' . $_POST['date1'] . ' - ' . $_POST['date2'] . ') - LIC CLOSE Detail';
    }
} else {
    echo $branchName . ' ( ' . $cdate . ' ) - LIC OPEN/CLOSE Detail';
}
?>',
            },
            {
                extend: 'print',
                filename: 'LIC CLOSE Detail',
                title: 'Jeevan Bikas Samaj',
                messageTop: ' <?php
if (isset($_POST['search'])) {
    if ($_SESSION['BranchID'] > 1) {
        echo '<h5 class="text-bold text-center">' . $branchName . '<br/> ( LIC CLOSE Detail - ' . $_POST['date1'] . ' - ' . $_POST['date2'] . ' ) </h5>';
    } else {
        echo '<h5 class="text-bold text-center">' . $bname . '<br/> ( LIC CLOSE Detail - ' . $_POST['date1'] . ' - ' . $_POST['date2'] . ' ) </h5>';
    }
} else {
    echo '<h5 class="text-bold text-center">' . $branchName . '<br/> ( LIC CLOSE Detail ' . $cdate . '  )</h5>';
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




