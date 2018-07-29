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
                    <div class="col-md-12">
					
                    <div class="box box-primary">
                        <div class="box-header">
                            <div class="col-sm-12">
                                <div class="col-sm-12">
                                    <!-- search form -->
                                    <form  action="" method="post" class="form-horizontal" >
                                        <div class=" form-group-sm">

                                            <?php
                                            if ($_SESSION['BranchID'] == 1) {
                                                ?>
                                                <div class="col-sm-3">
                                                    <select name="fiscal" id="fiscal" class="form-control select2" >
                                                        <option value="">Select FiscalYear</option>

                                                        <?php
                                                        $sql1 = "SELECT distinct(FiscalYear) FROM document";
                                                        $result = mysqli_query($con, $sql1);

                                                        while ($rows = mysqli_fetch_array($result)) {
                                                            ?>
                                                            <option value="<?php echo $rows['FiscalYear']; ?>" <?php if(isset($_POST['fiscal']) == $rows['FiscalYear']){echo "selected";} ?>><?php echo $rows['FiscalYear']; ?></option>;
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <?php
                                            }
                                            ?>
                                            
                                        </div>
                                    </form>
                                    <!-- /.search form -->
                                    <a href="circular_list.php"  class=" btn btn-flat bg-blue pull-right" title="Refresh"><i class="fa fa-refresh"></i></a>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-header -->
						
                        <div class="box-body no-padding">
                            <div class="table-responsive mailbox-messages tango">
                                <table id="detail" class="table table-hover table-striped table-bordered">
                                    <thead class="bg-red">
                                        <tr>
                                            <th>Date</th>
                                            <th>Subject</th>
                                            <th>Action / View</th>
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
                                                <td class="mailbox-star text-bold"><?php echo $row['FileName_Original']; ?></td>
                                                
                                                <td>
												<a href="openpdf.php?path=<?php echo "D:/" . $path . $fname; ?>" target="_new"><i class="fa fa-eye"></i></a>
												&nbsp;&nbsp; | &nbsp;&nbsp;
												<a href="downlist.php?path=<?php echo "D:/" . $path; ?>&file=<?php echo $fname; ?>">
                                                        
                                                        <i class="fa fa-download"></i> </a> 
												</td>
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
    $('#detail').DataTable({
        scrollX: true,
        scrollY: "300px",
        scrollCollapse: true,
        paging: false,
        dom: 'brtrip', 
		columnDefs: [
		{ "width": "10%", "targets": [0] },
		{ "width": "70%", "targets": [1] },
		{ "width": "10%", "targets": [2] }
		
		]
		
	});
	
	
	$('#fiscal').change(function () {
            var cid = $(this).val();
            $.ajax({
                type: "POST",
                url: "getcircular.php",
                data: "fiscalyear=" + cid, // serializes the form's elements.
                success: function (data)
                {
					$("#detail").hide();
					$(".tango").html(data);
                }
            });
        });
	
    
     $('tr[data-href]').on("click", function () {
        document.location = $(this).data('href');
    });

</script>