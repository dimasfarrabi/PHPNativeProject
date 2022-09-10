<?php 
session_start();
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleBarcodePart.php");



if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $ValFloat = htmlspecialchars(trim($_POST['ValFloat']), ENT_QUOTES, "UTF-8");
    $ValFloat = base64_decode(base64_decode($ValFloat));
    $ArrValFloat = explode("#",$ValFloat);
    $WOP = $ArrValFloat[0];
    $Stage = $ArrValFloat[1];
    $ClosedTime = $ArrValFloat[2];
    $Expense = $ArrValFloat[3];
    if($Stage == 'NOT PRINTED YET'){ $Stage = "CREATED"; $StageX = "CREATED";}
    elseif($Stage == 'WAITING FOR QC1'){ $StageX = "MACHINING OUT";}
    elseif($Stage == 'WAITING FOR FINISHING'){ $StageX = "QC1 OUT";}
    elseif($Stage == 'WAITING FOR QC2'){ $StageX = "FINISHING OUT";}
    else{ $StageX = $Stage; }
?>
<br>
<div class="col-md-12"><h5><strong>WO: <?php echo $WOP; ?>, Barcode List On Stage : <?php echo $Stage; ?></strong></h5></div>
<div class="col-md-12">
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="ListDetailTable">
            <thead class="theadCustom">
                <tr>
                    <th width="50" class="text-center trowCustom">No</th>
                    <th class="text-center trowCustom">Barcode ID</th>
                    <th class="text-center trowCustom">PPIC</th>
                    <th class="text-center trowCustom">PartNo</th>
                    <th class="text-center trowCustom">Qty Initial</th>
                    <th class="text-center trowCustom">Finishing Code</th>
                    <th class="text-center trowCustom">DATE <?php echo $StageX; ?></th>
                </tr>
            </thead>
            <?php
                $NO=1;
                $Data = GET_DETAIL_BARCODE_LIST($ClosedTime,$WOP,$Expense,$Stage,$linkMACHWebTrax);
                while($DataRes = sqlsrv_fetch_array($Data))
                {
                    $ValCode = trim($DataRes['Barcode_ID']);
                    $ValPPIC = trim($DataRes['PPIC']);
                    $ValPartNo = trim($DataRes['PartNo']);
                    $ValQtyInitial = trim ($DataRes['QtyInitial']);
                    $ValFinishCode = trim($DataRes['FinishingCode']);
                    $ValDate = trim($DataRes['Dates']);
            ?>
                <tr>
                    <td class="text-center"><?php echo $NO; ?></td>
                    <td class="text-center"><?php echo $ValCode; ?></td>
                    <td class="text-left"><?php echo $ValPPIC; ?></td>
                    <td class="text-left"><?php echo $ValPartNo; ?></td>
                    <td class="text-right"><?php echo $ValQtyInitial; ?></td>
                    <td class="text-left"><?php echo $ValFinishCode; ?></td>
                    <td class="text-center"><?php echo $ValDate; ?></td>
                </tr>
            <?php
                $NO++;
                }
            ?>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
<?php
}
?>
<script>
$('#ListDetailTable').DataTable( {
    "iDisplayLength": 10,
    "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
    scrollCollapse: true,
    autoWidth: true
});
</script>