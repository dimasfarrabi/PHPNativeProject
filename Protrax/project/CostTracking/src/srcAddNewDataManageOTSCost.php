<?php
session_start();
require_once("../../../ConfigDB.php");
require_once("../../../src/Modules/ModuleLogin.php");
require_once("../Modules/ModuleOTSCost.php");

date_default_timezone_set("Asia/Jakarta");
$Time = date("Y-m-d H:i:s");
 
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
    $DataID = htmlspecialchars(trim($_POST['DataID']), ENT_QUOTES, "UTF-8");
    $DataEnc = base64_decode(base64_decode($DataID));
    $DataQuote = htmlspecialchars(trim($_POST['DataQuote']), ENT_QUOTES, "UTF-8");
    $DataCategory = htmlspecialchars(trim($_POST['DataCategory']), ENT_QUOTES, "UTF-8");
    $DataHalf = htmlspecialchars(trim($_POST['DataHalf']), ENT_QUOTES, "UTF-8");
    $DataExpense = htmlspecialchars(trim($_POST['DataExpense']), ENT_QUOTES, "UTF-8");
    $DataPartNo = htmlspecialchars(trim($_POST['DataPartNo']), ENT_QUOTES, "UTF-8");
    // $DataPartDesc = str_replace(array("'", "\"", "&quot;"), "inches",trim($_POST['DataPartDesc']));
    $DataPartDesc = trim($_POST['DataPartDesc']);
    $DataUnitCost = htmlspecialchars(trim($_POST['DataUnitCost']), ENT_QUOTES, "UTF-8");
    $DataQtyUsage = htmlspecialchars(trim($_POST['DataQtyUsage']), ENT_QUOTES, "UTF-8");
    $DataTotalCost = htmlspecialchars(trim($_POST['DataTotalCost']), ENT_QUOTES, "UTF-8");
    if($DataUnitCost == ""){
        $DataUnitCost = "0";
    }
    if($DataQtyUsage == ""){
        $DataQtyUsage = "0";
    }
    if($DataTotalCost == ""){
        $DataTotalCost = "0";
    }
    # check data sebelumnya
    $TotalRow = CHECK_TOTAL_OTS_COST($DataQuote,$DataHalf,$DataExpense,$DataPartNo,$linkMACHWebTrax);
    if($TotalRow == "0")
    {
        # simpan data
        $ResAdd = ADD_NEW_TOTAL_DATA_OTS_COST($DataQuote,$DataCategory,$DataHalf,$DataExpense,$DataPartNo,$DataPartDesc,$DataUnitCost,$DataQtyUsage,$DataTotalCost,$linkMACHWebTrax);
        if($ResAdd == "TRUE")
        {
            $ValClosedTime = $DataHalf;
            $ValQuote = $DataQuote;
            $ValQuoteCategory = $DataCategory;
            $ValExpense = $DataExpense;
            $ValPartNo = $DataPartNo;
            $ValPartDesc = $DataPartDesc;
            $ValUnitCost = sprintf('%.2f',floatval(trim($DataUnitCost)));
            $ValQtyUsage = sprintf('%.0f',floatval(trim($DataQtyUsage)));
            $ValTotalCost = sprintf('%.2f',floatval(trim($DataTotalCost)));
            ?>
            <script>
                $(document).ready(function () {
                    var dt = $("#TableViewData").DataTable();
                    var newrow = $('<tr><td class="text-center">-</td><td class="text-center">-</td><td class="text-center"><?php echo $ValClosedTime; ?></td><td class="text-start"><?php echo $ValQuote; ?></td><td class="text-center"><?php echo $ValQuoteCategory; ?></td><td class="text-start"><?php echo $ValExpense; ?></td><td class="text-center"><?php echo $ValPartNo; ?></td><td class="text-start"><?php echo $ValPartDesc; ?></td><td class="text-end"><?php echo $ValUnitCost; ?></td><td class="text-center"><?php echo $ValQtyUsage; ?></td><td class="text-end"><?php echo $ValTotalCost; ?></td></tr>');
                    dt.row.add(newrow).draw(false);
                    $("#ModalNewManageOTSCost").modal("hide");
                });
            </script>
            <?php
        }
        else
        {
            ?>
            <div class="alert alert-danger fw-bold" role="alert">Data gagal tersimpan!</div>
            <?php
        }        
    }
    else
    {
        ?>
        <div class="alert alert-danger fw-bold" role="alert">PartNo sudah tersimpan sebelumnya!</div>
        <?php
    } 
}
else
{
    ?>
    <script language="javascript">
        window.location.replace("https://protrax.formulatrix.com/");
    </script>
    <?php
    exit();  
}
?>
