<?php 
session_start();
require_once("../../ConfigDB.php");
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleOTSCost.php");
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
    $InputHalf = htmlspecialchars(trim($_POST['Half']), ENT_QUOTES, "UTF-8");
    $InputExpense = htmlspecialchars(trim($_POST['Expense']), ENT_QUOTES, "UTF-8");
    $InputCategory = htmlspecialchars(trim($_POST['Category']), ENT_QUOTES, "UTF-8");
    $InputQuote = htmlspecialchars(trim($_POST['Quote']), ENT_QUOTES, "UTF-8");
    $ValTokenAdd = base64_encode(base64_encode($InputHalf."#".$InputExpense."#".$InputCategory."#".$InputQuote));

    ?>
<div class="col-md-12">Hasil Pencarian :</div>
<div class="col-md-12"><h6 id="TitleResult">Half : <?php echo $InputHalf; ?>. Category : <?php echo $InputCategory; ?>. Quote : <?php echo $InputQuote; ?>. Expense : <?php echo $InputExpense; ?>.</h6></div>
<div class="col-md-12 text-end pb-2">
    <button class="btn btn-sm btn-dark" id="BtnAddData" data-datatoken="<?php echo $ValTokenAdd; ?>">Tambah Data</button>
</div>
<div class="col-md-12">
    <div class="table-responsive">
        <table class="table table-border table-hover" id="TableViewData">
            <thead class="theadCustom">
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">#</th>
                    <th class="text-center">Half</th>
                    <th class="text-center">Quote</th>
                    <th class="text-center">Category</th>
                    <th class="text-center">Expense</th>
                    <th class="text-center">PartNo</th>
                    <th class="text-center">PartDesc</th>
                    <th class="text-center">UnitCost</th>
                    <th class="text-center">QtyUsage</th>
                    <th class="text-center">TotalCost</th>
                </tr>
            </thead>
            <tbody><?php 
            $No = 1;
            $QData = GET_DATA_DETAIL_OTS_COST($InputQuote,$InputCategory,$InputHalf,$InputExpense,$linkMACHWebTrax);
            while($RData = mssql_fetch_assoc($QData))
            {
                $ValIdx = trim($RData['Idx']);
                $ValQuote = trim($RData['Quote']);
                $ValQuoteCategory = trim($RData['QuoteCategory']);
                $ValClosedTime = trim($RData['ClosedTime']);
                $ValExpense = trim($RData['ExpenseAllocation']);
                $ValPartNo = trim($RData['PartNo']);
                $ValPartDesc = utf8_encode(trim($RData['PartDescription']));
                $ValUnitCost = trim($RData['UnitCost']);
                $ValUnitCost = sprintf('%.2f',floatval(trim($ValUnitCost)));
                $ValQtyUsage = trim($RData['QtyUsage']);
                $ValQtyUsage = sprintf('%.0f',floatval(trim($ValQtyUsage)));
                $ValTotalCost = trim($RData['TotalCost']);
                $ValTotalCost = sprintf('%.2f',floatval(trim($ValTotalCost)));
                $ValToken = base64_encode(base64_encode($InputHalf."#".$InputExpense."#".$InputCategory."#".$InputQuote."#".$ValIdx));
                ?>
                <tr>
                    <td class="text-center"><?php echo $No; ?></td>
                    <td class="text-center">
                        <span class="PointerList UpdateOTSCost" data-datatoken="<?php echo $ValToken; ?>" title="Update Periodic">Update</span>&nbsp;
                        <span class="PointerList DeleteOTSCost" data-datatoken="<?php echo $ValToken; ?>" title="Delete Periodic">Delete</span>
                    </td>
                    <td class="text-center"><?php echo $ValClosedTime; ?></td>
                    <td class="text-start"><?php echo $ValQuote; ?></td>
                    <td class="text-center"><?php echo $ValQuoteCategory; ?></td>
                    <td class="text-start"><?php echo $ValExpense; ?></td>
                    <td class="text-center"><?php echo $ValPartNo; ?></td>
                    <td class="text-start"><?php echo $ValPartDesc; ?></td>
                    <td class="text-end"><?php echo $ValUnitCost; ?></td>
                    <td class="text-center"><?php echo $ValQtyUsage; ?></td>
                    <td class="text-end"><?php echo $ValTotalCost; ?></td>
                </tr>
                <?php
                $No++;
            }
            
            ?></tbody>
        </table>
    </div>
</div>
<div class="col-md-12">&nbsp;</div>
<div class="col-md-12">&nbsp;</div>
<div id="TemporarySpace"></div>

<div class="modal fade" id="ModalLoadPartNo" tabindex="-1" role="dialog" aria-labelledby="Modal-View" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false"><div class="modal-dialog modal-xl" role="document"><div class="modal-content"><div class="modal-header"><h6 class="modal-title">Form Pencarian Part No</h6><button type="button" class="btn-close" data-bs-target="#ModalNewManageOTSCost" data-bs-toggle="modal" data-bs-dismiss="modal" aria-label="Close"></button></div><div class="modal-body"><div id="ContentModalFilterPartNo">
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <h6 class="card-header">Filter Pencarian Part No</h6>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <label for="FilterTypeCategoryFind" class="form-label mb-0 fw-bold">Jenis Pencarian</label>
                        <select class="form-select form-select-sm" aria-label="Select Type" id="FilterTypeCategoryFind">
                            <option>Part No</option>
                            <option>Part Description</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="FilterInputKeywordPartno" class="form-label mb-0 fw-bold">Kata Kunci</label>
                        <input type="text" class="form-control form-control-sm" id="FilterInputKeywordPartno">
                    </div>
                    <div class="col-md-3 pt-3 mt-1">
                        <button class="btn btn-sm btn-dark" id="BtnSearchPartNo">Cari Data</button>
                    </div>
                </div>
            </div>
        </div>
    </div>            
    <div class="col-md-12 pt-4">
        <div class="card">
            <h6 class="card-header">Tabel Item Master (Tanpa Konversi)</h6>
            <div class="card-body">
                <div class="row" id="ContentModalPartNo"></div>
            </div>
        </div>
    </div>
</div>
</div></div><div class="modal-footer"><button type="button" id="BtnClose" class="btn btn-sm btn-secondary" data-bs-target="#ModalNewManageOTSCost" data-bs-toggle="modal" data-bs-dismiss="modal">&nbsp;Close&nbsp;</button></div></div></div></div>
    <?php
}
else
{
    echo "";    
}
?>