<?php
include_once 'top.php';
if ($_POST['cid']) {
    ?>
    <select name="mid" id="mid" class="form-control select2" >
        <option value="">Select Member</option>
        <?php
        $sql1 = "SELECT * FROM member where officeid = '" . $_SESSION['BranchID'] . "' AND STATUS = 'ACTIVE' AND CenterID = '" . $_POST['cid'] . "' ORDER BY MemberID ASC ";
        $result = odbc_exec($connection, $sql1);
        while ($rows = odbc_fetch_array($result)) {
            ?>
            <option  value="<?php echo $rows['MemberID']; ?>" <?php
            if ($rows['MemberID'] == $_POST['mid']) {
                echo "selected";
            }
            ?>><?php echo $rows['MemberID'] . " - " . $rows['MemberCode'] . " - " . $rows['FirstName'] . "  " . $rows['LastName']; ?></option>

            <?php
        }
        ?>
        <select>
            <?php
        }
        ?>

        <script>

            $(document).ready(function () {
                $('#mid').select2();
            });
        </script>
