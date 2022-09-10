<?php
require_once("project/CostTracking/Modules/ModulePeriodicQuoteCost.php");
require_once("project/CostTracking/Modules/ModuleCostTracking.php");
date_default_timezone_set("Asia/Jakarta");
$YearNow = date("Y");
$DateNow = date("m/d/Y");

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
?>
<script src="project/CostTracking/lib/LibManageOTSCost.js?no=<?php echo base64_encode(date("mdyHis")); ?>"></script>
<div class="row">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-detail">
                <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Cost Tracking : Manage OTS Cost</li>
            </ol>
        </nav>
    </div>
<div class="row">
    <div class="col-sm-12 fw-bold"><h5><strong>Pencarian Data</strong></h5></div>
    <div class="col-md-2">
        <div class="row">
            <div class="col-sm-2">
                <label for="FilterClosedTime" class="form-label fw-bold pt-1">Half</label>
            </div>
            <div class="col-sm-10 mb-2">
                <select class="form-select form-select-sm" id="FilterClosedTime"><?php 
                $NoLoopClosedTime = 1;
                $TempClosedTime = "";
                $QListClosedTime = GET_LIST_CLOSEDTIME_NOT_OPEN("",$linkMACHWebTrax);
                while($RListClosedTime = mssql_fetch_assoc($QListClosedTime))
                {
                    $ClosedTime = $RListClosedTime['ClosedTime'];
                    if($NoLoopClosedTime == 1)
                    {
                        $TempClosedTime = $ClosedTime;
                    }
                    ?>
                    <option><?php echo $ClosedTime; ?></option>
                    <?php
                    $NoLoopClosedTime++;
                }                
                ?></select>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="row">
            <div class="col-sm-5">
                <label for="FilterQuoteCategory" class="form-label fw-bold pt-1">Category</label>
            </div>
            <div class="col-sm-7 mb-2">
                <select class="form-select form-select-sm" id="FilterQuoteCategory"><?php 
                $NoLoopQuoteCategory = 1;
                $TempQuoteCategory = "";
                $QListQuoteCategory = GET_LIST_QUOTE_CATEGORY("PSL",$linkMACHWebTrax);
                while($RListQuoteCategory = mssql_fetch_assoc($QListQuoteCategory))
                {
                    $QuoteCategory = $RListQuoteCategory['QuoteCategory'];
                    if($NoLoopQuoteCategory == 1)
                    {
                        $TempQuoteCategory = $QuoteCategory;
                    }
                    ?>
                    <option><?php echo $QuoteCategory; ?></option>
                    <?php
                    $NoLoopQuoteCategory++;
                }            
                ?></select>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="row">
            <div class="col-sm-2">
                <label for="FilterQuote" class="form-label fw-bold pt-1">Quote</label>
            </div>
            <div class="col-sm-10 mb-2">
                <select class="form-select form-select-sm" id="FilterQuote"></select>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="row">
            <div class="col-sm-2">
                <label for="FilterExpense" class="form-label fw-bold pt-1">Expense</label>
            </div>
            <div class="col-sm-10 mb-2">
                <select class="form-select form-select-sm" id="FilterExpense"><?php 
                $QListExpense = LIST_DIVISION_PERIODIC($linkMACHWebTrax);
                while($RListExpense = mssql_fetch_assoc($QListExpense))
                {
                    echo '<option>'.trim($RListExpense['ExpenseOption']).'</option>';
                }
                ?></select>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="row">
            <div class="col-sm-12">
                <button class="btn btn-dark btn-sm btn-labeled" id="BtnViewData">View Data</button>
            </div>
        </div>
    </div>
    <div class="col-sm-12"><hr></div>
</div>
<div class="row" id="ContentSearchData"></div>





