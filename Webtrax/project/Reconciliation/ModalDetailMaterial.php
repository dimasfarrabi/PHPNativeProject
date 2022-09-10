<?php
require_once("../../src/Modules/ModuleLogin.php");
require_once("Module/ModuleRecon.php"); 
date_default_timezone_set("Asia/Jakarta");

if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $ValCodeDec = base64_decode(htmlspecialchars(trim($_POST['ValCode']), ENT_QUOTES, "UTF-8"));
    $NewValCodeEnc = base64_encode($ValCodeDec);
    $ArrCodeDec = explode("*",$ValCodeDec);
    $PartNo = $ArrCodeDec[0];
    $Date1 = $ArrCodeDec[1];
    $Date2 = $ArrCodeDec[2];
    $PartDesc = $ArrCodeDec[3];

?>

<div class="col-md-12"><h5><strong>Part Number :<?php echo $PartNo." : ".$Date1." - ".$Date2;?><br>
Part Description :<?php echo $PartDesc; ?></strong></h5></div>
<br>
<div class="col-md-6">
<div><h5><strong>Tiga Warehouse</strong></h5></div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="">
            <thead class="theadCustom">
                <tr>
                    <th class="text-center trowCustom">Date</th>
                    <th class="text-center trowCustom">Part Number</th>
                    <th class="text-center trowCustom">Job ID</th>
                    <th class="text-center trowCustom">Sequence ID</th>
                    <th class="text-center trowCustom">Qty Issued</th>
                    <th class="text-center trowCustom">Location</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $TotalMaterialIssued=0;
                $UOM2="";
                    $QList2 = GET_MATERIAL_DETAIL($Date1,$Date2,$PartNo,$linkMACHWebTrax);
                    while($RList2 = sqlsrv_fetch_array($QList2))
                    {
                        $Date = trim($RList2['TigaDate']);
                        $PartNo = trim($RList2['PartNo']);
                        $TigaQty = trim($RList2['TigaQty']);
                        $JobID = trim($RList2['JobID']);
                        $SequenceID = trim($RList2['SequenceID']);
                        $LocCode = trim($RList2['LocCode']);
                        $TotalMaterialIssued = $TotalMaterialIssued + $TigaQty;
                        $TigaQty = number_format((float)$TigaQty,2,'.',',');
                ?>
                    <tr>
                        <td class="text-center"><?php echo $Date; ?></td>
                        <td class="text-center"><?php echo $PartNo; ?></td>
                        <td class="text-left"><?php echo $JobID; ?></td>
                        <td class="text-left"><?php echo $SequenceID; ?></td>
                        <td class="text-center"><?php echo $TigaQty; ?></td>
                        <td class="text-center"><?php echo $LocCode; ?></td>
                    </tr>
                <?php
                
                    }
                    $TotalMaterialIssued = number_format((float)$TotalMaterialIssued,2,'.',',');
                ?>
            </tbody>
            <tfoot>
                    <tr>
                        <td class="text-center" colspan="4">TOTAL</td>
                        <td class="text-center"><?php echo $TotalMaterialIssued; ?></td>
                        <td class="text-center"></td>
                    </tr>
            </tfoot>
        </table>
    </div>
</div>
<div class="col-md-6">
<div><h5><strong>Material Tracking</strong></h5></div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="">
            <thead class="theadCustom">
                <tr>
                    <th class="text-center trowCustom">Date</th>
                    <th class="text-center trowCustom">Part Number</th>
                    <th class="text-center trowCustom">Work Order</th>
                    <th class="text-center trowCustom">TLI ID</th>
                    <th class="text-center trowCustom">Qty Usage</th>
                    <th class="text-center trowCustom">Location</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $TotalMaterialUsage=0;
                    $UOM="";
                    $QList = GET_MATERIAL_DETAIL($Date1,$Date2,$PartNo,$linkMACHWebTrax);
                    while($RList = sqlsrv_fetch_array($QList))
                    {
                        $Tanggal = trim($RList['DateCreate']);
                        $PartNum = trim($RList['PartNo']);
                        $WO = trim($RList['WorkOrder']);
                        $TBZ_ID = trim($RList['TBZ_ID']);
                        $Qty = trim($RList['Qty']);
                        $LocationCode = trim($RList['LocationCode']);

                        $TotalMaterialUsage = $TotalMaterialUsage + $Qty;
                        $Qty = number_format((float)$Qty,2,'.',',');
                ?>
                    <tr>
                        <td class="text-center"><?php echo $Tanggal; ?></td>
                        <td class="text-center"><?php echo $PartNum; ?></td>
                        <td class="text-left"><?php echo $WO; ?></td>
                        <td class="text-left"><?php echo $TBZ_ID; ?></td>
                        <td class="text-center"><?php echo $Qty; ?></td>
                        <td class="text-center"><?php echo $LocationCode; ?></td>
                    </tr>
                <?php
                    }
                    $TotalMaterialUsage = number_format((float)$TotalMaterialUsage,2,'.',',');
                ?>
            </tbody>
            <tfoot>
                    <tr>
                        <td class="text-center" colspan="4">TOTAL</td>
                        <td class="text-center"><?php echo $TotalMaterialUsage; ?></td>
                        <td class="text-center"></td>
                    </tr>
            </tfoot>
        </table>
    </div>
</div>
<?php

}
?>