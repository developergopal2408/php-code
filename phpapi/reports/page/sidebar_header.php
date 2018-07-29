<?php
$sql = "SELECT * FROM OfficeDetail WHERE ID='" . $_SESSION['BranchID'] . "' ";
$res = odbc_exec($connection, $sql);
$row = odbc_fetch_array($res);
$branchName = $row['Name'];
function timeAgo($time_ago) {
    $time_ago = strtotime($time_ago) ? strtotime($time_ago) : $time_ago;
    $time = time() - $time_ago;

    switch ($time):
// seconds
        case $time <= 60;
            return 'lessthan a minute ago';
// minutes
        case $time >= 60 && $time < 3600;
            return (round($time / 60) == 1) ? 'a minute ago' : round($time / 60) . ' minutes ago';
// hours
        case $time >= 3600 && $time < 86400;
            return (round($time / 3600) == 1) ? 'a hour ago' : round($time / 3600) . ' hours ago';
// days
        case $time >= 86400 && $time < 604800;
            return (round($time / 86400) == 1) ? 'a day ago' : round($time / 86400) . ' days ago';
// weeks
        case $time >= 604800 && $time < 2600640;
            return (round($time / 604800) == 1) ? 'a week ago' : round($time / 604800) . ' weeks ago';
// months
        case $time >= 2600640 && $time < 31207680;
            return (round($time / 2600640) == 1) ? 'a month ago' : round($time / 2600640) . ' months ago';
// years
        case $time >= 31207680;
            return (round($time / 31207680) == 1) ? 'a year ago' : round($time / 31207680) . ' years ago';

    endswitch;
}
?>
<header class="main-header">
    <!-- Logo -->
    <a href="dashboard.php" class="logo">
        <span class="logo-mini"><b>JBS</b></span>
        <span class="logo-lg "><b>Branch Reports</b></span>
    </a>
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </a>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
			
			<?php
                $con = mysqli_connect("localhost", "root", "", "file_management");
				 if($_SESSION['DepartmentID'] == 1){
                            $did = "AND DepartmentID = '" . $_SESSION['DepartmentID'] . "'";
                        }else if($_SESSION['DepartmentID'] == 5){
							$did = "AND DepartmentID = '" . $_SESSION['DepartmentID'] . "'";
						}else{
                            $did = "AND DepartmentID BETWEEN '1' AND '8'";
                        }
						
						
                if ($_SESSION['BranchID'] == 1) {
                    $query = "select * from document where Notify_ho = '0' and SaveDateAD = '" . date('Y-m-d') . "' $did and ToBranchID = '".$_SESSION['BranchID']."' order by Id Desc";
                } else {
                    $query = "select * from document where Notify_ho = '0' and SaveDateAD = '" . date('Y-m-d') . "' AND  ToBranchID IN(0,'".$_SESSION['BranchID']."') order by Id Desc";
                }
                $runs = mysqli_query($con, $query);
                $r = mysqli_num_rows($runs);
                ?>
                <!-- Messages: style can be found in dropdown.less-->
                <li class="dropdown messages-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-envelope-o"></i>
                        <?php
                        if ($r == 0) {
                            echo "";
                        } else {
                            ?>
                            <span class="label label-success"><?php echo $r; ?></span>
                            <?php
                        }
                        ?>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">You have <?php echo $r; ?> messages</li>
                        <li>
                            <!-- inner menu: contains the actual data -->
                            <ul class="menu">
                                <?php
                                while ($row = mysqli_fetch_array($runs)) {
                                    $dtype = $row['Document_Type'];
                                    $stype = substr($dtype, 0, 1) . "R";

                                    $fname = $row['FileName'];

                                    if ($dtype == "Monthly_Report") {
                                        $path = $row['Document_Path_Month'] . $fname;
                                    } else {
                                        $path = $row['Document_Path_Folder'] . $fname;
                                    }



                                    $fmt = substr($fname, -3);
                                    if ($fmt == "pdf") {
                                        $fimg = "pdf.jpg";
                                    } else if ($fmt == "lsx" or $fmt == "xls") {
                                        $fimg = "xlsx.jpg";
                                    } else if ($fmt == "doc" or $fmt == "ocx") {
                                        $fimg = "word.jpg";
                                    } else if ($fmt == "jpg" or $fmt == "png") {
                                        $fimg = "pic.jpg";
                                    } else if ($fmt == "txt") {
                                        $fimg = "text.png";
                                    }
                                    $strTimeAgo = "";
                                    if (!empty($row["Uploaded_Time"])) {
                                        $strTimeAgo = timeAgo($row["Uploaded_Time"]);
                                    }
                                    ?>
                                    <li><!-- start message -->

                                        <a href="<?php if($dtype == 'Circular'){echo 'circular.php';}else{echo 'view_uploaded_files.php';}?>?dtype=<?php echo $dtype; ?>&fid=<?php echo $row['Id']; ?>" >
                                            <div class="pull-left">
                                                <img src="<?php echo $fimg; ?>" class="img-circle img-thumbnail" alt="User Image"/>

                                            </div>
                                            <h4>
                                                <?php echo $row['BranchCode'] . "-" . $stype . "-" . $row['Document_Name']; ?>
                                                <small><i class="fa fa-clock-o"></i> <?php echo $strTimeAgo; ?></small>
                                            </h4>
                                            <p><?php echo substr($row['Remarks'], 0, 25); ?></p>
                                        </a>
                                    </li>
                                    <!-- end message -->
                                    <?php
                                }
                                ?>
                            </ul>
                        </li>
                        <li class="footer"><a href="#">See All Messages</a></li>
                    </ul>
                </li>
			
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="image.php?img=<?php if(!($_SESSION['Photo'])){echo '../dist/img/user1-128x128.jpg';}else{echo $_SESSION['Photo'];}?>" class="user-image" alt="User Image">
                        <span class="hidden-xs"><?php echo strtoupper($_SESSION['uname']); ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="user-header">
                            <img src="image.php?img=<?php if(!($_SESSION['Photo'])){echo '../dist/img/user1-128x128.jpg';}else{echo $_SESSION['Photo'];}?>" class="img-circle" alt="User Image">
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