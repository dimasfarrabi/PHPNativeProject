<?php
session_start();
require_once("../../ConfigDB.php");
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleReport.php");
date_default_timezone_set("Asia/Jakarta");
$YearNow = date("Y");
$DateNow = date("m/d/Y");


if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $ValCode = htmlspecialchars(trim($_POST['ValCode']), ENT_QUOTES, "UTF-8");
    $Barcode = base64_decode($ValCode);
    // echo $Barcode;
?>
<h6>Barcode ID : <strong><?php echo $Barcode; ?></strong></h6>
<br>
<h7><strong>Start & End Machining By Operator</strong></h7>
<div class="table-responsive">
    <table class="table table-responsive table-bordered table-hover display" id="">
        <thead class="theadCustom">
            <tr>
                <th class="text-center">Operator</th>
                <th class="text-center">Machine</th>
                <th class="text-center">Start Machining</th>
                <th class="text-center">End Machining</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $data = GET_START_END_MACHINE($Barcode,$linkMACHWebTrax);
            while($res=sqlsrv_fetch_array($data))
            {
                $ValOperator = trim($res['Operator']);
                $MachineName = trim($res['MachineName']);
                $FullStart = trim($res['FullStart']);
                $FullEnd = trim($res['FullEnd']);
            ?>
            <tr>
                <td class="text-left"><?php echo $ValOperator; ?></td>
                <td class="text-left"><?php echo $MachineName; ?></td>
                <td class="text-center"><?php echo $FullStart; ?></td>
                <td class="text-center"><?php echo $FullEnd; ?></td>
            </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>
<br>
<h7><strong>QC History</strong></h7>
<div class="table-responsive">
    <table class="table table-responsive table-bordered table-hover display" id="">
        <thead class="theadCustom">
            <tr>
                <th class="text-center">Emloyee in charge</th>
                <th class="text-center">Date Record</th>
                <th class="text-center">QC1 Status</th>
                <th class="text-center">QC2 Status</th>
                <th class="text-center">Notes</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $data2 = GET_BARCODE_QC_HISTORY($Barcode,$linkMACHWebTrax);
            while($res2=sqlsrv_fetch_array($data2))
            {
                $ValEmployee = trim($res2['Username']);
                $DateRecord = trim($res2['Date']);
                $QC1Status = trim($res2['StatusQC1']);
                $QC2Status = trim($res2['StatusQC2']);
                $Notes = trim($res2['Notes']);
            ?>
            <tr>
                <td class="text-left"><?php echo $ValEmployee; ?></td>
                <td class="text-left"><?php echo $DateRecord; ?></td>
                <td class="text-center"><?php echo $QC1Status; ?></td>
                <td class="text-center"><?php echo $QC2Status; ?></td>
                <td class="text-left"><?php echo $Notes; ?></td>
            </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>
<?php
}
?>
