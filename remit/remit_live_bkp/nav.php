
<nav class="navbar navbar-fixed-top" style="background:#449D44;">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php" style="color:#FFF;">JBS REMIT</a>



        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1" >

            <ul class="nav navbar-nav navbar-right" style="margin-right: 10px;">


                <li><a href="index.php" class="hvr-grow" style="color:#FFF;">Request Remit</a></li>
                <li ><a href="branch_remit.php" class="hvr-grow" style="color:#FFF;">Pay Remit</a></li>
				<li ><a href="send_remit.php" class="hvr-grow" style="color:#FFF;">Send Remit</a></li>

                <!--<li ><a href="branch_remit_detail.php" class="hvr-grow" style="color:#FFF;">Branch Remit</a></li>-->
                <li><a href="remittanceDetail.php" class="hvr-grow" style="color:#FFF;">Requested Remit Detail</a></li>


                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" style="color:#FFF;text-transform: uppercase;">Welcome <?php echo $uname; ?><span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <?php
                        if ($_SESSION['BRANCHID'] == '1') {
                            ?>
                            <li class="hover"><a href="head_history.php" class="hvr-grow" >HO Remit</a></li>
                            <?php } else if ($_SESSION['POSITION'] == 'Branch Incharge' or $_SESSION['POSITION'] == 'Area Incharge' or $_SESSION['JOBTYPEID'] == '2' or $_SESSION['JOBTYPEID'] == '3') {
                            ?>
                            <li ><a href="manage_users.php" class="hvr-grow" >Manage Staff</a></li>
                            <?php
                        }
                        ?>
                        <li class="hover"><a href="profile.php" class="hvr-grow" >Profile</a></li>
                        <li class="hover"><a href="logout.php" class="hvr-grow" >Sign-Out</a></li>
                    </ul>

                </li>


            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
