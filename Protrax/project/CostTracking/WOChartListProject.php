<?php 
session_start();
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleCostTrackingChart.php");

if(!session_is_registered("UIDWebTrax"))
{
    ?>
    <script language="javascript">
        window.location.replace("https://webtrax.formulatrix.com/");
    </script>
    <?php
    exit();
}

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
                            "Location" => "SALATIGA"
                        );
                        array_push($ArrListProject,$TemporaryArray);
                    }
                }
                // $QListProjectPSM = GET_ALL_PROJECT_NAME_PSM_ALL_CATEGORY();
                // while ($RListProjectPSM = mssql_fetch_assoc($QListProjectPSM))
                // {
                //     $ValProjectNamePSM = trim($RListProjectPSM['ProjectName']);
                //     $BolCheckName = FALSE;
                //     foreach ($ArrListProject as $ListProject)
                //     {
                //         if(trim($ListProject['ProjectName']) == trim($ValProjectNamePSM) && trim($ListProject['ProjectName']) == trim($ValProjectNamePSM))
                //         {
                //             $BolCheckName = TRUE;
                //         }
                //     }
                //     if($BolCheckName == FALSE)
                //     {
                //         $TemporaryArray = array(
                //             "ProjectName" => trim($ValProjectNamePSM),
                //             "Location" => "SEMARANG"
                //         );
                //         array_push($ArrListProject,$TemporaryArray);
                //     }
                // }
            }
            break;
        default:
            {
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
                            "Location" => "SALATIGA"
                        );
                        array_push($ArrListProject,$TemporaryArray);
                    }
                }
                // $QListProjectPSM = GET_ALL_PROJECT_NAME_PSM($ValQuoteCategory);
                // while ($RListProjectPSM = mssql_fetch_assoc($QListProjectPSM))
                // {
                //     $ValProjectNamePSM = trim($RListProjectPSM['ProjectName']);
                //     $BolCheckName = FALSE;
                //     foreach ($ArrListProject as $ListProject)
                //     {
                //         if(trim($ListProject['ProjectName']) == trim($ValProjectNamePSM) && trim($ListProject['ProjectName']) == trim($ValProjectNamePSM))
                //         {
                //             $BolCheckName = TRUE;
                //         }
                //     }
                //     if($BolCheckName == FALSE)
                //     {
                //         $TemporaryArray = array(
                //             "ProjectName" => trim($ValProjectNamePSM),
                //             "Location" => "SEMARANG"
                //         );
                //         array_push($ArrListProject,$TemporaryArray);
                //     }
                // }
            }
            break;
    }
    sort($ArrListProject);

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
            foreach($ArrListProject as $ListProject)
            {
                $ValProjectName = trim($ListProject['ProjectName']);
                $ValLocation = trim($ListProject['Location']);
                $ValEncrypt = base64_encode(base64_encode($ValProjectName."#".$ValLocation));
                ?>
            <tr class="PointerListProject" data-split="<?php echo $ValEncrypt; ?>">
                <td class="text-left"><?php echo $ValProjectName; ?></td>
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