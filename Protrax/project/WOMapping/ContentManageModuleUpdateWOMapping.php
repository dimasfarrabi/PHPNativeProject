<?php 
session_start();
require_once("../../ConfigDB.php");
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleWOMapping.php");
require_once("../../Project/Report/Modules/ModuleReport.php");
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
*/
if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $ValWOMapping_ID = htmlspecialchars(trim($_POST['ValWOMapping_ID']), ENT_QUOTES, "UTF-8");
    $ValLocation = htmlspecialchars(trim($_POST['ValLocation']), ENT_QUOTES, "UTF-8");
    $ValClosedTime = htmlspecialchars(trim($_POST['ValClosedTime']), ENT_QUOTES, "UTF-8");

    ?>
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="FieldLocation1" class="form-label fw-bold">Location</label>
            <input type="text" class="form-control form-control-sm" id="FieldLocation1" value="<?php echo $ValLocation; ?>" readonly>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="FieldWOID1" class="form-label fw-bold">WOMapping_ID</label>
            <input type="text" class="form-control form-control-sm" id="FieldWOID1" value="<?php echo $ValWOMapping_ID; ?>" readonly>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="FieldClosedTime1" class="form-label fw-bold">Closed Time</label>
            <input type="text" class="form-control form-control-sm" id="FieldClosedTime1" value="<?php echo $ValClosedTime; ?>" readonly>
        </div>
    </div>
    <div class="col-md-12"><hr></div>
    <div class="col-md-12 d-grid mt-2">
        <button class="btn btn-sm btn-dark" id="ActChangeQuote" data-bs-target="#ModalUpdateDataSelected" data-bs-toggle="modal" data-bs-dismiss="modal">Change Quote</button>
    </div>
    <div class="col-md-12 d-grid mt-2">
        <button class="btn btn-sm btn-dark" id="ActRenameWOChild" data-bs-target="#ModalUpdateDataSelected" data-bs-toggle="modal" data-bs-dismiss="modal">Rename WO Child</button>
    </div>
    <div class="col-md-12 d-grid mt-2">
        <button class="btn btn-sm btn-dark" id="ActRenameWOParent" data-bs-target="#ModalUpdateDataSelected" data-bs-toggle="modal" data-bs-dismiss="modal">Rename WO Parent</button>
    </div>
    <div class="col-md-12 d-grid mt-2">
        <button class="btn btn-sm btn-dark" id="ActUpdateQtyParentQuote" data-bs-target="#ModalUpdateDataSelected" data-bs-toggle="modal" data-bs-dismiss="modal">Update Qty Parent</button>
    </div>
    <div class="col-md-12 d-grid mt-2">
        <button class="btn btn-sm btn-dark" id="ActRecalculateQtyQuote" data-bs-target="#ModalUpdateDataSelected" data-bs-toggle="modal" data-bs-dismiss="modal">Recalculate Qty Quote</button>
    </div>
    <div class="col-md-12 d-grid mt-2">
        <button class="btn btn-sm btn-dark" id="ActUpdatePMDM" data-bs-target="#ModalUpdateDataSelected" data-bs-toggle="modal" data-bs-dismiss="modal">Update PM / CO PM / DM</button>
    </div>
    <div class="col-md-12 d-grid mt-2">
        <button class="btn btn-sm btn-dark" id="ActChangeQuoteCategory" data-bs-target="#ModalUpdateDataSelected" data-bs-toggle="modal" data-bs-dismiss="modal">Change Quote Category</button>
    </div>
    <div class="col-md-12 d-grid mt-2">
        <button class="btn btn-sm btn-dark" id="ActChangeExpense" data-bs-target="#ModalUpdateDataSelected" data-bs-toggle="modal" data-bs-dismiss="modal">Change Expense Allocation</button>
    </div>
    <div class="col-md-12 d-grid mt-2">
        <button class="btn btn-sm btn-dark" id="ActEditTargetHour" data-bs-target="#ModalUpdateDataSelected" data-bs-toggle="modal" data-bs-dismiss="modal">Edit Target Hour</button>
    </div>
</div>

    <?php
}
else
{
    echo "";    
}
?>