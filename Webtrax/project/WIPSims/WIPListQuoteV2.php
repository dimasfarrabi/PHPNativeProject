<?php 
session_start();
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleWIPSims.php");
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
    // $ValQuoteCategory = htmlspecialchars(trim($_POST['ValQuoteCategory']), ENT_QUOTES, "UTF-8");
    $ValQuoteCategory = "";
	$ArrListQuote = array();
    $QListCategory = GET_LIST_QUOTE_BY_PARAM($ValQuoteCategory,$linkMACHWebTrax);
    while($RListCategory = sqlsrv_fetch_array($QListCategory))
    {
        $TempArray = array(
            "Quote" => trim($RListCategory['Quote']),
            "Location" => "PSL",
            "ProjectID" => trim($RListCategory['ProjectID'])
        );
        array_push($ArrListQuote,$TempArray);
    }
	$arrayAddOns = array(
                    "Quote" => "TEMPEST - CONSUMABLE",
                    "Location" => "PSL",
                    "ProjectID" => "tempestChip"
                    );
                    array_push($ArrListQuote,$arrayAddOns);
    $arrayAddOns = array(
                    "Quote" => "MANTIS - CONSUMABLE",
                    "Location" => "PSL",
                    "ProjectID" => "mantisChip"
                    );
                    array_push($ArrListQuote,$arrayAddOns);
    // $QListCategory2 = GET_LIST_QUOTE_BY_PARAM_PSM($ValQuoteCategory);
    // while($RListCategory2 = sqlsrv_fetch_array($QListCategory2))
    // {
        // $TempArray = array(
            // "Quote" => trim($RListCategory2['Quote']),
            // "Location" => "PSM",
            // "ProjectID" => trim($RListCategory2['ProjectID'])
        // );
        // array_push($ArrListQuote,$TempArray);
    // }
    asort($ArrListQuote);
    ?>
<div class="col-sm-12">
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="ListCategory">
            <thead class="theadCustom">
                <tr>
                    <th class="text-center">Quote</th>
                </tr>
            </thead>
            <tbody><?php 
            foreach($ArrListQuote as $ListQuote)
            {
                $EncLocation = base64_encode(base64_encode($ListQuote['Location']));
                echo '<tr data-id="'.$ListQuote['ProjectID'].'" data-log="'.$EncLocation.'" class="PointerList">';
                echo '<td>'.$ListQuote['Quote'].'</td>';
                echo '</tr>';
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