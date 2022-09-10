<?php
require_once("project/CostTracking/Modules/ModuleCostTracking.php");
require_once("project/CostTracking/Modules/ModuleCostTrackingChart.php");
date_default_timezone_set("Asia/Jakarta");
$YearNow = date("Y");
$DateNow = date("m/d/Y");
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

if((trim($AccessLogin) != "Manager"))
{
    if($RDataUserWebtrax['MnAdmin'] != "1")  
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
*/
?>
<script src="project/CostTracking/lib/LibManageQuantityBuild.js?no=<?php echo base64_encode(date("mdyHis")); ?>"></script>
<div class="row">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-detail ">
                <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Cost Tracking : Manage Quantity Build</li>
            </ol>
        </nav>
    </div>
</div>
<div class="row">
    <div class="col-md-5">
        <div class="row">
            <div class="col-md-4">
                <label for="TextSeason" class="form-label fw-bold">Season</label>
                <select class="form-select form-select-sm" aria-label="Select Season" id="TextSeason">
                    <?php
                    $QListClosedTime = GET_ALL_TYPE_CLOSED_TIME_DESC($linkMACHWebTrax);
                    while($RListClosedTime = sqlsrv_fetch_array($QListClosedTime))
                    {
                        echo '<option>'.trim($RListClosedTime['ClosedTime']).'</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-4">
                <label for="TextCategory" class="form-label fw-bold">Category</label>
                <select class="form-select form-select-sm" aria-label="Select Category" id="TextCategory">
                    <?php
                    $QListQuoteCategory = GET_LIST_QUOTE_CATEGORY("",$linkMACHWebTrax);
                    while($RListQuoteCategory = sqlsrv_fetch_array($QListQuoteCategory))
                    {
                        $QuoteCategory = $RListQuoteCategory['QuoteCategory'];
                        ?>
                        <option><?php echo $QuoteCategory; ?></option>
                        <?php
                    }       
                    ?>
                </select>
            </div>
            <div class="col-md-4 mt-4 pt-1">
                <button type="button" id="BtnViewData" class="btn btn-sm btn-dark">View Data</button>
            </div>
        </div>
    </div>
    <div class="col-md-7"></div>
</div>
<hr>
<div class="row">
    <div class="col-md-3">
        <div class="row" id="ListQuote"></div>
    </div>
    <div class="col-md-9">
        <div class="row" id="ContentPageManage"></div>
    </div>
</div>