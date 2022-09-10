<?php 
session_start();

require("../src/srcProcessFunction.php");
require("../src/srcFunction.php");
require("src/Modules/ModuleLogin.php");
date_default_timezone_set("Asia/Jakarta");
# data session
// $FullName = strtoupper(base64_decode($_SESSION['FullNameUser']));
// $UserNameSession = base64_decode(base64_decode($_SESSION['UIDWebTrax']));
// $StatusGuestAsAdmin = "";
// $StatusGuestAsSecurity = "";
// if(!isset($_SESSION['LoginMode']))
// {
//     # data webtrax user
//     $QDataUserWebtrax = GET_DATA_LOGIN_BY_USERNAME_ONLY($UserNameSession,$linkHRISWebTrax);
//     if(mssql_num_rows($QDataUserWebtrax) > 0)
//     {
//         $RDataUserWebtrax = mssql_fetch_assoc($QDataUserWebtrax);
//         $IsGuest = $RDataUserWebtrax['Is_Guest'];
//         $IsSecurity = $RDataUserWebtrax['Is_Security'];
//         $IsAdministration = $RDataUserWebtrax['Is_Admin'];
//         if($IsGuest == "1") # if admin (not superuser)
//         {
//             $StatusGuestAsAdmin = "1";
//             $StatusGuestAsSecurity = "";
//             $_SESSION['LoginMode'] = base64_encode("Guest");
//             $AccessLogin = base64_decode($_SESSION['LoginMode']);
//         }
//         if($IsSecurity == "1") # if security
//         {
//             $StatusGuestAsAdmin = "";
//             $StatusGuestAsSecurity = "1";
//             $_SESSION['LoginMode'] = base64_encode("Reguler");
//             $AccessLogin = base64_decode($_SESSION['LoginMode']);
//         }
//         if($IsAdministration == "1") # if superuser
//         {
//             $_SESSION['LoginMode'] = base64_encode("Administrator");
//             $AccessLogin = base64_decode($_SESSION['LoginMode']);
//         }
//     }
//     else # kondisi tidak terdaftar di webtrax user
//     {
//         $_SESSION['LoginMode'] = base64_encode("Guest");
//         $AccessLogin = base64_decode($_SESSION['LoginMode']);
//     }
// }
// else
// {
//     # data webtrax user
//     $QDataUserWebtrax = GET_DATA_LOGIN_BY_USERNAME_ONLY($UserNameSession,$linkHRISWebTrax);
//     if(mssql_num_rows($QDataUserWebtrax) > 0)
//     {
//         // $IsRegistered = 1;
//         $RDataUserWebtrax = mssql_fetch_assoc($QDataUserWebtrax);
//         $IsGuest = $RDataUserWebtrax['Is_Guest'];
//         $IsSecurity = $RDataUserWebtrax['Is_Security'];
//         $IsAdministration = $RDataUserWebtrax['Is_Admin'];
//         if($IsGuest == "1") # if admin (not superuser)
//         {
//             $StatusGuestAsAdmin = "1";
//             $StatusGuestAsSecurity = "";
//         }
//         if($IsSecurity == "1") # if security
//         {
//             $StatusGuestAsAdmin = "";
//             $StatusGuestAsSecurity = "1";
//         }
//     }
//     $AccessLogin = base64_decode($_SESSION['LoginMode']);
// }



?>
<!DOCTYPE html>
<html>
<style>
.accordion {
  cursor: pointer;
  outline: none;
  transition: 0.4s;
}

.panel {
  max-height: 0;
  overflow: hidden;
  transition: max-height 0.2s ease-out;
}
</style>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
<title>WEBTRAX</title>
<link href="../images/favicon.ico" rel="icon" title="WebTrax">
<link rel="stylesheet" href="../forindotracking/vitalets-bootstrap-datepicker/vitalets-bootstrap-datepicker-c7af15b/css/datepicker.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="../forindotracking/Bootstrap-3.3.6/css/bootstrap.min.css">
<link rel="stylesheet" href="../css/custom.css">
<link rel="stylesheet" href="style/custom.css?no=<?php echo base64_encode(date("mdyHis")); ?>">
<link rel="stylesheet" href="../libs/datatables_1.10.19/css/jquery.dataTables.min.css">
<script src="../forindotracking/js/jquery-1.12.0.min.js"></script>
<script src="../forindotracking/vitalets-bootstrap-datepicker/vitalets-bootstrap-datepicker-c7af15b/js/bootstrap-datepicker.js"></script>
<script src="../libs/datatables_1.10.19/js/jquery.dataTables.min.js"></script>
<script src="lib/home.js?no=<?php echo base64_encode(date("mdyHis")); ?>"></script>
</head>
<body>
<div class="bg-main">
    <nav class="navbar navbar-inverse navbar-dark">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-home">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="home.php"><img id="img-logo" src="../images/final logo white.png" alt="logo"/></a>
            </div>
            <div class="collapse navbar-collapse" id="navbar-home">
                <ul class="nav navbar-nav navbar-right">
                    <li><p class="navbar-text text-left white-navbar" id="bar-name"></p></li>
                    <li><a href="home.php" title="Home">Home</a></li>
                    <?php //<li><a href="home.php?link=1" title="Timetrack">Individu Timetrack</a></li> //msh disembunyikan ?>
                    
                    <li><a href="src/logout.php" title="Logout"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span></a></li>
                </ul>
            </div>
        </div>        
    </nav>

    
<div id="menuSide" class="sidenav">
    <span class="closebtn" id="closebtn">&times;</span>
    <span class="sideTitle">Menu</span>
    

				
    <span class="sideTitle accordion">Electricy Usage</span>
    <!-- <a href="home.php?link=8"><img src="../images/dot.gif">&nbsp;Dashboard</a> -->
    <div class="panel" style="background-color: #393734;">
        <a href="home.php?link=17"><img src="../images/dot.gif">&nbsp;Site 1 (Salatiga Old)</a>
        <a href="home.php?link=12"><img src="../images/dot.gif">&nbsp;Site 2 (Semarang)</a>
        <a href="home.php?link=9"><img src="../images/dot.gif">&nbsp;Site 3 (Salatiga New)</a>
    </div>
    <span class="sideTitle accordion">Cost Tracking</span>
    <div class="panel" style="background-color: #393734;">
        <a href="home.php?link=4"><img src="../images/dot.gif">&nbsp;WO Closed (Periodical)</a>
        <a href="home.php?link=20"><img src="../images/dot.gif">&nbsp;WO Closed (Periodical) Chart</a>
        <!-- <a href="home.php?link=28"><img src="../images/dot.gif">&nbsp;Quality Report (Periodical)</a> -->
        <a href="home.php?link=5"><img src="../images/dot.gif">&nbsp;WO Open</a>
        <a href="home.php?link=53"><img src="../images/dot.gif">&nbsp;WO Target Cost (Periodical)</a>
        <?php /*<a href="home.php?link=21"><img src="../images/dot.gif">&nbsp;WO Open Chart</a>*/ ?>
        <a href="home.php?link=30"><img src="../images/dot.gif">&nbsp;Quantity Point</a>
        <a href="home.php?link=18"><img src="../images/dot.gif">&nbsp;Quality Point</a>
        <a href="home.php?link=19"><img src="../images/dot.gif">&nbsp;Point Achievements</a>
    </div>
	<span class="sideTitle accordion">Production</span>
    <div class="panel" style="background-color: #393734;">
        <a href="home.php?link=41"><img src="../images/dot.gif">&nbsp;WIP</a>
        <a href="home.php?link=36"><img src="../images/dot.gif">&nbsp;PPIC Barcode Part</a>
        <a href="home.php?link=54"><img src="../images/dot.gif">&nbsp;Kitting History Report</a>
    </div>
    <span class="sideTitle accordion">KPI</span>
    <div class="panel" style="background-color: #393734;">
        <a href="home.php?link=38"><img src="../images/dot.gif">&nbsp;Material Cutting</a>
        <a href="home.php?link=39"><img src="../images/dot.gif">&nbsp;Machining Process</a>
        <a href="home.php?link=40"><img src="../images/dot.gif">&nbsp;Finishing Process</a>
        <!-- <a href="home.php?link=42"><img src="../images/dot.gif">&nbsp;Injection Process</a> -->
        <a href="home.php?link=43"><img src="../images/dot.gif">&nbsp;Injection Process V2</a>
        <!-- <a href="home.php?link=43"><img src="../images/dot.gif">&nbsp;QC Process</a> -->
        <?php /*<a href="home.php?link=15"><img src="../images/dot.gif">&nbsp;Output</a> */?>
    </div>
	<span class="sideTitle accordion">CCTV</span>
    <div class="panel" style="background-color: #393734;">
	    <a href="home.php?link=13"><img src="../images/dot.gif">&nbsp;CCTV Surveilance</a>
    </div>
	<span class="sideTitle accordion">Employee</span>
    <div class="panel" style="background-color: #393734;">
        <a href="home.php?link=22"><img src="../images/dot.gif">&nbsp;Day Off Balance</a>
        <?php //<a href="home.php?link=24"><img src="../images/dot.gif">&nbsp;Employee List</a> ?>
        <a href="home.php?link=27"><img src="../images/dot.gif">&nbsp;Employee Statistic</a>
    </div>
	<span class="sideTitle accordion">Administration</span>
    <div class="panel" style="background-color: #393734;">
        <a href="home.php?link=16"><img src="../images/dot.gif">&nbsp;Manage Electricy Usage Salatiga Old</a>
        <a href="home.php?link=11"><img src="../images/dot.gif">&nbsp;Manage Electricy Usage Semarang</a>
        <a href="home.php?link=3"><img src="../images/dot.gif">&nbsp;Manage Electricy Usage Salatiga New</a>
    </div>
	<span class="sideTitle accordion">Reconciliation</span>
    <div class="panel" style="background-color: #393734;">
        <a href="home.php?link=31"><img src="../images/dot.gif">&nbsp;Time Tracking Reconciliation</a>
        <a href="home.php?link=32"><img src="../images/dot.gif">&nbsp;Machine Hour Reconciliation</a>
        <a href="home.php?link=33"><img src="../images/dot.gif">&nbsp;Material Tracking Reconciliation</a>
        <a href="home.php?link=45"><img src="../images/dot.gif">&nbsp;Material Tracking Reconciliation V2</a>
        <a href="home.php?link=34"><img src="../images/dot.gif">&nbsp;Synchronize Reconciliation v2</a>
        <a href="home.php?link=35"><img src="../images/dot.gif">&nbsp;Synchronize Reconciliation v1</a>
        <a href="home.php?link=48"><img src="../images/dot.gif">&nbsp;WO Mapping Reconciliation</a>
        <a href="home.php?link=47"><img src="../images/dot.gif">&nbsp;IoT Spindle Report</a>
    </div>
    <span class="sideTitle accordion">Shipping</span>
    <div class="panel" style="background-color: #393734;">
        <a href="home.php?link=49"><img src="../images/dot.gif">&nbsp;Freight Weight Chart</a>
        <a href="home.php?link=51"><img src="../images/dot.gif">&nbsp;Freight Weight Chart (Inbound)</a>
        <a href="home.php?link=50"><img src="../images/dot.gif">&nbsp;Destination Country Chart</a>
        <a href="home.php?link=52"><img src="../images/dot.gif">&nbsp;Courier Chart</a>
    </div>
    <span class="sideTitle accordion">Others</span>
    <div class="panel" style="background-color: #393734;">
        <a href="home.php?link=37"><img src="../images/dot.gif">&nbsp;Machining Material Supply</a>
        <a href="home.php?link=46"><img src="../images/dot.gif">&nbsp;Machine Spindle Report</a>
    </div>
    <!-- <span class="sideTitle">Electricy Usage</span> -->
    <!-- <a href="home.php?link=8"><img src="../images/dot.gif">&nbsp;Dashboard</a> -->
	<!-- <a href="home.php?link=17"><img src="../images/dot.gif">&nbsp;Site 1 (Salatiga Old)</a>
	<a href="home.php?link=12"><img src="../images/dot.gif">&nbsp;Site 2 (Semarang)</a>
    <a href="home.php?link=9"><img src="../images/dot.gif">&nbsp;Site 3 (Salatiga New)</a>
    <span class="sideTitle">Cost Tracking</span>
    <a href="home.php?link=4"><img src="../images/dot.gif">&nbsp;WO Closed (Periodical)</a>
	<a href="home.php?link=20"><img src="../images/dot.gif">&nbsp;WO Closed (Periodical) Chart</a>
    <a href="home.php?link=5"><img src="../images/dot.gif">&nbsp;WO Open</a> -->
	<?php /*<a href="home.php?link=21"><img src="../images/dot.gif">&nbsp;WO Open Chart</a>*/ ?>
	<!-- <a href="home.php?link=18"><img src="../images/dot.gif">&nbsp;Quality Point</a>
	<a href="home.php?link=19"><img src="../images/dot.gif">&nbsp;Employee Time Tracking</a>
	<span class="sideTitle">Production</span>
	<a href="home.php?link=14"><img src="../images/dot.gif">&nbsp;WIP</a> -->
	<?php /*<a href="home.php?link=15"><img src="../images/dot.gif">&nbsp;Output</a>*/ ?>
    <!-- <span class="sideTitle">Administration</span> -->
    <?php /*<a href="home.php?link=3"><img src="../images/dot.gif">&nbsp;Kelola KWH Tracking</a>
	<a href="home.php?link=11"><img src="../images/dot.gif">&nbsp;Kelola KWH Tracking PSM</a>
    <a href="home.php?link=10"><img src="../images/dot.gif">&nbsp;Kelola Guest</a>*/?>	
	<!-- <a href="home.php?link=16"><img src="../images/dot.gif">&nbsp;Manage Electricy Usage Salatiga Old</a>
	<a href="home.php?link=11"><img src="../images/dot.gif">&nbsp;Manage Electricy Usage Semarang</a>
	<a href="home.php?link=3"><img src="../images/dot.gif">&nbsp;Manage Electricy Usage Salatiga New</a>        
	<a href="home.php?link=10"><img src="../images/dot.gif">&nbsp;Manage Guest Access</a>
	<a href="home.php?link=25"><img src="../images/dot.gif">&nbsp;Manage Target Cost</a>
	<a href="home.php?link=26"><img src="../images/dot.gif">&nbsp;Manage Target Qty</a>
	<span class="sideTitle">CCTV</span>
	<a href="home.php?link=13"><img src="../images/dot.gif">&nbsp;CCTV Surveilance</a>
	<span class="sideTitle">Employee</span>
	<a href="home.php?link=22"><img src="../images/dot.gif">&nbsp;Day Off Balance</a> -->
	<?php //<a href="home.php?link=24"><img src="../images/dot.gif">&nbsp;Employee List</a> ?>
	<!-- <a href="home.php?link=27"><img src="../images/dot.gif">&nbsp;Employee Statistic</a>
	<span class="sideTitle">Under Development</span>
	<a href="home.php?link=14"><img src="../images/dot.gif">&nbsp;WIP</a>
	<a href="home.php?link=15"><img src="../images/dot.gif">&nbsp;Output</a>
	<a href="home.php?link=27"><img src="../images/dot.gif">&nbsp;Employee Statistic</a>
	<a href="home.php?link=24"><img src="../images/dot.gif">&nbsp;Employee List</a> -->

                

</div>

    <div class="menuNav" id="menuNav"><span class="glyphicon glyphicon-option-vertical"></span>&nbsp;</div>


    <div class="container-fluid container-home-webtrax">
        <div class="row">
            <div class="col-md-12 content-all"><?php
                    if(isset($_REQUEST['link']))
                    {
                        require_once("homecontent.php");
                    }
                    else
                    {
                      echo "";
                    }
                
                ?>
                
            </div>
        </div>
    </div>
</div>



<script src="../forindotracking/Bootstrap-3.3.6/js/bootstrap.min.js"></script>
<script src="../libs/datatables_1.10.19/js/dataTables.bootstrap.min.js"></script>
</body>
</html>
<script>
var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var panel = this.nextElementSibling;
    if (panel.style.maxHeight) {
      panel.style.maxHeight = null;
    } else {
      panel.style.maxHeight = panel.scrollHeight + "px";
    } 
  });
}
</script>