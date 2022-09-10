<?php 
session_start();
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleBarcodePart.php");



if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $ValTemplateNameEnc = "";
    $ValQuoteName = htmlspecialchars(trim($_POST['ValQuoteName']), ENT_QUOTES, "UTF-8");
    $ValProjectID = htmlspecialchars(trim($_POST['ValProjectID']), ENT_QUOTES, "UTF-8");
    $EncValLocation = htmlspecialchars(trim($_POST['ValLocation']), ENT_QUOTES, "UTF-8");
    $ValLocation = base64_decode(base64_decode($EncValLocation));
    $EncLocation = $EncValLocation;
    // echo $ValQuoteName."||".$ValProjectID."||".$ValLocation;
?>
<style>
    .DataParent{
    cursor: pointer;
    }   
    .tableFixHead {
        overflow-y: auto;
        height: 450px;
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
<div class="col-md-12"><h5><strong>Table WO Parent : <?php echo $ValQuoteName; ?></strong></h5></div>
<div class="col-md-12">
    <div class="table-responsive tableFixHead">
        <table class="table table-bordered table-hover table-fixed" id="TableQ">
            <thead class="theadCustom">
                <tr>
                    <th>No</th>
                    <th>WO Parent</th>
                    <th>Cost Allocation</th>
                    <th>Closed Time</th>
                    <th>WOP Qty</th>
                </tr>
            </thead>
            <tbody>
            <?php
                $no = 1;
                $Data = GET_WOP_LIST($ValQuoteName,$linkMACHWebTrax);
                while($DataRes = sqlsrv_fetch_array($Data))
                {
                    $ValWOP = trim($DataRes['WOParent']);
                    $ValExpense = trim($DataRes['ExpenseAllocation']);
                    $valClosedTime = trim($DataRes['ClosedTime']);
                    $ValQty = trim($DataRes['Qty']);
                    $ValQty = number_format((float)$ValQty, 2, '.', ',');
                    $ValDataRowEncrypt = base64_encode(base64_encode($ValWOP."#".$ValExpense."#".$valClosedTime));
            ?>
                <tr class="DataParent" data-float="<?php echo $ValDataRowEncrypt; ?>">
                    <td class="text-center"><?php echo $no; ?></td>
                    <td class="text-left"><?php echo $ValWOP; ?></td>
                    <td class="text-left"><?php echo $ValExpense; ?></td>
                    <td class="text-center"><?php echo $valClosedTime; ?></td>
                    <td class="text-right"><?php echo $ValQty; ?></td>
                </tr>
            <?php
                $no++;
                }
            ?>
            </tbody>
        </table>
    </div>
</div>
<?php
}
?>