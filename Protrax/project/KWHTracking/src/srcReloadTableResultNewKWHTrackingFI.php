<?php 
session_start();
require_once("../../../ConfigDB.php");
require_once("../../../src/Modules/ModuleLogin.php");
require_once("../Modules/ModuleKWHTracking.php");

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
    $ValData = htmlspecialchars(trim($_POST['data']), ENT_QUOTES, "UTF-8");
    if($ValData == "1")
    {
        ?>
<strong>Table Top 10 Result</strong>
<div class="table-responsive">
    <table class="table table-responsive table-striped table-hover display" id="TableData">
        <thead class="theadCustom">    
            <tr>
                <th class="text-center">No</th>
                <th class="text-center">Date</th>
                <th class="text-center">Usage</th>
            </tr>
        </thead>
        <tbody><?php 
        $QData = GET_LIST_TOP10_KWH_ADDED("FI",$linkHRISWebTrax);
        $No = 1;
        while($RData = mssql_fetch_assoc($QData))
        {
            $ValDate = date("m/d/Y",strtotime($RData['DateLog']));
            $ValUsage = trim($RData['KWH']);
            ?>
            <tr>
                <td class="text-center"><?php echo $No; ?></td>
                <td class="text-center"><?php echo $ValDate; ?></td>
                <td class="text-center"><?php echo $ValUsage; ?></td>
            </tr>
            <?php
            $No++;
        }
        ?></tbody>
    </table>
</div>
        <?php
    }
    else
    {
        echo "";   
    }
}
else
{
    echo "";    
}
?>