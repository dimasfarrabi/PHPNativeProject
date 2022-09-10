<?php 
session_start();
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleWIPSims.php");
date_default_timezone_set("Asia/Jakarta");

$Date1 = date("m/d/Y",strtotime("-1 day"));
$Date1ID = "".date("m/d/",strtotime($Date1))."".substr(date("Y",strtotime($Date1)),-2);
$Date2 = date("m/d/Y",strtotime("-2 day"));
$Date2ID = "".date("m/d/",strtotime($Date2))."".substr(date("Y",strtotime($Date2)),-2);    
$Date3 = date("m/d/Y",strtotime("-3 day"));
$Date3ID = "".date("m/d/",strtotime($Date3))."".substr(date("Y",strtotime($Date3)),-2);
$Date4 = date("m/d/Y",strtotime("-4 day"));
$Date4ID = "".date("m/d/",strtotime($Date4))."".substr(date("Y",strtotime($Date4)),-2);



if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $ValQuoteCategorySelected = htmlspecialchars(trim($_POST['ValQuoteCategorySelected']), ENT_QUOTES, "UTF-8");
    $ValQuoteName = htmlspecialchars(trim($_POST['ValQuoteName']), ENT_QUOTES, "UTF-8");
    # data project
    $QDataProject = GET_DATA_PROJECT_BY_NAME($ValQuoteCategorySelected,$ValQuoteName,$linkMACHWebTrax);
    $RDataProject = sqlsrv_fetch_array($QDataProject);
    $ValProjectID = trim($RDataProject['Idx']);
  
?>
<script src="project/wipsims/lib/libwipoutputcontent.js?no=<?php echo base64_encode(date("mdyHis")); ?>"></script>
<style>.TimeLog{cursor: pointer;color: #337AB7;}</style>
<div class="col-md-12">
    <div class="form-inline">
            <label for="InputDataTime">Data Filter</label>&nbsp;
            <label class="radio-inline">
                <input type="radio" class="radio-inline-custom RadioFilter" name="RadioFilter" id="RadioFilter1" value="Daily" checked>Daily
            </label>
            <label class="radio-inline">
                <input type="radio" class="radio-inline-custom RadioFilter" name="RadioFilter" id="RadioFilter2" value="Weekly">Weekly
            </label>
            <label class="radio-inline">
                <input type="radio" class="radio-inline-custom RadioFilter" name="RadioFilter" id="RadioFilter3" value="Monthly">Monthly
            </label>
            <label class="radio-inline">
                <input type="radio" class="radio-inline-custom RadioFilter" name="RadioFilter" id="RadioFilter4" value="Half">Half
            </label>
            <label class="radio-inline">
                <input type="radio" class="radio-inline-custom RadioFilter" name="RadioFilter" id="RadioFilter5" value="Year">Year
            </label>          
        </div>
    </div>
<div class="col-md-12">&nbsp;</div>
<div id="ContentDataFilter">
<div class="col-md-12">
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="TableQuoteSelected">
            <thead class="theadCustom">
                <tr>
                    <td class="text-center trowCustom" width="30"><strong>No</strong></td>
                    <td class="text-center trowCustom" width="100"><strong>PartNo</strong></td>
                    <td class="text-center trowCustom"><strong>Part Description</strong></td>
                    <td class="text-center trowCustom" width="110"><strong>Qty Output<br>(<?php echo $Date4ID;?>)</strong></td>
                    <td class="text-center trowCustom" width="110"><strong>Qty Output<br>(<?php echo $Date3ID;?>)</strong></td>
                    <td class="text-center trowCustom" width="110"><strong>Qty Output<br>(<?php echo $Date2ID;?>)</strong></td>
                    <td class="text-center trowCustom" width="110"><strong>Qty Output<br>(<?php echo $Date1ID;?>)</strong></td>
                </tr>
            </thead>
            <tbody><?php
            $No = 1;
            $QData = GET_DATA_OUTPUT_DAILY($ValProjectID,$Date4,$Date3,$Date2,$Date1,$linkMACHWebTrax);
            while($RData = sqlsrv_fetch_array($QData))
            {
                $ValPartNo = trim($RData['PartNo']);
                $ValPartDesc = htmlspecialchars_decode(trim($RData['PartDesc']), ENT_QUOTES);
                $ValQtyOutput1 = trim($RData['QtyOutput1']);
                $ValQtyOutput1 = number_format((float)$ValQtyOutput1, 0, '.', ',');
                $ValQtyOutput2 = trim($RData['QtyOutput2']);
                $ValQtyOutput2 = number_format((float)$ValQtyOutput2, 0, '.', ',');
                $ValQtyOutput3 = trim($RData['QtyOutput3']);
                $ValQtyOutput3 = number_format((float)$ValQtyOutput3, 0, '.', ',');
                $ValQtyOutput4 = trim($RData['QtyOutput4']);
                $ValQtyOutput4 = number_format((float)$ValQtyOutput4, 0, '.', ',');
                ?>
                <tr id="<?php echo "row-".$No; ?>">
                    <td class="text-center"><?php echo $No; ?></td>
                    <td class="text-center"><?php echo $ValPartNo; ?></td>
                    <td class="text-left"><?php echo $ValPartDesc; ?></td>
                    <td class="text-center TimeLog"><?php echo $ValQtyOutput1; ?></td>
                    <td class="text-center TimeLog"><?php echo $ValQtyOutput2; ?></td>
                    <td class="text-center TimeLog"><?php echo $ValQtyOutput3; ?></td>
                    <td class="text-center TimeLog"><?php echo $ValQtyOutput4; ?></td>
                </tr>
                <?php
                $No++;
            }
            ?></tbody>
        </table>
    </div>
</div>
</div>
    <?php
}
else
{
    echo "";    
}
?>