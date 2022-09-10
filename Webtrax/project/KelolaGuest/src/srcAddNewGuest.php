<?php
require_once("../../../src/Modules/ModuleLogin.php");
require("../../../../src/srcProcessFunction.php");
require_once("../Modules/ModuleKelolaGuest.php");
date_default_timezone_set("Asia/Jakarta");
$Time = date("Y-m-d H:i:s");
 
session_start();
if(!session_is_registered("UIDWebTrax"))
{
    ?>
    <script language="javascript">
        window.location.replace("https://webtrax.formulatrix.com/");
    </script>
    <?php
    exit();
}

if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $ValGuest = htmlspecialchars(trim($_POST['InputNewGuest']), ENT_QUOTES, "UTF-8");
    $ValUsername = htmlspecialchars(trim($_POST['InputNewUsername']), ENT_QUOTES, "UTF-8");
    $ValPassword = htmlspecialchars(trim($_POST['InputNewPassword']), ENT_QUOTES, "UTF-8");
    $ValCategoryGuest = htmlspecialchars(trim($_POST['InputCategoryGuest']), ENT_QUOTES, "UTF-8");
    $ValCategoryGuest = strtolower($ValCategoryGuest);
    $ValLocationCompany = htmlspecialchars(trim($_POST['InputLocationCompany']), ENT_QUOTES, "UTF-8");    
    if($ValCategoryGuest == "administrator"  || $ValCategoryGuest == "guest"  || $ValCategoryGuest == "security")
    {
		# check username
        $QDataAccount = GET_DATA_ACCOUNT_BY_USERNAME($ValUsername,$linkHRISWebTrax);
		if(mssql_num_rows($QDataAccount) == 0)
        {		
			if($ValCategoryGuest == "administrator")
			{
				$ValCategoryGuest2 = "0";
				$ValPassword = md5($ValPassword);
				SET_NEW_DATA_ADMINISTRATOR($ValUsername,$ValPassword,$ValGuest,$Time,$ValCategoryGuest2,$ValLocationCompany,$linkHRISWebTrax);
			}
			if($ValCategoryGuest == "guest")
			{
				$ValCategoryGuest2 = "1";
				$ValPassword = md5($ValPassword);
				SET_NEW_DATA_GUEST($ValUsername,$ValPassword,$ValGuest,$Time,$ValCategoryGuest2,$ValLocationCompany,$linkHRISWebTrax);
			}
			if($ValCategoryGuest == "security")
			{
				$ValCategorySecurity = "1";
				$ValPassword = md5($ValPassword);
				SET_NEW_DATA_SECURITY($ValUsername,$ValPassword,$ValGuest,$Time,$ValCategorySecurity,$ValLocationCompany,$linkHRISWebTrax);
			}
			
			$_SESSION['ManageDataGuest'] = '<script type="text/javascript">$(document).ready(function(){alert("New user added!!");})</script>';
		}
        else
        {
            $_SESSION['ManageDataGuest'] = '<script type="text/javascript">$(document).ready(function(){alert("Username already exists!!");})</script>';
        }


        ?>
        <script language="javascript">
			window.location.href = "https://webtrax.formulatrix.com/home.php?link=10";
        </script>
        <?php
    }
    else
    {
        $_SESSION['ManageDataGuest'] = '<script type="text/javascript">$(document).ready(function(){alert("Category still empty!!");})</script>';
    }

}
else
{
    ?>
    <script language="javascript">
        window.location.replace("https://webtrax.formulatrix.com/");
    </script>
    <?php
    exit();  
}
?>
