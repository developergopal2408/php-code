<?php
include_once 'top.php';
$con = mysqli_connect("localhost","root","","file_management");
if ($_POST['fiscalyear']) {
$sol = mysqli_query($con, "select * from document where  FiscalYear = '".$_POST['fiscalyear']."' AND Document_Type = 'Circular' order by Uploaded_Time Desc");
?>
<table id="details" class="table table-hover table-striped table-bordered">
                                    <thead class="bg-red">
                                        <tr>
                                            <th>Date</th>
                                            <th>Subject</th>
                                            <th>Action / View</th>
                                        </tr>
                                    </thead>
                                    <tbody>
<?php
while ($row = mysqli_fetch_array($sol)) {
$dtype = $row['Document_Type'];
$fname = $row['FileName'];
if ($dtype == "Circular") {
     $path = $row['Document_Path_Folder'];
   }
?>
<tr>
   <td><?php echo $row['SaveDateBS']; ?></td>
   <td class="mailbox-star text-bold"><?php echo $row['FileName_Original']; ?></td>
    <td>
	<a href="openpdf.php?path=<?php echo "D:/" . $path . $fname; ?>" target="_new"> <i class="fa fa-eye"></i></a>
	&nbsp;&nbsp; | &nbsp;&nbsp;
	<a href="downlist.php?path=<?php echo "D:/" . $path; ?>&file=<?php echo $fname; ?>"> <i class="fa fa-download"></i> </a> 
	</td>
</tr>
<?php
}
?>
</tbody>
</table>
<?php

}
?>
<script>
    $('#details').DataTable({
        scrollX: true,
        scrollY: "300px",
        scrollCollapse: true,
        paging: false,
        dom: 'brtrip', 
		columnDefs: [
		{ "width": "8%", "targets": [0] },
		{ "width": "70%", "targets": [1] },
		{ "width": "10%", "targets": [2] }
		
		]
		
	});
	</script>
