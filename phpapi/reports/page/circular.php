<?php
include_once 'top.php';
include_once 'header.php';
?>
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
                <i class="fa fa-folder-open"></i> Circular's
                <small>List OF Circular's</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Folders</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-3">
                    <div class="box box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title">Circular's List</h3>

                            <div class="box-tools">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body no-padding">
                            <ul class="nav nav-pills nav-stacked">
                                <li>
                                    <a href="circular.php"><i class="fa fa-folder-o"></i>All Circular Files
                                        <span class="label label-primary pull-right">
                                            <?php
                                            $con = mysqli_connect("localhost", "root", "", "file_management");
                                            if ($BranchID == 1) {
                                                $qrys = mysqli_query($con, "select Document_Type from document where Document_Type = 'Circular'");
                                            } else {
                                                $qrys = mysqli_query($con, "select Document_Type from document where Document_Type = 'Circular' AND ToBranchID IN(0,$BranchID) ");
                                            }
                                            $rows = mysqli_num_rows($qrys);
                                            echo $rows;
                                            ?>
                                        </span>
                                    </a>
                                </li>
                                <?php
                                if ($BranchID == 1) {
                                    $sql = mysqli_query($con, "select * from document where Document_Type = 'Circular' group by Document_Type");
                                } else {
                                    $sql = mysqli_query($con, "select * from document where Document_Type = 'Circular' AND  ToBranchID IN(0,$BranchID) group by Document_Type");
                                }
                                while ($run = mysqli_fetch_array($sql)) {
                                    $dt = $run['Document_Type'];
                                    ?>


                                    <li>
                                        <a href="circular.php?dtype=<?php echo $dt; ?>"><i class="fa fa-folder-o"></i> 
                                            <?php echo "Latest " . $run['Document_Type']; ?>
                                            <span class="label label-primary pull-right">
                                                <?php
                                                if ($BranchID == 1) {
                                                    $qry = mysqli_query($con, "select Document_Type from document  Where Document_Type = '$dt'");
                                                } else {
                                                    $qry = mysqli_query($con, "select Document_Type from document  Where Document_Type = '$dt' AND ToBranchID IN(0,$BranchID)");
                                                }

                                                $row = mysqli_num_rows($qry);
                                                echo $row;
                                                ?>
                                            </span>
                                        </a>
                                    </li>


                                    <?php
                                }
                                ?>
                            </ul>
                        </div>
                        <!-- /.box-body -->
                    </div>

                </div>
                <!-- /.col -->
                <div class="col-md-9">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Circular Detail List</h3>

                            <div class="box-tools pull-right">
                                <div class="has-feedback">
                                    <input type="text" class="form-control input-sm" placeholder="Search Mail">
                                    <span class="glyphicon glyphicon-search form-control-feedback"></span>
                                </div>
                            </div>
                            <!-- /.box-tools -->
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body no-padding">
                            <div class="table-responsive mailbox-messages">
                                <table id="detail" class="table table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Branch Code</th>
                                            <th>Report Type</th>
                                            <th>File Type</th>
                                            <th >Date/Time Duration</th>
                                            <th>View</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $doctype = $_GET['dtype'];
                                        $fid = $_GET['fid'];
                                        if ($doctype AND $BranchID == 1) {
                                            $sol = mysqli_query($con, "select * from document where Document_Type = '$doctype' order by Uploaded_Time Desc");
                                            $update = mysqli_query($con, "update document set Notify_Ho = '1' where Id = '$fid'");
                                        } else if ($doctype AND $BranchID > 1) {
                                            $sol = mysqli_query($con, "select * from document where Document_Type = '$doctype' AND ToBranchID IN(0,$BranchID) order by Uploaded_Time Desc");
                                            $update = mysqli_query($con, "update document set Notify_Ho = '1' where Id = '$fid'");
                                        } else if ($BranchID == 1){
                                            $sol = mysqli_query($con, "select * from document where Document_Type = 'Circular' order by Uploaded_Time Desc");
                                            $update = mysqli_query($con, "update document set Notify_Ho = '1' where Id = '$fid'");
                                        }else {
                                            $sol = mysqli_query($con, "select * from document where Document_Type = 'Circular' AND ToBranchID IN (0,$BranchID) order by Uploaded_Time Desc ");
                                            $update = mysqli_query($con, "update document set Notify_Ho = '1' where Id = '$fid'");
                                        }

                                        while ($row = mysqli_fetch_array($sol)) {
                                            $dtype = $row['Document_Type'];
                                            $fname = $row['FileName'];
                                            if ($dtype == "Circular") {
                                                $path = $row['Document_Path_Folder'];
                                            }

                                            $fmt = substr($fname, -3);
                                            if ($fmt == "pdf") {
                                                $fimg = "pdf.jpg";
                                            } else if ($fmt == "lsx") {
                                                $fimg = "xlsx.jpg";
                                            } else if ($fmt == "doc") {
                                                $fimg = "word.jpg";
                                            } else if ($fmt == "jpg" or $fmt == "png") {
                                                $fimg = "pic.jpg";
                                            } else if ($fmt == "txt") {
                                                $fimg = "text.png";
                                            }
                                            $strTimeAgo = "";
                                            if (!empty($row["Uploaded_Time"])) {
                                                $strTimeAgo = timeago($row["Uploaded_Time"]);
                                            }
                                            ?>
                                            <tr id="row" class="<?php if($row['Notify_Ho'] == '1' AND $row['Update_Ho'] == '0'){echo 'bg-black';} ?>"  class="clickable-row text-sm" style="cursor:pointer;"  data-href="update_ho.php?id=<?php echo $row['Id']; ?>">
                                                <td><?php echo $row['SaveDateBS']; ?></td>
                                                <td class="mailbox-star text-bold"><?php echo $row['BranchCode']; ?></td>
                                                <td class="mailbox-name"><a href="downlist.php?path=<?php echo "D:/" . $path; ?>&file=<?php echo $fname; ?>">
                                                        <?php echo str_replace("_", " ", $row['Document_Type']); ?>
                                                        <i class="fa fa-download"></i> </a> </td>
                                                <td class="mailbox-date"><?php echo $fname; ?></td>
                                                <td class="mailbox-date"><?php echo $strTimeAgo; ?></td>

                                                <td><a href="openpdf.php?path=<?php echo "D:/" . $path . $fname; ?>" target="_new"><i class="fa fa-eye"></i></a></td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <!-- /.table -->
                            </div>
                            <!-- /.mail-box-messages -->
                        </div>

                    </div>
                    <!-- /. box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->

    </div>
    <!--/.content-wrapper -->

    <?php
    include_once 'copyright.php';
    ?>
</div>

<?php
include_once 'footer.php';
?>


<script>
    $("#detail").DataTable({
        scrollY: "300px",
        scrollCollapse: true,
        paging: false,
    });
    
     $('tr[data-href]').on("click", function () {
        document.location = $(this).data('href');
    });

</script>