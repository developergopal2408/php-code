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
$staffid = $_SESSION['STAFFID'];
$fname = $_SESSION['firstname'];
$lname = $_SESSION['lastname'];
$uname = "$fname $lname";
include_once 'nav.php';

$query = "SELECT * FROM remittance WHERE STAFFID = '$staffid' ORDER BY TDATE desc";
$run = mysqli_query($con, $query);


?>
<body style="margin-top:70px;">
    <div class="container-fluid" style="padding:10px; ">
        <div class="row">
            <div class="col-lg-12">	
                <div class="responsive-table">
                    <table id="myTable1" class="table table-striped  table-bordered dataTable table-condensed table-hover" style="font-size: 10px; ">

                        <div class="form-group pull-right" style="margin-right:10px;">
                            <button id="excel" class="btn btn-sm btn-success" href="#" onClick ="$('#myTable1').tableExport({type: 'excel', escape: 'false'});">XLS</button>
                        </div>
                        <thead>  
                            <tr>
                               
                                <th>Remit No</th>
                                <th>Remit Company</th>
                                <th>From Branch</th>
                                <th>Receiver Name</th>
                               
                                <th>District</th>
                                <th>ID TYPE</th>
                          
                                <th>ID NO</th>
                                <th>DOB</th>
                                <th>Receiver Contact No</th>
                                <th>Sender Name</th>
                                <th>Sender Contact No</th>
                                <th>Country</th>
                                <th>Sender Relation</th>
                                <th>Expected Amount</th>
                                <th>Status</th>
                            </tr> 
                        </thead>  
                        <tbody>   <?php
                            if (!$run or mysqli_num_rows($run) > 0) {
                                while ($row1 = mysqli_fetch_array($run)) {
                                    if($row1['STATUS'] == 'PENDING'){
                                        
                                    
                                    ?>  

                                    <tr id="row" class='clickable-row text-danger' data-href="remittance_view_detail.php?msg_id=<?php echo $row1['MSGID']; ?>">


                                        
                                        <td><?php echo $row1['REMITNO']; ?></td>
                                        <td><?php echo $row1['REMITCOMPANY']; ?></td>
                                        <td><?php echo $row1['BRANCHNAME']; ?></td>
                                        <td><?php echo $row1['RECEIVERNAME']; ?></td>
                                  
                                        <td><?php echo $row1['RECEIVERDISTRICT']; ?></td>
                                        <td><?php echo $row1['RECEIVERIDTYPE']; ?></td>
                                       
                                        <td><?php echo $row1['RECEIVERIDNO']; ?></td>
                                        <td><?php echo $row1['RECEIVERDOB']; ?></td>
                                        <td><?php echo $row1['RECEIVERCONTACTNO']; ?></td>
                                        <td><?php echo $row1['SENDERNAME']; ?></td>
                                        <td><?php echo $row1['SENDERCONTACTNO']; ?></td>
                                        <td><?php echo $row1['SENDERCOUNTRY']; ?></td>
                                        <td><?php echo $row1['SENDERRELATION']; ?></td>
                                        <td><?php echo $row1['EXPECTEDAMT']; ?></td>
                                        <td><?php echo $row1['STATUS']; ?></td>



                                    </tr>

                                    <?php
                                }else{?>
                                     <tr id="row" class='clickable-row text-primary' data-href="remittance_view_detail.php?msg_id=<?php echo $row1['MSGID']; ?>">


                                        
                                        <td><?php echo $row1['REMITNO']; ?></td>
                                        <td><?php echo $row1['REMITCOMPANY']; ?></td>
                                        <td><?php echo $row1['BRANCHNAME']; ?></td>
                                        <td><?php echo $row1['RECEIVERNAME']; ?></td>
                                        
                                        <td><?php echo $row1['RECEIVERDISTRICT']; ?></td>
                                        <td><?php echo $row1['RECEIVERIDTYPE']; ?></td>
                                        <!--<td><?php //echo $row1['RECEIVERIDISSUEDATE']; ?></td>--><td><?php echo $row1['RECEIVERIDNO']; ?></td>
                                        <td><?php echo $row1['RECEIVERDOB']; ?></td>
                                        <td><?php echo $row1['RECEIVERCONTACTNO']; ?></td>
                                        <td><?php echo $row1['SENDERNAME']; ?></td>
                                        <td><?php echo $row1['SENDERCONTACTNO']; ?></td>
                                        <td><?php echo $row1['SENDERCOUNTRY']; ?></td>
                                        <td><?php echo $row1['SENDERRELATION']; ?></td>
                                        <td><?php echo $row1['EXPECTEDAMT']; ?></td>
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

    </div><!--end of container-->

    <script>
        $(document).ready(function () {
            $('#myTable').dataTable();
        });
        $(document).ready(function () {
            $('#myTable1').dataTable();
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
