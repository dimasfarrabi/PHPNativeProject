<?php
session_start();
require_once("../../ConfigDB.php");
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleUser.php");
date_default_timezone_set("Asia/Jakarta");
$YearNow = date("Y");
$DateNow = date("m/d/Y");
/*
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
if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $ValID = htmlspecialchars(trim($_POST['ValID']), ENT_QUOTES, "UTF-8");
    $ValID = base64_decode(base64_decode(trim($ValID)));
    # GET DATA USER
    $QUser = GET_DATA_PROTRAX_USER($ValID,$linkMACHWebTrax);
    $RUser = sqlsrv_fetch_array($QUser);
    $ValName = trim($RUser['FullName']);
    $ValUsername = trim($RUser['username']);
    $ValCompany = trim($RUser['Company']);
    $ValActive = trim($RUser['Is_Active']);
    $ValType = trim($RUser['TypeUser']);
    $ValMnAdmin = trim($RUser['MnAdmin']);
    $ValMnSecurity = trim($RUser['MnSecurity']);
    $ValMnCostTracking = trim($RUser['MnCostTracking']);
    $ValMnProduction = trim($RUser['MnProduction']);
    $ValMnCCTV = trim($RUser['MnCCTV']);
    $ValMnReport = trim($RUser['MnReport']);
    $ValMnPPIC = trim($RUser['MnPPIC']);
    $ValMnOprMachCNC = trim($RUser['MnOprMachCNC']);
    $ValMnOprMachManual = trim($RUser['MnOprMachManual']);
    $ValMnOprFabrication = trim($RUser['MnOprFabrication']);
    $ValMnOprFinishing = trim($RUser['MnOprFinishing']);
    $ValMnOprQA = trim($RUser['MnOprQA']);
    $ValMnOprQC = trim($RUser['MnOprQC']);
    $ValMnOprAssembly = trim($RUser['MnOprAssembly']);
    $ValMnOprCuttingMaterial = trim($RUser['MnOprCuttingMaterial']);
    $ValMnOprPacking = trim($RUser['MnOprPacking']);
    $ValMnOprInjection = trim($RUser['MnOprInjection']);
    $ValMnWarehouse = trim($RUser['MnWarehouse']);
    $ValMnExim = trim($RUser['MnExim']);
    $ValMnKAShift = trim($RUser['MnKAShift']);
    $ValMnClosedWO = trim($RUser['MnClosedWO']);
    ?>
<div class="row">
    <form>
        <div class="col-md-12 mb-2">
            <label for="InputNewGuestMod" class="form-label fw-bold">Name</label>
            <input type="text" class="form-control" name="InputNewGuestMod" id="InputNewGuestMod" value="<?php echo $ValName; ?>" required>
        </div>
        <div class="col-md-12 mb-2">
            <label for="InputNewUsernameMod" class="form-label fw-bold">Username</label>
            <input type="text" class="form-control" name="InputNewUsernameMod" id="InputNewUsernameMod" value="<?php echo $ValUsername; ?>" readonly>
        </div>
        <div class="col-md-12 mb-2">
            <label for="InputActiveMod" class="form-label fw-bold">Is Active</label>
            <div class="row row-cols-auto">
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="InputActiveMod" id="InputActiveMod1" value="1"<?php if($ValActive == "1"){echo " checked";} ?>>
                        <label class="form-check-label" for="InputActiveMod1">Yes</label>
                    </div> 
                </div>
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="InputActiveMod" id="InputActiveMod2" value="0"<?php if($ValActive == "0"){echo " checked";} ?>>
                        <label class="form-check-label" for="InputActiveMod2">No</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 mb-2">
            <label for="InputLocationCompanyMod" class="form-label fw-bold">Company</label>
            <select class="form-select" name="InputLocationCompanyMod" id="InputLocationCompanyMod">
                <option value="FI"<?php if($ValCompany == "FI"){echo " selected";} ?>>Formulatrix Indonesia</option>
                <option value="PSL"<?php if($ValCompany == "PSL"){echo " selected";} ?>>Promanufacture Salatiga</option>
                <option value="PSM"<?php if($ValCompany == "PSM"){echo " selected";} ?>>Promanufacture Semarang</option>
            </select>
        </div>
        <div class="col-md-12 mb-2">
            <label for="InputTypeUserMod" class="form-label fw-bold">Type</label>
            <select class="form-select" name="InputTypeUserMod" id="InputTypeUserMod">
                <option<?php if($ValType == "Employee"){echo " selected";} ?>>Employee</option>
                <option<?php if($ValType == "Manager"){echo " selected";} ?>>Manager</option>
            </select>
        </div>
        <div class="col-md-12 mb-2">
            <label for="InputAccessAdminMod" class="form-label fw-bold">Is Administration</label>
            <div class="row row-cols-auto">
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="InputAccessAdminMod" id="InputAccessAdminMod1" value="1"<?php if($ValMnAdmin == "1"){echo " checked";} ?>>
                        <label class="form-check-label" for="InputAccessAdminMod1">Yes</label>
                    </div> 
                </div>
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="InputAccessAdminMod" id="InputAccessAdminMod2" value="0"<?php if($ValMnAdmin == "0"){echo " checked";} ?>>
                        <label class="form-check-label" for="InputAccessAdminMod2">No</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 mb-2">
            <label for="InputAccessSecurityMod" class="form-label fw-bold">Access Menu Security</label>
            <div class="row row-cols-auto">
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="InputAccessSecurityMod" id="InputAccessSecurityMod1" value="1"<?php if($ValMnSecurity == "1"){echo " checked";} ?>>
                        <label class="form-check-label" for="InputAccessSecurityMod1">Yes</label>
                    </div>
                </div>
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="InputAccessSecurityMod" id="InputAccessSecurityMod2" value="0"<?php if($ValMnSecurity == "0"){echo " checked";} ?>>
                        <label class="form-check-label" for="InputAccessSecurityMod2">No</label>
                    </div>
                </div>
            </div>
        </div>            
        <div class="col-md-12 mb-2">
            <label for="InputAccessCostTrackingMod" class="form-label fw-bold">Access Menu Cost Tracking</label>
            <div class="row row-cols-auto">
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="InputAccessCostTrackingMod" id="InputAccessCostTrackingMod1" value="1"<?php if($ValMnCostTracking == "1"){echo " checked";} ?>>
                        <label class="form-check-label" for="InputAccessCostTrackingMod1">Yes</label>
                    </div>
                </div>
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="InputAccessCostTrackingMod" id="InputAccessCostTrackingMod2" value="0"<?php if($ValMnCostTracking == "0"){echo " checked";} ?>>
                        <label class="form-check-label" for="InputAccessCostTrackingMod2">No</label>
                    </div>
                </div>
            </div>
        </div>            
        <div class="col-md-12 mb-2">
            <label for="InputAccessProductionMod" class="form-label fw-bold">Access Menu Production</label>
            <div class="row row-cols-auto">
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="InputAccessProductionMod" id="InputAccessProductionMod1" value="1"<?php if($ValMnProduction == "1"){echo " checked";} ?>>
                        <label class="form-check-label" for="InputAccessProductionMod1">Yes</label>
                    </div>
                </div>
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="InputAccessProductionMod" id="InputAccessProductionMod2" value="0"<?php if($ValMnProduction == "0"){echo " checked";} ?>>
                        <label class="form-check-label" for="InputAccessProductionMod2">No</label>
                    </div>
                </div>
            </div>
        </div>            
        <div class="col-md-12 mb-2">
            <label for="InputAccessCCTVMod" class="form-label fw-bold">Access Menu CCTV</label>
            <div class="row row-cols-auto">
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="InputAccessCCTVMod" id="InputAccessCCTVMod1" value="1"<?php if($ValMnCCTV == "1"){echo " checked";} ?>>
                        <label class="form-check-label" for="InputAccessCCTVMod1">Yes</label>
                    </div>
                </div>
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="InputAccessCCTVMod" id="InputAccessCCTVMod2" value="0"<?php if($ValMnCCTV == "0"){echo " checked";} ?>>
                        <label class="form-check-label" for="InputAccessCCTVMod2">No</label>
                    </div>
                </div>
            </div>
        </div>            
        <div class="col-md-12 mb-2">
            <label for="InputAccessReportMod" class="form-label fw-bold">Access Menu Report</label>
            <div class="row row-cols-auto">
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="InputAccessReportMod" id="InputAccessReportMod1" value="1"<?php if($ValMnReport == "1"){echo " checked";} ?>>
                        <label class="form-check-label" for="InputAccessReportMod1">Yes</label>
                    </div>
                </div>
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="InputAccessReportMod" id="InputAccessReportMod2" value="0"<?php if($ValMnReport == "0"){echo " checked";} ?>>
                        <label class="form-check-label" for="InputAccessReportMod2">No</label>
                    </div>
                </div>
            </div>
        </div>            
        <div class="col-md-12 mb-2">
            <label for="InputAccessPPICMod" class="form-label fw-bold">Access Menu PPIC</label>
            <div class="row row-cols-auto">
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="InputAccessPPICMod" id="InputAccessPPICMod1" value="1"<?php if($ValMnPPIC == "1"){echo " checked";} ?>>
                        <label class="form-check-label" for="InputAccessPPICMod1">Yes</label>
                    </div>
                </div>
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="InputAccessPPICMod" id="InputAccessPPICMod2" value="0"<?php if($ValMnPPIC == "0"){echo " checked";} ?>>
                        <label class="form-check-label" for="InputAccessPPICMod2">No</label>
                    </div>
                </div>
            </div>
        </div>            
        <div class="col-md-12 mb-2">
            <label for="InputAccessOprMachCNCMod" class="form-label fw-bold">Access Menu Operator Machining CNC</label>
            <div class="row row-cols-auto">
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="InputAccessOprMachCNCMod" id="InputAccessOprMachCNCMod1" value="1"<?php if($ValMnOprMachCNC == "1"){echo " checked";} ?>>
                        <label class="form-check-label" for="InputAccessOprMachCNCMod1">Yes</label>
                    </div>
                </div>
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="InputAccessOprMachCNCMod" id="InputAccessOprMachCNCMod2" value="0"<?php if($ValMnOprMachCNC == "0"){echo " checked";} ?>>
                        <label class="form-check-label" for="InputAccessOprMachCNCMod2">No</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 mb-2">
            <label for="InputAccessOprMachManualMod" class="form-label fw-bold">Access Menu Operator Machining Manual</label>
            <div class="row row-cols-auto">
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="InputAccessOprMachManualMod" id="InputAccessOprMachManualMod1" value="1"<?php if($ValMnOprMachManual == "1"){echo " checked";} ?>>
                        <label class="form-check-label" for="InputAccessOprMachManualMod1">Yes</label>
                    </div>
                </div>
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="InputAccessOprMachManualMod" id="InputAccessOprMachManualMod2" value="0"<?php if($ValMnOprMachManual == "0"){echo " checked";} ?>>
                        <label class="form-check-label" for="InputAccessOprMachManualMod2">No</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 mb-2">
            <label for="InputAccessOprFabricationMod" class="form-label fw-bold">Access Menu Operator Fabrication</label>
            <div class="row row-cols-auto">
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="InputAccessOprFabricationMod" id="InputAccessOprFabricationMod1" value="1"<?php if($ValMnOprFabrication == "1"){echo " checked";} ?>>
                        <label class="form-check-label" for="InputAccessOprFabricationMod1">Yes</label>
                    </div>
                </div>
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="InputAccessOprFabricationMod" id="InputAccessOprFabricationMod2" value="0"<?php if($ValMnOprFabrication == "0"){echo " checked";} ?>>
                        <label class="form-check-label" for="InputAccessOprFabricationMod2">No</label>
                    </div>
                </div>
            </div>
        </div>        
        <div class="col-md-12 mb-2">
            <label for="InputAccessOprFinishingMod" class="form-label fw-bold">Access Menu Operator Finishing</label>
            <div class="row row-cols-auto">
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="InputAccessOprFinishingMod" id="InputAccessOprFinishingMod1" value="1"<?php if($ValMnOprFinishing == "1"){echo " checked";} ?>>
                        <label class="form-check-label" for="InputAccessOprFinishingMod1">Yes</label>
                    </div>
                </div>
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="InputAccessOprFinishingMod" id="InputAccessOprFinishingMod2" value="0"<?php if($ValMnOprFinishing == "0"){echo " checked";} ?>>
                        <label class="form-check-label" for="InputAccessOprFinishingMod2">No</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 mb-2">
            <label for="InputAccessOprQAMod" class="form-label fw-bold">Access Menu Operator QA</label>
            <div class="row row-cols-auto">
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="InputAccessOprQAMod" id="InputAccessOprQAMod1" value="1"<?php if($ValMnOprQA == "1"){echo " checked";} ?>>
                        <label class="form-check-label" for="InputAccessOprQAMod1">Yes</label>
                    </div>
                </div>
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="InputAccessOprQAMod" id="InputAccessOprQAMod2" value="0"<?php if($ValMnOprQA == "0"){echo " checked";} ?>>
                        <label class="form-check-label" for="InputAccessOprQAMod2">No</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 mb-2">
            <label for="InputAccessOprQCMod" class="form-label fw-bold">Access Menu Operator QC</label>
            <div class="row row-cols-auto">
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="InputAccessOprQCMod" id="InputAccessOprQCMod1" value="1"<?php if($ValMnOprQC == "1"){echo " checked";} ?>>
                        <label class="form-check-label" for="InputAccessOprQCMod1">Yes</label>
                    </div>
                </div>
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="InputAccessOprQCMod" id="InputAccessOprQCMod2" value="0"<?php if($ValMnOprQC == "0"){echo " checked";} ?>>
                        <label class="form-check-label" for="InputAccessOprQCMod2">No</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 mb-2">
            <label for="InputAccessOprAssemblyMod" class="form-label fw-bold">Access Menu Operator Assembly</label>
            <div class="row row-cols-auto">
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="InputAccessOprAssemblyMod" id="InputAccessOprAssemblyMod1" value="1"<?php if($ValMnOprAssembly == "1"){echo " checked";} ?>>
                        <label class="form-check-label" for="InputAccessOprAssemblyMod1">Yes</label>
                    </div>
                </div>
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="InputAccessOprAssemblyMod" id="InputAccessOprAssemblyMod2" value="0"<?php if($ValMnOprAssembly == "0"){echo " checked";} ?>>
                        <label class="form-check-label" for="InputAccessOprAssemblyMod2">No</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 mb-2">
            <label for="InputAccessOprCuttingMaterialMod" class="form-label fw-bold">Access Menu Operator Cutting Material</label>
            <div class="row row-cols-auto">
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="InputAccessOprCuttingMaterialMod" id="InputAccessOprCuttingMaterialMod1" value="1"<?php if($ValMnOprCuttingMaterial == "1"){echo " checked";} ?>>
                        <label class="form-check-label" for="InputAccessOprCuttingMaterialMod1">Yes</label>
                    </div>
                </div>
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="InputAccessOprCuttingMaterialMod" id="InputAccessOprCuttingMaterialMod2" value="0"<?php if($ValMnOprCuttingMaterial == "0"){echo " checked";} ?>>
                        <label class="form-check-label" for="InputAccessOprCuttingMaterialMod2">No</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 mb-2">
            <label for="InputAccessOprPackingMod" class="form-label fw-bold">Access Menu Operator Packing</label>
            <div class="row row-cols-auto">
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="InputAccessOprPackingMod" id="InputAccessOprPackingMod1" value="1"<?php if($ValMnOprPacking == "1"){echo " checked";} ?>>
                        <label class="form-check-label" for="InputAccessOprPackingMod1">Yes</label>
                    </div>
                </div>
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="InputAccessOprPackingMod" id="InputAccessOprPackingMod2" value="0"<?php if($ValMnOprPacking == "0"){echo " checked";} ?>>
                        <label class="form-check-label" for="InputAccessOprPackingMod2">No</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 mb-2">
            <label for="InputAccessOprInjectionMod" class="form-label fw-bold">Access Menu Operator Injection</label>
            <div class="row row-cols-auto">
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="InputAccessOprInjectionMod" id="InputAccessOprInjectionMod1" value="1"<?php if($ValMnOprInjection == "1"){echo " checked";} ?>>
                        <label class="form-check-label" for="InputAccessOprInjectionMod1">Yes</label>
                    </div>
                </div>
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="InputAccessOprInjectionMod" id="InputAccessOprInjectionMod2" value="0"<?php if($ValMnOprInjection == "0"){echo " checked";} ?>>
                        <label class="form-check-label" for="InputAccessOprInjectionMod2">No</label>
                    </div>
                </div>
            </div>
        </div>        
        <div class="col-md-12 mb-2">
            <label for="InputAccessOprWarehouseMod" class="form-label fw-bold">Access Menu Operator Warehouse</label>
            <div class="row row-cols-auto">
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="InputAccessOprWarehouseMod" id="InputAccessOprWarehouseMod1" value="1"<?php if($ValMnWarehouse == "1"){echo " checked";} ?>>
                        <label class="form-check-label" for="InputAccessOprWarehouseMod1">Yes</label>
                    </div>
                </div>
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="InputAccessOprWarehouseMod" id="InputAccessOprWarehouseMod2" value="0"<?php if($ValMnWarehouse == "0"){echo " checked";} ?>>
                        <label class="form-check-label" for="InputAccessOprWarehouseMod2">No</label>
                    </div>
                </div>
            </div>
        </div>        
        <div class="col-md-12 mb-2">
            <label for="InputAccessOprEximMod" class="form-label fw-bold">Access Menu Operator Exim</label>
            <div class="row row-cols-auto">
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="InputAccessOprEximMod" id="InputAccessOprEximMod1" value="1"<?php if($ValMnExim == "1"){echo " checked";} ?>>
                        <label class="form-check-label" for="InputAccessOprEximMod1">Yes</label>
                    </div>
                </div>
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="InputAccessOprEximMod" id="InputAccessOprEximMod2" value="0"<?php if($ValMnExim == "0"){echo " checked";} ?>>
                        <label class="form-check-label" for="InputAccessOprEximMod2">No</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 mb-2">
            <label for="InputAccessOprKAShiftMod" class="form-label fw-bold">Access Menu Operator KaShift</label>
            <div class="row row-cols-auto">
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="InputAccessOprKAShiftMod" id="InputAccessOprKAShiftMod1" value="1"<?php if($ValMnKAShift == "1"){echo " checked";} ?>>
                        <label class="form-check-label" for="InputAccessOprKAShiftMod1">Yes</label>
                    </div>
                </div>
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="InputAccessOprKAShiftMod" id="InputAccessOprKAShiftMod2" value="0"<?php if($ValMnKAShift == "0"){echo " checked";} ?>>
                        <label class="form-check-label" for="InputAccessOprKAShiftMod2">No</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 mb-2">
            <label for="InputAccessCloseWO" class="form-label fw-bold">Access Close WO</label>
            <div class="row row-cols-auto">
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="InputAccessCloseWO" id="InputAccessCloseWO1" value="1"<?php if($ValMnClosedWO == "1"){echo " checked";} ?>>
                        <label class="form-check-label" for="InputAccessCloseWO1">Yes</label>
                    </div>
                </div>
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="InputAccessCloseWO" id="InputAccessCloseWO2" value="0"<?php if($ValMnClosedWO == "0"){echo " checked";} ?>>
                        <label class="form-check-label" for="InputAccessCloseWO2">No</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12"><hr></div>
        <div class="col-md-12 d-grid">
            <button class="btn btn-dark btn-labeled" id="BtnUpdateUser" type="button">Update</button>
        </div> 
    </form>
</div>    
<div class="row" id="ResUpdateMsg"></div>
    
    <?php
}


?>

