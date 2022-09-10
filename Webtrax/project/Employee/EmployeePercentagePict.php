<?php 
session_start();
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleEmployee.php");
date_default_timezone_set("Asia/Jakarta");
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
    $EncUrlPict = htmlspecialchars(trim($_POST['ValPath']), ENT_QUOTES, "UTF-8");
    $FullName = htmlspecialchars(trim($_POST['ValFN']), ENT_QUOTES, "UTF-8");
    $DetailPosition = htmlspecialchars(trim($_POST['ValDetailPosition']), ENT_QUOTES, "UTF-8");
    $Phone = htmlspecialchars(trim($_POST['ValPhone']), ENT_QUOTES, "UTF-8");
    $UrlPict = urldecode($EncUrlPict);
    $ArrUrlPict = explode("/FOTOKARYAWAN",$UrlPict);
	if(strpos(trim($ArrUrlPict[1]), ".") !== false)
    {
        echo '<div class="col-md-12 text-center imgload"><img src="'.$UrlPict.'" class="img-responsive center-block" id="PictEmp" alt="pic" /></div>';  
    }
    else
    {
        echo '<div class="col-md-12 text-center imgload">No Image</div>'; 
    }
    echo '<div class="col-md-12 text-left"><strong>Name</strong></div>';
    echo '<div class="col-md-12 text-left">'.$FullName.'</div>';
    echo '<div class="col-md-12 text-left"><strong>Detail Position</strong></div>';
    echo '<div class="col-md-12 text-left">'.$DetailPosition.'</div>';
    echo '<div class="col-md-12 text-left"><strong>Phone</strong></div>';
    echo '<div class="col-md-12 text-left">'.$Phone.'</div>';

}
else
{
    echo "";    
}
?>