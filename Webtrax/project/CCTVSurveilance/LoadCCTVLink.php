<?php 
session_start();
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleCCTV.php");

if(!session_is_registered("UIDWebTrax"))
{
    ?>
    <script language="javascript">
        window.location.replace("https://webtrax.formulatrix.com/");
    </script>
    <?php
    exit();
}

if($_SERVER['REQUEST_METHOD'] == "GET")
{    
    $ValLink =  htmlspecialchars(trim($_GET['link']), ENT_QUOTES, "UTF-8");
    $ValAccs =  htmlspecialchars(trim($_GET['accs']), ENT_QUOTES, "UTF-8");

    if(isset($ValLink) && isset($ValAccs) && trim($ValLink) != "" && trim($ValAccs) == "1" )
    {
        $ValLink = base64_decode($ValLink);
        $ValLink = str_replace("Link","",$ValLink);
        // $Result = shell_exec("start iexplore ".$ValLink."");
        // echo "(".$Result.")";
        echo $ValLink;
    }
}
else
{
    echo "";    
}
?>