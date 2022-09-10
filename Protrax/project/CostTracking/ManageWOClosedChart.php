<?php
require_once("project/CostTracking/Modules/ModuleCostTrackingChart.php");
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


$ArrListProject = array();
$ValQuoteCategory = "Quote";
$QListProject = GET_ALL_PROJECT_NAME_PSL($ValQuoteCategory,$linkMACHWebTrax);
while ($RListProject = mssql_fetch_assoc($QListProject))
{
    $ValProjectName = trim($RListProject['ProjectName']);
    $BolCheckName = FALSE;
    foreach ($ArrListProject as $ListProject)
    {
        if(trim($ListProject['ProjectName']) == trim($ValProjectName) && trim($ListProject['ProjectName']) == trim($ValProjectName))
        {
            $BolCheckName = TRUE;
        }
    }
    if($BolCheckName == FALSE)
    {
        $TemporaryArray = array(
            "ProjectName" => trim($ValProjectName),
            "QuoteCategory" => trim($ValQuoteCategory),
            "Location" => "SALATIGA"
        );
        array_push($ArrListProject,$TemporaryArray);
    }
}
sort($ArrListProject);
# data webtrax quote
$ArrListQuote = array();
$QListQuote = GET_LIST_WEBTRAX_QUOTE($linkMACHWebTrax);
while($RListQuote = mssql_fetch_assoc($QListQuote))
{
    $TempArray = array(
        "QuoteID" => trim($RListQuote['QuoteID']),
        "Quote" => trim($RListQuote['Quote'])
    );
    array_push($ArrListQuote,$TempArray);
}
# data list result
$ArrListResult = array();
foreach($ArrListProject as $ListProject)
{
    $TempBol = FALSE;
    $TempValProjectName = "";
    $TempValQuoteCategory = "";
    $TempValQuoteID = "";

    if(trim($ListProject['QuoteCategory']) == "Quote")
    {
        foreach($ArrListQuote as $ListQuote)
        {
            if(trim($ListProject['ProjectName']) == trim($ListQuote['Quote']))
            {
                $TempValProjectName = trim($ListProject['ProjectName']);
                $TempValQuoteCategory = trim($ListProject['QuoteCategory']);
                $TempValQuoteID = trim($ListQuote['QuoteID']);
            }
        }
    }
    else
    {
        $TempValProjectName = trim($ListProject['ProjectName']);
        $TempValQuoteCategory = trim($ListProject['QuoteCategory']);
        $TempValQuoteID = trim($ListProject['QuoteID']);
    }
    foreach ($ArrListResult as $ListResult)
    {
        if(trim($ListResult['QuoteID']) == trim($TempValQuoteID))
        {
             $TempBol = TRUE;
        }
    }
    if($TempBol == FALSE)
    {
        if((trim($TempValProjectName) != "") && (trim($TempValQuoteCategory) != "") && (trim($TempValQuoteID) != ""))
        {
            $TempArray = array(
                "ProjectName" => $TempValProjectName,
                "QuoteCategory" => $TempValQuoteCategory,
                "QuoteID" => $TempValQuoteID
            );
            array_push($ArrListResult,$TempArray);
        }
    }    
}

?>
<script src="project/CostTracking/lib/LibManageWOClosedChart.js?no=<?php echo base64_encode(date("mdyHis")); ?>"></script>
<div class="row">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-detail ">
                <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Cost Tracking : Manage WO Closed Chart</li>
            </ol>
        </nav>
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        <div class="row" id="ListQuote">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="TableListProject">
                        <thead class="theadCustom">
                            <tr>
                                <td class="text-center fw-bold">Project</td>
                            </tr>
                        </thead>
                        <tbody><?php 
                            foreach($ArrListResult as $ListResult)
                            {
                                $ValProjectName = trim($ListResult['ProjectName']);
                                $ValQuoteCategory = trim($ListResult['QuoteCategory']);
                                $ValQuoteID = trim($ListResult['QuoteID']);
                                $ValEncrypt = base64_encode(base64_encode($ValProjectName."#".$ValQuoteID."#".$ValQuoteCategory));
                            ?>
                            <tr class="PointerListProject" data-split="<?php echo $ValEncrypt; ?>">
                                <td class="text-left"><?php echo $ValQuoteID; ?></td>
                            </tr>
                                <?php
                            }
                        ?></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="row" id="ContentPageManage"></div>
    </div>
</div>