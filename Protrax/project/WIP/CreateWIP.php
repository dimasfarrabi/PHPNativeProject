<?php
require_once("project/WIP/Modules/ModuleWIP.php"); 
require_once("project/WIP/Modules/ModuleWIPProcess.php"); 
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
    $RDataUserWebtrax = mssql_fetch_assoc($QDataUserWebtrax);
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
    if($RDataUserWebtrax['MnProduction'] != "1")
    {
        ?>
        <script language="javascript">
            window.location.replace("https://protrax.formulatrix.com/");
        </script>
        <?php
        exit();
    }
}<script src="project/WIP/lib/LibInventoryBinV3.js?no=<?php echo base64_encode(date("mdyHis")); ?>"></script>
*/

?>
<style>
    .TextFont{font-size: 18px;}
</style>
<div class="row">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-detail ">
                <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Production : Create WIP Barcode</li>
            </ol>
        </nav>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <h6 class="card-header text-white bg-secondary">Pembuatan WIP</h6>
            <div class="card-body pt-2">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="FilType" class="form-label fw-bold">Pilih Berdasarkan</label>
                            <form id="FilType">
                                <label class="radio-inline TextFont">
                                <input type="radio" name="RadioFilter" class="radio-inline-custom RadioFilter" value="ByTemplate">&nbsp;<strong>Template</strong></label>
                                <label class="radio-inline TextFont" style="margin-left:50px;">
                                <input type="radio" name="RadioFilter" class="radio-inline-custom RadioFilter" value="ByPart">&nbsp;<strong>Per Part</strong></label>
                                <label class="radio-inline TextFont" style="margin-left:50px;">
                            </form>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="InvisibleText" class="TextFont">Search</label>
                        <div>
                            <button type="button" class="btn btn-md btn-dark" id="BtnOK" style="width:30%">OK</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row" style="margin-top:20px;">
    <div class="col-md-6" id="SearchSection">
        
    </div>
    <div class="col-md-6" id="FormSection">
        
    </div>
</div>
<script>
$(document).ready(function () { 
    $("#BtnOK").click(function(){ 
        var FilType = $('input[name=RadioFilter]:checked', '#FilType').val();
        if(FilType == undefined)
        {
            alert('Permintaan Tidak Dapat Diproses!');
            return false;
        }
        else if(FilType == "ByTemplate"){
            var formdata = new FormData();
            formdata.append('FilType', FilType);
            $.ajax({
                url: 'project/WIP/CreateWIPSearchTemplate.php',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: formdata,
                type: 'post',
                beforeSend: function () {
                    $("#BtnOK").attr('disabled', true);
                    $('#SearchSection').html("");
                    $("#SearchSection").before('<div class="col-sm-12 d-flex justify-content-center" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                    $('#SearchSection').html("");
                },
                success: function (xaxa) {
                    $('#SearchSection').html("");
                    $('#SearchSection').html(xaxa);
                    $('#SearchSection').fadeIn('fast');
                    $("#ContentLoading").remove();
                    $("#BtnOK").attr('disabled', false);
                    $("#keywords").focus();
                    var FilParam = '';
                    var Keywords = '';
                    SHOW_TEMPLATE_LIST(FilParam,Keywords);
                    $("#BtnSearchTemplate").click(function(){  
                        var FilParam = $("#FilKey").val();
                        var Keywords = $("#keywords").val().trim();
                        SHOW_TEMPLATE_LIST(FilParam,Keywords);
                    });
                },
                error: function () {
                    alert("Request cannot proceed!");
                    $("#BtnOK").blur();
                    $('#SearchSection').html("");
                    $("#ContentLoading").remove();
                    $("#BtnOK").attr('disabled', false);
                }
            });
        }
        else{
            var formdata = new FormData();
            formdata.append('FilType', FilType);
            $.ajax({
                url: 'project/WIP/CreateWIPSearchPart.php',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: formdata,
                type: 'post',
                beforeSend: function () {
                    $("#BtnOK").attr('disabled', true);
                    $('#FormSection').hide();
                    $('#SearchSection').html("");
                    $("#SearchSection").before('<div class="col-sm-12 d-flex justify-content-center" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                    $('#SearchSection').html("");
                },
                success: function (xaxa) {
                    $('#SearchSection').html("");
                    $('#SearchSection').html(xaxa);
                    $('#SearchSection').fadeIn('fast');
                    $("#ContentLoading").remove();
                    $("#BtnOK").attr('disabled', false);
                },
                error: function () {
                    alert("Request cannot proceed!");
                    $("#BtnOK").blur();
                    $('#SearchSection').html("");
                    $("#ContentLoading").remove();
                    $("#BtnOK").attr('disabled', false);
                }
            });
        }
    });
});
function SHOW_TEMPLATE_LIST(Val1,Val2)
{
    var formdata = new FormData();
    formdata.append('FilParam', Val1);
    formdata.append('Keywords', Val2);
    $.ajax({
        url: 'project/WIP/TemplateList.php',
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        data: formdata,
        type: 'post',
        beforeSend: function () {
            $('#TemplateTableA').html("");
            $("#TemplateTableA").before('<div class="col-sm-12 d-flex justify-content-center" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
            $('#TemplateTableA').html("");
        },
        success: function (xaxa) {
            $('#TemplateTableA').html("");
            $('#TemplateTableA').html(xaxa);
            $('#TemplateTableA').fadeIn('fast');
            $("#ContentLoading").remove();
            $('#ListTemplateName').DataTable({
                "iDisplayLength": 10,
                "lengthChange": false,
                searching: false,
            });
            $("#keywords").val('');
            $("#keywords").focus();
            $("#ModalCreateBarcode").on('show.bs.modal', function (event) {
                var act = $(event.relatedTarget);
                var DataCode = act.data('ecode');
                var formdata = new FormData();
                formdata.append("ValCode", DataCode);
                $.ajax({
                    url: 'project/WIP/ModalCreateBarcode.php',
                    dataType: 'text',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formdata,
                    type: 'post',
                    beforeSend: function () {
                        $('#ModalCreateBarcodeContent').html("");
                        $("#ModalCreateBarcodeContent").before('<div class="col-sm-12 d-flex justify-content-center" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                        $('#ModalCreateBarcodeContent').html("");
                    },
                    success: function (xaxa) {
                        $('#ModalCreateBarcodeContent').html("");
                        $('#ModalCreateBarcodeContent').html(xaxa);
                        $('#ModalCreateBarcodeContent').fadeIn('fast');
                        $("#ContentLoading").remove();
                        $("#BtnPlus").click(function(){ 
                            var a = parseFloat($("#JmlBarcode").val());
                            var b = a+1;
                            $("#JmlBarcode").val(b);
                        });
                        $("#BtnMinus").click(function(){ 
                            var a = parseFloat($("#JmlBarcode").val());
                            var b = a-1;
                            $("#JmlBarcode").val(b);
                        });
                        $("#BtnSubmitJmlBarcode").click(function(){ 
                            var valPart = $("#ValPartNo").val();
                            var valQty = $("#ValQty").val();
                            var JmlBC = $("#JmlBarcode").val();
                            var Tipe = $(this).data("id");
                            var formdata = new FormData();
                            formdata.append("valPart", valPart);
                            formdata.append("valQty", valQty);
                            formdata.append("JmlBC", JmlBC);
                            formdata.append("Tipe", Tipe);
                            $.ajax({
                                url: 'project/WIP/src/srcSubmitModalCreateBarcode.php',
                                dataType: 'text',
                                cache: false,
                                contentType: false,
                                processData: false,
                                data: formdata,
                                type: 'post',
                                beforeSend: function () {
                                },
                                success: function (xaxa) {
                                    var Res = xaxa;
                                    if(Res == "TRUE"){
                                        $("#ModalCreateBarcode").modal('hide');
                                        SHOW_PRINT(Tipe);
                                    }
                                    else{
                                        alert('Permintaan Tidak Dapat Diproses');
                                    }
                                },
                                error: function () {
                                }
                            });
                        });
                    },
                    error: function () {
                        alert("Request cannot proceed!");
                        $('#ModalCreateBarcodeContent').html("");
                        $("#ContentLoading").remove();
                    }
                });
            });
        },
        error: function () {
            alert("Request cannot proceed!");
            $('#TemplateTableA').html("");
            $("#ContentLoading").remove();
        }
    });
}
function SHOW_PRINT(Data1)
{
    var formdata = new FormData();
    formdata.append("Tipe", Data1);
    $.ajax({
        url: 'project/WIP/CreateWIPReadyPrint.php',
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        data: formdata,
        type: 'post',
        beforeSend: function () {
            $('#FormSection').html("");
            $("#FormSection").before('<div class="col-sm-12 d-flex justify-content-center" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
            $('#FormSection').html("");
        },
        success: function (xaxa) {
            $('#FormSection').html("");
            $('#FormSection').html(xaxa);
            $('#FormSection').fadeIn('fast');
            $("#ContentLoading").remove();
        },
        error: function () {
            alert("Request cannot proceed!");
            $('#FormSection').html("");
            $("#ContentLoading").remove();
        }
    });
}
</script>