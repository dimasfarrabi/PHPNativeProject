<?php 
session_start();
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleWIPSims.php");


if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $ValQuoteCategory = htmlspecialchars(trim($_POST['ValQuoteCategory']), ENT_QUOTES, "UTF-8");
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
            $QListQuote = GET_LIST_QUOTE($ValQuoteCategory,$linkMACHWebTrax);
            while($RListQuote = sqlsrv_fetch_array($QListQuote))
            {
                $ValQuote = $RListQuote['ProjectName'];
                echo '<tr class="PointerListQuoteOpen">';
                echo '<td>'.$ValQuote.'</td>';
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