<?php 
session_start();
require_once("../../ConfigDB.php");
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleWOMapping.php");
require_once("../../Project/Report/Modules/ModuleReport.php");
date_default_timezone_set("Asia/Jakarta");
$DateNow = date("m/d/Y");
$YearNow = date("Y");
$FullName = "Local-Dimas Farrabi";
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
    $RDataUserWebtrax = sqlsrv_fetch_array($QDataUserWebtrax);
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
    $ValQuoteCode = htmlspecialchars(trim($_POST['ValQuote']), ENT_QUOTES, "UTF-8");
    $ValQuote = base64_decode($ValQuoteCode);
    $arr = explode("*",$ValQuote);
    $Quote = $arr[0];
    $Category = $arr[1];
    $Leader = GET_PM($Quote,$Category,$linkMACHWebTrax);
    $arrLeader = explode("*",$Leader);
    $PM = $arrLeader[0];
    $COPM = $arrLeader[1];
?>
<div class="col-md-3">
    <div class="form-group">
        <label for="FilPM" class="form-label fw-bold">PM</label>
        <input type="text" class="form-control form-control-sm" id="FilPM" value="<?php echo $PM ; ?>" disabled>
    </div>
</div>
<div class="col-md-3">
    <div class="form-group">
        <label for="FilCOPM" class="form-label fw-bold">CO PM</label>
        <input type="text" class="form-control form-control-sm" id="FilCOPM" value="<?php echo $COPM ; ?>" disabled>
    </div>
</div> 
<div class="col-md-6">
    <div class="form-group">
        <label for="FilCategory" class="form-label fw-bold">Quote Category</label>
        <input type="text" class="form-control form-control-sm" id="FilCategory" value="<?php echo $Category ; ?>" disabled>
    </div>
</div> 
<div class="col-md-5">
    <div class="form-group">
        <label for="FILWOP" class="form-label fw-bold">WO Parent</label>
        <input type="text" style="text-transform: uppercase" class="form-control form-control-sm" id="FILWOP">
    </div>
    <?php
    /*
    <div class="form-group">
        <label for="FilWOP" class="form-label fw-bold">Pilih WO Parent</label>
        <select class="form-select form-select-sm" id="FILWOP">
            <option value="1">--Pilih WO Parent--</option>
            
            $data = GET_WOP($Quote,$Category,$linkMACHWebTrax);
            while($res=sqlsrv_fetch_array($data))
            {
                $ValWOP = trim($res['WOParent']);
                $ValQtyParent = trim($res['Qty']);
                $ValProduct = trim($res['Product']);
                $enc = $ValWOP.":".$ValQtyParent.":".$ValProduct;
            ?>
            <option value="<?php echo $enc; ?>"><?php echo $ValWOP; ?></option>
            <?php
            }
            
        </select>
    </div>*/
            ?>
</div>
<div class="col-md-1" style="margin-top:28px;">
<span aria-hidden="true" data-bs-toggle="modal" data-bs-target="#ModalNewWOP" title="Buat WOP Baru"><button class="btn btn-sm btn-info">....</button></span>
<div><img src="../images/ajax-loader1.gif" id="LoadingCekWOP" class="load_img"/></div>
</div>
<div class="col-md-3">
    <div class="form-group">
        <label for="QtyWOP" class="form-label fw-bold">Qty Parent</label>
        <input type="text" class="form-control form-control-sm" id="QtyWOP"> 
    </div>
</div>
<div class="col-md-3">
    <div class="form-group">
        <label for="FilProduct" class="form-label fw-bold">Product</label>
        <input type="text" class="form-control form-control-sm" id="FilProduct">
    </div>
</div> 
<div class="col-md-6">
    <div class="form-group">
        <label for="FilWOC" class="form-label fw-bold">WO Child</label>
        <input type="text" class="form-control form-control-sm" id="FilWOC">
    </div>
</div>
<div class="col-md-3">
    <div class="form-group">
        <label for="JenisWO" class="form-label fw-bold">Jenis WO</label>
        <select class="form-select form-select-sm" id="JenisWO">
            <option>WO Pekerjaan</option>
            <option>WO Pembelian</option>
        </select>
    </div>
</div>
<div class="col-md-3">
    <div class="form-group">
        <label for="TipeOrder" class="form-label fw-bold">Order Type</label>
        <select class="form-select form-select-sm" id="TipeOrder">
            <option>System</option>
            <option>Sparepart</option>
            <option>Additional Order</option>
            <option>Engineer Order</option>
            <option>Others</option>
        </select>
    </div>
</div> 
<div class="col-md-12" style="margin-top:20px;">
<i><strong>*) Click On Expense Label to Fill Target Cost</strong></i>
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="FormExpense">
            <thead>
                <tr>
                    <th class="text-center" width="250">Expense Allocation*</th>
                    <th class="text-center">Target<br>Man Hour ($)</th>
                    <th class="text-center">Target<br>Mach Hour ($)</th>
                    <th class="text-center">Target<br>Cost Material ($)</th>
                    <th class="text-center">Limit Max<br>Running Time (hour)</th>
                    <th class="text-center">Estimate<br>Finished Date</th>
                    <th class="text-center">Estimate<br>Finished Half</th>
                    <th class="text-center">Division<br>Manager</th>
                    <th class="text-center">Location</th>
                    <th class="text-center">#</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="10" class="text-center">Production</td>
                </tr>
                <?php
                $DataExp = GET_EXPENSE_PRODUCTION($linkMACHWebTrax);
                while($resExp=sqlsrv_fetch_array($DataExp))
                {
                    $ValExpense = trim($resExp['ExpenseAllocation']);
                    $enc = base64_encode($Quote.'*'.$ValExpense.'*'.$Category);
                    $CheckBox = '<input class="form-check-input" type="checkbox" id="'.$ValExpense.'" value="'.$ValExpense.'">';
                    $Label = '<label style="cursor:pointer;" class="form-check-label" for="'.$ValExpense.'" aria-hidden="true" data-bs-toggle="modal" data-ecode="'.$enc.'" data-bs-target="#ManHourModal">'.$ValExpense.'</label>';
                    
                ?>
                <tr data-idrows="<?php echo $ValExpense; ?>">
                    <td class="text-right"><?php echo $Label; ?></td>
                    <td class="text-right"></td>
                    <td class="text-right"></td>
                    <td class="text-right"></td>
                    <td class="text-right"></td>
                    <td class="text-right"></td>
                    <td class="text-right"></td>
                    <td class="text-right"></td>
                    <td class="text-center"></td>
                    <td class="text-left"><span class="PointerList checkExpense" data-id="<?php echo $ValExpense; ?>">[Reset]</span></td>
                </tr>
                <?php
                }
                ?>
                <tr>
                    <td colspan="10" class="text-center">Non-production</td>
                </tr>
                <?php
                $datax = GET_NON_PRODUCTION_EXPENSE($linkMACHWebTrax);
                while($resx=sqlsrv_fetch_array($datax))
                {
                    $ValExpense = trim($resx['ExpenseOption']);
                    $enc = base64_encode($Quote.'*'.$ValExpense.'*'.$Category);
                    $Label = '<label style="cursor:pointer;" class="form-check-label" for="'.$ValExpense.'" aria-hidden="true" data-bs-toggle="modal" data-ecode="'.$enc.'" data-bs-target="#ManHourModal">'.$ValExpense.'</label>';
                    ?>
                    <tr data-idrows="<?php echo $ValExpense; ?>">
                        <td class="text-right"><?php echo $Label; ?></td>
                        <td class="text-right"></td>
                        <td class="text-right"></td>
                        <td class="text-right"></td>
                        <td class="text-right"></td>
                        <td class="text-right"></td>
                        <td class="text-right"></td>
                        <td class="text-right"></td>
                        <td class="text-center"></td>
                        <td class="text-left"><span class="PointerList checkExpense" data-id="<?php echo $ValExpense; ?>">[Reset]</span></td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<div class="col-md-12" style="margin-top:20px;">
    <button class="btn btn-md btn-success" aria-hidden="true" data-bs-toggle="modal" data-bs-target="#ModalProsesCreate" id="BtnSubmitWO" style="float:right; width:40%;">SUBMIT</button>
</div>
<div class="modal fade" id="ModalNewWOP" tabindex="-1" role="dialog" aria-labelledby="Modal-View" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Buat WOP Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
				<div id="NewWOPForm"></div>
                
                <!-- <div class="text-center"><img src="../images/ajax-loader1.gif" id="LoadingImg" class="load_img" style="height:10px;"/></div> -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">&nbsp;Close&nbsp;</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="ManHourModal" tabindex="-1" role="dialog" aria-labelledby="Modal-View" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Input Target Hour/Cost</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
				<div class="row"  id="InputManHour"></div>
                
                <!-- <div class="text-center"><img src="../images/ajax-loader1.gif" id="LoadingImg" class="load_img" style="height:10px;"/></div> -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">&nbsp;Close&nbsp;</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="ModalProsesCreate" tabindex="-1" role="dialog" aria-labelledby="Modal-View" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Processing</h5>
                <div id="ContentLoading1"><div class="spinner-border text-secondary" role="status"><span class="visually-hidden" id="LoadingImg"></span ></div></div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
				<div id="SubmitWO">
                    <!-- <div class="alert alert-danger fw-bold" id="FailedBar2" role="alert"><span id="FailedBar2Content"></span></div>
                    <div class="alert alert-danger fw-bold" id="FailedBar" role="alert"><span id="FailedBarContent"></span></div>
                    <div class="alert alert-success fw-bold" id="SuccessBar" role="alert"><span id="SuccessBarContent"></span></div> -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">&nbsp;Close&nbsp;</button>
            </div>
        </div>
    </div>
</div>
<?php
}
?>