<?php
require_once("../../ConfigDB.php");
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModulePeoplePoint.php");
date_default_timezone_set("Asia/Jakarta");
$YearNow = date("Y");
$DateNow = date("m/d/Y");

if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $Code = htmlspecialchars(trim($_POST['ValCode']), ENT_QUOTES, "UTF-8");
    $ValCode = base64_decode($Code);
    $arr = explode("*",$ValCode);
    $Name = $arr[0];
    $Divisi = $arr[1];
    $ClosedTime = $arr[2];
    // echo "$Name,$Divisi,$ClosedTime";
    $Data = GET_EMPLOYEE_PREVIOUS_POINT($Name,$Divisi,$ClosedTime,$linkMACHWebTrax);
    $arrData = explode("*",$Data);
    $PoinAsli = $arrData[0];
    $TotalPoin = $arrData[1];
?>
<h6>Employee Name: <?php echo $Name; ?></h6>
<h6>Divisi: <?php echo $Divisi; ?></h6>
<h6>Half: <?php echo $ClosedTime; ?></h6>
<br>
<div class="col-md-12">
    <div class="form-group">
        <label for="Points" class="form-label fw-bold">Points</label>
        <input type="text" class="form-control form-control-sm" id="Points" value="<?php echo $PoinAsli; ?>" disabled>
    </div>
</div> 
<div class="col-md-12">
    <div class="form-group">
        <label for="Proporsi" class="form-label fw-bold">Production Points (Max 80%)</label>
        <input type="text" class="form-control form-control-sm" id="Proporsi" value="<?php echo $TotalPoin;?>" disabled>
    </div>
</div>
<div class="col-md-12">
    <div class="form-group">
        <label for="Discretion" class="form-label fw-bold">Discretionary (Max 20%)*</label>
        <input type="text" class="form-control form-control-sm" id="Discretion" placeholder="0.00">
    </div>
</div> 
<div class="col-md-12">
    <div class="form-group">
        <label for="Exception" class="form-label fw-bold">Exception (%)*</label>
        <input type="text" class="form-control form-control-sm" id="Exception" placeholder="0.00">
    </div>
</div>
<div class="col-md-12">
    <div class="form-group">
        <label for="Total" class="form-label fw-bold">Total Points</label>
        <input type="text" class="form-control form-control-sm" id="Total" placeholder="0.00" disabled>
    </div><i>*) Press enter to calculate points</i>
</div>

<br>
</div class="col-md-12">
    <button class="btn btn-sm btn-success" id="BtnSave" style="width:100%">SAVE</button>
</div>
<div class="col-md-12" id="SimpanPoint"></div>
<?php
}
?>