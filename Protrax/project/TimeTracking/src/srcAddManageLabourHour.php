<?php
session_start();
require_once("../../../ConfigDB.php");
require_once("../../../src/Modules/ModuleLogin.php");
require_once("../Modules/ModuleLabourHour.php");

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
    $DataIDEnc = htmlspecialchars(trim($_POST['DataID']), ENT_QUOTES, "UTF-8");
    $Employee = htmlspecialchars(trim($_POST['DataEmployee']), ENT_QUOTES, "UTF-8");
    $Category = htmlspecialchars(trim($_POST['DataCategory']), ENT_QUOTES, "UTF-8");
    $Season = htmlspecialchars(trim($_POST['DataSeason']), ENT_QUOTES, "UTF-8");
    $WOID = htmlspecialchars(trim($_POST['DataWOID']), ENT_QUOTES, "UTF-8");
    $WOC = htmlspecialchars(trim($_POST['DataWOC']), ENT_QUOTES, "UTF-8");
    $Expense = htmlspecialchars(trim($_POST['DataExpense']), ENT_QUOTES, "UTF-8");
    $Total = htmlspecialchars(trim($_POST['DataTotal']), ENT_QUOTES, "UTF-8");
    $Location = htmlspecialchars(trim($_POST['Location']), ENT_QUOTES, "UTF-8");
    $ValNewTotal = sprintf('%.3f',floatval($Total));
    $Employee2 = str_replace(" - PSM","",$Employee);
    # check data
    $Check = CHECK_LABOUR_HOUR_BY_INPUT($WOID,$Employee2,$linkMACHWebTrax);
    if($Check == 0)
    {
        # insert data
        $Result = INSERT_NEW_LABOUR_HOUR($WOID,$Employee2,$Total,$Location,$linkMACHWebTrax);
        if($Result == "TRUE")
        {
            $Total2 = sprintf('%.3f',floatval(trim($Total)));
            
            ?>
            <script>
                $(document).ready(function () {
                    var dt = $("#TableViewData").DataTable();
                    var newrow = $('<tr><td class="text-center">-</td><td class="text-start"><?php echo $Employee2; ?></td><td class="text-center"><?php echo $WOID; ?></td><td class="text-start"><?php echo $WOC; ?></td><td class="text-center"><?php echo $Expense; ?></td><td class="text-center"><?php echo $Season; ?></td><td class="text-end"><?php echo $Total2; ?></td><td class="text-center">-</td></tr>');
                    dt.row.add(newrow).draw(false);                
                    $('#ModalAddLabourHour').modal("hide");
                });
            </script>
             <?php 
        }
        else
        {
            ?>
            <script>
                $(document).ready(function () {
                    alert("Error! Please try again later!");
                });
            </script>
            <?php
        }
    }
    else
    {
        # info data sdh ada seblmnya
        ?>
            <script>
                $(document).ready(function () {
                    $('#TempProcess').html("");
                    $("#TextTotal").val("");
                    $("#TempProcess").html('<span class="fw-bold text-danger">Data saved before!</span>');
                });
            </script>
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
