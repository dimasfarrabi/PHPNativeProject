<?php
switch ($_GET['link']) {
    case '1':
		require("FormTimetracking.php");
        break;
    case '2':
		require("FormMappingSubstationActivity.php");
        break;
    case '3':
		require("project/KWHTracking/FormKWHTracking.php");
        break;
    case '4':
		require("project/CostTracking/TableWOClosedPSL.php");
        break;
    case '5':
		require("project/CostTracking/TableWOOpenPSL.php");
        break;
    case '6':
		require("project/CostTracking/TableWOClosedPSM.php");
        break;
    case '7':
		require("project/CostTracking/TableWOOpenPSM.php");
        break;
    case '8':
		require("project/KWHTracking/Dashboard.php");
        break;
    case '9':
		// require("project/KWHTracking/Dashboard2.php");
		require("project/KWHTracking/DashboardKWHTrackingPSL.php");
        break;
    case '10':
		require("project/KelolaGuest/FormKelolaGuest.php");
        break;
    case '11':
		require("project/KWHTracking/KWHTrackingPSM.php");
        break;
    case '12':
		// require("project/KWHTracking/DashboardPSM.php");
		require("project/KWHTracking/DashboardKWHTrackingPSM.php");
        break;
    case '13':
        require("project/CCTVSurveilance/CCTVPage.php");
        break;
    case '14':
        require("project/WIPSims/WIPSims.php");
        break;
    case '15':
        require("project/WIPSims/WIPOutput.php");
        break;
    case '16':
        require("project/KWHTracking/KelolaKWHTrackingFI.php");
        break;
    case '17':
        require("project/KWHTracking/DashboardKWHTrackingFI.php");
        break;
    case '18':
        require("project/QualityPoint/QualityPointPage.php");
        break;
    case '19':
        require("project/PointAchievement/PointAchievementPage.php");
        break;
    case '20':
        require("project/CostTracking/WOCloseChart.php");
        break;
    case '21':
        require("project/CostTracking/WOOpenChart.php");
        break;
    case '22':
        require("project/Employee/MainPage.php");
        break;
    case '23':
        require("project/Security/MainPage.php");
        break;
    case '24':
        require("project/Employee/EmployeeListMain.php");
        break;
    case '25':
        require("project/CostTracking/FormInputTargetCost.php");
        break;
    case '26':
        require("project/CostTracking/FormInputTargetQty.php");
        break;
    case '27':
        require("project/Employee/EmployeePercentage.php");
        break;
    case '28':
        require("project/CostTracking/TableQualityReport.php");
        break;
    case '30':
        require("project/QuantityPoint/QuantityPointPage.php");
        break;
    case '31':
        require("project/Reconciliation/ReconTTPage.php");
        break;
    case '32':
        require("project/Reconciliation/ReconMachinePage.php");
        break;
    case '33':
        require("project/Reconciliation/ReconMaterialPage.php");
        break;
    case '34':
        require("project/Reconciliation/SyncReconPageV2.php");
        break;
    case '35':
        require("project/ReconciliationV1/SyncReconPage.php");
        break;   
    case '36':
        require("project/WIPSims/BarcodePartPage.php");
        break;  
    case '37':
        require("project/WIPSims/MachineSchedulePage.php");
        break;
    case '38':
        require("project/KPI/WarehouseProcessPage.php");
        break;  
    case '39':
        require("project/KPI/MachiningProcessPage.php");
        break; 
    case '40':
        require("project/KPI/FinishingProcessPage.php");
        break; 
    case '41':
        require("project/WIPSims/WIPSimsV2.php");
        break;          
    case '42':
        require("project/KPI/InjectionProcessPage.php");
        break;
    case '43':
        require("project/KPI/InjectionProcessPage2.php");
        break;
    // case '44':
    //     require("project/KPI/QCProcessPage.php");
    //     break;
    case '45':
        require("project/Reconciliation/ReconMaterialPageV2.php");
        break;
    case '46':
        require("project/WIPSims/SpindleReportPage.php");
        break;
    case '47':
        require("project/Reconciliation/IoTSpindlePage.php");
        break;
    case '48':
        require("project/Reconciliation/WOReconPage.php");
        break;
    case '49':
        require("project/Shipping/FreightQtyWeightChartV2.php");
        break;
    case '50':
        require("project/Shipping/DestinationCountryChart.php");
        break;
    case '51':
        require("project/Shipping/InboundFreightPage.php");
        break;
    case '52':
        require("project/Shipping/CourierChartV2.php");
        break;
    case '53':
        require("project/CostTracking/WOTargetCostPage.php");
        break;
    case '54':
        require("project/WIPSims/KittingHistory.php");
        break;
    default:
        echo '<script type="text/javascript">$(document).ready(function(){window.location.href = "home.php";});</script>';
        break;
}

?>