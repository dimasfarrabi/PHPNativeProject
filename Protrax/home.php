<?php
session_start();

require("ConfigDB.php");
require("src/Modules/ModuleLogin.php");
date_default_timezone_set("Asia/Jakarta");

?><!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
<title>PROTRAX</title>
<link rel="icon" href="images/favicon.ico" title="ProTrax">
<link rel="stylesheet" href="lib/bootstrap-5.0.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="lib/icons-1.5.0/font/bootstrap-icons.css">
<link rel="stylesheet" href="style/custom.css?no=<?php echo base64_encode(date("mdyHis")); ?>">
<link rel="stylesheet" href="lib/vitalets-bootstrap-datepicker/vitalets-bootstrap-datepicker-c7af15b/css/datepicker.css">
<link rel="stylesheet" href="lib/DataTables-v1.11.3/datatables.min.css">
<script src="lib/js/jquery-3.6.0.min.js"></script>
<script src="lib/DataTables-v1.11.3/datatables.min.js"></script>
</head>
<body class="body-content">
    <nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark navbar-custom">
        <div class="container-fluid container-navbar">
            <span class="navbar-text">
                <button class="btn btn-sm btn-outline-secondary me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#CanvasSide" aria-controls="CanvasSide" id="NavMnBtn" title="Menu Protrax">Menu</button>
            </span>
            <span class="navbar-text"><img id="img-logo" src="../images/final logo white.png" alt="logo"/></span>    
                <button class="navbar-toggler navbar-navigation-custom" type="button" data-bs-toggle="collapse" data-bs-target="#NavAltMenu" aria-controls="NavAltMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="NavAltMenu">
                <div class="navbar-nav" id="NavAltMenuList">
                    <span class="nav-link navbar-text fw-bold">X</span>
                    <hr class="dropdown-divider dropdown-divider-custom" />
                    <a class="nav-link" href="home.php">Home</a>
                    <a class="nav-link" href="Logout.php" title="Logout">Log Out</a>
                </div>
            </div>
        </div>
    </nav>
    <div class="offcanvas offcanvas-start" tabindex="-1" id="CanvasSide" aria-labelledby="offcanvasExampleLabel">
        <div class="offcanvas-header" id="header-offcanvas">
            <h5 class="offcanvas-title" id="offcanvasLabel">MENU</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body" id="menu-offcanvas">        
            <?php
        // if($BolProdAcc == false)
        // {
        //     # Menu Security
        //     if(isset($RDataUserWebtrax['MnSecurity']) && trim($RDataUserWebtrax['MnSecurity']) == "1" && trim($RDataUserWebtrax['MnAdmin']) == "0")
        //     {
        //         if($AccessLogin == "Manager")
        //         {
                ?>
            <span class="sideTitle">Building Management</span>
            <a href="home.php?link=2"><i class="bi bi-caret-right-fill"></i>&nbsp;Manage Electricity Usage Salatiga New</a> 
            <a href="home.php?link=3"><i class="bi bi-caret-right-fill"></i>&nbsp;Manage Electricity Usage Salatiga Old</a>
            <a href="home.php?link=4"><i class="bi bi-caret-right-fill"></i>&nbsp;Manage Electricity Usage Semarang</a>
                <?php
                // }
                // else
                // {
                ?>
            <span class="sideTitle">Building Management</span>
            <a href="home.php?link=1"><i class="bi bi-caret-right-fill"></i>&nbsp;Manage Electricity Usage</a>                
                <?php  
            //     }
            // }
            // # Menu CostTracking
            // if(isset($RDataUserWebtrax['MnCostTracking']) && trim($RDataUserWebtrax['MnCostTracking']) == "1" && trim($RDataUserWebtrax['MnAdmin']) == "0")
            // {
            //     if($AccessLogin == "Manager")
            //     {
                ?>
            <span class="sideTitle">Cost Tracking</span>
            <a href="home.php?link=5"><i class="bi bi-caret-right-fill"></i>&nbsp;Manage Quality Point</a>
            <a href="home.php?link=7"><i class="bi bi-caret-right-fill"></i>&nbsp;Manage Target Cost</a>
            <a href="home.php?link=8"><i class="bi bi-caret-right-fill"></i>&nbsp;Manage Target Qty</a> 
            <a href="home.php?link=14"><i class="bi bi-caret-right-fill"></i>&nbsp;Manage WO Closed Chart</a>  
            <a href="home.php?link=15"><i class="bi bi-caret-right-fill"></i>&nbsp;Manage Quantity Build</a>
            <a href="home.php?link=20"><i class="bi bi-caret-right-fill"></i>&nbsp;Manage Periodic Quote Cost</a> 
            <a href="home.php?link=21"><i class="bi bi-caret-right-fill"></i>&nbsp;Manage OTS Cost</a> 
            <a href="home.php?link=22"><i class="bi bi-caret-right-fill"></i>&nbsp;Manage ShiftCode Machine</a>
            <a href="home.php?link=32"><i class="bi bi-caret-right-fill"></i>&nbsp;Manage Discretionary People Point</a>
                <?php
                // }
                // else
                // {
                ?>
            <!-- <span class="sideTitle">Cost Tracking</span>
            <a href="home.php?link=5"><i class="bi bi-caret-right-fill"></i>&nbsp;Manage Quality Point</a>
            <a href="home.php?link=7"><i class="bi bi-caret-right-fill"></i>&nbsp;Manage Target Cost</a>
            <a href="home.php?link=8"><i class="bi bi-caret-right-fill"></i>&nbsp;Manage Target Qty</a> 
            <a href="home.php?link=14"><i class="bi bi-caret-right-fill"></i>&nbsp;Manage WO Closed Chart</a>  
            <a href="home.php?link=15"><i class="bi bi-caret-right-fill"></i>&nbsp;Manage Quantity Build</a>
            <a href="home.php?link=20"><i class="bi bi-caret-right-fill"></i>&nbsp;Manage Periodic Quote Cost</a> 
            <a href="home.php?link=21"><i class="bi bi-caret-right-fill"></i>&nbsp;Manage OTS Cost</a> 
            <a href="home.php?link=22"><i class="bi bi-caret-right-fill"></i>&nbsp;Manage ShiftCode Machine</a> -->
                <?php
            //     }
            // }
            # Menu Production
            // if(isset($RDataUserWebtrax['MnProduction']) && trim($RDataUserWebtrax['MnProduction']) == "1" && trim($RDataUserWebtrax['MnAdmin']) == "0")
            // {
            //     if($AccessLogin == "Manager")
            //     {
                ?>
            <span class="sideTitle">Production</span>
            <a href="home.php?link=6"><i class="bi bi-caret-right-fill"></i>&nbsp;WIP</a>            
            <a href="home.php?link=33"><i class="bi bi-caret-right-fill"></i>&nbsp;Move And Receive Inventory Bin</a>            
            <a href="home.php?link=34"><i class="bi bi-caret-right-fill"></i>&nbsp;Move Inventory Bin</a>            
            <a href="home.php?link=37"><i class="bi bi-caret-right-fill"></i>&nbsp;Move Inventory Bin V3</a>            
            <a href="home.php?link=35"><i class="bi bi-caret-right-fill"></i>&nbsp;Receive Inventory Bin</a>     
            <a href="home.php?link=43"><i class="bi bi-caret-right-fill"></i>&nbsp;Create WIP Barcode</a>     
            <a href="home.php?link=44"><i class="bi bi-caret-right-fill"></i>&nbsp;Form Pengambilan & Keluar Part</a>     
            <span class="sideTitle">Inventory</span>       
            <a href="home.php?link=38"><i class="bi bi-caret-right-fill"></i>&nbsp;Proses In/Out Part TBZ Per Label</a>   
            <a href="home.php?link=40"><i class="bi bi-caret-right-fill"></i>&nbsp;Stock Opname Form</a>   
            <a href="home.php?link=41"><i class="bi bi-caret-right-fill"></i>&nbsp;Stock Opname Form V2</a>   
                <?php
                // }
                // else
                // {
                ?>
            <!-- <span class="sideTitle">Production</span>
            <a href="home.php?link=6"><i class="bi bi-caret-right-fill"></i>&nbsp;WIP</a>-->
                <?php   
            //     }
            // }
            // # Menu CCTV
			// if(isset($RDataUserWebtrax['MnCCTV']) && trim($RDataUserWebtrax['MnCCTV']) == "1")
            // {
            //     if($AccessLogin == "Manager")
            //     {
                ?>
            <span class="sideTitle">CCTV</span>
            <a href="home.php?link=9"><i class="bi bi-caret-right-fill"></i>&nbsp;CCTV Surveilance</a>            
                <?php
                // }
                // else
                // {
                ?>
            <!-- <span class="sideTitle">CCTV</span>
            <a href="home.php?link=9"><i class="bi bi-caret-right-fill"></i>&nbsp;CCTV Surveilance</a>                -->
                <?php   
            //     }
            // }
            // # Menu Report
			// if(isset($RDataUserWebtrax['MnReport']) && trim($RDataUserWebtrax['MnReport']) == "1" && trim($RDataUserWebtrax['MnAdmin']) == "0")
            // {
            //     if($AccessLogin == "Manager")
            //     {
                ?>
            <span class="sideTitle">Report</span>
            <a href="home.php?link=11"><i class="bi bi-caret-right-fill"></i>&nbsp;Timetracking</a>
            <a href="home.php?link=23"><i class="bi bi-caret-right-fill"></i>&nbsp;Machine Tracking</a>
            <a href="home.php?link=24"><i class="bi bi-caret-right-fill"></i>&nbsp;Material Tracking</a>       
            <a href="home.php?link=25"><i class="bi bi-caret-right-fill"></i>&nbsp;WO Mapping</a>       
            <a href="home.php?link=26"><i class="bi bi-caret-right-fill"></i>&nbsp;Barcode Status</a>       
            <a href="home.php?link=19"><i class="bi bi-caret-right-fill"></i>&nbsp;Monitoring Machine</a>          
            <a href="home.php?link=29"><i class="bi bi-caret-right-fill"></i>&nbsp;IoT Machine Spindle</a>     
            <span class="sideTitle">WO Mapping</span>
            <a href="home.php?link=30"><i class="bi bi-caret-right-fill"></i>&nbsp;Manage WO Mapping</a>       
            <a href="home.php?link=31"><i class="bi bi-caret-right-fill"></i>&nbsp;Create WO Mapping</a>       
            <a href="home.php?link=39"><i class="bi bi-caret-right-fill"></i>&nbsp;WO Mapping Recalculate</a>       
            <!-- <a href="home.php?link=23"><i class="bi bi-caret-right-fill"></i>&nbsp;Machine Tracking</a>
            <a href="home.php?link=24"><i class="bi bi-caret-right-fill"></i>&nbsp;Material Tracking</a>           -->
                <?php
                // }
                // else
                // {
                ?>
            <!-- <span class="sideTitle">Report</span>
            <a href="home.php?link=11"><i class="bi bi-caret-right-fill"></i>&nbsp;Timetracking</a> 
            <?php /*<a href="home.php?link=12"><i class="bi bi-caret-right-fill"></i>&nbsp;Machine Tracking</a>  
            <a href="home.php?link=13"><i class="bi bi-caret-right-fill"></i>&nbsp;Material Tracking</a> */ ?> 
            <a href="home.php?link=19"><i class="bi bi-caret-right-fill"></i>&nbsp;Monitoring Machine</a>                 -->
                <?php   
            //     }
            // }
            // # Menu PPIC
			// if(isset($RDataUserWebtrax['MnPPIC']) && trim($RDataUserWebtrax['MnPPIC']) == "1" && trim($RDataUserWebtrax['MnAdmin']) == "0")
            // {
            //     if($AccessLogin == "Manager")
            //     {
                ?>
            <span class="sideTitle">PPIC</span>    
            <span class="sideTitleLv1">BC Part / Job</span>   
            <a href="home.php?link=17" class="sideTitleLv2"><i class="bi bi-caret-right-fill"></i>&nbsp;Buat BC Part / Job</a> 
            <a href="home.php?link=36" class="sideTitleLv2"><i class="bi bi-caret-right-fill"></i>&nbsp;TBZ In Out</a> 
            <span class="sideTitleLv1">BC Material</span> 
            <a href="home.php?link=28" class="sideTitleLv2"><i class="bi bi-caret-right-fill"></i>&nbsp;Machine Material Supply Mapping</a>           
                <?php
                // }
                // else
                // {
                ?>
            <!-- <span class="sideTitle">PPIC</span>    
            <span class="sideTitleLv1">BC Part / Job</span>   
            <a href="home.php?link=17" class="sideTitleLv2"><i class="bi bi-caret-right-fill"></i>&nbsp;Buat BC Part / Job</a>          -->
                <?php   
            //     }
            // }
            // # Menu Operator Machining CNC
            // if(isset($RDataUserWebtrax['MnOprMachCNC']) && trim($RDataUserWebtrax['MnOprMachCNC']) == "1" && trim($RDataUserWebtrax['MnAdmin']) == "0")
            // {
            //     if($AccessLogin == "Manager")
            //     {
                ?>
            <span class="sideTitle">Machining CNC</span>         
                <?php
                // }
                // else
                // {
                ?>
            <!-- <span class="sideTitle">Machining CNC</span>        -->
                <?php   
            //     }
            // }
            // # Menu Operator Machining Manual
            // if(isset($RDataUserWebtrax['MnOprMachManual']) && trim($RDataUserWebtrax['MnOprMachManual']) == "1" && trim($RDataUserWebtrax['MnAdmin']) == "0")
            // {
            //     if($AccessLogin == "Manager")
            //     {
                ?>
            <span class="sideTitle">Machining Manual</span>         
                <?php
                // }
                // else
                // {
                ?>
            <!-- <span class="sideTitle">Machining Manual</span>        -->
                <?php   
            //     }
            // }
            // # Menu Operator Fabrication
            // if(isset($RDataUserWebtrax['MnOprFabrication']) && trim($RDataUserWebtrax['MnOprFabrication']) == "1" && trim($RDataUserWebtrax['MnAdmin']) == "0")
            // {
            //     if($AccessLogin == "Manager")
            //     {
                ?>
            <span class="sideTitle">Fabrication</span>         
                <?php
                // }
                // else
                // {
                ?>
            <!-- <span class="sideTitle">Fabrication</span>        -->
                <?php   
            //     }
            // }
            // # Menu Operator Finishing
            // if(isset($RDataUserWebtrax['MnOprFinishing']) && trim($RDataUserWebtrax['MnOprFinishing']) == "1" && trim($RDataUserWebtrax['MnAdmin']) == "0")
            // {
            //     if($AccessLogin == "Manager")
            //     {
                ?>
            <span class="sideTitle">Finishing</span>   
            <a href="home.php?link=42" class="sideTitleLv2"><i class="bi bi-caret-right-fill"></i>&nbsp;Finishing</a>       
                <?php
                // }
                // else
                // {
                ?>
            <!-- <span class="sideTitle">Finishing</span>        -->
                <?php   
            //     }
            // }
            // # Menu Operator QA
            // if(isset($RDataUserWebtrax['MnOprQA']) && trim($RDataUserWebtrax['MnOprQA']) == "1" && trim($RDataUserWebtrax['MnAdmin']) == "0")
            // {
            //     if($AccessLogin == "Manager")
            //     {
                ?>
            <span class="sideTitle">QA</span>         
                <?php
                // }
                // else
                // {
                ?>
            <!-- <span class="sideTitle">QA</span>        -->
                <?php   
            //     }
            // }
            // # Menu Operator QC
            // if(isset($RDataUserWebtrax['MnOprQC']) && trim($RDataUserWebtrax['MnOprQC']) == "1" && trim($RDataUserWebtrax['MnAdmin']) == "0")
            // {
            //     if($AccessLogin == "Manager")
            //     {
                ?>
            <span class="sideTitle">QC</span>         
                <?php
                // }
                // else
                // {
                ?>
            <!-- <span class="sideTitle">QC</span>        -->
                <?php   
            //     }
            // }
            // # Menu Assembly
            // if(isset($RDataUserWebtrax['MnOprAssembly']) && trim($RDataUserWebtrax['MnOprAssembly']) == "1" && trim($RDataUserWebtrax['MnAdmin']) == "0")
            // {
            //     if($AccessLogin == "Manager")
            //     {
                ?>
            <span class="sideTitle">Assembly</span>         
                <?php
                // }
                // else
                // {
                ?>
            <!-- <span class="sideTitle">Assembly</span>        -->
                <?php   
            //     }
            // }
            // # Menu Operator Cutting Material
            // if(isset($RDataUserWebtrax['MnOprCuttingMaterial']) && trim($RDataUserWebtrax['MnOprCuttingMaterial']) == "1" && trim($RDataUserWebtrax['MnAdmin']) == "0")
            // {
            //     if($AccessLogin == "Manager")
            //     {
                ?>
            <span class="sideTitle">Cutting Material</span>         
                <?php
                // }
                // else
                // {
                ?>
            <!-- <span class="sideTitle">Cutting Material</span>        -->
                <?php   
            //     }
            // }
            // # Menu Operator Packing
            // if(isset($RDataUserWebtrax['MnOprPacking']) && trim($RDataUserWebtrax['MnOprPacking']) == "1" && trim($RDataUserWebtrax['MnAdmin']) == "0")
            // {
            //     if($AccessLogin == "Manager")
            //     {
                ?>
            <span class="sideTitle">Packing</span>         
                <?php
                // }
                // else
                // {
                ?>
            <!-- <span class="sideTitle">Packing</span>        -->
                <?php   
            //     }
            // }
            // # Menu Operator Injection
            // if(isset($RDataUserWebtrax['MnOprInjection']) && trim($RDataUserWebtrax['MnOprInjection']) == "1" && trim($RDataUserWebtrax['MnAdmin']) == "0")
            // {
            //     if($AccessLogin == "Manager")
            //     {
                ?>
            <span class="sideTitle">Injection</span>         
                <?php
                // }
                // else
                // {
                ?>
            <!-- <span class="sideTitle">Injection</span>        -->
                <?php   
            //     }
            // }
            // # Menu Warehouse
            // if(isset($RDataUserWebtrax['MnWarehouse']) && trim($RDataUserWebtrax['MnWarehouse']) == "1" && trim($RDataUserWebtrax['MnAdmin']) == "0")
            // {
            //     if($AccessLogin == "Manager")
            //     {
                ?>
            <span class="sideTitle">Warehouse</span>         
                <?php
                // }
                // else
                // {
                ?>
            <!-- <span class="sideTitle">Warehouse</span>        -->
                <?php   
            //     }
            // }
            // # Menu Exim
            // if(isset($RDataUserWebtrax['MnExim']) && trim($RDataUserWebtrax['MnExim']) == "1" && trim($RDataUserWebtrax['MnAdmin']) == "0")
            // {
            //     if($AccessLogin == "Manager")
            //     {
                ?>
            <span class="sideTitle">Export Import</span>         
                <?php
                // }
                // else
                // {
                ?>
            <!-- <span class="sideTitle">Export Import</span>        -->
                <?php   
            //     }
            // }
            // # Menu KaShift
            // if(isset($RDataUserWebtrax['MnKAShift']) && trim($RDataUserWebtrax['MnKAShift']) == "1" && trim($RDataUserWebtrax['MnAdmin']) == "0")
            // {
            //     if($AccessLogin == "Manager")
            //     {
                ?>
            <span class="sideTitle">KaShift</span>         
                <?php
                // }
                // else
                // {
                ?>
            <!-- <span class="sideTitle">KaShift</span>        -->
                <?php   
            //     }
            // }
			
			
            // # Menu Admin
            // if(isset($RDataUserWebtrax['MnAdmin']) && trim($RDataUserWebtrax['MnAdmin']) == "1")
            // {
                ?>                
            <!-- <span class="sideTitle">Building Management</span>
            <a href="home.php?link=2"><i class="bi bi-caret-right-fill"></i>&nbsp;Manage Electricity Usage Salatiga New</a>
            <a href="home.php?link=3"><i class="bi bi-caret-right-fill"></i>&nbsp;Manage Electricity Usage Salatiga Old</a>
            <a href="home.php?link=4"><i class="bi bi-caret-right-fill"></i>&nbsp;Manage Electricity Usage Semarang</a>
            <span class="sideTitle">Cost Tracking</span>
            <a href="home.php?link=5"><i class="bi bi-caret-right-fill"></i>&nbsp;Manage Quality Point</a>  
            <a href="home.php?link=7"><i class="bi bi-caret-right-fill"></i>&nbsp;Manage Target Cost</a>
            <a href="home.php?link=8"><i class="bi bi-caret-right-fill"></i>&nbsp;Manage Target Qty</a> 
            <a href="home.php?link=14"><i class="bi bi-caret-right-fill"></i>&nbsp;Manage WO Closed Chart</a>  
            <a href="home.php?link=15"><i class="bi bi-caret-right-fill"></i>&nbsp;Manage Quantity Build</a>
            <a href="home.php?link=20"><i class="bi bi-caret-right-fill"></i>&nbsp;Manage Periodic Quote Cost</a> 
            <a href="home.php?link=21"><i class="bi bi-caret-right-fill"></i>&nbsp;Manage OTS Cost</a> 
            <a href="home.php?link=22"><i class="bi bi-caret-right-fill"></i>&nbsp;Manage ShiftCode Machine</a>
            <span class="sideTitle">Production</span>
            <a href="home.php?link=6"><i class="bi bi-caret-right-fill"></i>&nbsp;WIP</a>  
            <span class="sideTitle">Report</span>
            <a href="home.php?link=11"><i class="bi bi-caret-right-fill"></i>&nbsp;Timetracking</a>  
            <?php /*<a href="home.php?link=12"><i class="bi bi-caret-right-fill"></i>&nbsp;Machine Tracking</a>  
            <a href="home.php?link=13"><i class="bi bi-caret-right-fill"></i>&nbsp;Material Tracking</a> */ ?> 
            <a href="home.php?link=19"><i class="bi bi-caret-right-fill"></i>&nbsp;Monitoring Machine</a>       
            <span class="sideTitle">PPIC</span>
            <span class="sideTitleLv1">BC Part / Job</span>   
            <a href="home.php?link=17" class="sideTitleLv2"><i class="bi bi-caret-right-fill"></i>&nbsp;Buat BC Part / Job</a> 
            <span class="sideTitle">Machining CNC</span> 
            <span class="sideTitle">Machining Manual</span>   
            <span class="sideTitle">Fabrication</span>  
            <span class="sideTitle">Finishing</span>
            <span class="sideTitle">QA</span>     
            <span class="sideTitle">QC</span>     
            <span class="sideTitle">Assembly</span>
            <span class="sideTitle">Cutting Material</span>
            <span class="sideTitle">Packing</span>   
            <span class="sideTitle">Injection</span>   
            <span class="sideTitle">Warehouse</span>   
            <span class="sideTitle">Export Import</span>   
            <span class="sideTitle">KaShift</span>
            <span class="sideTitle">CCTV</span>
            <a href="home.php?link=9"><i class="bi bi-caret-right-fill"></i>&nbsp;CCTV Surveilance</a>  -->
            <span class="sideTitle">Administration</span>
            <a href="home.php?link=10"><i class="bi bi-caret-right-fill"></i>&nbsp;Manage User Access</a>
            <a href="home.php?link=16"><i class="bi bi-caret-right-fill"></i>&nbsp;Manage Labour Hour</a> 
            <a href="home.php?link=27"><i class="bi bi-caret-right-fill"></i>&nbsp;Manage Recalculate Log</a> 
                <?php
        //     }
        // }
        // else
        // {
        //     //tdk terdaftar di protrax user
            
        // }
        ?>
        </div>
    </div>
    <div class="container-fluid" id="ContentPage">
        <?php
        if(isset($_REQUEST['link']))
        {
            require_once("HomeContent.php");
        }
        else
        {
            echo "";


        }
        ?>
    </div>
    <script src="lib/bootstrap-5.0.2/dist/js/bootstrap.min.js"></script><?php /*
    <!--<script src="lib/DataTables-v1.11.3/DataTables-1.11.3/js/dataTables.bootstrap.min.js"></script>--> */ ?>
</body>
</html>
