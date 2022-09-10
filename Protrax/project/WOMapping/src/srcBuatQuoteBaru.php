<?php
session_start();
require_once("../../../ConfigDB.php");
require_once("../../../src/Modules/ModuleLogin.php");
require_once("../Modules/ModuleWOMapping.php");
/*
date_default_timezone_set("Asia/Jakarta");
$Time = date("Y-m-d H:i:s");
# data session
$FullName = strtoupper(base64_decode($_SESSION['FullNameUserProTrax']));
$UserNameSession = base64_decode(base64_decode($_SESSION['UIDProTrax']));
 
if(!session_is_registered("UIDProTrax"))
{
    ?>
    <script language="javascript">
        window.location.replace("https://protrax.formulatrix.com/");
    </script>
    <?php
    exit();
}
*/
$FullName = "local-Dimas Farrabi";
if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $QuoteName = htmlspecialchars(trim($_POST['QuoteName']), ENT_QUOTES, "UTF-8");
    $QuoteCat = htmlspecialchars(trim($_POST['QuoteCat']), ENT_QUOTES, "UTF-8");
    $NamaPM = htmlspecialchars(trim($_POST['NamaPM']), ENT_QUOTES, "UTF-8");
    // echo "$QuoteName >> $QuoteCat >> $NamaPM";
    $CekQuote = CHECK_NEW_QUOTE($QuoteName,$QuoteCat,$linkMACHWebTrax);
    if(sqlsrv_num_rows($CekQuote) > 0)
    {
        echo '<br><div class="alert alert-danger fw-bold" id="FailedBar2" role="alert">Quote Already Exist</div>';
    }
    else
    {
        // echo "Quote Baru Berhasil Disimpan!";
        $insertQuote = INSERT_QUOTE_BARU($QuoteName,$QuoteCat,$NamaPM,$FullName,$linkMACHWebTrax);
        if($insertQuote == 'FALSE')
        {
            echo '<br><div class="alert alert-danger fw-bold" id="FailedBar" role="alert">CREATE FAILED</div>';
        }
        else
        {
            echo '<br><div class="alert alert-success fw-bold" id="SuccessBar" role="alert">CREATE SUCCES</div>';
            ?>
            <script language="javascript">
                setTimeout(myFunction, 1000);
                function myFunction()
                {
                    $('#SuccessBar').hide();
                    $("#ModalNewQuote").modal('hide');
                    window.location.replace("http://localhost/protrax/home.php?link=31");
                }
                
            </script>
            <?php
        }
    }
}
else
{
    echo "Error!";
}
?>
<script language="javascript">
    setTimeout(myFunction2, 3000);
    function myFunction2()
    {
        $('#FailedBar').hide();
        $('#FailedBar2').hide();
    }
    
</script>