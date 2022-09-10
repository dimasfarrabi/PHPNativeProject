<?php 
session_start();
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModulePointAchievement.php");
date_default_timezone_set("Asia/Jakarta");



if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $ValClosedTime = htmlspecialchars(trim($_POST['ValClosedTime']), ENT_QUOTES, "UTF-8");
    $ValPM = htmlspecialchars(trim($_POST['ValPM']), ENT_QUOTES, "UTF-8");
    $ValEmployee = htmlspecialchars(trim($_POST['ValEmployee']), ENT_QUOTES, "UTF-8");

    # cari data karyawan
    $ArrListEmployee = array();
    $QListEmployeePSL = GET_LIST_EMPLOYEE_PRODUCTION_BY_NAME($ValEmployee,$linkMACHWebTrax);
    while($RListEmployeePSL = sqlsrv_fetch_array($QListEmployeePSL))
    {
        $ValEmployeeRes = trim($RListEmployeePSL['FullName']);
        $ValDetailPosition = trim($RListEmployeePSL['DetailPosition']);
		$CompanyCode = trim($RListEmployeePSL['CompanyCode']);
        if ( $CompanyCode == 'FOR') { $CompanyCode = "PSL";}
        $TemporaryArray = array(
            "FullName" => $ValEmployeeRes,
            "Location" => $CompanyCode,
            "Position" => $ValDetailPosition
        );
        array_push($ArrListEmployee,$TemporaryArray);
    }
    // $QListEmployeePSM = GET_LIST_EMPLOYEE_PRODUCTION_PSM_BY_NAME($ValEmployee);
    // while($RListEmployeePSM = sqlsrv_fetch_array($QListEmployeePSM))
    // {
    //     $ValEmployeeRes = trim($RListEmployeePSM['FullName']);
    //     $ValDetailPosition = trim($RListEmployeePSM['DetailPosition']);
    //     $TemporaryArray = array(
    //         "FullName" => $ValEmployeeRes,
    //         "Location" => "SEMARANG",
    //         "Position" => $ValDetailPosition
    //     );
    //     array_push($ArrListEmployee,$TemporaryArray);
    // }
    // sort($ArrListEmployee);
    $RowData = count($ArrListEmployee);
    if($RowData > 10){ 
?>
<style>
    .ListTableEmployee{height: 365px;overflow-y: scroll;}</style>
<?php }?>
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->
<div class="row">
    <div class="col-sm-12">
        <div class="navbar-form" role="search">
            <div class="form-group">
                <input type="text" class="form-control" id="InputFindEmployee" placeholder="Search employee">
            </div>
            <button type="button" class="btn btn-dark btn-labeled" id="BtnSearchEmployee"><i class="fa fa-search"></i></button>
            <!-- <button type="button" class="btn btn-dark btn-labeled"><i class="fa fa-download" id="Download_CSV"></i></button> -->
            
        </div>
    </div>
    <div class="col-sm-12">&nbsp;</div>
</div>
<div id="ContentEmployee">
    <div class="table-responsive ListTableEmployee">
        <table class="table table-bordered table-hover ListTableCustom" id="ListTableEmp">
            <thead class="theadCustom">
                <tr>
                    <th class="text-center">Employee</th>
                </tr>
            </thead>
            <tbody><?php
            foreach($ArrListEmployee as $ListEmployee)
            {
                $ValEmployee = trim($ListEmployee['FullName']);
                $ValLocation = trim($ListEmployee['Location']);
                $ValPosition = trim($ListEmployee['Position']);
                $ValEncrypt = base64_encode(base64_encode($ValEmployee."#".$ValLocation."#".$ValPosition));
                echo '<tr class="PointerListEmployee" data-roles="'.$ValEncrypt.'">';
                echo '<td class="text-left">'.$ValEmployee.'</td>';
                echo '</tr>';
            }
            ?></tbody>
        </table>
    </div>
</div>
<?php
}
else
{
    echo "";    
}
?>
<script>
$(document).ready(function () {
    $("#Download_CSV_SL3").click(function(){
        var Filter = $("#TempFilter").text();
        window.location.href = 'project/PointAchievement/_DownloadEmployeePoint.php?ClosedTime='+Filter;
    });
    
});
</script>
<script>
</script>