<?php 
session_start();
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleQualityPoint.php");
date_default_timezone_set("Asia/Jakarta");
$DateNow = date("m/d/Y");
$YearNow = date("Y");
/*
if(!session_is_registered("UIDProTrax"))
{
    ?>
    <script language="javascript">
        window.location.replace("https://protrax.formulatrix.com/");
    </script>
    <?php
    exit();
}
*/
if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $ValCookies = base64_decode(htmlspecialchars(trim($_POST['ValData']), ENT_QUOTES, "UTF-8"));
    $ArrValIDEnc = explode("*",$ValCookies);
    $ValClosedTime = $ArrValIDEnc[0];
    $ValQuote = $ArrValIDEnc[1];
    $ValDivision = $ArrValIDEnc[3];
    $ValLocation = $ArrValIDEnc[4];
    
    # set data
    $ArrDataDetail = array();
    $QData = DETAIL_CONTENT_ACTUAL($ValQuote,$ValClosedTime,$ValDivision,$linkMACHWebTrax);
    while($RData = sqlsrv_fetch_array($QData))
    {
        $ArrTemp = array(
            "WOMapping_ID" => trim($RData['WOMapping_ID']),
            "WOChild" => trim($RData['WOChild']),
            "Quote" => trim($RData['Quote']),
            "QuoteCategory" => trim($RData['QuoteCategory']),
            "QtyIn_QC1" => trim($RData['QtyIn_QC1']),
            "QtyIn_QC2" => trim($RData['QtyIn_QC2']),
            "QtyOut_QC1" => trim($RData['QtyOut_QC1']),
            "QtyOut_QC2" => trim($RData['QtyOut_QC2']),                
        );
        array_push($ArrDataDetail,$ArrTemp);
    }   
    ?>
<div class="col-md-12"><strong>Cost Allocation : <?php echo $ValDivision; ?></strong></div>
<div class="col-md-12">
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="TableDetail">
            <thead class="theadCustom">
                <tr>
                    <th class="text-center trowCustom" width="40">No</th>
                    <th class="text-center trowCustom">WOC</th>
                    <th class="text-center trowCustom" width="100">QC1 IN</th>
                    <th class="text-center trowCustom" width="100">QC1 OUT</th>
                    <th class="text-center trowCustom" width="100">QC2 IN</th>
                    <th class="text-center trowCustom" width="100">QC2 OUT</th>
                    <th class="text-center trowCustom" width="100">FPY QC1</th>
                    <th class="text-center trowCustom" width="100">FPY QC2</th>
                    <th class="text-center trowCustom" width="100">Total FPY</th>
                </tr>
            </thead>
            <tbody><?php 
            $No = 1;
            foreach($ArrDataDetail as $DataDetail)
            {
                if($DataDetail['QtyIn_QC1'] != 0)
                {
                    $ValFPYQC1 = floatval($DataDetail['QtyOut_QC1']) / floatval($DataDetail['QtyIn_QC1']);
                    if($ValFPYQC1 < 0){$ValFPYQC1 = 0;}
                    if(!is_numeric($ValFPYQC1)){$ValFPYQC1 = 0;}
                }
                else
                {
                    $ValFPYQC1 = 0;
                }
                if($DataDetail['QtyIn_QC2'] != 0)
                {
                    $ValFPYQC2 = floatval($DataDetail['QtyOut_QC2']) / floatval($DataDetail['QtyIn_QC2']);
                    if($ValFPYQC2 < 0){$ValFPYQC2 = 0;}
                    if(!is_numeric($ValFPYQC2)){$ValFPYQC2 = 0;}
                }
                else
                {
                    $ValFPYQC2 = 0;
                }
                $TotalFPC = (floatval($DataDetail['QtyOut_QC1']) + floatval($DataDetail['QtyOut_QC2']) ) / (floatval($DataDetail['QtyIn_QC1']) + floatval($DataDetail['QtyIn_QC2']));
                $ValFPYQC1 = number_format((float)trim(($ValFPYQC1 * 100)), 2, '.', ',');
                $ValFPYQC2 = number_format((float)trim(($ValFPYQC2 * 100)), 2, '.', ',');                    
                $TotalFPC = number_format((float)trim(($TotalFPC * 100)), 2, '.', ','); 
                ?>
                <tr>
                    <td class="text-center"><?php echo $No; ?></td>
                    <td class="text-start"><?php echo $DataDetail['WOChild']; ?></td>
                    <td class="text-end"><?php echo $DataDetail['QtyIn_QC1']; ?></td>
                    <td class="text-end"><?php echo $DataDetail['QtyOut_QC1']; ?></td>
                    <td class="text-end"><?php echo $DataDetail['QtyIn_QC2']; ?></td>
                    <td class="text-end"><?php echo $DataDetail['QtyOut_QC2']; ?></td>
                    <td class="text-end"><?php echo $ValFPYQC1; ?></td>
                    <td class="text-end"><?php echo $ValFPYQC2; ?></td>
                    <td class="text-end"><?php echo $TotalFPC; ?></td>
                </tr>
                <?php 
                $No++;
            }
            ?></tbody>
        </table>
    </div>
</div>
    <?php
}
else
{
    echo "";    
}
?>