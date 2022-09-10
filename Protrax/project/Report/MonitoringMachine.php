<?php
require_once("Modules/ModuleMonitoringMachine.php");
date_default_timezone_set("Asia/Jakarta");
$YearNow = date("Y");
$DateNow = date("m/d/Y");


/*if(!session_is_registered("UIDProTrax"))
{
    ?>
    <script language="javascript">
        window.location.replace("https://protrax.formulatrix.com/");
    </script>
    <?php
    exit();
}*/
# data session
/*
$FullName = strtoupper(base64_decode($_SESSION['FullNameUserProTrax']));
$UserNameSession = base64_decode(base64_decode($_SESSION['UIDProTrax']));
# data protrax user
$BolProdAcc = false;
$QDataUserWebtrax = GET_DATA_LOGIN_BY_USERNAME_ONLY($UserNameSession,$linkMACHWebTrax);
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

if((trim($AccessLogin) != "Manager"))
{
    if($RDataUserWebtrax['MnAdmin'] != "1")  
    {
        ?>
        <script language="javascript">
            window.location.replace("https://protrax.formulatrix.com/");
        </script>
        <?php
        exit();
    }
}
else
{
    if($RDataUserWebtrax['MnReport'] != "1")
    {
        ?>
        <script language="javascript">
            window.location.replace("https://protrax.formulatrix.com/");
        </script>
        <?php
        exit();
    }
}
$DateNow = date("m/d/Y");
*/
    $ArrListMachineSlotPSL = array();
    $ArrListMachineSlotPSM = array();
    $TotalMachine = 0;
    $TotalON = 0;
    $TotalOFF = 0;
    # list machine PSL
    $QListMachineSlotPSL = LIST_MACHINE_SLOT_PSL($linkMACHWebTrax);
    while($RListMachineSlotPSL = sqlsrv_fetch_array($QListMachineSlotPSL))
    {
        $TotalMachine = $TotalMachine + 1;
        if(trim($RListMachineSlotPSL['Is_Active']) == "1"){$TotalON = $TotalON + 1;}
        if(trim($RListMachineSlotPSL['Is_Active']) == "0"){$TotalOFF = $TotalOFF + 1;}
        $EmployeePSL = trim($RListMachineSlotPSL['Employee']);
        $BarcodePSL = GET_MATERIAL_PSL(trim($RListMachineSlotPSL['Machine']),$linkMACHWebTrax);
        $GroupLineWOPSL = '';
        $GroupNotesPSL = GET_GROUP_NOTES_PSL(trim($RListMachineSlotPSL['Machine']),$linkMACHWebTrax);
        $GroupNoOfMachTrackPSL = GET_ACTIVE_GROUPNO_PSL(trim($RListMachineSlotPSL['Machine']),$linkMACHWebTrax);
        $GroupLinePartNoPSL = GET_PARTNO_LINEITEM_PSL($GroupNoOfMachTrackPSL,$linkMACHWebTrax);
        if(trim($GroupNotesPSL) == "INJECTION TRACKING")
        {
            $GroupLineWOPSL = GET_WO_MACHINE_TRACKING_INJECTION_LINE_ITEM_PSL($GroupNoOfMachTrackPSL,$linkMACHWebTrax);
        }
        else
        {
            $GroupLineWOPSL = GET_WO_MACHINE_TRACKING_LINE_ITEM_PSL($GroupNoOfMachTrackPSL,$linkMACHWebTrax);   
        }
        $RawMatInjectionPSL = GET_RAW_MAT_INJECTION_PSL($GroupNoOfMachTrackPSL,$linkMACHWebTrax);
        $WOChildInfoPSL = '';
        if(trim($GroupNotesPSL) == "")
        {
            $WOChildInfoPSL = $GroupLineWOPSL;
        }
        else
        {
            $WOChildInfoPSL = GET_WOC_RAW_MATERIAL_BY_BCMATERIAL_PSL($BarcodePSL,$linkMACHWebTrax);
        }
        $BarcodeIDPSL = '';
        if(trim($BarcodePSL) == "0" || trim($BarcodePSL) == "")
        {
            $PartBarcodePSL = GET_PART_BARCODE_PSL(trim($RListMachineSlotPSL['Machine']),$linkMACHWebTrax);
            if(trim($GroupNotesPSL) == "INJECTION TRACKING")
            {
                $WOChildInfoPSL = $GroupLineWOPSL;
            }
            else
            {
                $WOChildInfoPSL = GET_WO_BY_BARCODE_PSL($PartBarcodePSL,$linkMACHWebTrax);
            }
            if(trim($GroupNotesPSL) == "INJECTION TRACKING")
            {
                $BarcodeIDPSL = 'Part : <strong>INJECTION TRACKING</strong>';
            }
            else
            {
                $BarcodeIDPSL = 'Part : <strong>'.trim($PartBarcodePSL).'</strong>';  
            }
        }
        else
        {
            $BarcodeIDPSL = 'Material : <strong>'.trim($BarcodePSL).'</strong>';
        }
        $ArrayTemp = array(
            "Slot" => trim($RListMachineSlotPSL['Slot']),
            "Machine" => trim($RListMachineSlotPSL['Machine']),
            "Is_Active" => trim($RListMachineSlotPSL['Is_Active']),
            "Employee" => trim($RListMachineSlotPSL['Employee']),
            "Duration" => trim($RListMachineSlotPSL ['Durasi']),
            "Barcode" => $BarcodeIDPSL,
            "WOChild" => $WOChildInfoPSL
        );
        array_push($ArrListMachineSlotPSL,$ArrayTemp);
    }
    # list machine PSM
    /*$QListMachineSlotPSM = LIST_MACHINE_SLOT_PSM();
    while($RListMachineSlotPSM = sqlsrv_fetch_array($QListMachineSlotPSM))
    {
        $TotalMachine = $TotalMachine + 1;
        if(trim($RListMachineSlotPSM['Is_Active']) == "1"){$TotalON = $TotalON + 1;}
        if(trim($RListMachineSlotPSM['Is_Active']) == "0"){$TotalOFF = $TotalOFF + 1;}
        $EmployeePSM = trim($RListMachineSlotPSM['Employee']);
        $BarcodePSM = GET_MATERIAL_PSM(trim($RListMachineSlotPSM['Machine']),$linkMACHWebTrax);
        $GroupLineWOPSM = '';
        $GroupNotesPSM = GET_GROUP_NOTES_PSM(trim($RListMachineSlotPSM['Machine']),$linkMACHWebTrax);
        $GroupNoOfMachTrackPSM = GET_ACTIVE_GROUPNO_PSM(trim($RListMachineSlotPSM['Machine']),$linkMACHWebTrax);
        $GroupLinePartNoPSM = GET_PARTNO_LINEITEM_PSM($GroupNoOfMachTrackPSM,$linkMACHWebTrax);
        if(trim($GroupNotesPSM) == "INJECTION TRACKING")
        {
            $GroupLineWOPSM = GET_WO_MACHINE_TRACKING_INJECTION_LINE_ITEM_PSM($GroupNoOfMachTrackPSM,$linkMACHWebTrax);
        }
        else
        {
            $GroupLineWOPSM = GET_WO_MACHINE_TRACKING_LINE_ITEM_PSM($GroupNoOfMachTrackPSM,$linkMACHWebTrax);   
        }
        $WOChildInfoPSM = '';
        if(trim($GroupNotesPSM) == "")
        {
            $WOChildInfoPSM = $GroupLineWOPSM;
        }
        else
        {
            $WOChildInfoPSM = GET_WOC_RAW_MATERIAL_BY_BCMATERIAL_PSM($BarcodePSM,$linkMACHWebTrax);
        }
        $BarcodeIDPSM = '';
        if(trim($BarcodePSM) == "0" || trim($BarcodePSM) == "")
        {
            $PartBarcodePSM = GET_PART_BARCODE_PSM(trim($RListMachineSlotPSM['Machine']),$linkMACHWebTrax);
            $WOChildInfoPSM = GET_WO_BY_BARCODE_PSM($PartBarcodePSM,$linkMACHWebTrax);
            // if(trim($GroupNotesPSM) == "INJECTION TRACKING")
            // {
            //     $BarcodeIDPSM = 'Material : <strong>'.trim($RawMatInjectionPSM).'</strong>';
            // }
            // else
            // {
                $BarcodeIDPSM = 'Part : <strong>'.trim($PartBarcodePSM).'</strong>';  
            // }
        }
        else
        {
            $BarcodeIDPSM = 'Material : <strong>'.trim($BarcodePSM).'</strong>';
        }
        $ArrayTemp = array(
            "Slot" => trim($RListMachineSlotPSM['Slot']),
            "Machine" => trim($RListMachineSlotPSM['Machine']),
            "Is_Active" => trim($RListMachineSlotPSM['Is_Active']),
            "Employee" => trim($RListMachineSlotPSM['Employee']),
            "Duration" => trim($RListMachineSlotPSM['Durasi']),
            "Barcode" => $BarcodeIDPSM,
            "WOChild" => $WOChildInfoPSM
        );
        array_push($ArrListMachineSlotPSM,$ArrayTemp);
    }*/
?>
<script src="project/Report/lib/LibMonitoringMachine.js?no=<?php echo base64_encode(date("mdyHis")); ?>"></script>
<style>.alert-success{background-color:#ADFF2F;}.alert-warning{background-color:#FFFF00;}.alert-danger{background-color:#F08080;}.alert-dark{background-color:#F5F5F5;}</style>
<div class="row">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-detail ">
                <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Report : Monitoring Machine</li>
            </ol>
        </nav>
    </div>
</div>
<div class="row" id="ContentResult">
    <div class="col-md-12 fw-bold">Total Mesin : <?php echo $TotalMachine; ?>. Total ON : <?php echo $TotalON; ?>. Total OFF : <?php echo $TotalOFF; ?>.</div>
    <div class="col-md-6">
        <div class="row">
            <div class="col-md-12 text-center fw-bold"><h5>SALATIGA</h5></div>
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">SLOT</th>
                                <th class="text-start">NAMA MESIN</th>
                            </tr>
                        </thead>
                        <?php 
                        foreach ($ArrListMachineSlotPSL as $ListMachineSlotPSL)
                        {
                            $IsActive = trim($ListMachineSlotPSL['Is_Active']);
                            if($IsActive != "0")
                            {
                                # set employee
                                $TextEmployeePSL = trim($ListMachineSlotPSL['Employee']);
                                $TextEmployeePSL = '<span class="TitleMachine text-dark fw-bold"> : '.$TextEmployeePSL.'</span>';
                                # set duration
                                $TextDurationPSL = trim($ListMachineSlotPSL['Duration']);
                                # set detail
                                $LabelDetailPSL1 = '<div class="mt-0 pt-0">Status : <strong>[RUNNING]</strong> - <strong>'.$TextDurationPSL.'</strong></div>';
                                $LabelDetailPSL2 = '<div class="mt-0 pt-0">'.trim($ListMachineSlotPSL['Barcode']).'</div>';
                                $LabelDetailPSL3 = '<div class="mt-0 pt-0">WO Child : <strong>'.trim($ListMachineSlotPSL['WOChild']).'</strong></div>';

                                $LabelDetailPSL = '<div class="mt-0 pt-0">'.trim($ListMachineSlotPSL['Barcode']).'. WO Child : <strong>'.trim($ListMachineSlotPSL['WOChild']).'</strong>. Duration : <strong>'.$TextDurationPSL.'</strong></div>';
                                # set color
                                $ColorBackgroundPSL = ' class="alert alert-success"';
                                $ArrTextDurationPSL = explode(":",$TextDurationPSL);
                                if((int)$ArrTextDurationPSL[0] >= 0 && (int)$ArrTextDurationPSL[0] < 7 )
                                {
                                    $ColorBackgroundPSL = ' class="alert alert-success"';
                                }
                                elseif ((int)$ArrTextDurationPSL[0] == 7 )
                                {
                                    $ColorBackgroundPSL = ' class="alert alert-warning"';
                                }
                                else
                                {
                                    $ColorBackgroundPSL = ' class="alert alert-danger"';
                                }

                            ?>
                        <tr<?php echo $ColorBackgroundPSL; ?>>
                            <td class="text-center"><?php echo trim($ListMachineSlotPSL['Slot']) ;?></td>
                            <td>
                                <div>
                                    <span class="TitleMachine text-dark fw-bold"><?php echo $ListMachineSlotPSL['Machine']; ?></span>
                                    <?php echo $TextEmployeePSL; ?>
                                    <?php echo $LabelDetailPSL1.$LabelDetailPSL2.$LabelDetailPSL3;?>
                                </div>
                            </td>
                        </tr>
                            <?php
                            }
                            else
                            {
                            ?>
                        <tr class="alert alert-dark">
                            <td class="text-center"><?php echo trim($ListMachineSlotPSL['Slot']) ;?></td>
                            <td>
                                <div>
                                    <span class="TitleMachine text-dark fw-bold"><?php echo $ListMachineSlotPSL['Machine']; ?> - [OFF]</span>
                                </div>
                            </td>
                        </tr>
                            <?php
                            }
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="row">
            <div class="col-md-12 text-center fw-bold"><h5>SEMARANG</h5></div>
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">SLOT</th>
                                <th class="text-start">NAMA MESIN</th>
                            </tr>
                        </thead>
                        <?php 
                        foreach ($ArrListMachineSlotPSM as $ListMachineSlotPSM)
                        {
                            $IsActive = trim($ListMachineSlotPSM['Is_Active']);
                            if($IsActive != "0")
                            {
                                # set employee
                                $TextEmployeePSM = trim($ListMachineSlotPSM['Employee']);
                                $TextEmployeePSM = '<span class="TitleMachine text-dark fw-bold"> : '.$TextEmployeePSM.'</span>';
                                # set duration
                                $TextDurationPSM = trim($ListMachineSlotPSM['Duration']);
                                # set detail
                                $LabelDetailPSM1 = '<div class="mt-0 pt-0">Status : <strong>[RUNNING]</strong> - <strong>'.$TextDurationPSM.'</strong></div>';
                                $LabelDetailPSM2 = '<div class="mt-0 pt-0">'.trim($ListMachineSlotPSM['Barcode']).'</div>';
                                $LabelDetailPSM3 = '<div class="mt-0 pt-0">WO Child : <strong>'.trim($ListMachineSlotPSM['WOChild']).'</strong></div>';
                                
                                $LabelDetailPSM = '<div class="mt-0 pt-0">'.trim($ListMachineSlotPSM['Barcode']).'. WO Child : <strong>'.trim($ListMachineSlotPSM['WOChild']).'</strong>. Duration : <strong>'.$TextDurationPSM.'</strong></div>';
                                # set color
                                $ColorBackgroundPSM = ' class="alert alert-success"';
                                $ArrTextDurationPSM = explode(":",$TextDurationPSM);
                                if((int)$ArrTextDurationPSM[0] >= 0 && (int)$ArrTextDurationPSM[0] < 7 )
                                {
                                    $ColorBackgroundPSM = ' class="alert alert-success"';
                                }
                                elseif ((int)$ArrTextDurationPSM[0] == 7 )
                                {
                                    $ColorBackgroundPSM = ' class="alert alert-warning"';
                                }
                                else
                                {
                                    $ColorBackgroundPSM = ' class="alert alert-danger"';
                                }

                            ?>
                        <tr<?php echo $ColorBackgroundPSM; ?>>
                            <td class="text-center"><?php echo trim($ListMachineSlotPSM['Slot']) ;?></td>
                            <td>
                                <div>
                                    <span class="TitleMachine text-dark fw-bold"><?php echo $ListMachineSlotPSM['Machine']; ?></span>
                                    <?php echo $TextEmployeePSM; ?>
                                    <?php echo $LabelDetailPSM1.$LabelDetailPSM2.$LabelDetailPSM3;?>
                                </div>
                            </td>
                        </tr>
                            <?php
                            }
                            else
                            {
                            ?>
                        <tr class="alert alert-dark">
                            <td class="text-center"><?php echo trim($ListMachineSlotPSM['Slot']) ;?></td>
                            <td>
                                <div>
                                    <span class="TitleMachine text-dark fw-bold"><?php echo $ListMachineSlotPSM['Machine']; ?> - [OFF]</span>
                                </div>
                            </td>
                        </tr>
                            <?php
                            }
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<span id="ShowError"></span>
