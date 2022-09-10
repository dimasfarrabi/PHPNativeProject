<?php 
session_start();
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleCostTrackingChart.php");
/*
if(!session_is_registered("UIDWebTrax"))
{
    ?>
    <script language="javascript">
        window.location.replace("https://webtrax.formulatrix.com/");
    </script>
    <?php
    exit();
}
*/
if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $ValQuoteCategoryEnc = htmlspecialchars(trim($_POST['ValQuoteCategory']), ENT_QUOTES, "UTF-8");
    $ArrQuoteCategoryEnc = base64_decode(base64_decode($ValQuoteCategoryEnc));
    $ArrQuoteCategory = explode("#",$ArrQuoteCategoryEnc);
    $ValQuoteCategory = $ArrQuoteCategory[1];
    $ArrListProject = array();
    switch ($ValQuoteCategory) {
        case 'All':
            {
                $QListProject = GET_ALL_PROJECT_NAME_PSL_ALL_CATEGORY($linkMACHWebTrax);
                while ($RListProject = sqlsrv_fetch_array($QListProject))
                {
                    $ValProjectName = trim($RListProject['ProjectName']);
                    $ValQuoteCategory = trim($RListProject['QuoteCategory']);
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
            }
            break;
        default:
            {
                $QListProject = GET_ALL_PROJECT_NAME_PSL($ValQuoteCategory,$linkMACHWebTrax);
                while ($RListProject = sqlsrv_fetch_array($QListProject))
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
            }
            break;
    }
    sort($ArrListProject);
    # data webtrax quote
    $ArrListQuote = array();
    $QListQuote = GET_LIST_WEBTRAX_QUOTE($ValQuoteCategory,$linkMACHWebTrax);
    while($RListQuote = sqlsrv_fetch_array($QListQuote))
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
<div class="col-md-12">
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="TableListProject">
            <thead class="theadCustom">
                <tr>
                    <td class="text-center">Project</td>
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
    <?php
}
else
{
    echo "";    
}
?>