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
    $ValCategory = htmlspecialchars(trim($_POST['ValCategory']), ENT_QUOTES, "UTF-8");
    $ValYear = htmlspecialchars(trim($_POST['ValYear']), ENT_QUOTES, "UTF-8");
    $QuoteID = htmlspecialchars(trim($_POST['Quote']), ENT_QUOTES, "UTF-8");
    // echo "$ValCategory >> $ValYear >> $QuoteID";
?>
<style>
    .tableFixHead {
        overflow-y: auto;
        max-height: 500px;
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
<h5>Year : <strong><?php echo $ValYear; ?>.</strong></h5>
<h5>Project : <strong><?php echo $QuoteID; ?>.</strong></h5>
<div class="table-responsive tableFixHead">
    <table class="table table-bordered table-hover" id="">
        <thead class="theadCustom">
            <tr>
                <th>WO Parent</th>
                <th>Labor Cost ($)</th>
                <th>Machine Cost ($)</th>
                <th>Material Cost ($)</th>
                <th>OTS Cost ($)</th>
                <th>Total Cost</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $TotalCost = 0;
            $data = GET_CONTENT_DETAIL_DATA($ValYear,$QuoteID,$ValCategory,$linkMACHWebTrax);
            while($res=sqlsrv_fetch_array($data))
            {
                $WOP = trim($res['WOParent']);
                $ManCost = trim($res['ManCost']);
                $MachCost = trim($res['MachCost']);
                $MatCost = trim($res['MatCost']);
                $OTSCost = trim($res['OTSCost']);
                $AllCost = trim($res['TotalCost']);
                $TotalCost = @($TotalCost + $AllCost);
                $ManCost = number_format((float)$ManCost, 2, '.', ',');
                $MachCost = number_format((float)$MachCost, 2, '.', ',');
                $MatCost = number_format((float)$MatCost, 2, '.', ',');
                $OTSCost = number_format((float)$OTSCost, 2, '.', ',');
                $AllCost = number_format((float)$AllCost, 2, '.', ',');
            ?>
            <tr>
                <td class="text-left"><?php echo $WOP; ?></td>
                <td class="text-right"><?php echo $ManCost; ?></td>
                <td class="text-right"><?php echo $MachCost; ?></td>
                <td class="text-right"><?php echo $MatCost; ?></td>
                <td class="text-right"><?php echo $OTSCost; ?></td>
                <td class="text-right"><?php echo $AllCost; ?></td>
            </tr>
            <?php
            }
            $TotalCost = number_format((float)$TotalCost, 2, '.', ',');
            ?>
        </tbody>
        <tfoot>
            <tr>
                <td class="text-center" colspan="5"><strong>TOTAL</strong></td>
                <td class="text-right"><strong><?php echo $TotalCost; ?></strong></td>
            </tr>
        </tfoot>
    </table>
</div>
<?php
}