<?php 
session_start();
require_once("../../ConfigDB.php");
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleCostTracking.php");
require_once("Modules/ModuleTarget.php"); 
date_default_timezone_set("Asia/Jakarta");

if(!session_is_registered("UIDProTrax"))
{
    ?>
    <script language="javascript">
        window.location.replace("https://protrax.formulatrix.com/");
    </script>
    <?php
    exit();
}

if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $ValSeason = htmlspecialchars(trim($_POST['ValSeason']), ENT_QUOTES, "UTF-8");
    $ValQuoteCategory = htmlspecialchars(trim($_POST['ValQuoteCategory']), ENT_QUOTES, "UTF-8");
    $QListQuote = LIST_QUOTE_BY_PARAMETERS($ValSeason,$ValQuoteCategory,$linkMACHWebTrax);
    $Row = mssql_num_rows($QListQuote);
    if($Row != 0)
    {
?>
<div class="col-md-12 mb-2">
    <label for="InputQuoteF" class="form-label fw-bold">Quote</label>
    <select class="form-select" id="InputQuoteF"><?php 
    while($RListQuote = mssql_fetch_assoc($QListQuote))
    {
        ?>
        <option><?php echo trim($RListQuote['Quote']); ?></option>
        <?php
    }
    ?></select>
</div>
<?php
    }
    else
    {
?>
<div class="col-md-12 mb-2">
    <label for="InputQuoteF" class="form-label fw-bold">Quote</label>
    <select class="form-select" id="InputQuoteF" disabled></select>
</div>
<?php        
    }
}
else
{
    echo "";    
}
?>