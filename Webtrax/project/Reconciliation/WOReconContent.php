
<?php

require_once("../../src/Modules/ModuleLogin.php");
require_once("Module/ModuleRecon.php"); 
date_default_timezone_set("Asia/Jakarta");


if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $Category = htmlspecialchars(trim($_POST['Category']), ENT_QUOTES, "UTF-8");
    $Season = htmlspecialchars(trim($_POST['Season']), ENT_QUOTES, "UTF-8");
    $Type = htmlspecialchars(trim($_POST['Type']), ENT_QUOTES, "UTF-8");
    // echo "$Category >> $Season >> $Type";
    switch ($Type) {
        case 'Time Tracking':
            {
                $Head1 = "WO Labor Time<br>(Hour)";
                $Head2 = "Time Tracking Stabilize<br>(Hour)";
                $Head3 = "Difference* (Hour)";
                $info = "*)Labor Time - Stabilize";
                $Data = GET_WO_TIMETRACK_RECON($Category,$Season,$linkMACHWebTrax);
            }
        break;
        case 'Machine Tracking':
            {
                $Head1 = "WO Machine Time<br>(Hour)";
                $Head2 = "Machine Tracking Stabilize<br>(Hour)";
                $Head3 = "Difference* (Hour)";
                $info = "*)Machine Time - Stabilize";
                $Data = GET_WO_MACHINETRACK_RECON($Category,$Season,$linkMACHWebTrax);
            }
        break;
        case 'Material Tracking':
            {
                $Head1 = "WO Material Cost ($)";
                $Head2 = "Material Track Cost ($)";
                $Head3 = "Difference* ($)";
                $info = "*)WO Material Cost - Material Track Cost";
                $Data = GET_WO_MATERIALTRACK_RECON($Category,$Season,$linkMACHWebTrax);
            }
        break;
    }
?>
<div class="col-md-12"><h5>WO Mapping Recon : <?php echo $Type; ?>, <?php echo $Season; ?></h5></div>
<div class="col-md-12">
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="TableWORecon">
            <span><i><?php echo $info ; ?></i></span>
            <thead class="theadCustom">
                <tr>
                    <th class="text-center trowCustom">Quote</th>
                    <th class="text-center trowCustom"><?php echo $Head1; ?></th>
                    <th class="text-center trowCustom"><?php echo $Head2; ?></th>
                    <th class="text-center trowCustom"><?php echo $Head3; ?></th>
                    <th class="text-center trowCustom">Difference* (%)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $TotalWO = $TotalComp = $TotalDiff = 0;
                while($Datares=sqlsrv_fetch_array($Data))
                {
                    $Quote = trim($Datares['Quote']);
                    $FromWO = trim($Datares['FromWO']);
                    $Components = trim($Datares['Components']);
                    $Diff = ($FromWO-$Components);
                    $DiffPercent= ($Diff/$FromWO)*100;
                    $TotalWO = $TotalWO + $FromWO;
                    $TotalComp = $TotalComp + $Components;
                    $TotalDiff = $TotalDiff + $Diff;
                    $Components = number_format((float)$Components,2,'.',',');
                    $FromWO = number_format((float)$FromWO,2,'.',',');
                    $Diff = number_format((float)$Diff,2,'.',',');
                    $DiffPercent = number_format((float)$DiffPercent,2,'.',',');
                ?>
                <tr>
                    <td class="text-left"><?php echo $Quote; ?></td>
                    <td class="text-right"><?php echo $FromWO; ?></td>
                    <td class="text-right"><?php echo $Components; ?></td>
                    <td class="text-right"><?php echo $Diff; ?></td>
                    <td class="text-right"><?php echo $DiffPercent; ?></td>
                </tr>
                <?php
                }
                    $TotalDiffPercent = ($TotalDiff/$TotalWO)*100;
                    $TotalWO = number_format((float)$TotalWO,2,'.',',');
                    $TotalComp = number_format((float)$TotalComp,2,'.',',');
                    $TotalDiff = number_format((float)$TotalDiff,2,'.',',');
                    $TotalDiffPercent = number_format((float)$TotalDiffPercent,2,'.',',');
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <td class="text-center"><strong>TOTAL</strong></td>
                    <td class="text-right"><strong><?php echo $TotalWO; ?></strong></td>
                    <td class="text-right"><strong><?php echo $TotalComp; ?></strong></td>
                    <td class="text-right"><strong><?php echo $TotalDiff; ?></strong></td>
                    <td class="text-right"><strong><?php echo $TotalDiffPercent; ?></strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<?php
}
?>