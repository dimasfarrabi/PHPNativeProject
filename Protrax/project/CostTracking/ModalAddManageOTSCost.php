<?php 
session_start();
require_once("../../ConfigDB.php");
require_once("../../src/Modules/ModuleLogin.php");
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
# data session
$FullName = strtoupper(base64_decode($_SESSION['FullNameUserProTrax']));
$UserNameSession = base64_decode(base64_decode($_SESSION['UIDProTrax']));
# data protrax user
$BolProdAcc = false;
$QDataUserWebtrax = GET_DATA_LOGIN_BY_USERNAME_ONLY($UserNameSession,$linkHRISWebTrax);
if(mssql_num_rows($QDataUserWebtrax) > 0)
{
    $RDataUserWebtrax = mssql_fetch_assoc($QDataUserWebtrax);
    $TypeUser = trim($RDataUserWebtrax['TypeUser']);
    $_SESSION['LoginMode'] = base64_encode($TypeUser);
    $AccessLogin = base64_decode($_SESSION['LoginMode']);   
}
else # kondisi tidak terdaftar di protrax user & akan di set sebagai employee dan hak akses ke bagian produksi saja
{
    $_SESSION['LoginMode'] = base64_encode("Employee");
    $AccessLogin = base64_decode($_SESSION['LoginMode']);
    $BolProdAcc = true;
}

if((trim($AccessLogin) != "Manager"))
{
    if($RDataUserWebtrax['MnAdmin'] != "1" && $RDataUserWebtrax['MnCostTracking'] != "1")  
    {
        ?>
        <script language="javascript">
            window.location.replace("https://protrax.formulatrix.com/");
        </script>
        <?php
        exit();
    }
}
else
{
    if($RDataUserWebtrax['MnAdmin'] != "1")
    {
        if($RDataUserWebtrax['MnCostTracking'] != "1")
        {
            ?>
            <script language="javascript">
                window.location.replace("https://protrax.formulatrix.com/");
            </script>
            <?php
            exit();
        }
    }
}

if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $DataIDEnc = htmlspecialchars(trim($_POST['DataID']), ENT_QUOTES, "UTF-8");
    $DataID = base64_decode(base64_decode($DataIDEnc));
    $ArrDataID = explode("#",$DataID);
    $ValHalf = trim($ArrDataID [0]);
    $ValExpense = trim($ArrDataID [1]);
    $ValCategory = trim($ArrDataID [2]);
    $ValQuote = trim($ArrDataID [3]);
    $ValToken = base64_encode(base64_encode($ValHalf."#".$ValExpense."#".$ValCategory."#".$ValQuote));
?>
<div class="row">
    <div class="col-md-3">
        <div class="row">
            <div class="col-md-12 mb-2">
                <label for="TextVQuote" class="form-label fw-bold">Quote</label>
                <input type="text" class="form-control form-control-sm" id="TextVQuote" value="<?php echo $ValQuote; ?>" readonly>
            </div>
            <div class="col-md-12 mb-2">
                <label for="TextVCategory" class="form-label fw-bold">Category</label>
                <input type="text" class="form-control form-control-sm" id="TextVCategory" value="<?php echo $ValCategory; ?>" readonly>
            </div>
            <div class="col-md-12 mb-2">
                <label for="TextVHalf" class="form-label fw-bold">Half</label>
                <input type="text" class="form-control form-control-sm" id="TextVHalf" value="<?php echo $ValHalf; ?>" readonly>
            </div>
            <div class="col-md-12 mb-2">
                <label for="TextVExpense" class="form-label fw-bold">Expense</label>
                <input type="text" class="form-control form-control-sm" id="TextVExpense" value="<?php echo $ValExpense; ?>" readonly>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="row">
            <div class="col-md-3 mb-2">
                <label for="TextVPartNo" class="form-label fw-bold">Part No</label>
                <input type="text" class="form-control form-control-sm" id="TextVPartNo" value="" readonly>
            </div>
            <div class="col-md-9 mb-2 mt-1 pt-4">
                <button class="btn btn-sm btn-dark" data-bs-target="#ModalLoadPartNo"  data-bs-toggle="modal" data-bs-dismiss="modal" id="BtnFindPartNo" >Cari PartNo</button>
            </div>
            <div class="col-md-12 mb-2">
                <label for="TextVPartDesc" class="form-label fw-bold">Part Description</label>
                <textarea class="form-control" id="TextVPartDesc" readonly></textarea>
            </div>
            <div class="col-md-4 mb-2">
                <label for="TextVUnitCost" class="form-label fw-bold">Unit Cost</label>
                <input type="text" class="form-control form-control-sm" id="TextVUnitCost" value="" readonly>
            </div>
            <div class="col-md-4 mb-2">
                <label for="TextVQtyUsage" class="form-label fw-bold">Qty Usage</label>
                <input type="text" class="form-control form-control-sm" id="TextVQtyUsage" value="">
            </div>
            <div class="col-md-4 mb-2">
                <label for="TextVTotalCost" class="form-label fw-bold">Total Cost</label>
                <input type="text" class="form-control form-control-sm" id="TextVTotalCost" value="" readonly>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">&nbsp;</div>
            <div class="col-md-12">
                <button class="btn btn-sm btn-dark btn-labeled" id="BtnNewOTSCost" data-token="<?php echo $ValToken; ?>">Simpan Data</button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 pt-2" id="ModalContentInfo"></div>
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