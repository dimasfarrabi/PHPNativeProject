<?php
require_once("project/User/Modules/ModuleUser.php");
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
# data session
$FullName = strtoupper(base64_decode($_SESSION['FullNameUserProTrax']));
$UserNameSession = base64_decode(base64_decode($_SESSION['UIDProTrax']));
# data protrax user
$BolProdAcc = false;
$QDataUserWebtrax = GET_DATA_LOGIN_BY_USERNAME_ONLY($UserNameSession,$linkHRISWebTrax);
if(mssql_num_rows($QDataUserWebtrax) > 0)
{
    $RDataUserWebtrax = sqlsrv_fetch_array($QDataUserWebtrax);
    $TypeUser = trim($RDataUserWebtrax['TypeUser']);
    $_SESSION['LoginMode'] = base64_encode($TypeUser);
    $AccessLogin = base64_decode($_SESSION['LoginMode']);   
}
else # kondisi tidak terdaftar di protrax user & akan di set sebagai employee dan hak akses ke bagian produksi saja
{
    $_SESSION['LoginMode'] = base64_encode("Employee");
    $AccessLogin = base64_decode($_SESSION['LoginMode']);
    $BolProdAcc = true;
}

if($RDataUserWebtrax['MnAdmin'] != "1")  
{
    ?>
    <script language="javascript">
        window.location.replace("https://protrax.formulatrix.com/");
    </script>
    <?php
    exit();
}
*/
?>
<?php /*<script src="project/User/lib/LibManageUserAccess.js?no=<?php echo base64_encode(date("mdyHis")); ?>"></script> */ ?>
<div class="row">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-detail ">
                <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Administration : Manage User Access</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12"><button type="button" id="AddNewUser" class="btn btn-secondary btn-sm">Add New User</button></div>
            <div class="col-md-12"><hr></div>
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-responsive table-hover display" id="TableDataUser">
                        <thead>    
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center" width="30">#</th>
                                <th class="text-center">Name</th>
                                <th class="text-center">Username</th>
                                <th class="text-center">Status<br>Account</th>
                                <th class="text-center">Company</th>
                                <th class="text-center">Type</th>
                                <th class="text-center">IsAdmin</th>
                                <th class="text-center">MnSecurity</th>
                                <th class="text-center">MnCostTracking</th>
                                <th class="text-center">MnProduction</th>
                                <th class="text-center">MnCCTV</th>                               
                                <th class="text-center">MnReport</th>
                                <th class="text-center">MnPPIC</th>
                                <th class="text-center">MnOprMachCNC</th>
                                <th class="text-center">MnOprMachManual</th>
                                <th class="text-center">MnOprFabrication</th>
                                <th class="text-center">MnOprFinishing</th>
                                <th class="text-center">MnOprQA</th>
                                <th class="text-center">MnOprQC</th>
                                <th class="text-center">MnOprAssembly</th>
                                <th class="text-center">MnOprCuttingMaterial</th>
                                <th class="text-center">MnOprPacking</th>
                                <th class="text-center">MnOprInjection</th>
                                <th class="text-center">MnWarehouse</th>
                                <th class="text-center">MnExim</th>
                                <th class="text-center">MnKAShift</th>
                                <th class="text-center">MnClosedWO</th>
                            </tr>
                        </thead>
                        <tbody><?php
                        $No = 1;
                        $QList = GET_LIST_PROTRAX_USER($linkMACHWebTrax);
                        while($RList = sqlsrv_fetch_array($QList))
                        {
                            $ValID = trim($RList['Idx']);
                            $ValID = base64_encode(base64_encode(trim($ValID)));
                            if(trim($RList['Is_Active']) == "1"){$ValStatusAccount = '<span class="badge bg-success"><i class="bi bi-check-lg"></i></span>';}else{$ValStatusAccount = '<span class="badge bg-danger"><i class="bi bi-x-lg"></i></span>';}
                            switch(trim($RList['Company']))
                            {
                                case "PSL":
                                    $ValCompany = "Promanufacture Salatiga";
                                    break;
                                case "PSM":
                                    $ValCompany = "Promanufacture Semarang";
                                    break;
                                case "FI":
                                    $ValCompany = "Formulatrix Salatiga";
                                    break;
                                default:
                                        $ValCompany = "";
                                    break;
                            }
                            if(trim($RList['MnAdmin']) == "1"){$ValAdmin = '<span class="badge bg-success"><i class="bi bi-check-lg"></i></span>';}else{$ValAdmin = '<span class="badge bg-danger"><i class="bi bi-x-lg"></i></span>';}
                            if(trim($RList['MnSecurity']) == "1"){$ValMnSecurity = '<span class="badge bg-success"><i class="bi bi-check-lg"></i></span>';}else{$ValMnSecurity = '<span class="badge bg-danger"><i class="bi bi-x-lg"></i></span>';}                            
                            if(trim($RList['MnCostTracking']) == "1"){$ValMnCostTracking = '<span class="badge bg-success"><i class="bi bi-check-lg"></i></span>';}else{$ValMnCostTracking = '<span class="badge bg-danger"><i class="bi bi-x-lg"></i></span>';}
                            if(trim($RList['MnProduction']) == "1"){$ValMnProduction = '<span class="badge bg-success"><i class="bi bi-check-lg"></i></span>';}else{$ValMnProduction = '<span class="badge bg-danger"><i class="bi bi-x-lg"></i></span>';}
                            if(trim($RList['MnCCTV']) == "1"){$ValMnCCTV = '<span class="badge bg-success"><i class="bi bi-check-lg"></i></span>';}else{$ValMnCCTV = '<span class="badge bg-danger"><i class="bi bi-x-lg"></i></span>';}                            
                            if(trim($RList['MnReport']) == "1"){$ValMnReport = '<span class="badge bg-success"><i class="bi bi-check-lg"></i></span>';}else{$ValMnReport = '<span class="badge bg-danger"><i class="bi bi-x-lg"></i></span>';}
                            if(trim($RList['MnPPIC']) == "1"){$ValMnPPIC = '<span class="badge bg-success"><i class="bi bi-check-lg"></i></span>';}else{$ValMnPPIC = '<span class="badge bg-danger"><i class="bi bi-x-lg"></i></span>';}
                            if(trim($RList['MnOprMachCNC']) == "1"){$ValMnOprMachCNC = '<span class="badge bg-success"><i class="bi bi-check-lg"></i></span>';}else{$ValMnOprMachCNC= '<span class="badge bg-danger"><i class="bi bi-x-lg"></i></span>';}
                            if(trim($RList['MnOprMachManual']) == "1"){$ValMnOprMachManual = '<span class="badge bg-success"><i class="bi bi-check-lg"></i></span>';}else{$ValMnOprMachManual = '<span class="badge bg-danger"><i class="bi bi-x-lg"></i></span>';}
                            if(trim($RList['MnOprFabrication']) == "1"){$ValMnOprFabrication = '<span class="badge bg-success"><i class="bi bi-check-lg"></i></span>';}else{$ValMnOprFabrication = '<span class="badge bg-danger"><i class="bi bi-x-lg"></i></span>';}
                            if(trim($RList['MnOprFinishing']) == "1"){$ValMnOprFinishing = '<span class="badge bg-success"><i class="bi bi-check-lg"></i></span>';}else{$ValMnOprFinishing = '<span class="badge bg-danger"><i class="bi bi-x-lg"></i></span>';}
                            if(trim($RList['MnOprQA']) == "1"){$ValMnOprQA = '<span class="badge bg-success"><i class="bi bi-check-lg"></i></span>';}else{$ValMnOprQA = '<span class="badge bg-danger"><i class="bi bi-x-lg"></i></span>';}
                            if(trim($RList['MnOprQC']) == "1"){$ValMnOprQC = '<span class="badge bg-success"><i class="bi bi-check-lg"></i></span>';}else{$ValMnOprQC = '<span class="badge bg-danger"><i class="bi bi-x-lg"></i></span>';}
                            if(trim($RList['MnOprAssembly']) == "1"){$ValMnOprAssembly = '<span class="badge bg-success"><i class="bi bi-check-lg"></i></span>';}else{$ValMnOprAssembly = '<span class="badge bg-danger"><i class="bi bi-x-lg"></i></span>';}
                            if(trim($RList['MnOprCuttingMaterial']) == "1"){$ValMnOprCuttingMaterial = '<span class="badge bg-success"><i class="bi bi-check-lg"></i></span>';}else{$ValMnOprCuttingMaterial = '<span class="badge bg-danger"><i class="bi bi-x-lg"></i></span>';}
                            if(trim($RList['MnOprPacking']) == "1"){$ValMnOprPacking = '<span class="badge bg-success"><i class="bi bi-check-lg"></i></span>';}else{$ValMnOprPacking = '<span class="badge bg-danger"><i class="bi bi-x-lg"></i></span>';}
                            if(trim($RList['MnOprInjection']) == "1"){$ValMnOprInjection = '<span class="badge bg-success"><i class="bi bi-check-lg"></i></span>';}else{$ValMnOprInjection = '<span class="badge bg-danger"><i class="bi bi-x-lg"></i></span>';}
                            if(trim($RList['MnWarehouse']) == "1"){$ValMnWarehouse = '<span class="badge bg-success"><i class="bi bi-check-lg"></i></span>';}else{$ValMnWarehouse = '<span class="badge bg-danger"><i class="bi bi-x-lg"></i></span>';}
                            if(trim($RList['MnExim']) == "1"){$ValMnExim = '<span class="badge bg-success"><i class="bi bi-check-lg"></i></span>';}else{$ValMnExim = '<span class="badge bg-danger"><i class="bi bi-x-lg"></i></span>';}
                            if(trim($RList['MnKAShift']) == "1"){$ValMnKAShift = '<span class="badge bg-success"><i class="bi bi-check-lg"></i></span>';}else{$ValMnKAShift = '<span class="badge bg-danger"><i class="bi bi-x-lg"></i></span>';}
                            if(trim($RList['MnClosedWO']) == "1"){$ValMnCloseWO = '<span class="badge bg-success"><i class="bi bi-check-lg"></i></span>';}else{$ValMnCloseWO = '<span class="badge bg-danger"><i class="bi bi-x-lg"></i></span>';}
							
                            ?>
                            <tr>
                                <td class="text-center"><?php echo $No; ?></td>
                                <td class="text-center"><span class="ActUpdate" data-bs-toggle="modal" data-bs-target="#DataGuest" data-bs-dataid="<?php echo $ValID; ?>"><i class="bi bi-gear-fill text-primary" title="Update Data User"></i></span>&nbsp;&nbsp;
                                
                                <span class="ActKey" data-bs-toggle="modal" data-bs-target="#DataKey" data-bs-dataid="<?php echo $ValID; ?>"><i class="bi bi-key-fill text-primary" title="Update Password User"></i></span>
                                
                                &nbsp;&nbsp;<a href="project/User/src/srcDeleteUser.php?key=<?php echo $ValID; ?>" class="ActDelete"><i class="bi bi-trash-fill text-primary" title="Delete User"></i></a></td>
                                <td class="text-start"><?php echo trim($RList['FullName']); ?></td>
                                <td class="text-start"><?php echo trim($RList['username']); ?></td>
                                <td class="text-center"><?php echo $ValStatusAccount; ?></td>
                                <td class="text-start"><?php echo $ValCompany; ?></td>
                                <td class="text-center"><?php echo trim($RList['TypeUser']); ?></td>
                                <td class="text-center"><?php echo $ValAdmin; ?></td>
                                <td class="text-center"><?php echo $ValMnSecurity; ?></td>
                                <td class="text-center"><?php echo $ValMnCostTracking; ?></td>
                                <td class="text-center"><?php echo $ValMnProduction; ?></td>
                                <td class="text-center"><?php echo $ValMnCCTV; ?></td>
                                <td class="text-center"><?php echo $ValMnReport; ?></td>
                                <td class="text-center"><?php echo $ValMnPPIC; ?></td>
                                <td class="text-center"><?php echo $ValMnOprMachCNC; ?></td>
                                <td class="text-center"><?php echo $ValMnOprMachManual; ?></td>
                                <td class="text-center"><?php echo $ValMnOprFabrication; ?></td>
                                <td class="text-center"><?php echo $ValMnOprFinishing; ?></td>
                                <td class="text-center"><?php echo $ValMnOprQA; ?></td>
                                <td class="text-center"><?php echo $ValMnOprQC; ?></td>
                                <td class="text-center"><?php echo $ValMnOprAssembly; ?></td>
                                <td class="text-center"><?php echo $ValMnOprCuttingMaterial; ?></td>
                                <td class="text-center"><?php echo $ValMnOprPacking; ?></td>
                                <td class="text-center"><?php echo $ValMnOprInjection; ?></td>
                                <td class="text-center"><?php echo $ValMnWarehouse; ?></td>
                                <td class="text-center"><?php echo $ValMnExim; ?></td>
                                <td class="text-center"><?php echo $ValMnKAShift; ?></td>
                                <td class="text-center"><?php echo $ValMnCloseWO; ?></td>
                            </tr>
                            <?php
                            $No++;
                        }
                        ?></tbody>
                    </table>
                </div>
                
            </div>
        </div>            
    </div>
    <div class="col-md-12">&nbsp;</div>
    <div class="col-md-12">&nbsp;</div>
    <div class="col-md-12">&nbsp;</div>
    <div class="col-md-12">&nbsp;</div>
    <div class="col-md-12">&nbsp;</div>
    <div class="col-md-12">&nbsp;</div>
</div>
<span id="Temporary"></span>


<div class="modal fade" id="NewUser" tabindex="-1" role="dialog" aria-labelledby="Modal-View" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <form>
                        <div class="col-md-12 mb-2">
                            <label for="InputNewGuest" class="form-label fw-bold">Name</label>
                            <input type="text" class="form-control" name="InputNewGuest" id="InputNewGuest" required>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="InputNewUsername" class="form-label fw-bold">Username</label>
                            <input type="text" class="form-control" name="InputNewUsername" id="InputNewUsername" required>
                            <p class="help-block">*) Please fill this column with a valid employee email.</p>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="InputNewPassword" class="form-label fw-bold">Password</label>
                            <input type="password" class="form-control" name="InputNewPassword" id="InputNewPassword" required>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="InputLocationCompany" class="form-label fw-bold">Company</label>
                            <select class="form-select" name="InputLocationCompany" id="InputLocationCompany">
                                <option value="FI">Formulatrix Indonesia</option>
                                <option value="PSL">Promanufacture Salatiga</option>
                                <option value="PSM">Promanufacture Semarang</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="InputTypeUser" class="form-label fw-bold">Type</label>
                            <select class="form-select" name="InputTypeUser" id="InputTypeUser">
                                <option>Employee</option>
                                <option>Manager</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="InputAccessAdmin" class="form-label fw-bold">Is Administration</label>
                            <div class="row row-cols-auto">
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="InputAccessAdmin" id="InputAccessAdmin1" value="1">
                                        <label class="form-check-label" for="InputAccessAdmin1">Yes</label>
                                    </div> 
                                </div>
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="InputAccessAdmin" id="InputAccessAdmin2" value="0" checked>
                                        <label class="form-check-label" for="InputAccessAdmin2">No</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="InputAccessSecurity" class="form-label fw-bold">Access Menu Security</label>
                            <div class="row row-cols-auto">
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="InputAccessSecurity" id="InputAccessSecurity1" value="1">
                                        <label class="form-check-label" for="InputAccessSecurity1">Yes</label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="InputAccessSecurity" id="InputAccessSecurity2" value="0" checked>
                                        <label class="form-check-label" for="InputAccessSecurity2">No</label>
                                    </div>
                                </div>
                            </div>
                        </div>            
                        <div class="col-md-12 mb-2">
                            <label for="InputAccessCostTracking" class="form-label fw-bold">Access Menu Cost Tracking</label>
                            <div class="row row-cols-auto">
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="InputAccessCostTracking" id="InputAccessCostTracking1" value="1">
                                        <label class="form-check-label" for="InputAccessCostTracking1">Yes</label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="InputAccessCostTracking" id="InputAccessCostTracking2" value="0" checked>
                                        <label class="form-check-label" for="InputAccessCostTracking2">No</label>
                                    </div>
                                </div>
                            </div>
                        </div>            
                        <div class="col-md-12 mb-2">
                            <label for="InputAccessProduction" class="form-label fw-bold">Access Menu Production</label>
                            <div class="row row-cols-auto">
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="InputAccessProduction" id="InputAccessProduction1" value="1">
                                        <label class="form-check-label" for="InputAccessProduction1">Yes</label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="InputAccessProduction" id="InputAccessProduction2" value="0" checked>
                                        <label class="form-check-label" for="InputAccessProduction2">No</label>
                                    </div>
                                </div>
                            </div>
                        </div>            
                        <div class="col-md-12 mb-2">
                            <label for="InputAccessCCTV" class="form-label fw-bold">Access Menu CCTV</label>
                            <div class="row row-cols-auto">
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="InputAccessCCTV" id="InputAccessCCTV1" value="1">
                                        <label class="form-check-label" for="InputAccessCCTV1">Yes</label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="InputAccessCCTV" id="InputAccessCCTV2" value="0" checked>
                                        <label class="form-check-label" for="InputAccessCCTV2">No</label>
                                    </div>
                                </div>
                            </div>
                        </div>            
                        <div class="col-md-12 mb-2">
                            <label for="InputAccessReport" class="form-label fw-bold">Access Menu Report</label>
                            <div class="row row-cols-auto">
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="InputAccessReport" id="InputAccessReport1" value="1">
                                        <label class="form-check-label" for="InputAccessReport1">Yes</label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="InputAccessReport" id="InputAccessReport2" value="0" checked>
                                        <label class="form-check-label" for="InputAccessReport2">No</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="InputAccessPPIC" class="form-label fw-bold">Access Menu PPIC</label>
                            <div class="row row-cols-auto">
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="InputAccessPPIC" id="InputAccessPPIC1" value="1">
                                        <label class="form-check-label" for="InputAccessPPIC1">Yes</label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="InputAccessPPIC" id="InputAccessPPIC2" value="0" checked>
                                        <label class="form-check-label" for="InputAccessPPIC2">No</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="InputAccessOprMachCNC" class="form-label fw-bold">Access Menu Operator Machining CNC</label>
                            <div class="row row-cols-auto">
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="InputAccessOprMachCNC" id="InputAccessOprMachCNC1" value="1">
                                        <label class="form-check-label" for="InputAccessOprMachCNC1">Yes</label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="InputAccessOprMachCNC" id="InputAccessOprMachCNC2" value="0" checked>
                                        <label class="form-check-label" for="InputAccessOprMachCNC2">No</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="InputAccessOprMachManual" class="form-label fw-bold">Access Menu Operator Machining Manual</label>
                            <div class="row row-cols-auto">
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="InputAccessOprMachManual" id="InputAccessOprMachManual1" value="1">
                                        <label class="form-check-label" for="InputAccessOprMachManual1">Yes</label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="InputAccessOprMachManual" id="InputAccessOprMachManual2" value="0" checked>
                                        <label class="form-check-label" for="InputAccessOprMachManual2">No</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="InputAccessOprFabrication" class="form-label fw-bold">Access Menu Operator Fabrication</label>
                            <div class="row row-cols-auto">
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="InputAccessOprFabrication" id="InputAccessOprFabrication1" value="1">
                                        <label class="form-check-label" for="InputAccessOprFabrication1">Yes</label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="InputAccessOprFabrication" id="InputAccessOprFabrication2" value="0" checked>
                                        <label class="form-check-label" for="InputAccessOprFabrication2">No</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="InputAccessOprFinishing" class="form-label fw-bold">Access Menu Operator Finishing</label>
                            <div class="row row-cols-auto">
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="InputAccessOprFinishing" id="InputAccessOprFinishing1" value="1">
                                        <label class="form-check-label" for="InputAccessOprFinishing1">Yes</label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="InputAccessOprFinishing" id="InputAccessOprFinishing2" value="0" checked>
                                        <label class="form-check-label" for="InputAccessOprFinishing2">No</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="InputAccessOprQA" class="form-label fw-bold">Access Menu Operator QA</label>
                            <div class="row row-cols-auto">
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="InputAccessOprQA" id="InputAccessOprQA1" value="1">
                                        <label class="form-check-label" for="InputAccessOprQA1">Yes</label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="InputAccessOprQA" id="InputAccessOprQA2" value="0" checked>
                                        <label class="form-check-label" for="InputAccessOprQA2">No</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="InputAccessOprQC" class="form-label fw-bold">Access Menu Operator QC</label>
                            <div class="row row-cols-auto">
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="InputAccessOprQC" id="InputAccessOprQC1" value="1">
                                        <label class="form-check-label" for="InputAccessOprQC1">Yes</label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="InputAccessOprQC" id="InputAccessOprQC2" value="0" checked>
                                        <label class="form-check-label" for="InputAccessOprQC2">No</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="InputAccessOprAssembly" class="form-label fw-bold">Access Menu Operator Assembly</label>
                            <div class="row row-cols-auto">
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="InputAccessOprAssembly" id="InputAccessOprAssembly1" value="1">
                                        <label class="form-check-label" for="InputAccessOprAssembly1">Yes</label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="InputAccessOprAssembly" id="InputAccessOprAssembly2" value="0" checked>
                                        <label class="form-check-label" for="InputAccessOprAssembly2">No</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="InputAccessOprCuttingMaterial" class="form-label fw-bold">Access Menu Operator Cutting Material</label>
                            <div class="row row-cols-auto">
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="InputAccessOprCuttingMaterial" id="InputAccessOprCuttingMaterial1" value="1">
                                        <label class="form-check-label" for="InputAccessOprCuttingMaterial1">Yes</label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="InputAccessOprCuttingMaterial" id="InputAccessOprCuttingMaterial2" value="0" checked>
                                        <label class="form-check-label" for="InputAccessOprCuttingMaterial2">No</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="InputAccessOprPacking" class="form-label fw-bold">Access Menu Operator Packing</label>
                            <div class="row row-cols-auto">
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="InputAccessOprPacking" id="InputAccessOprPacking1" value="1">
                                        <label class="form-check-label" for="InputAccessOprPacking1">Yes</label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="InputAccessOprPacking" id="InputAccessOprPacking2" value="0" checked>
                                        <label class="form-check-label" for="InputAccessOprPacking2">No</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="InputAccessOprInjection" class="form-label fw-bold">Access Menu Operator Injection</label>
                            <div class="row row-cols-auto">
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="InputAccessOprInjection" id="InputAccessOprInjection1" value="1">
                                        <label class="form-check-label" for="InputAccessOprInjection1">Yes</label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="InputAccessOprInjection" id="InputAccessOprInjection2" value="0" checked>
                                        <label class="form-check-label" for="InputAccessOprInjection2">No</label>
                                    </div>
                                </div>
                            </div>
                        </div>	
                        <div class="col-md-12 mb-2">
                            <label for="InputAccessWarehouse" class="form-label fw-bold">Access Menu Warehouse</label>
                            <div class="row row-cols-auto">
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="InputAccessWarehouse" id="InputAccessWarehouse1" value="1">
                                        <label class="form-check-label" for="InputAccessWarehouse1">Yes</label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="InputAccessWarehouse" id="InputAccessWarehouse2" value="0" checked>
                                        <label class="form-check-label" for="InputAccessWarehouse2">No</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="InputAccessExim" class="form-label fw-bold">Access Menu Exim</label>
                            <div class="row row-cols-auto">
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="InputAccessExim" id="InputAccessExim1" value="1">
                                        <label class="form-check-label" for="InputAccessExim1">Yes</label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="InputAccessExim" id="InputAccessExim2" value="0" checked>
                                        <label class="form-check-label" for="InputAccessExim2">No</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="InputAccessKAShift" class="form-label fw-bold">Access Menu KaShift</label>
                            <div class="row row-cols-auto">
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="InputAccessKAShift" id="InputAccessKAShift1" value="1">
                                        <label class="form-check-label" for="InputAccessKAShift1">Yes</label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="InputAccessKAShift" id="InputAccessKAShift2" value="0" checked>
                                        <label class="form-check-label" for="InputAccessKAShift2">No</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="InputAccessCloseWOIN" class="form-label fw-bold">Access Close WO</label>
                            <div class="row row-cols-auto">
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="InputAccessCloseWOIN" id="InputAccessCloseWOIN1" value="1">
                                        <label class="form-check-label" for="InputAccessCloseWOIN1">Yes</label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="InputAccessCloseWOIN" id="InputAccessCloseWOIN2" value="0" checked>
                                        <label class="form-check-label" for="InputAccessCloseWOIN2">No</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12"><hr></div>
                        <div class="col-md-12 d-grid">
                            <button class="btn btn-dark btn-labeled" id="BtnNewUser" type="submit">Submit</button>
                        </div> 
                    </form>
                </div>
                <div class="row" id="ResultMsg"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">&nbsp;Close&nbsp;</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="DataGuest" tabindex="-1" role="dialog" aria-labelledby="Modal-View" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Data User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" class="form-control" id="InputID" readonly>
                <div id="ContentDataGuest"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">&nbsp;Close&nbsp;</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="DataKey" tabindex="-1" role="dialog" aria-labelledby="Modal-View" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 mb-2">
                        <label for="InputPassword" class="form-label fw-bold">New Password</label>
                        <input type="password" class="form-control" name="InputPassword" id="InputPassword" required>
                    <input type="hidden" class="form-control" id="InputIDKey" readonly>
                    </div>
                    <div class="col-md-12 mb-2 d-grid">
                        <button type="button" id="BtnUpdateKey" class="btn btn-dark">Update</button>
                    </div>
                    <div class="col-md-12">
                        <div id="UpdatePassword"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">&nbsp;Close&nbsp;</button>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function () {
    $('#TableDataUser').removeAttr('width').DataTable( {
        "iDisplayLength": 5,
        "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
        scrollY:        "350px",
        scrollX:        true,
        scrollCollapse: true,
        columnDefs: [
            { width: 60, targets: 1 }
        ],
        fixedColumns: true
    } );
    $(".ActDelete").click(function () {
        return confirm("Are you sure to delete this data?");
    });
    $("#DataGuest").on('show.bs.modal', function (event) {
        var dt = event.relatedTarget;
        var dataid = dt.getAttribute('data-bs-dataid');
        $("#InputID").val(dataid);
        DATA_MODAL();
    });
    $("#AddNewUser").click(function(){
        $("#NewUser").modal("show");
    });
    $("#NewUser").on("hidden.bs.modal", function () {     
        $("#InputNewGuest").val("");
        $("#InputNewUsername").val("");
        $("#InputNewPassword").val("");
        $("#InputLocationCompany")[0].selectedIndex = 0;
        $("#InputTypeUser")[0].selectedIndex = 0;
        $("#InputAccessAdmin2").prop("checked", true);
        $("#InputAccessSecurity2").prop("checked", true);
        $("#InputAccessCostTracking2").prop("checked", true);
        $("#InputAccessProduction2").prop("checked", true);
        $("#InputAccessCCTV2").prop("checked", true);
        $("#ResultMsg").html("");
    });
    $("#BtnNewUser").click(function(){
        if($("#InputNewGuest").val().trim() == "")
        {
            $("#InputNewGuest").focus();
            return false;
        }
        if($("#InputNewUsername").val().trim() == "")
        {
            $("#InputNewUsername").focus();
            return false;
        }
        if($("#InputNewPassword").val().trim() == "")
        {
            $("#InputNewPassword").focus();
            return false;
        }
        NEW_USER();
        return false;
    });
    $("#DataGuest").on("shown.bs.modal",function(){
        $("#BtnUpdateUser").on("click",function(){
            UPDATE_USER(); 
            return false;
        });
    });
    $("#DataKey").on('show.bs.modal', function (event) {
        var dt = event.relatedTarget;
        var dataid = dt.getAttribute('data-bs-dataid');
        $("#InputIDKey").val(dataid);
    });
    $("#DataKey").on("hidden.bs.modal", function () {     
        $("#InputPassword").val("");
        $("#UpdatePassword").html("");
    });
    $("#BtnUpdateKey").on("click",function(){
        KEY_MODAL();
    });
});
function NEW_USER()
{
    var Name = $("#InputNewGuest").val().trim();
    var Username = $("#InputNewUsername").val().trim();
    var Password = $("#InputNewPassword").val().trim();
    var Company = $("#InputLocationCompany").val().trim();
    var Type = $("#InputTypeUser").val().trim();
    var IsAdmin = $("input[name='InputAccessAdmin']:checked").val().trim();
    var MnSecurity = $("input[name='InputAccessSecurity']:checked").val().trim();
    var MnCostTracking = $("input[name='InputAccessCostTracking']:checked").val().trim();
    var MnProduction = $("input[name='InputAccessProduction']:checked").val().trim();
    var MnCCTV = $("input[name='InputAccessCCTV']:checked").val().trim();    
    var MnReport = $("input[name='InputAccessReport']:checked").val().trim();
    var MnPPIC = $("input[name='InputAccessPPIC']:checked").val().trim();
    var MnOprMachCNC = $("input[name='InputAccessOprMachCNC']:checked").val().trim();
    var MnOprMachManual = $("input[name='InputAccessOprMachManual']:checked").val().trim();
    var MnOprFabrication = $("input[name='InputAccessOprFabrication']:checked").val().trim();
    var MnOprFinishing = $("input[name='InputAccessOprFinishing']:checked").val().trim();
    var MnOprQA = $("input[name='InputAccessOprQA']:checked").val().trim();
    var MnOprQC = $("input[name='InputAccessOprQC']:checked").val().trim();
    var MnOprAssembly = $("input[name='InputAccessOprAssembly']:checked").val().trim();
    var MnOprCuttingMaterial = $("input[name='InputAccessOprCuttingMaterial']:checked").val().trim();
    var MnOprPacking = $("input[name='InputAccessOprPacking']:checked").val().trim();
    var MnOprInjection = $("input[name='InputAccessOprInjection']:checked").val().trim();
    var MnWarehouse = $("input[name='InputAccessWarehouse']:checked").val().trim();
    var MnExim = $("input[name='InputAccessExim']:checked").val().trim();
    var MnKAShift = $("input[name='InputAccessKAShift']:checked").val().trim();
    var MnClosedWO = $("input[name='InputAccessCloseWOIN']:checked").val().trim();
    
    var FormDataNew = new FormData();
    FormDataNew.append('ValName', Name);
    FormDataNew.append('ValUsername', Username);
    FormDataNew.append('ValPassword', Password);
    FormDataNew.append('ValCompany', Company);
    FormDataNew.append('ValType', Type);
    FormDataNew.append('ValIsAdmin', IsAdmin);
    FormDataNew.append('ValMnSecurity', MnSecurity);
    FormDataNew.append('ValMnCostTracking', MnCostTracking);
    FormDataNew.append('ValMnProduction', MnProduction);
    FormDataNew.append('ValMnCCTV', MnCCTV);  
    FormDataNew.append('ValMnReport', MnReport);
    FormDataNew.append('ValMnPPIC', MnPPIC);
    FormDataNew.append('ValMnOprMachCNC', MnOprMachCNC);
    FormDataNew.append('ValMnOprMachManual', MnOprMachManual);
    FormDataNew.append('ValMnOprFabrication', MnOprFabrication);
    FormDataNew.append('ValMnOprFinishing', MnOprFinishing);
    FormDataNew.append('ValMnOprQA', MnOprQA);
    FormDataNew.append('ValMnOprQC', MnOprQC);
    FormDataNew.append('ValMnOprAssembly', MnOprAssembly);
    FormDataNew.append('ValMnOprCuttingMaterial', MnOprCuttingMaterial);
    FormDataNew.append('ValMnOprPacking', MnOprPacking);
    FormDataNew.append('ValMnOprInjection', MnOprInjection);
    FormDataNew.append('ValMnWarehouse', MnWarehouse);
    FormDataNew.append('ValMnExim', MnExim);
    FormDataNew.append('ValMnKAShift', MnKAShift);
    FormDataNew.append('ValMnClosedWO', MnClosedWO);
    
    $.ajax({
        url: 'project/User/src/srcNewUser.php',
        data: FormDataNew,
        dataType: 'html',
        cache: false,
        contentType: false,
        processData: false,
        type: "POST",
        beforeSend: function () {
            $("#ResultMsg").html("");
            if($("#ResultMsgInfo").length != 0)
            {
                $("#ResultMsgInfo").remove();
            }
            $("#BtnNewUser").attr("disabled",true);
            $("#BtnNewUser").after('<div class="text-center justify-content-center"><img src="images/ajax-loader1.gif" id="LoadingLogin" class="load_img"/></div>');
            $("#LoadingLogin").after('<div id="ResultMsg"></div>');
        },
        success: function (xaxa) {
            $("#ResultMsg").hide();
            $("#ResultMsg").html("");
            $("#ResultMsg").html(xaxa);
            $("#ResultMsg").fadeIn('fast');
            $("#LoadingLogin").remove();
            $("#BtnNewUser").attr("disabled",false);
        },
        error: function () {
            $("#BtnNewUser").attr("disabled",false);
            $("#LoadingLogin").remove();
            $("#ResultMsg").html('<br><div class="alert alert-danger fw-bold" id="ResultMsgInfo" role="alert">Request cannot proceed! Try Again!</div>');
        }
    });
    return false;
}
function DATA_MODAL()
{
    $("#ContentDataGuest").html('<div class="text-center justify-content-center"><img src="images/ajax-loader1.gif" id="LoadingLoad" class="load_img"/></div>');
    var DataID = $("#InputID").val();    
    var formdata = new FormData();
    formdata.append('ValID', DataID);
    $.ajax({
        url: 'project/User/ModalEditUser.php',
        data: formdata,
        dataType: 'html',
        cache: false,
        contentType: false,
        processData: false,
        type: "POST",
        beforeSend: function () {
            $("#ContentDataGuest").html("");
            $("#ContentDataGuest").html('<div class="text-center justify-content-center"><img src="images/ajax-loader1.gif" id="LoadingLoad" class="load_img"/></div>');
        },
        success: function (xaxa) {
            $("#ContentDataGuest").hide();
            $("#ContentDataGuest").html("");
            $("#ContentDataGuest").html(xaxa);
            $("#ContentDataGuest").fadeIn('fast');
        },
        error: function () {
            $("#ContentDataGuest").html("");
            $("#ContentDataGuest").html('<div class="alert alert-danger fw-bold" role="alert">Request cannot proceed! Try Again!</div>');
        }
    });    
}
function UPDATE_USER()
{
    var Name = $("#InputNewGuestMod").val().trim();
    var Username = $("#InputNewUsernameMod").val().trim();
    var IsActive = $("input[name='InputActiveMod']:checked").val().trim();
    var Company = $("#InputLocationCompanyMod").val().trim();
    var Type = $("#InputTypeUserMod").val().trim();
    var IsAdmin = $("input[name='InputAccessAdminMod']:checked").val().trim();
    var MnSecurity = $("input[name='InputAccessSecurityMod']:checked").val().trim();
    var MnCostTracking = $("input[name='InputAccessCostTrackingMod']:checked").val().trim();
    var MnProduction = $("input[name='InputAccessProductionMod']:checked").val().trim();
    var MnCCTV = $("input[name='InputAccessCCTVMod']:checked").val().trim(); 
    var MnReport = $("input[name='InputAccessReportMod']:checked").val().trim();
    var MnPPIC = $("input[name='InputAccessPPICMod']:checked").val().trim();
    var MnOprMachCNC = $("input[name='InputAccessOprMachCNCMod']:checked").val().trim();
    var MnOprMachManual = $("input[name='InputAccessOprMachManualMod']:checked").val().trim();
    var MnOprFabrication = $("input[name='InputAccessOprFabricationMod']:checked").val().trim();
    var MnOprFinishing = $("input[name='InputAccessOprFinishingMod']:checked").val().trim();
    var MnOprQA = $("input[name='InputAccessOprQAMod']:checked").val().trim();
    var MnOprQC = $("input[name='InputAccessOprQCMod']:checked").val().trim();
    var MnOprAssembly = $("input[name='InputAccessOprAssemblyMod']:checked").val().trim();
    var MnOprCuttingMaterial = $("input[name='InputAccessOprCuttingMaterialMod']:checked").val().trim();
    var MnOprPacking = $("input[name='InputAccessOprPackingMod']:checked").val().trim();
    var MnOprInjection = $("input[name='InputAccessOprInjectionMod']:checked").val().trim();
    var MnWarehouse = $("input[name='InputAccessOprWarehouseMod']:checked").val().trim();
    var MnExim = $("input[name='InputAccessOprEximMod']:checked").val().trim();
    var MnKAShift = $("input[name='InputAccessOprKAShiftMod']:checked").val().trim();
    var MnClosedWO = $("input[name='InputAccessCloseWO']:checked").val().trim();
    
    var FormDataUpdate = new FormData();
    FormDataUpdate.append('ValName', Name);
    FormDataUpdate.append('ValUsername', Username);
    FormDataUpdate.append('ValIsActive', IsActive);
    FormDataUpdate.append('ValCompany', Company);
    FormDataUpdate.append('ValType', Type);
    FormDataUpdate.append('ValIsAdmin', IsAdmin);
    FormDataUpdate.append('ValMnSecurity', MnSecurity);
    FormDataUpdate.append('ValMnCostTracking', MnCostTracking);
    FormDataUpdate.append('ValMnProduction', MnProduction);
    FormDataUpdate.append('ValMnCCTV', MnCCTV);    
    FormDataUpdate.append('ValMnReport', MnReport);
    FormDataUpdate.append('ValMnPPIC', MnPPIC);
    FormDataUpdate.append('ValMnOprMachCNC', MnOprMachCNC);
    FormDataUpdate.append('ValMnOprMachManual', MnOprMachManual);
    FormDataUpdate.append('ValMnOprFabrication', MnOprFabrication);    
    FormDataUpdate.append('ValMnOprFinishing', MnOprFinishing);
    FormDataUpdate.append('ValMnOprQA', MnOprQA);
    FormDataUpdate.append('ValMnOprQC', MnOprQC);
    FormDataUpdate.append('ValMnOprAssembly', MnOprAssembly);
    FormDataUpdate.append('ValMnOprCuttingMaterial', MnOprCuttingMaterial);    
    FormDataUpdate.append('ValMnOprPacking', MnOprPacking);
    FormDataUpdate.append('ValMnOprInjection', MnOprInjection);
    FormDataUpdate.append('ValMnWarehouse', MnWarehouse);
    FormDataUpdate.append('ValMnExim', MnExim);
    FormDataUpdate.append('ValMnKAShift', MnKAShift);
    FormDataUpdate.append('ValMnKAShift', MnKAShift);
    FormDataUpdate.append('ValMnClosedWO', MnClosedWO);
    
    $.ajax({
        url: 'project/User/src/srcUpdateUser.php',
        data: FormDataUpdate,
        dataType: 'html',
        cache: false,
        contentType: false,
        processData: false,
        type: "POST",
        beforeSend: function () {
            $("#ResUpdateMsg").html("");
            $("#BtnUpdateUser").attr("disabled",true);
            $("#BtnUpdateUser").after('<div class="text-center justify-content-center"><img src="images/ajax-loader1.gif" id="LoadingUpdate" class="load_img"/></div>');
            $("#LoadingUpdate").after('<div id="ResUpdateMsg"></div>');
        },
        success: function (xaxa) {
            $("#ResUpdateMsg").hide();
            $("#ResUpdateMsg").html("");
            $("#ResUpdateMsg").html(xaxa);
            $("#ResUpdateMsg").fadeIn('fast');
            $("#LoadingUpdate").remove();
            $("#BtnUpdateUser").attr("disabled",false);
        },
        error: function () {
            $("#BtnUpdateUser").attr("disabled",false);
            $("#LoadingUpdate").remove();
            $("#ResUpdateMsg").html('<br><div class="alert alert-danger fw-bold" id="ResultMsgInfo" role="alert">Request cannot proceed! Try Again!</div>');
        }
    });
    return false;
}
function KEY_MODAL()
{
    var DataID = $("#InputIDKey").val();    
    var DataPassword = $("#InputPassword").val();   
    var formdata = new FormData();
    formdata.append('ValID', DataID);
    formdata.append('ValPassword', DataPassword);    
    $.ajax({
        url: 'project/User/src/srcUpdatePassword.php',
        data: formdata,
        dataType: 'html',
        cache: false,
        contentType: false,
        processData: false,
        type: "POST",
        beforeSend: function () {
            $("#UpdatePassword").html("");
            $("#BtnUpdateKey").attr("disabled",true);
            $("#BtnUpdateKey").after('<div class="text-center justify-content-center"><img src="images/ajax-loader1.gif" id="LoadingUpdatePwd" class="load_img"/></div>');
            $("#LoadingUpdate").after('<div id="UpdatePassword"></div>');
        },
        success: function (xaxa) {
            $("#UpdatePassword").hide();
            $("#UpdatePassword").html("");
            $("#UpdatePassword").html(xaxa);
            $("#UpdatePassword").fadeIn('fast');
            $("#LoadingUpdatePwd").remove();
            $("#BtnUpdateKey").attr("disabled",false);
        },
        error: function () {
            $("#BtnUpdateKey").attr("disabled",false);
            $("#LoadingUpdatePwd").remove();
            $("#UpdatePassword").html('<br><div class="alert alert-danger fw-bold" id="ResultMsgInfo" role="alert">Request cannot proceed! Try Again!</div>');
        }
    });
}
</script>