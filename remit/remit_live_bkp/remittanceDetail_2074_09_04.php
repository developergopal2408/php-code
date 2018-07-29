<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include_once 'header.php';
if (!isset($_SESSION['STAFFID'])) {
    header('Location:login.php');
}
$categoryID = $_SESSION['CATEGORYID'];
$staffid = $_SESSION['STAFFID'];
$branchcode = $_SESSION['BRANCHCODE']; 
$fname = $_SESSION['firstname'];
$lname = $_SESSION['lastname'];
$uname = "$fname $lname";
include_once 'nav.php';
require_once('nepali_calendar.php');
require_once('functions.php');
$cal = new Nepali_Calendar();

if ($categoryID == '1') {
    $query = "SELECT * FROM remittance where TDATE = '".date('Y-m-d')."' OR STATUS BETWEEN 'PENDING' AND 'REJECTED' ORDER BY  TDATE DESC";
    $run = mysqli_query($con, $query);
} else if ($categoryID == '2' or $categoryID == '3') {
    $query = "SELECT * FROM remittance WHERE BCODE = '$branchcode' ORDER BY TDATE desc";
    $run = mysqli_query($con, $query);
}

?>
<body style="margin-top:70px;">
    <div class="container-fluid" >
        <div class="row">
            <div class="col-md-12">	
			<div class="pull-left" >
			<h4>Requested Remit Detail</h4><hr>
			</div>
			<div class="pull-right" >
             <button id="excel" class="btn btn-sm btn-success" href="#" onClick ="$('#myTable1').tableExport({type: 'excel', escape: 'false'});">XLS</button>
			 <hr>
            </div>
			</div>
			<div class="col-md-12">	
                <div class="responsive-table">
                    <table id="myTable1" class="table table-striped  table-bordered dataTable table-condensed table-hover" style="font-size: 11px; ">

                        
                        <thead>  
                            <tr>
                                <th>Date</th>
                                <th>Remit No</th>
                                <th>Remit Company</th>
                                <th>From Branch</th>
                                <th>Receiver Name</th>
                                <th>ID TYPE</th>                               
                                <th>ID NO</th>
								<th>DOB</th> 
                                <th>Receiver Phone No</th>
                                <th>Sender Name</th>
                                <th>Country</th>
                                <th>Sender Relation</th>
                                <th>ExpAmount</th>
								<th>PAID DATE</th>
								<th>PAID AMOUNT</th>
                                <th>Status</th>
                            </tr> 
                        </thead>  
                        <tbody>   <?php
                            if (!$run or mysqli_num_rows($run) > 0) {
                                while ($row1 = mysqli_fetch_array($run)) {
									  if($row1['PAIDDATE'] == true){
                                       $pdate =  $row1['PAIDDATE'];
                                    }else{
                                        $pdate = '0000-00-00';
                                    }
                                    list($pyear, $pmonth, $pday) = explode('-', $pdate);
                                    $npdate = $cal->eng_to_nep($pyear, $pmonth, $pday);
                                    $npyr = $npdate['year'];
                                    $npmonth = $npdate['month'];
                                    $npday = $npdate['date'];
                                    $cpdate = $npyr . "-" . $npmonth . "-" . $npday;

                                    $date = $row1['TDATE'];
                                    list($year, $month, $day) = explode('-', $date);
                                    $nepdate = $cal->eng_to_nep($year, $month, $day);
                                    $nyr = $nepdate['year'];
                                    $nmonth = $nepdate['month'];
                                    $nday = $nepdate['date'];
                                    $cdate = $nyr . "-" . $nmonth . "-" . $nday;
                                    if ($row1['STATUS'] == 'PENDING') {
                                        ?>  

                                        <tr id="row" class='clickable-row text-primary' data-href="remittance_view_detail.php?msg_id=<?php echo $row1['MSGID']; ?>">


                                            <td><?php echo $cdate; ?></td>                    
                                            <td><?php echo $row1['REMITNO']; ?></td>
                                            <td><?php echo $row1['REMITCOMPANY']; ?></td>
                                            <td><?php echo $row1['BRANCHNAME']; ?></td>
                                            <td><?php echo $row1['RECEIVERNAME']; ?></td>
                                            <td><?php echo $row1['RECEIVERIDTYPE']; ?></td>
                                            <td><?php echo $row1['RECEIVERIDNO']; ?></td>
											<td><?php echo $row1['RECEIVERDOB']; ?></td>
                                            <td><?php echo $row1['RECEIVERCONTACTNO']; ?></td>
                                            <td><?php echo $row1['SENDERNAME']; ?></td>
                                            <td><?php echo $row1['SENDERCOUNTRY']; ?></td>
                                            <td><?php echo $row1['SENDERRELATION']; ?></td>
                                            <td><?php echo $row1['EXPECTEDAMT']; ?></td>
											<td><?php echo $cpdate; ?></td>
											<td class="text-bold"><b style="font-size:14px;"><?php echo $row1['PAIDAMT']; ?></b></td>
                                            <td><?php echo $row1['STATUS']; ?></td>
                                        </tr>

                                    <?php } else if ($row1['STATUS'] == 'REJECTED') {
                                        ?>
                                        <tr id="row" class='clickable-row text-danger' data-href="remittance_view_detail.php?msg_id=<?php echo $row1['MSGID']; ?>">


                                            <td><?php echo $cdate; ?></td> 
                                            <td><?php echo $row1['REMITNO']; ?></td>
                                            <td><?php echo $row1['REMITCOMPANY']; ?></td>
                                            <td><?php echo $row1['BRANCHNAME']; ?></td>
                                            <td><?php echo $row1['RECEIVERNAME']; ?></td>
                                            <td><?php echo $row1['RECEIVERIDTYPE']; ?></td>
                                            <td><?php echo $row1['RECEIVERIDNO']; ?></td>
											<td><?php echo $row1['RECEIVERDOB']; ?></td>
                                            <td><?php echo $row1['RECEIVERCONTACTNO']; ?></td>
                                            <td><?php echo $row1['SENDERNAME']; ?></td>
                                            <td><?php echo $row1['SENDERCOUNTRY']; ?></td>
                                            <td><?php echo $row1['SENDERRELATION']; ?></td>
                                            <td><?php echo $row1['EXPECTEDAMT']; ?></td>
											<td><?php echo $cpdate; ?></td>
											<td class="text-bold"><b style="font-size:14px;"><?php echo $row1['PAIDAMT']; ?></b></td>
                                            <td><?php echo $row1['STATUS']; ?></td>



                                        </tr>
                                        <?php
                                    }//end of else..
                                    else {
                                        ?>
                                        <tr id="row" class='clickable-row text-default' data-href="remittance_view_detail.php?msg_id=<?php echo $row1['MSGID']; ?>">


                                            <td><?php echo $cdate; ?></td>                    
                                            <td><?php echo $row1['REMITNO']; ?></td>
                                            <td><?php echo $row1['REMITCOMPANY']; ?></td>
                                            <td><?php echo $row1['BRANCHNAME']; ?></td>
                                            <td><?php echo $row1['RECEIVERNAME']; ?></td>
                                            
                                            
                                            <td><?php echo $row1['RECEIVERIDTYPE']; ?></td>
                                       
                                            <td><?php echo $row1['RECEIVERIDNO']; ?></td>
											<td><?php echo $row1['RECEIVERDOB']; ?></td>
                                            
                                            <td><?php echo $row1['RECEIVERCONTACTNO']; ?></td>
                                            <td><?php echo $row1['SENDERNAME']; ?></td>
                                            
                                            <td><?php echo $row1['SENDERCOUNTRY']; ?></td>
                                            <td><?php echo $row1['SENDERRELATION']; ?></td>
                                            <td><?php echo $row1['EXPECTEDAMT']; ?></td>
											<td><?php echo $cpdate; ?></td>
											<td class="text-bold"><b style="font-size:14px;"><?php echo $row1['PAIDAMT']; ?></b></td>
                                            <td><?php echo $row1['STATUS']; ?></td>



                                        </tr>
                                        <?php
                                    }//end of else..
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
				</div>
            </div>
        </div>

    </div><!--end of container-->

    <script>
        $(document).ready(function () {
            $('#myTable').dataTable({
				 "order": [[ 0, "desc" ]]
			});
        });
        $(document).ready(function () {
            $('#myTable1').dataTable({
				 "order": [[ 0, "desc" ]]
			});
        });


        $('tr[data-href]').on("click", function () {
            document.location = $(this).data('href');
        });
    </script>

    <script>

    </script>


    <?php
    include 'footer.php';
    ?>
