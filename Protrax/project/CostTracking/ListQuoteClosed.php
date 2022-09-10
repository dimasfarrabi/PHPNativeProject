<?php 
session_start();
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleCostTracking.php");

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
    $ValTime = htmlspecialchars(trim($_POST['ValTime']), ENT_QUOTES, "UTF-8");
    $ValQuoteCategory = htmlspecialchars(trim($_POST['ValQuoteCategory']), ENT_QUOTES, "UTF-8");
    $ValType = htmlspecialchars(trim($_POST['ValType']), ENT_QUOTES, "UTF-8");
    $LinkOpt = $linkMACHWebTrax;
        
    ?>
<script src="project/costtracking/lib/liblistquote.js?no=<?php echo base64_encode(date("mdyHis")); ?>"></script>
<div class="col-md-12">
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="TableListQuote">
            <thead class="theadCustom">
                <tr>
                    <td class="text-center">Quote</td>
                </tr>
            </thead>
            <tbody><?php 
            $QListQuote = GET_LIST_QUOTE_BY_PARAM($ValTime,$ValQuoteCategory,$ValType,$LinkOpt);
            while($RListQuote = mssql_fetch_assoc($QListQuote))
            {
                $ValQuote = $RListQuote['Quote'];
                echo '<tr class="PointerListQuote">';
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