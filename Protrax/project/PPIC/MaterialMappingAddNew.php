<?php
require_once("../../ConfigDB.php");
require_once("Modules/ModuleNewBCPartJob.php");
if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $ValCodeDec = htmlspecialchars(trim($_POST['ValCode']), ENT_QUOTES, "UTF-8");
    $ArrCodeDec = explode("*",$ValCodeDec);
    $Machine = $ArrCodeDec[0];
    $MachineCode = $ArrCodeDec[1];
    // echo "$Machine >> $MachineCode";
?>
<div class="col-md-12"><h6>Machine : <strong><?php echo $Machine; ?></strong></h6></div>
<div class="col-md-12">
    <div class="row">
        <div class="col-md-9">
            <select class="form-select" id="selectQuote" style="margin-top:30px" placeholder="Select Quote">
            <option disabled selected>Select Quote</option>
            <?php
                    $QListMach = GET_OPEN_QUOTE($linkMACHWebTrax);
                    while($RListMach = sqlsrv_fetch_array($QListMach))
                    {
                    $Quote = trim($RListMach['Quote']);
                    ?>
                    <option><?php echo $Quote; ?></option>
                    <?php
                    }                
                    ?>
            </select>
        </div>
        <div class="col-md-3">
            <button id="ShowWO" type="button" class="btn btn-dark btn-labeled block btn-sm" style="margin-top:30px">Search WO</button>
        </div>
    </div>
</div>
<?php
}
?>