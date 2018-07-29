<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu" data-widget="tree">
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-bar-chart-o"></i> 
                    <span> Overdue Reports</span> 
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="savingdue.php"><i class="fa fa-briefcase"></i>Saving OverDue Daily</a></li>
                    <li><a href="loandue.php"><i class="fa fa-google-wallet"></i>Loan OverDue Daily</a></li>
                    <li><a href="funddue.php"><i class="fa fa-money"></i>Fund OverDue Daily</a></li>
                    <li><a href="oreport.php"><i class="fa fa-cloud-upload"></i>Loan OverDue Summary</a></li>
                </ul>
            </li>

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-bar-chart-o"></i> 
                    <span> Monthly Report</span> 
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="staffperformance.php"><i class="fa fa-user"></i>Staff Performance Detail</a></li>
                    <li><a href="branchtrial.php"><i class="fa fa-cloud-upload"></i>Branch Trial</a></li>
                    <li><a href="mpreport.php"><i class="fa fa-cloud-upload"></i>Monthly Progress Report</a></li>

                </ul>
            </li>

            <?php
            if ($_SESSION['JobTypeID'] == '3' or $_SESSION['JobTypeID'] == '6' or $_SESSION['StaffID'] == '18') {
                ?>
                <li><a href="performance.php"><i class="glyphicon glyphicon-adjust"></i>Performance</a></li>
                <?php
            }
            ?>
            <?php
            if ($_SESSION['BranchID'] == '1' AND $_SESSION['StaffID'] == '18') {
                ?>
                <li><a href="noperformance.php"><i class="glyphicon glyphicon-arrow-down"></i>Performance नपाउने लिस्ट</a></li>
                <?php
            }
            ?>

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-bar-chart-o"></i> 
                    <span> Data Analysis</span> 
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="memberpersonal.php"><i class="fa fa-user"></i>Member Personal Detail</a></li>
                    <li><a href="malemember.php"><i class="fa fa-user"></i>Male Member</a></li>
                    <li><a href="pdetail.php"><i class="fa fa-user"></i>Pension Detail</a></li>
                    <li><a href="passive_member.php"><i class="fa fa-user"></i>Passive Member Detail</a></li>
                    <li><a href="swcdetail.php"><i class="fa fa-users"></i>StaffWise Center Detail</a></li>
                    <li><a href="indmember.php"><i class="fa fa-users"></i>Member Individual</a></li>
                    <li><a href="loanage.php"><i class="fa fa-users"></i>Loan Dis. According To Age</a></li>
                    <li><a href="chequedis.php"><i class="glyphicon glyphicon-barcode"></i>Cheque Issue</a></li>
                    <li><a href="cwcamt.php" class="text-sm"><i class="glyphicon glyphicon-cloud"></i>Centerwise Comp Saving Setup</a></li>
                    <li><a href="memberppi.php" class="text-sm"><i class="glyphicon glyphicon-user"></i>Member PPI</a></li>
                    <li><a href="ladetail.php" class="text-sm"><i class="glyphicon glyphicon-align-justify"></i>Loan Analysis Detail</a></li>

                </ul>
            </li> 

            <li class="treeview">
                <a href="#"><i class="fa fa-area-chart"></i> <span> Branch Reports</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="ledgervidan.php"><i class="fa fa-cloud-upload"></i>Ledger With Compile</a></li>
                    <li><a href="daybook.php"><i class="fa fa-bar-chart"></i> DayBook</a></li>
                    <li><a href="loandisbursed.php"><i class="fa fa-barcode"></i> Loan Disbursed</a></li>
                    <li><a href="loan_utilization.php"><i class="fa fa-cloud-upload"></i>Loan Utilization</a></li>
                    <li><a href="registered_member.php"><i class="fa fa-users"></i> Registered members</a></li>
                    <li><a href="dropout_member.php"><i class="fa fa-cloud-upload"></i> Dropout Members</a></li>
                    <li><a href="dailycheque.php"><i class="fa fa-cloud-upload"></i>Daily Cheque Detail</a></li>
                    <li><a href="paymentslip.php"><i class="fa fa-cloud-download"></i>Payment Slip</a></li>
                    <li><a href="pension.php"><i class="fa fa-money"></i>Pension Card Print</a></li>
                    <li><a href="loanapproval.php"><i class="fa fa-money"></i>Loan Approval</a></li>
                    <li><a href="licoc.php"><i class="fa fa-money"></i>LIC Open/Close</a></li>
                    <li><a href="cattleinsurance.php"><i class="fa fa-money"></i>Cattle Insurance</a></li>
                    <li><a href="erfund.php"><i class="fa fa-money"></i>Emergency Relief Fund</a></li>
                    <li><a href="memberlist.php"><i class="fa fa-users"></i>Member Statement</a></li>
                    <?php
                    if ($_SESSION['BranchID'] != 1) {
                        ?>
                            <!--<li><a href="loandetail.php" ><i class="fa fa-google-wallet"></i> <span>Error transaction Report</span></a></li>-->
                        <li><a href="subledger-borrowing.php" ><i class="fa fa-google-wallet"></i> <span>Borrowing Accounts</span></a></li>
                        <li><a href="subledger-remittance.php" ><i class="fa fa-paypal"></i> <span>Remittance Accounts</span></a></li>
                        <li><a href="mainledger.php"><i class="fa fa-money"></i>Main-Ledger</a></li>
                        <li><a href="subledger.php"><i class="fa fa-money"></i>Sub-Ledger</a></li>
                        <?php
                    }
                    ?>
                </ul>
            </li>

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
                        <li><a href="netmembers.php" class="text-sm"><i class="fa fa-users"></i> Net Members</a></li>
                        <li><a href="mainledger.php"><i class="fa fa-money"></i>Main-Ledger</a></li>
                        <li><a href="subledger.php"><i class="fa fa-money"></i>Sub-Ledger</a></li>
                        <li><a href="collstatus.php"><i class="fa fa-money"></i>Center Meeting Status</a></li>
                    </ul>
                </li>
                <?php
            }
            ?>

        </ul>
    </section>
    <!-- /.sidebar -->
</aside>