<?php
include_once 'top.php';
$branchid = $_REQUEST['branchid'];
if ($branchid AND $_SESSION['BranchID'] == 1) {
    $oid = "officeid='$branchid'";
    $ido = "o.id='$branchid'";
} else if ($_SESSION['BranchID'] > 1) {
    $oid = "officeid='" . $_SESSION['BranchID'] . "'";
    $ido = "o.id='" . $_SESSION['BranchID'] . "'";
}

$qry = "select o.id,
(select count(distinct Vdcid) from centermain where o.id=officeid and active='Y' and Formeddate<='$sdate'and vdcid in(select vdcid from vdc where ismun='4')and $oid)Rmunpre,
(select count(distinct Vdcid) from centermain where o.id=officeid and active='Y' and Formeddate<='$cdate'and vdcid in(select vdcid from vdc where ismun='4')and $oid)Rmuntill,
(select count(distinct Vdcid) from centermain where o.id=officeid and active='Y' and Formeddate<='$sdate'and vdcid in(select vdcid from vdc where ismun <>'4')and $oid)Munpre,
(select count(distinct Vdcid) from centermain where o.id=officeid and active='Y' and Formeddate<='$cdate'and vdcid in(select vdcid from vdc where ismun <>'4')and $oid)MunTill,
(select count( centerid) from centermain where o.id=officeid and active='Y' and Formeddate<='$cdate'and $oid)cenpre, 
(select count( centerid) from centermain where o.id=officeid and active='Y' and Formeddate<='$cdate'and $oid)cenTill,
(select count( memberid) from Member where o.id=officeid and status='Active' and Regdate<='$sdate'and $oid)actMempre,
(select count( memberid) from Member where o.id=officeid and status='Dropout' and Regdate<='$sdate' and Dropoutdate>'$sdate'and $oid)act_pre_Mem,
(select count( memberid) from member where o.id=officeid and status='Active' and Regdate<='$cdate'and $oid)MemTill,
(select count( memberid) from Member where o.id=officeid and status='Dropout' and Regdate<='$cdate' and Dropoutdate>'$cdate'and $oid)act_till_Mem,
(select count( memberid) from Member where o.id=officeid and status='active'and Gender='Male' and Regdate<='$sdate'and $oid)actmalepre,
(select count( memberid) from Member where o.id=officeid and status='Dropout' and Gender='Male'and Regdate<='$sdate' and Dropoutdate>'$sdate'and $oid)act_pre_male,
(select count( memberid) from member where o.id=officeid and status='active'and Gender='Male' and Regdate<='$cdate'and $oid)MaleTill,
(select count( memberid) from Member where o.id=officeid and status='Dropout'and Gender='Male' and Regdate<='$cdate' and Dropoutdate>'$cdate'and $oid)act_till_Male,
(select count( memberid) from member where o.id=officeid and status='Passive' and Regdate<='$sdate'and $oid)passPre,
(select count( memberid) from member where o.id=officeid and status='Passive' and Regdate<='$cdate'and $oid)passtill,
(select count( memberid) from Member where o.id=officeid and status='Dropout' and Regdate<='$sdate' and Dropoutdate<='$sdate'and $oid)dro_pre_Mem,
(select count( memberid) from Member where o.id=officeid and status='Dropout' and Regdate<='$cdate' and Dropoutdate<='$cdate'and $oid)dro_till_Mem
from officedetail o
where $ido
group by o.id";

$exe = sqlsrv_query($connection, $qry) or die(print_r(sqlsrv_errors(), true));
while ($run = sqlsrv_fetch_array($exe)) {
    $totalrmun = $run['Rmuntill'] - $run['Rmunpre'];
    $totalmun = $run['MunTill'] - $run['Munpre'];
    $totalcen = $run['cenTill'] - $run['cenpre'];
    $totalact = $run['actMempre'] - $run['act_pre_Mem'];
    ?>
    <tr class = "bg-gray">
        <td class = "text-bold">B</td>
        <td class = "text-bold">Program Expansion</td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td>1</td>
        <td>Rural Municipality</td>
        <td><?php echo $run['Rmunpre']; ?></td>
        <td><?php echo $totalrmun; ?></td>
        <td><?php echo $run['Rmuntill']; ?></td>
    </tr>
    <tr>
        <td>2</td>
        <td>Municipality</td>
        <td><?php echo $run['Munpre']; ?></td>
        <td><?php echo $totalmun; ?></td>
        <td><?php echo $run['MunTill']; ?></td>
    </tr>
    <tr>
        <td>3</td>
        <td>No. Of Center</td>
        <td><?php echo $run['cenpre']; ?></td>
        <td><?php echo $totalcen; ?></td>
        <td><?php echo $run['cenTill']; ?></td>
    </tr>
    <tr>
        <td>4</td>
        <td>No. Of Group</td>
        <td><?php echo $run['act_pre_Mem']; ?></td>
        <td><?php echo $totalcen; ?></td>
        <td><?php echo $run['actMempre']; ?></td>
    </tr>
    <tr>
        <td>5</td>
        <td>No. Of Total Member</td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td>6</td>
        <td>No. Of Total Active Member</td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td>7</td>
        <td>No. Of Male Active Member</td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td>8</td>
        <td>No. Of Total Passive Member</td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td>9</td>
        <td>No. Of Male Passive Member</td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td>10</td>
        <td>No. Of Borrowers</td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td>11</td>
        <td>No. Of Dropout Member</td>
        <td></td>
        <td></td>
        <td></td>
    </tr>

    <?php
}
?>


