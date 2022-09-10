<?php 
session_start();
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleQualityPoint.php");
date_default_timezone_set("Asia/Jakarta");
$DateNow = date("m/d/Y");
$YearNow = date("Y");
if(!session_is_registered("UIDWebTrax"))
{
    ?>
    <script language="javascript">
        window.location.replace("https://webtrax.formulatrix.com/");
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
?>
<div class="row">
    <div class=col-md-12>
        <div class="form-group">
            <label for="InputQuote">Quote</label>
            <input type="text" class="form-control" id="InputQuote" value="<?php echo $ValQuoteName; ?>" readonly>
        </div>
    </div>
    <div class=col-md-12>
        <div class="form-group">
            <label for="InputClosedTimeM">Closed Time</label>
            <input type="text" class="form-control" id="InputClosedTimeM" value="<?php echo $ValClosedTime; ?>" readonly>
        </div>
    </div>
    <div class=col-md-12>
        <div class="form-group">
            <label for="InputDivision">Division</label>
            <input type="text" class="form-control" id="InputDivision" value="<?php echo $ValDivisionName; ?>" readonly>
        </div>
    </div>
    <div class=col-md-6>
        <div class="form-group">
            <label for="InputActual">Actual (%)</label>
            <input type="text" class="form-control" id="InputActual" value="">
        </div>
    </div>
    <div class=col-md-6>
        <div class="form-group">
            <label for="InputTargetMin">Target Min (%)</label>
            <input type="text" class="form-control" id="InputTargetMin" value="">
        </div>
    </div>
    <div class=col-md-12>
        <button type="button" id="BtnUpdateQualityPoint" data-cookies="<?php echo $NewValCodeEnc; ?>" class="btn btn-dark btn-labeled btn-block">Update Data</button>
    </div>
</div>
<?php

}
else
{
    echo "";    
}
?>