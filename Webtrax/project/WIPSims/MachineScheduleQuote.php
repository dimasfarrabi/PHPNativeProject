<?php 
session_start();
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleMachinePlan.php");



if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $ValTemplateNameEnc = "";
    $ValMachineName = htmlspecialchars(trim($_POST['ValMachine']), ENT_QUOTES, "UTF-8");
    $MachineCode = htmlspecialchars(trim($_POST['MachineCode']), ENT_QUOTES, "UTF-8");
    $EncValLocation = htmlspecialchars(trim($_POST['ValLocation']), ENT_QUOTES, "UTF-8");
    $ValLocation = base64_decode(base64_decode($EncValLocation));
    $EncLocation = $EncValLocation;
    // echo $ValMachineName."||".$MachineCode."||".$ValLocation;
    $QuoteArr = array();
    $Data = GET_QUOTE_MACHINE($MachineCode,$ValMachineName,$ValLocation,$linkMACHWebTrax);
    while($DataRes = sqlsrv_fetch_array($Data))
    {
        $ValQuote = trim($DataRes['Quote']);
        array_push($QuoteArr,$ValQuote);
    }
    $Data2 = GET_QUOTE_MACHINE("",$ValMachineName,$ValLocation,$linkMACHWebTrax);
    while($DataRes2 = sqlsrv_fetch_array($Data2))
    {
        $ValQuote2 = trim($DataRes2['Quote']);
        array_push($QuoteArr,$ValQuote2);
    }
    $QuoteEnc = $QuoteArr[0]."#".$QuoteArr[1]."#".$QuoteArr[2];
    // echo $QuoteEnc;
    ?>
    <style>
    .DataQuote{
    cursor: pointer;
    }   
    .tableFixHead {
        overflow-y: auto;
        height: 300px;
      }
      .tableFixHead thead th {
        position: sticky;
        top: 0;
      }
      table {
        border-collapse: collapse;
        width: 100%;
      }
      th,
      td {
        padding: 8px 16px;
        border: 1px solid #ccc;
      }
      th {
        background: #eee;
      }
</style>
<div class="col-md-12">
    <div class="table-responsive tableFixHead">
        <table class="table table-bordered table-hover table-fixed" id="TableQ">
            <thead class="theadCustom">
                <tr>
                    <th>Quote</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach($QuoteArr as $Quote)
                {
                    $ValDataRowEncrypt = base64_encode(base64_encode($Quote."#".$ValLocation));
                ?>
                <tr class="DataQuote" data-float="<?php echo $ValDataRowEncrypt; ?>">
                    <td class="text-left"><?php echo $Quote; ?></td>
                </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<?php
}
?>