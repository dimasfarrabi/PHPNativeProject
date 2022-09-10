<?php
// require_once("project/MachiningCNC/Modules/ModuleSingleTimeTracking.php");
require_once("project/Inventory/Modules/ModuleStockOpname.php");
require_once("project/Inventory/Modules/ModuleInOutpartTBZ.php");
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
    if($RDataUserWebtrax['MnAdmin'] != "1" && $RDataUserWebtrax['MnInventory'] != "1")  
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
    if($RDataUserWebtrax['MnAdmin'] != "1")
    {
        if($RDataUserWebtrax['MnInventory'] != "1")
        {
            ?>
            <script language="javascript">
                window.location.replace("https://protrax.formulatrix.com/");
            </script>
            <?php
            exit();
        }
    }
}*/
$FullName = "DIMAS RIZKY FARRABI";
# data karyawan
$QData = GET_DETAIL_EMPLOYEE_BY_NAME($FullName,$linkMACHWebTrax);
while($RData = sqlsrv_fetch_array($QData))
{
    if(isset($RData['NIK'])){$UserNIK = trim($RData['NIK']);}else{$UserNIK = "";}
    if(isset($RData['FullName'])){$UserFN = trim($RData['FullName']);}else{$UserFN = "";}
    if(isset($RData['DivisionName'])){$UserDivName = trim($RData['DivisionName']);}else{$UserDivName = "";}
    if(isset($RData['CompanyCode'])){$UserCompanyCode = trim($RData['CompanyCode']);}else{$UserCompanyCode = "";}
}

$ValQuoteCategory = "";
$ArrListQuote = array();
$QListCategory = GET_LIST_QUOTE_BY_PARAM($ValQuoteCategory,$linkMACHWebTrax);
while($RListCategory = sqlsrv_fetch_array($QListCategory))
{
    $TempArray = array(
        "Quote" => trim($RListCategory['Quote']),
        "Location" => "PSL",
        "ProjectID" => trim($RListCategory['ProjectID'])
    );
    array_push($ArrListQuote,$TempArray);
}
asort($ArrListQuote);
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js" integrity="sha512-AIOTidJAcHBH2G/oZv9viEGXRqDNmfdPVPYOYKGy3fti0xIplnlgMHUGfuNRzC6FkzIo0iIxgFnr9RikFxK+sw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" type="text/css" href="lib/datetimepicker-master/jquery.datetimepicker.css"/>
<div class="row">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-detail ">
                <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Inventory : Stock Opname Form</li>
            </ol>
        </nav>
    </div>
</div>  
<div class="row">
    <div class="col-md-3">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="ListCategory">
                <thead class="theadCustom">
                    <tr>
                        <th class="text-center">Quote</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    foreach($ArrListQuote as $ListQuote)
                    {
                        $EncLocation = base64_encode($ListQuote['Location']);
                        echo '<tr data-id="'.$ListQuote['ProjectID'].'" data-log="'.$EncLocation.'" class="PointerList">';
                        echo '<td>'.$ListQuote['Quote'].'</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-9">
        <div class="row" id="OpnameContent">
            
        </div>
        <div class="row" id="FormContent" style="margin-top:20px;">
            
        </div>
    </div>
</div>
<script>
$(document).ready(function () { 
    var BolClickListCategory = "TRUE";
    if (BolClickListCategory == "TRUE") {
        $(".PointerList").click(function () {
            if (BolClickListCategory == "TRUE") {
                $("#ListCategory tr").removeClass('PointerListSelected');
                $(this).closest('.PointerList').addClass("PointerListSelected");
                var QuoteName = $(this).text();
                var ProjectID = $(this).data('id');
                var Location = $(this).data('log');
                $("#TempQuote").text(QuoteName);
                var formdata = new FormData();
                formdata.append('ValQuoteName', QuoteName);
                formdata.append('ValProjectID', ProjectID);
                formdata.append('ValLocation', Location);
                $.ajax({
                    url: 'project/Inventory/StockOpnameContent.php',
                    dataType: 'text',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formdata,
                    type: 'post',
                    beforeSend: function () {
                        BolClickListCategory = "FALSE";
                        $("html, body").animate({ scrollTop: $("#OpnameContent").offset().top - 70 }, "fast");
                        $('#OpnameContent').html("");
                        $("#OpnameContent").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                        $('#OpnameContent').html("");
                        $('#FormContent').hide();
                    },
                    success: function (xaxa) {
                        $('#OpnameContent').html("");
                        $('#OpnameContent').html(xaxa);
                        $('#OpnameContent').fadeIn('fast');
                        $("#ContentLoading").remove();
                        BolClickListCategory = "TRUE";
                        $("#BtnOpen").click(function(){ 
                            var InputTemplate = $("#InputTemplate option:selected").val();
                            var InputLocation = $("#InputLocation option:selected").val();
                            if(InputTemplate == "-- Pilih Template --")
                            {
                                $('#InputTemplate').focus();
                                return false;
                            }
                            if(InputLocation == "-- Pilih Lokasi --")
                            {
                                $('#InputLocation').focus();
                                return false;
                            }
                            SHOW_CONTENT_FORM(InputTemplate,InputLocation);
                        });
                    },
                    error: function () {
                        alert("Request cannot proceed!");
                        $('#OpnameContent').html("");
                        $("#ContentLoading").remove();
                        BolClickListCategory = "TRUE";
                    }
                });
            }
            else {
                return false;
            }
        });
    }
});
function SHOW_CONTENT_FORM(Input1,Input2)
{
    var formdata = new FormData();
    formdata.append('Input1', Input1);
    formdata.append('Input2', Input2);
    $.ajax({
        url: 'project/Inventory/StockOpnameForm.php',
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        data: formdata,
        type: 'post',
        beforeSend: function () {
            $('#FormContent').html("");
            $("#BtnOpen").prop('disabled', true);
            $("#FormContent").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
            $('#FormContent').html("");
        },
        success: function (xaxa) {
            $('#FormContent').html("");
            $('#FormContent').html(xaxa);
            $('#FormContent').fadeIn('fast');
            $("#ContentLoading").remove();
            $("#InputPartNo").focus();
            $("#BtnOpen").prop('disabled', false);
            $("#BtnProses").prop('disabled', true);
            $('#txtFilterTanggal1').datetimepicker({
                lang:'en',
                timepicker:false,
                format:'m/d/Y',
                formatDate:'m/d/Y'
            });
            $("#BtnPart").click(function(){
                var PartNo = $("#InputPartNo").val().trim();
                if(PartNo == "")
                {
                    $("#InputPartNo").focus();
                    return false;
                }
                var formdata = new FormData();
                formdata.append('PartNo', PartNo);
                $.ajax({
                    url: 'project/Inventory/StockOpnameTempTable.php',
                    dataType: 'text',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formdata,
                    type: 'post',
                    beforeSend: function () {
                        $('#TempTable').html("");
                        $("#BtnPart").prop('disabled', true);
                        $("#TempTable").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                        $('#TempTable').html("");
                    },
                    success: function (xaxa) {
                        $('#TempTable').html("");
                        $('#TempTable').html(xaxa);
                        $('#TempTable').fadeIn('fast');
                        $("#ContentLoading").remove();
                        $("#BtnPart").prop('disabled', false);
                        $("#BtnProses").prop('disabled', false);
                    },
                    error: function () { 
                        alert("Request cannot proceed!");
                        $('#TempTable').html("");
                        $("#ContentLoading").remove();
                    }
                });
            });
            $("#InputPartNo").on("keypress", function (e) {
                if (e.which == 13) {
                    $('#BtnPart').trigger('click');
                }
            });
             
        },
        error: function () {
            alert("Request cannot proceed!");
            $("#BtnOpen").prop('disabled', false);
            $('#FormContent').html("");
            $("#ContentLoading").remove();
        }
    });
}
</script>