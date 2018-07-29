<?php
$sql = "SELECT * FROM OfficeDetail WHERE ID='" . $_SESSION['BranchID'] . "' ";
$res = odbc_exec($connection, $sql);
$row = odbc_fetch_array($res);
$_SESSION['Name'] = $row['Name'];
$_SESSION['Code'] = $row['Code'];
$branchName = $_SESSION['Name'];
?>
<header class="main-header">
    <a href="dashboard.php" class="logo">
        <span class="logo-mini"><b>ERS</b></span>
        <span class="logo-lg "><b>E - REMITTANCE</b></span>
    </a>
    <nav class="navbar navbar-static-top">
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </a>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="../dist/img/user1-128x128.jpg" class="user-image" alt="User Image">
                        <span class="hidden-xs"><?php echo strtoupper($_SESSION['uname']); ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="user-header">
                            <img src="../dist/img/user1-128x128.jpg" class="img-circle" alt="User Image">
                            <p>
                                <?php echo strtoupper($branchName); ?>
                            </p>
                        </li>
                        <li class="user-footer">
                            <div class="center">
                                <a href="logout.php" class="btn btn-sm bg-green">Sign out</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>