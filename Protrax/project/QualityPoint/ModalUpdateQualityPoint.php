<?php 
session_start();
require_once("../../ConfigDB.php");
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleQualityPoint.php");
date_default_timezone_set("Asia/Jakarta");
$DateNow = date("m/d/Y");
$YearNow = date("Y");
if(!session_is_registered("UIDProTrax"))
{
    ?>
    <script language="javascript">
        window.location.replace("https://protrax.formulatrix.com/");
    </script>
    <?php
    exit();
}

if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $ValCodeDec = base64_decode(htmlspecialchars(trim($_POST['ValCode']), ENT_QUOTES, "UTF-8"));
    $NewValCodeEnc = base64_encode($ValCodeDec);
    $ValTempDec = base64_decode(htmlspecialchars(trim($_POST['ValTemp']), ENT_QUOTES, "UTF-8"));
    $ArrCodeDec = explode("*",$ValCodeDec);
    $ArrTempDec = explode("*",$ValTempDec);
    $ValClosedTime = $ArrCodeDec[0];
    $ValQuoteName = $ArrTempDec[0];
    $ValDivisionName = $ArrCodeDec[3];
    $ValTypeModal = $ArrTempDec[2];
    # get data selected
    $QData = GET_DETAIL_MODAL_QP_SELECTED($ArrCodeDec[0],$ArrCodeDec[3],$ArrTempDec[0],$linkMACHWebTrax);
    $RData = mssql_fetch_assoc($QData);
    $ValFormActual = number_format((float)trim($RData['Actual']), 2, '.', ',');
    $ValFormTargetMin = number_format((float)trim($RData['TargetMin']), 2, '.', ',');
    if($ValFormActual == "0.00")
    {
        $ValFormActual = "";
    }
    if($ValFormTargetMin == "0.00")
    {
        $ValFormTargetMin = "";
    }
    $ValFormTargetMax = number_format((float)trim($RData['TargetMax']), 2, '.', ',');
    $ValFormGoalAchievement = number_format((float)trim($RData['GoalAchievement']), 2, '.', ',');
    if($ValFormTargetMax == number_format(0, 2, '.', ','))
    {
        $ValFormTargetMax = 100;
    }
    $BtnDisabled = "";
    if(mssql_num_rows($QData) == "0")
    {
        $BtnDisabled = " disabled";    
    }

?>
<div class="row">
    <div class="col-md-12 mb-3">
        <label for="InputQuote" class="form-label fw-bold">Quote</label>
        <input type="text" class="form-control" id="InputQuote<?php echo $ValTypeModal; ?>" value="<?php echo $ValQuoteName; ?>" readonly>
    </div>
    <div class="col-md-12 mb-3">
        <label for="InputClosedTimeM" class="form-label fw-bold">Closed Time</label>
        <input type="text" class="form-control" id="InputClosedTimeM<?php echo $ValTypeModal; ?>" value="<?php echo $ValClosedTime; ?>" readonly>
    </div>
    <div class="col-md-12 mb-3">
        <label for="InputDivision" class="form-label fw-bold">Division</label>
        <input type="text" class="form-control" id="InputDivision<?php echo $ValTypeModal; ?>" value="<?php echo $ValDivisionName; ?>" readonly>
    </div>
    <div class="col-md-4 mb-3">
        <label for="InputActual" class="form-label fw-bold">Actual (%)</label>
        <input type="text" class="form-control" id="InputActual<?php echo $ValTypeModal; ?>" value="<?php echo $ValFormActual; ?>">
    </div>
    <div class="col-md-4 mb-3">
        <label for="InputTargetMin" class="form-label fw-bold">Target Min (%)</label>
        <input type="text" class="form-control" id="InputTargetMin<?php echo $ValTypeModal; ?>" value="<?php echo $ValFormTargetMin; ?>">
    </div>
    <div class="col-md-4 mb-3">
        <label for="InputTargetMax" class="form-label fw-bold">Target Max (%)</label>
        <input type="text" class="form-control" id="InputTargetMax<?php echo $ValTypeModal; ?>" value="<?php echo $ValFormTargetMax; ?>">
    </div>
    <div class="col-md-4">
        <button type="button" id="BtnDeleteQualityPoint<?php echo $ValTypeModal; ?>" data-cookies="<?php echo $NewValCodeEnc; ?>" class="btn btn-danger btn-labeled w-100"<?php echo $BtnDisabled;?>>Delete Data</button>
    </div>
    <div class="col-md-8">
        <button type="button" id="BtnUpdateQualityPoint<?php echo $ValTypeModal; ?>" data-cookies="<?php echo $NewValCodeEnc; ?>" class="btn btn-dark btn-labeled w-100">Update Data</button>
    </div>
</div>
<?php

}
else
{
    echo "";    
}
?>