<?php
include_once 'top.php'; //Include Sidebar_header.php-->
include_once 'header.php'; //Include Sidebar.php-->
$mid = $_GET['mid'];
$oid = $_GET['oid'];
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
                <i class="fa fa-building"></i> 
                <?php
                $b = odbc_exec($connection,"select Name from officedetail where ID = '$oid'");
                $run = odbc_fetch_array($b);
                echo $run['Name'];
                
                $m = odbc_exec($connection,"select FirstName,LastName from member where MemberID = '$mid' and OfficeID='$oid'");
                $rum = odbc_fetch_array($m);
                
                ?>
                <small>Loan Analysis Detail of <?php echo $rum['FirstName']. " " . $rum['LastName'];?></small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Loan Analysis Detail</li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-danger">
                        <div class="box-body">
                            <table id="ladetail" class="display table table-condensed table-bordered table-striped" style="width: auto;">
                                <thead class="bg-red text-sm" style="font-size:10px;">
                                    <tr>
                                        <th>MemID</th>
                                        <th>D.L.ID</th>
                                        <th>L.No</th>
                                        <th>DLoan</th>
                                        <th>AnalysedDate</th>
                                        <th>AnalysedLoan</th>
                                        <th>ADate</th>
                                        <th>ALoan</th>
                                        <th>Status</th>
                                        <th>HouseNo</th>
                                        <th>HouseValue</th>
                                        <th>LandArea</th>
                                        <th>LandValue</th>
                                        <th>LiveStockNo</th>
                                        <th>LiveStockValue</th>
                                        <th>FarmingAmt</th>
                                        <th>BusinessAmt</th>
                                        <th>CashAmt</th>
                                        <th>JwelleryAmt</th>
                                        <th>OtherAmt</th>
                                        <th>IBusiness</th>
                                        <th>Ijob</th>
                                        <th>Iwages</th>
                                        <th>Ifarming</th>
                                        <th>IOther</th>
                                        <th>EBusiness</th>
                                        <th>Ehealth</th>
                                        <th>Efood</th>
                                        <th>Erepair</th>
                                        <th>Eclothes</th>
                                        <th>Ecommunication</th>
                                        <th>EEducation</th>
                                        <th>EloanPayment</th>
                                        <th>Erent</th>
                                        <th>ETravel</th>
                                        <th>EwaterElec</th>
                                        <th>Lorg1Loan</th>
                                        <th>Lorg2Loan</th>
                                        <th>Lorg3Loan</th>
                                        <th>LorgotherLoan</th>
                                        <th>Net_cash</th>
                                        <th>Net_worth</th>

                                    </tr>
                                </thead>

                                <tbody class="text-sm" style="font-size:10px;">
                                    <?php
                                    $qry = "select * from analysisloan where memberid='$mid' and officeid = '$oid' order by ApprovedDate Desc";
                                    $result = odbc_exec($connection, $qry);
                                    while ($res = odbc_fetch_array($result)) {
                                        ?>
                                        <tr>
                                            <td><?php echo $res['MemberID']; ?></td>
                                            <td><?php echo $res['DemandLoanID']; ?></td>
                                            <td><?php echo $res['LoanNo']; ?></td>
                                            <td><?php echo $res['DemandLoan']; ?></td>
                                            <td><?php echo $res['AnalyzedDate']; ?></td>
                                            <td><?php echo $res['AnalyzedLoan']; ?></td>
                                            <td><?php echo $res['ApprovedDate']; ?></td>
                                            <td><?php echo $res['ApprovedLoan']; ?></td>
                                            <td><?php echo $res['Status']; ?></td>
                                            <td><?php echo $res['HouseNo']; ?></td>
                                            <td><?php echo $res['HouseValue']; ?></td>
                                            <td><?php echo $res['LandArea']; ?></td>
                                            <td><?php echo $res['LandValue']; ?></td>
                                            <td><?php echo $res['LiveStockNo']; ?></td>
                                            <td><?php echo $res['LiveStockValue']; ?></td>
                                            <td><?php echo $res['FarmingAmount']; ?></td>
                                            <td><?php echo $res['BusinessAmount']; ?></td>
                                            <td><?php echo $res['CashAmount']; ?></td>
                                            <td><?php echo $res['JewelryAmount']; ?></td>
                                            <td><?php echo $res['OtherAmount']; ?></td>
                                            <td><?php echo $res['IBusiness']; ?></td>
                                            <td><?php echo $res['IJob']; ?></td>
                                            <td><?php echo $res['IWages']; ?></td>
                                            <td><?php echo $res['IFarming']; ?></td>
                                            <td><?php echo $res['IOther']; ?></td>
                                            <td><?php echo $res['EBusiness']; ?></td>
                                            <td><?php echo $res['EHealth']; ?></td>
                                            <td><?php echo $res['EFood']; ?></td>
                                            <td><?php echo $res['ERepair']; ?></td>
                                            <td><?php echo $res['EClothes']; ?></td>
                                            <td><?php echo $res['ECommunication']; ?></td>
                                            <td><?php echo $res['EEducation']; ?></td>
                                            <td><?php echo $res['ELoanPayment']; ?></td>
                                            <td><?php echo $res['ERent']; ?></td>
                                            <td><?php echo $res['ETravel']; ?></td>
                                            <td><?php echo $res['EWaterElec']; ?></td>
                                            <td><?php echo $res['LOrg1Loan']; ?></td>
                                            <td><?php echo $res['LOrg2Loan']; ?></td>
                                            <td><?php echo $res['LOrg3Loan']; ?></td>
                                            <td><?php echo $res['LOrgOtherLoan']; ?></td>
                                            <td><?php echo $res['NetCash']; ?></td>
                                            <td><?php echo $res['NetWorth']; ?></td>
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
    $('#ladetail').DataTable({
        scrollX: true,
        scrollY: "300px",
        scrollCollapse: true,
        paging: false,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                filename: 'Loan Analysis Detail',
                title: 'Jeevan Bikas Samaj',
                messageTop: '<?php echo $run['Name'] . "- Loan Analysis Detail ";?>',
            },
            
            {
                extend: 'print',
                filename: 'Member PPI',
                title: 'Jeevan Bikas Samaj',
                messageTop: '<?php echo $run['Name'] . "- Loan Analysis Detail ";?>',
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