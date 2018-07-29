<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">

            <li class="treeview">
                <a href="#"><i class="fa fa-area-chart"></i> <span> Branch Reports</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="ledgervidan.php"><i class="fa fa-cloud-upload"></i>Ledger With Compile</a></li>
                    <li><a href="daybookss.php"><i class="fa fa-bar-chart"></i> DayBook</a></li>
                    <li><a href="loandisbursed.php"><i class="fa fa-barcode"></i> Loan Disbursed</a></li>
                    <li><a href="loan_utilization.php"><i class="fa fa-cloud-upload"></i>Loan Utilization</a></li>
                    <li><a href="registered_member.php"><i class="fa fa-users"></i> Registered members</a></li>
                    <li><a href="dropout_member.php"><i class="fa fa-cloud-upload"></i> Dropout Members</a></li>
                    <li><a href="dailycheque.php"><i class="fa fa-cloud-upload"></i>Daily Cheque Detail</a></li>
                    <li><a href="cashsaving.php"><i class="fa fa-cloud-download"></i>Payment Slip</a></li>
                    <li><a href="pension.php"><i class="fa fa-money"></i>Pension Card Print</a></li>
                    <li><a href="loanapproval.php"><i class="fa fa-money"></i>Loan Approval</a></li>
                    <li><a href="licopen.php"><i class="fa fa-money"></i>LIC Open</a></li>
                    <li><a href="licclose.php"><i class="fa fa-money"></i>LIC Close</a></li>
                    <li><a href="cattleinsurance.php"><i class="fa fa-money"></i>Cattle Insurance</a></li>
                    <li><a href="erfund.php"><i class="fa fa-money"></i>Emergency Relief Fund</a></li>
                    <li><a href="memberlist.php"><i class="fa fa-users"></i>Member Statement</a></li>
                    <li><a href="loanledger.php"><i class="fa fa-file"></i>Member Loan Ledger</a></li>
                    <?php
                    if ($_SESSION['BranchID'] != 1) {
                        ?>
        <!--<li><a href="loandetail.php" ><i class="fa fa-google-wallet"></i> <span>Error transaction Report</span></a></li>-->
                        <li><a href="MemberCheck.php" ><i class="fa fa-google-wallet"></i> <span>Cheque Print</span></a></li>
                        <li><a href="subledger-borrowing.php" ><i class="fa fa-google-wallet"></i> <span>Borrowing Accounts</span></a></li>
                        <li><a href="subledger-remittance.php" ><i class="fa fa-paypal"></i> <span>Remittance Accounts</span></a></li>
                        <li><a href="mainledger.php"><i class="fa fa-money"></i>Main-Ledger</a></li>
                        <li><a href="sub-mainledger.php"><i class="fa fa-money"></i>Sub-Ledger</a></li>
                        <!-- <li><a href="view_member.php"><i class="fa fa-money"></i>Edit Member Details</a></li>-->
                        <?php
                    }
                    ?>
                </ul>
            </li>

            <li class="treeview">
                <a href="#"><i class="glyphicon glyphicon-record"></i> <span> Data Analysis</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="memberpersonal.php"><i class="fa fa-user"></i>Member Personal Detail</a></li>
                    <li><a href="malemember.php"><i class="fa fa-cloud-upload"></i>Male Member</a></li>
                    <li><a href="pdetail.php"><i class="fa fa-user"></i>Pension Detail</a></li>
                    <li><a href="passive_member.php"><i class="fa fa-user"></i>Passive Member Detail</a></li>
                    <li><a href="swcdetail.php"><i class="fa fa-cloud-upload"></i>StaffWise Center Detail</a></li>
                    <li><a href="indmember.php"><i class="fa fa-cloud-upload"></i>Member Individual</a></li>
                    <li><a href="loanage.php"><i class="fa fa-cloud-upload"></i>Loan Dis. According To Age</a></li>
                    <li><a href="chequedis.php"><i class="fa fa-cloud-upload"></i>Cheque Issue</a></li>
                    <li><a href="cwcamt.php"><i class="fa fa-cloud-upload"></i>CenterWise Comp Saving SetUp</a></li>
                    <li><a href="memberppi.php"><i class="fa fa-cloud-upload"></i>Member PPI</a></li>
                    <li><a href="ladetail.php"><i class="fa fa-cloud-upload"></i>Member Loan Analysis</a></li>
                </ul>
            </li>

            <li class="treeview">
                <a href="#"><i class="glyphicon glyphicon-record"></i> <span> Monthly Report</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="staffperformance.php"><i class="fa fa-user"></i>Staff Performance Detail</a></li>
                    <!--<li><a href="passive_member.php"><i class="fa fa-user"></i>Passive Member Detail</a></li>-->
                    <li><a href="bt.php"><i class="fa fa-cloud-upload"></i>Branch Trial</a></li>
                    <!--<li><a href="plbalance.php"><i class="fa fa-cloud-upload"></i>Profit & Loss </a></li>-->
                </ul>
            </li>
            <!--<li ><a href="dashboard.php"><i class="fa fa-dashboard"></i> <span> Dashboard</span></a></li> -->
            <li class="treeview">
                <a href="#"><i class="glyphicon glyphicon-record"></i> <span> Overdue</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="savingdue.php"><i class="fa fa-cloud-upload"></i>Saving Due</a></li>
                    <li><a href="loandue1.php"><i class="fa fa-cloud-upload"></i>Loan OverDue</a></li>
                    <li><a href="funddue.php"><i class="fa fa-cloud-upload"></i>Fund Due</a></li>
                    <?php
                    if ($_SESSION['StaffID'] == 72 or $_SESSION['StaffID'] == 78 or $_SESSION['StaffID'] == 37 or $_SESSION['BranchID'] > 1) {
                        ?>
                        <li><a href="einstreceipt.php"><i class="fa fa-cloud-upload"></i>Err Installment Receipt</a></li>
                        <li><a href="eli.php"><i class="fa fa-cloud-upload"></i>Error Loan Installment Amt</a></li>
                        <?php
                    }
                    ?>
                <!--<li><a href="oreport.php"><i class="fa fa-cloud-upload"></i>Loan OverDue Summary</a></li>-->
                </ul>
            </li>



            <?php
            if ($_SESSION['JobTypeID'] == '3' or $_SESSION['JobTypeID'] == '6' or $_SESSION['StaffID'] == '18') {
                ?>
                <li><a href="#perform" data-toggle="modal" data-target="#perform" ><i class="glyphicon glyphicon-adjust"></i>Performance</a></li>  <?php
            }
            ?>
            <?php
            if ($_SESSION['BranchID'] == '1' AND $_SESSION['StaffID'] == '18') {
                ?>
                <li><a href = "#performs" data-toggle="modal" data-target="#performs"><i class="glyphicon glyphicon-arrow-down"></i>Performance नपाउने लिस्ट</a></li>
                <?php
            }
            ?>







            <?php
            if ($_SESSION['BranchID'] == 1) {
                ?>
                <li class="treeview">
                    <a href="#"><i class="fa fa-building"></i> <span>Head Office Reports</span> <i class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li><a href="dayend.php"><i class="fa fa-bar-chart"></i> Day End</a></li>
                        <li><a href="demandloan.php" class="text-sm"><i class="fa fa-bar-chart"></i> Loan Demand Remaining</a></li>
                        <li><a href="analysisloan.php" class="text-sm"><i class="fa fa-bar-chart"></i> Loan Analysis</a></li>
                        <li><a href="tndmember.php" class="text-sm"><i class="fa fa-users"></i> New/Dropout Members</a></li>
                        <li><a href="netmembers.php" class="text-sm"><i class="fa fa-users"></i> Total Members</a></li>

                        <li><a href="mainledger.php"><i class="fa fa-money"></i>Main-Ledger</a></li>
                        <li><a href="sub-mainledger.php"><i class="fa fa-money"></i>Sub-Ledger</a></li>
                        <li><a href="collstatus.php"><i class="fa fa-money"></i>Center Meeting Status</a></li>
                        <li><a href="bwisebalance.php"><i class="fa fa-file"></i>Branch Wise Balance</a></li>
    <!--<li><a href="loanoverdue.php"><i class="fa fa-file"></i>Loan Overdue</a></li>-->
						<li><a href="memberqueries.php"><i class="fa fa-comment"></i>Member Queries</a></li>
						<li><a href="finlitem.php"><i class="fa fa-users"></i>FinliteM Detail</a></li>
						
						<li><a href="branchvisit.php"><i class="fa fa-arrow-up"></i>Branch Visit</a></li>
						<li><a href="fieldvisit.php"><i class="fa fa-arrow-down"></i>Field Visit</a></li>
						
                        <?php
                        if ($_SESSION['StaffID'] == 72 or $_SESSION['StaffID'] == 78 or $_SESSION['StaffID'] == 37) {
                            ?>
                            <li><a href="compile.php"><i class="fa fa-bar-chart-o"></i>Compile</a></li>

                            <li><a href="loan_disburse_detail.php"><i class="fa fa-file"></i>Loan Disburse Detail</a></li>
                            <?php
                        }
                        ?>

                    </ul>
                </li>
                <?php
            }
            ?>

            <li><a href="circular_list.php"><i class="fa fa-eye"></i>Circular</a></li>
            <li><a href="createfile.php"><i class="fa fa-upload"></i>Upload Files (Send Items)</a></li>
            <li><a href="view_uploaded_files.php"><i class="fa fa-eye"></i>View All Received Files</a></li>



<!--<li><a href="createfile.php"><i class="fa fa-upload"></i>Upload & View File</a></li>-->

        </ul>
    </section>
    <!-- /.sidebar -->
</aside>

<div class="modal fade" id="perform" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Input Password</h4>
            </div>
            <div class="modal-body">
                <form action="pass.php" method="post" >
                    <div class="form-group">
                        <input type="password" required name="pass" class="form-control" id="pass"  placeholder="Your FinliteX Password Here">
                    </div>
                    <div align="center">
                        <button type="submit" class="btn btn-primary login-btn">Enter</button>
                    </div>
            </div>
            </form>
        </div>
    </div>

</div>

<div class="modal fade" id="performs" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Input Password</h4>
            </div>
            <div class="modal-body">
                <form action="pass1.php" method="post" >
                    <div class="form-group">
                        <input type="password" required name="pass" class="form-control" id="pass"  placeholder="Your FinliteX Password Here">
                    </div>
                    <div align="center">
                        <button type="submit" class="btn btn-primary login-btn">Enter</button>
                    </div>
            </div>
            </form>
        </div>
    </div>

</div>