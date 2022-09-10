<?php
switch ($_GET['link']) {
    case '1':
		require("project/Security/MainPage.php");
        break;
    case '2':
		require("project/KWHTracking/FormKWHTracking.php");
        break;
    case '3':
		require("project/KWHTracking/KelolaKWHTrackingFI.php");
        break;
    case '4':
		require("project/KWHTracking/KWHTrackingPSM.php");
        break;
    case '5':
		require("project/QualityPoint/QualityPointPage.php");
        break;
    case '6':
		require("project/WIP/WIP.php");
        break;
    case '7':
		require("project/CostTracking/FormInputTargetCost.php");
        break;
    case '8':
		require("project/CostTracking/FormInputTargetQty.php");
        break;
    case '9':
		require("project/CCTVSurveilance/Main.php");
        break;
    case '10':
		require("project/User/Main.php");
        break;
    case '11':
		require("project/Report/DownloadTimeTracking.php");
        break;
    // case '12':
		// require("project/Report/DownloadMachineTracking.php");
        // break;
    // case '13':
		// require("project/Report/DownloadMaterialTracking.php");
        // break;
    case '14':
		require("project/CostTracking/ManageWOClosedChart.php");
        break;
    case '15':
		require("project/CostTracking/FormInputQuantityBuildPoint.php");
        break;
    case '16':
		require("project/TimeTracking/ManageLabourHour.php");
        break;
    case '17':
		require("project/PPIC/FormNewBCPartJob.php");
        break;
    // case '18':
		// require("project/PPIC/FormNewBCMaterial.php");
        // break;
    case '19':
		require("project/Report/MonitoringMachine.php");
        break;
    case '20':
		require("project/CostTracking/FormManagePeriodicQuoteCost.php");
        break;
    case '21':
		require("project/CostTracking/FormManageOTSCost.php");
        break;
    case '22':
		require("project/CostTracking/FormManageShiftCodeMachine.php");
        break;
    case '23':
		require("project/Report/DownloadMachineTracking.php");
        break;    
    case '24':
	    require("project/Report/DownloadMaterialTracking.php");
        break;  
    case '25':
	    require("project/Report/DownloadWOMapping.php");
        break;      
    case '26':
	    require("project/Report/BarcodeStatusPage.php");
        break;
    case '27':
	    require("project/Report/RecalculateLogPage.php");
        break;  
    case '28':
	    require("project/PPIC/MaterialMappingPage.php");
        break;
    case '29':
	    require("project/Report/IoTSpindlePage.php");
        break;
    case '30':
        require("project/WOMapping/ManageWOMapping.php");
        break;
    case '31':
        require("project/WOMapping/CreateWOMapping.php");
        break;
    case '32':
        require("project/CostTracking/ManagePeoplePoint.php");
        break;
    case '33':
        require("project/WIP/MoveAndReceivedInventory.php");
        break;
    case '34':
        require("project/WIP/MoveInvPage.php");
        break;
    case '35':
        require("project/WIP/ReceiveInvPage.php");
        break;
    case '36':
        require("project/Inventory/FormInOutPartTBZPerLabel.php");
        break;
    case '37':
        require("project/WIP/MoveInvPageV3.php");
        break;
    case '38':
        require("project/Inventory/FormInOutPartTBZPerLabel_REF_1.php");
        break;
    case '39':
        require("project/WOMapping/RecalWOMapping.php");
        break;
    case '40':
        require("project/Inventory/StockOpname.php");
        break;
    case '41':
        require("project/Inventory/StockOpnameV2.php");
        break;
    case '42':
        require("project/KaShift/FormMachiningCheckInOut.php");
        break;
    case '43':
        require("project/WIP/CreateWIP.php");
        break;
    case '44':
        require("project/WIP2/FormAmbilKeluar.php");
        break;
    default:
        echo '<script type="text/javascript">$(document).ready(function(){window.location.href = "home.php";});</script>';
        break;
}

?>