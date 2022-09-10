<?php
require_once("project/WOMapping/Modules/ModuleWOMapping.php");
require_once("project/CostTracking/Modules/ModuleCostTracking.php");
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

if((trim($AccessLogin) != "Manager"))
{
    if($RDataUserWebtrax['MnAdmin'] != "1" && $RDataUserWebtrax['MnWOMapping'] != "1")  
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
        if($RDataUserWebtrax['MnWOMapping'] != "1")
        {
            ?>
            <script language="javascript">
                window.location.replace("https://protrax.formulatrix.com/");
            </script>
            <?php
            exit();
        }
    }
}<script src="project/WOMapping/lib/LibManageWOMapping.js?no=<?php echo base64_encode(date("mdyHis")); ?>"></script>
*/

?>


<div class="row">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-detail ">
                <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">WO Mapping : Manage WO Mapping</li>
            </ol>
        </nav>
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        <div class="row">
            <div class="col-md-12 pt-3">
                <div class="card">
                    <h6 class="card-header text-white bg-secondary">Filter ClosedTime</h6>
                    <div class="card-body pt-2">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="FilterLocation" class="form-label fw-bold">Lokasi</label>
                                    <select class="form-select form-select-sm" id="FilterLocation">
                                        <option>PSL</option>
                                        <option>PSM</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="FilterSeason" class="form-label fw-bold">Closed Time</label>
                                    <select class="form-select form-select-sm" id="FilterSeason">
                                        <option>OPEN</option>
                                        <?php 
                                        $QListClosedTimeF = GET_LIST_CLOSEDTIME_NOT_OPEN("",$linkMACHWebTrax);
                                        while($RListClosedTimeF = sqlsrv_fetch_array($QListClosedTimeF))
                                        {
                                            $ClosedTime = $RListClosedTimeF['ClosedTime'];
                                            ?>
                                            <option><?php echo $ClosedTime; ?></option>
                                            <?php
                                        }                
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12"><div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="UsedOpen" readonly>
                                    <label class="form-check-label" for="UsedOpen">Termasuk Open</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 pt-3">
                <div class="card">
                    <h6 class="card-header text-white bg-secondary">Custom Search</h6>
                    <div class="card-body pt-2">
                        <div class="row">
                            <div class="col-md-12"><div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="UsedClosedTime" checked>
                                    <label class="form-check-label" for="UsedClosedTime">Dengan ClosedTime</label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="FilterCustom" class="form-label fw-bold">Category</label>
                                    <select class="form-select form-select-sm" id="FilterCustom">
                                        <option>WO Mapping ID</option>
                                        <option>Quote</option>
                                        <option>Expense Allocation</option>
                                        <option>Product</option>
                                        <option>WO Child</option>
                                        <option>PM</option>
                                        <option>WO Type</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="FilterKeywords" class="form-label fw-bold">Keywords</label>
                                    <input type="text" class="form-control form-control-sm" id="FilterKeywords" placeholder="Keywords" value="">
                                </div>
                            </div>                                
                            <div class="col-md-12 d-grid mt-2">
                                <button class="btn btn-sm btn-dark" id="BtnViewWO">View Data</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-9 pt-2"><div class="row" id="ContentResult"></div></div>
    <div class="col-md-12 mt-4 pb-4"></div>
</div>
          
<div class="modal fade" id="ModalUpdateWO" tabindex="-1" role="dialog" aria-labelledby="Modal-View" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update WO Mapping</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="ContentModalUpdateWOMapping"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal" id="CloseModalUpdateWO">&nbsp;Close&nbsp;</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="ModalUpdateCT" tabindex="-1" role="dialog" aria-labelledby="Modal-View" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Closed Time</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="ContentModalUpdateClosedTime"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">&nbsp;Close&nbsp;</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="ModalDeleteWO" tabindex="-1" role="dialog" aria-labelledby="Modal-View" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete WO Mapping</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="ContentModalDeleteWO"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">&nbsp;Close&nbsp;</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="ModalUpdateDataSelected" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="Modal-View" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="TitleModalUpdateDataSelected"></h5>
            </div>
            <div class="modal-body">
                <div id="ContentModalUpdateDataSelected"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-primary" data-bs-target="#ModalUpdateWO" data-bs-toggle="modal" data-bs-dismiss="modal" id="BackFirst">&nbsp;Back to first modal&nbsp;</button>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function () {
    if ($("#FilterSeason option:selected").text().trim() == "OPEN")
    {
        $("#UsedOpen").prop("checked", false);
        $("#UsedOpen").attr("disabled", true); 
    }
    $("#FilterSeason").on('change', function () {
        var FilterSeason = $("#FilterSeason option:selected").text().trim();
        if (FilterSeason == "OPEN") {
            $("#UsedOpen").prop("checked", false);
            $("#UsedOpen").attr("disabled", true); 
        }
        else {
            $("#UsedOpen").prop("checked", false);
            $("#UsedOpen").attr("disabled", false); 
        }
    });
    $("#BtnViewWO").click(function () {
        var Location = $("#FilterLocation option:selected").val().trim();
        var ClosedTime = $("#FilterSeason").val().trim();
        var Open = "off";
        if ($('#UsedOpen').is(":checked")) {
            Open = $('#UsedOpen').val().trim();
        }
        var UsedCL = "off";
        if ($('#UsedClosedTime').is(":checked")) {
            UsedCL = $('#UsedClosedTime').val().trim();
        }
        var FilterType = $("#FilterCustom").val().trim();
        var Keywords = $("#FilterKeywords").val().trim();
        var formdata = new FormData();
        formdata.append('Location', Location);
        formdata.append('ClosedTime', ClosedTime);
        formdata.append('Open', Open);
        formdata.append('UsedCL', UsedCL);
        formdata.append('FilterType', FilterType);
        formdata.append('Keywords', Keywords);
        $.ajax({
            url: 'project/WOMapping/ContentManageWOMapping.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#BtnViewWO").attr("disabled", true);
                $("#ContentResult").html("");
                $("#ContentResult").append('<div class="col-sm-12 pt-3 d-flex justify-content-center" id="ContentLoading1"><div class="spinner-border text-secondary" role="status"><span class="visually-hidden" id="LoadingViewData">Loading...</span ></div></div>');
            },
            success: function (xaxa) {
                $("#ContentLoading1").remove();
                $("#ContentResult").html("");
                $("#ContentResult").html(xaxa);
                $("#ContentResult").fadeIn("fast");                
                $("#BtnViewWO").blur();
                $("#BtnViewWO").attr("disabled", false);
                $("#TableWOMapping tbody .OptionUpdate").css({ 'color': '#337AB7', 'cursor': 'pointer', 'font-size': '14px' });
                $("#TableWOMapping tbody .UpdateCT").css({ 'color': '#337AB7', 'cursor': 'pointer', 'font-size': '14px' });
                $("#TableWOMapping tbody .DeleteWO").css({ 'color': '#337AB7', 'cursor': 'pointer', 'font-size': '14px' });
                $("#TableWOMapping tbody").css({ 'font-size': '13px' });
                $("#TableWOMapping").DataTable({
                    "pagingType": "full",
                    "columnDefs": [{
                        "targets": 1,
                        "orderable": false
                    }]
                });
                $("#TableWOMapping tr > th:nth-child(2)").css({ 'min-width': '80px', 'max-width': '80px' });
                $("#TableWOMapping").on("click", "tbody tr td .OptionUpdate", function () {
                    var ClosedTime = $(this).closest("tr").find("td:eq(2)").text();
                    var WOMapping_ID = $(this).closest("tr").find("td:eq(5)").text();
                    var Location = $(this).closest("tr").find("td:eq(27)").text();
                    var DataRows = $(this).closest("tr").data("idrows");
                    var formdata = new FormData();
                    formdata.append('ValClosedTime', ClosedTime);
                    formdata.append('ValWOMapping_ID', WOMapping_ID);
                    formdata.append('ValLocation', Location);
                    formdata.append('ValDataRows', DataRows);
                    $.ajax({
                        url: 'project/WOMapping/ContentManageModuleUpdateWOMapping.php',
                        dataType: 'text',
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formdata,
                        type: 'post',
                        beforeSend: function () {
                            $("#ModalUpdateWO").modal("show");
                            $("#ContentModalUpdateWOMapping").html("");
                            $("#ContentModalUpdateWOMapping").append('<div class="col-sm-12 pt-3 d-flex justify-content-center" id="ContentLoading2"><div class="spinner-border text-secondary" role="status"><span class="visually-hidden" id="LoadingViewData">Loading...</span ></div></div>');
                        },
                        success: function (xaxa) {
                            $("#ContentLoading2").remove();
                            $("#ContentModalUpdateWOMapping").html("");
                            $("#ContentModalUpdateWOMapping").html(xaxa);
                            $("#ContentModalUpdateWOMapping").fadeIn("fast");
                            if(ClosedTime != 'OPEN')
                            {
                                $("#ActChangeQuote").attr("disabled", true);
                                $("#ActRenameWOChild").attr("disabled", true);
                                $("#ActRenameWOParent").attr("disabled", true);
                                $("#ActUpdateQtyParentQuote").attr("disabled", true);
                                $("#ActRecalculateQtyQuote").attr("disabled", true);
                                $("#ActUpdatePMDM").attr("disabled", true);
                                $("#ActChangeQuoteCategory").attr("disabled", true);
                                $("#ActChangeExpense").attr("disabled", true);
                                $("#ActEditTargetHour").attr("disabled", true);
                            }
                            $("#ActEditTargetHour").click(function(){ 
                                $("#TitleModalUpdateDataSelected").text("Edit Target Hour");
                                var formdata = new FormData();
                                formdata.append('ValClosedTime', ClosedTime);
                                formdata.append('ValWOMapping_ID', WOMapping_ID);
                                formdata.append('ValLocation', Location);
                                $.ajax({
                                    url: 'project/WOMapping/ContentEditTargetHour.php',
                                    dataType: 'text',
                                    cache: false,
                                    contentType: false,
                                    processData: false,
                                    data: formdata,
                                    type: 'post',
                                    beforeSend: function () {
                                        $("#ContentModalUpdateDataSelected").html("");
                                        $("#ContentModalUpdateDataSelected").append('<div class="col-sm-12 pt-3 d-flex justify-content-center" id="ContentLoading5"><div class="spinner-border text-secondary" role="status"><span class="visually-hidden" id="LoadingViewData">Loading...</span ></div></div>');
                                    },
                                    success: function (xaxa) {
                                        $("#ContentLoading5").remove();
                                        $("#ContentModalUpdateDataSelected").html("");
                                        $("#ContentModalUpdateDataSelected").html(xaxa);
                                        $("#ContentModalUpdateDataSelected").fadeIn("fast");
                                        $("#InputTargetHour").focus();
                                        $("#BtnUpdateTargetHour").click(function(){
                                            if ($("#InputTargetHour").val() == "")
                                            {
                                                $("#InputTargetHour").focus();
                                                return false;
                                            }
                                            if (confirm("Apakah anda yakin akan mengganti Target Hour dari WO Mapping ini?") == true) {
                                                var NewTargetHour = $("#InputTargetHour").val().trim();
                                                var TemporaryBtn = $("#BtnUpdateTargetHour").data("temp");
                                                var formdata = new FormData();
                                                formdata.append('ValWOMapping_ID', WOMapping_ID);
                                                formdata.append('NewTargetHour', NewTargetHour);
                                                $.ajax({
                                                    url: 'project/WOMapping/src/srcUpdateTargetHour.php',
                                                    dataType: 'text',
                                                    cache: false,
                                                    contentType: false,
                                                    processData: false,
                                                    data: formdata,
                                                    type: 'post',
                                                    beforeSend: function () { 
                                                        $("#BtnUpdateTargetHour").attr("disabled", true);
                                                        $("#InfoUpdateHour").html("");
                                                        $("#InfoUpdateHour").append('<div class="col-sm-12 pt-3 d-flex justify-content-center" id="ContentLoading6"><div class="spinner-border text-secondary" role="status"><span class="visually-hidden" id="LoadingViewData">Loading...</span ></div></div>');
                                                    },
                                                    success: function (xaxa) {
                                                        $("#ContentLoading6").remove();
                                                        $("#InfoUpdateHour").html("");
                                                        var Results = xaxa;
                                                        if(Results == "True")
                                                        {
                                                            $("#TableWOMapping tr[data-idrows='" + TemporaryBtn + "']").find("td:eq(15)").text(NewTargetHour);
                                                            $("#BackFirst").click();
                                                        }
                                                        else
                                                        {
                                                            $("#InfoUpdateHour").append('<div class="alert alert-danger alert-dismissible" role="alert"><strong>Error!</strong><br>Data ada yang gagal diupdate! Mohon dicoba lagi.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                                                            $("#BtnUpdateTargetHour").attr("disabled", false);
                                                        }
                                                    },
                                                    error: function () {
                                                        alert("Request cannot proceed!");
                                                        $("#ContentLoading6").remove();
                                                        $("#InfoUpdateHour").html("");
                                                        $("#BtnUpdateTargetHour").attr("disabled", false);
                                                    }
                                                });
                                            }
                                        });
                                    },
                                    error: function () {
                                        alert("Request cannot proceed!");
                                        $("#ContentLoading5").remove();
                                        $("#ContentModalUpdateDataSelected").html("");
                                    }
                                });
                            });
                            $("#ActChangeQuote").click(function(){
                                $("#TitleModalUpdateDataSelected").text("Change Quote");
                                var formdata = new FormData();
                                formdata.append('ValClosedTime', ClosedTime);
                                formdata.append('ValWOMapping_ID', WOMapping_ID);
                                formdata.append('ValLocation', Location);
                                $.ajax({
                                    url: 'project/WOMapping/ContentChangeQuoteManageWOMapping.php',
                                    dataType: 'text',
                                    cache: false,
                                    contentType: false,
                                    processData: false,
                                    data: formdata,
                                    type: 'post',
                                    beforeSend: function () {
                                        $("#ContentModalUpdateDataSelected").html("");
                                        $("#ContentModalUpdateDataSelected").append('<div class="col-sm-12 pt-3 d-flex justify-content-center" id="ContentLoading5"><div class="spinner-border text-secondary" role="status"><span class="visually-hidden" id="LoadingViewData">Loading...</span ></div></div>');
                                    },
                                    success: function (xaxa) {
                                        $("#ContentLoading5").remove();
                                        $("#ContentModalUpdateDataSelected").html("");
                                        $("#ContentModalUpdateDataSelected").html(xaxa);
                                        $("#ContentModalUpdateDataSelected").fadeIn("fast");
                                        
                                        $("#BtnChangeQuote").click(function(){
                                            if ($("#FieldNewQuote").val().trim() == "")
                                            {
                                                $("#FieldNewQuote").focus();
                                                return false;
                                            }
                                            if (confirm("Apakah anda yakin akan mengganti quote dari WO Mapping ini?") == true) {
                                                var Location = $("#FieldLocation2").val().trim();
                                                var WOMappingID = $("#FieldWOID2").val().trim();
                                                var ClosedTime = $("#FieldClosedTime2").val().trim();
                                                var NewQuote = $("#FieldNewQuote").val().trim().toUpperCase();
                                                var TemporaryBtn = $("#BtnChangeQuote").data("temp");
                                                var formdata = new FormData();
                                                formdata.append('ValLocation', Location);
                                                formdata.append('ValWOMappingID', WOMappingID);
                                                formdata.append('ValClosedTime', ClosedTime);
                                                formdata.append('ValNewQuote', NewQuote);
                                                formdata.append('ValTemporaryBtn', TemporaryBtn);
                                                $.ajax({
                                                    url: 'project/WOMapping/src/srcUpdateQuoteWOMapping.php',
                                                    dataType: 'text',
                                                    cache: false,
                                                    contentType: false,
                                                    processData: false,
                                                    data: formdata,
                                                    type: 'post',
                                                    beforeSend: function () {
                                                        $("#BtnChangeQuote").attr("disabled", true);
                                                        $("#InfoChangeQuote").html("");
                                                        $("#InfoChangeQuote").append('<div class="col-sm-12 pt-3 d-flex justify-content-center" id="ContentLoading6"><div class="spinner-border text-secondary" role="status"><span class="visually-hidden" id="LoadingViewData">Loading...</span ></div></div>');
                                                    },
                                                    success: function (xaxa) {
                                                        $("#ContentLoading6").remove();
                                                        $("#InfoChangeQuote").html("");
                                                        var Results = xaxa;
                                                        if (Results == "0")
                                                        {
                                                            $("#InfoChangeQuote").append('<div class="alert alert-danger alert-dismissible" role="alert"><strong>Error!</strong><br>Data ada yang gagal diupdate! Mohon dicoba lagi.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                                                            $("#BtnChangeQuote").attr("disabled", false);
                                                        }
                                                        if (Results == "1")
                                                        {
                                                            $("#TableWOMapping tr[data-idrows='" + TemporaryBtn + "']").find("td:eq(3)").text(NewQuote);
                                                            $("#BackFirst").click();
                                                        }
                                                    },
                                                    error: function () {
                                                        alert("Request cannot proceed!");
                                                        $("#ContentLoading6").remove();
                                                        $("#InfoChangeQuote").html("");
                                                        $("#BtnChangeQuote").attr("disabled", false);
                                                    }
                                                });                                                
                                            }
                                        });
                                    },
                                    error: function () {
                                        alert("Request cannot proceed!");
                                        $("#ContentLoading5").remove();
                                        $("#ContentModalUpdateDataSelected").html("");
                                    }
                                });
                            });
                            $("#ActRenameWOChild").click(function () {
                                $("#TitleModalUpdateDataSelected").text("Rename WO Child");
                                var formdata = new FormData();
                                formdata.append('ValClosedTime', ClosedTime);
                                formdata.append('ValWOMapping_ID', WOMapping_ID);
                                formdata.append('ValLocation', Location);
                                $.ajax({
                                    url: 'project/WOMapping/ContentRenameWOChildManageWOMapping.php',
                                    dataType: 'text',
                                    cache: false,
                                    contentType: false,
                                    processData: false,
                                    data: formdata,
                                    type: 'post',
                                    beforeSend: function () {
                                        $("#ContentModalUpdateDataSelected").html("");
                                        $("#ContentModalUpdateDataSelected").append('<div class="col-sm-12 pt-3 d-flex justify-content-center" id="ContentLoading5"><div class="spinner-border text-secondary" role="status"><span class="visually-hidden" id="LoadingViewData">Loading...</span ></div></div>');
                                    },
                                    success: function (xaxa) {
                                        $("#ContentLoading5").remove();
                                        $("#ContentModalUpdateDataSelected").html("");
                                        $("#ContentModalUpdateDataSelected").html(xaxa);
                                        $("#ContentModalUpdateDataSelected").fadeIn("fast");
                                        $("#BtnChangeWOC").click(function () {
                                            if ($("#FieldNewWOChild").val().trim() == "") {
                                                $("#FieldNewWOChild").focus();
                                                return false;
                                            }
                                            if (confirm("Apakah anda yakin akan mengganti WO Child dari WO Mapping ini?") == true) {
                                                var Location = $("#FieldLocation2").val().trim();
                                                var WOMappingID = $("#FieldWOID2").val().trim();
                                                var ClosedTime = $("#FieldClosedTime2").val().trim();
                                                var NewWOC = $("#FieldNewWOChild").val().trim().toUpperCase();
                                                var TemporaryBtn = $("#BtnChangeWOC").data("temp");
                                                var formdata = new FormData();
                                                formdata.append('ValLocation', Location);
                                                formdata.append('ValWOMappingID', WOMappingID);
                                                formdata.append('ValClosedTime', ClosedTime);
                                                formdata.append('ValNewWOC', NewWOC);
                                                formdata.append('ValTemporaryBtn', TemporaryBtn);
                                                $.ajax({
                                                    url: 'project/WOMapping/src/srcUpdateWOCWOMapping.php',
                                                    dataType: 'text',
                                                    cache: false,
                                                    contentType: false,
                                                    processData: false,
                                                    data: formdata,
                                                    type: 'post',
                                                    beforeSend: function () {
                                                        $("#BtnChangeWOC").attr("disabled", true);
                                                        $("#InfoUpdateWOChild").html("");
                                                        $("#InfoUpdateWOChild").append('<div class="col-sm-12 pt-3 d-flex justify-content-center" id="ContentLoading6"><div class="spinner-border text-secondary" role="status"><span class="visually-hidden" id="LoadingViewData">Loading...</span ></div></div>');
                                                    },
                                                    success: function (xaxa) {
                                                        $("#ContentLoading6").remove();
                                                        $("#InfoUpdateWOChild").html("");
                                                        var Results = xaxa;
                                                        if (Results == "0") {
                                                            $("#InfoUpdateWOChild").append('<div class="alert alert-danger alert-dismissible" role="alert"><strong>Error!</strong><br>Data ada yang gagal diupdate! Mohon dicoba lagi.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                                                            $("#BtnChangeWOC").attr("disabled", false);
                                                        }
                                                        if (Results == "1") {
                                                            $("#TableWOMapping tr[data-idrows='" + TemporaryBtn + "']").find("td:eq(6)").text(NewWOC);
                                                            var TempExpense = $("#TableWOMapping tr[data-idrows='" + TemporaryBtn + "']").find("td:eq(4)").text();
                                                            $("#TableWOMapping tr[data-idrows='" + TemporaryBtn + "']").find("td:eq(26)").text(TempExpense + NewWOC);
                                                            $("#BackFirst").click();
                                                        }
                                                    },
                                                    error: function () {
                                                        alert("Request cannot proceed!");
                                                        $("#ContentLoading6").remove();
                                                        $("#InfoUpdateWOChild").html("");
                                                        $("#BtnChangeWOC").attr("disabled", false);
                                                    }
                                                });
                                            }
                                        });
                                    },
                                    error: function () {
                                        alert("Request cannot proceed!");
                                        $("#ContentLoading5").remove();
                                        $("#ContentModalUpdateDataSelected").html("");
                                    }
                                });
                            });
                            $("#ActRenameWOParent").click(function () {
                                $("#TitleModalUpdateDataSelected").text("Rename WO Parent");
                                var formdata = new FormData();
                                formdata.append('ValClosedTime', ClosedTime);
                                formdata.append('ValWOMapping_ID', WOMapping_ID);
                                formdata.append('ValLocation', Location);
                                $.ajax({
                                    url: 'project/WOMapping/ContentRenameWOParentManageWOMapping.php',
                                    dataType: 'text',
                                    cache: false,
                                    contentType: false,
                                    processData: false,
                                    data: formdata,
                                    type: 'post',
                                    beforeSend: function () {
                                        $("#ContentModalUpdateDataSelected").html("");
                                        $("#ContentModalUpdateDataSelected").append('<div class="col-sm-12 pt-3 d-flex justify-content-center" id="ContentLoading5"><div class="spinner-border text-secondary" role="status"><span class="visually-hidden" id="LoadingViewData">Loading...</span ></div></div>');
                                    },
                                    success: function (xaxa) {
                                        $("#ContentLoading5").remove();
                                        $("#ContentModalUpdateDataSelected").html("");
                                        $("#ContentModalUpdateDataSelected").html(xaxa);
                                        $("#ContentModalUpdateDataSelected").fadeIn("fast");
                                        $("#BtnChangeWOParent").click(function () {
                                            if ($("#FieldNewWOParent").val().trim() == "") {
                                                $("#FieldNewWOParent").focus();
                                                return false;
                                            }
                                            if (confirm("Apakah anda yakin akan mengganti WO Parent dari WO Mapping ini?") == true) {
                                                var Location = $("#FieldLocation2").val().trim();
                                                var WOMappingID = $("#FieldWOID2").val().trim();
                                                var ClosedTime = $("#FieldClosedTime2").val().trim();
                                                var NewWOP = $("#FieldNewWOParent").val().trim().toUpperCase();
                                                var TemporaryBtn = $("#BtnChangeWOParent").data("temp");

                                                var formdata = new FormData();
                                                formdata.append('ValLocation', Location);
                                                formdata.append('ValWOMappingID', WOMappingID);
                                                formdata.append('ValClosedTime', ClosedTime);
                                                formdata.append('ValNewWOP', NewWOP);
                                                formdata.append('ValTemporaryBtn', TemporaryBtn);
                                                $.ajax({
                                                    url: 'project/WOMapping/src/srcUpdateWOPWOMapping.php',
                                                    dataType: 'text',
                                                    cache: false,
                                                    contentType: false,
                                                    processData: false,
                                                    data: formdata,
                                                    type: 'post',
                                                    beforeSend: function () {
                                                        $("#BtnChangeWOParent").attr("disabled", true);
                                                        $("#InfoUpdateWOParent").html("");
                                                        $("#InfoUpdateWOParent").append('<div class="col-sm-12 pt-3 d-flex justify-content-center" id="ContentLoading6"><div class="spinner-border text-secondary" role="status"><span class="visually-hidden" id="LoadingViewData">Loading...</span ></div></div>');
                                                    },
                                                    success: function (xaxa) {
                                                        $("#ContentLoading6").remove();
                                                        $("#InfoUpdateWOParent").html("");
                                                        var Results = xaxa;
                                                        if (Results == "0") {
                                                            $("#InfoUpdateWOParent").append('<div class="alert alert-danger alert-dismissible" role="alert"><strong>Error!</strong><br>Data ada yang gagal diupdate! Mohon dicoba lagi.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                                                            $("#BtnChangeWOParent").attr("disabled", false);
                                                        }
                                                        if (Results == "1") {
                                                            $("#TableWOMapping tr[data-idrows='" + TemporaryBtn + "']").find("td:eq(7)").text(NewWOP);
                                                            $("#BackFirst").click();
                                                        }
                                                    },
                                                    error: function () {
                                                        alert("Request cannot proceed!");
                                                        $("#ContentLoading6").remove();
                                                        $("#InfoUpdateWOParent").html("");
                                                        $("#BtnChangeWOParent").attr("disabled", false);
                                                    }
                                                });
                                            }
                                        });
                                    },
                                    error: function () {
                                        alert("Request cannot proceed!");
                                        $("#ContentLoading5").remove();
                                        $("#ContentModalUpdateDataSelected").html("");
                                    }
                                });
                            });
                            $("#ActUpdateQtyParentQuote").click(function () {
                                $("#TitleModalUpdateDataSelected").text("Update Qty Parent & Qty Quote");
                                var formdata = new FormData();
                                formdata.append('ValClosedTime', ClosedTime);
                                formdata.append('ValWOMapping_ID', WOMapping_ID);
                                formdata.append('ValLocation', Location);
                                $.ajax({
                                    url: 'project/WOMapping/ContentUpdateQtyParentQuoteManageWOMapping.php',
                                    dataType: 'text',
                                    cache: false,
                                    contentType: false,
                                    processData: false,
                                    data: formdata,
                                    type: 'post',
                                    beforeSend: function () {
                                        $("#ContentModalUpdateDataSelected").html("");
                                        $("#ContentModalUpdateDataSelected").append('<div class="col-sm-12 pt-3 d-flex justify-content-center" id="ContentLoading5"><div class="spinner-border text-secondary" role="status"><span class="visually-hidden" id="LoadingViewData">Loading...</span ></div></div>');
                                    },
                                    success: function (xaxa) {
                                        $("#ContentLoading5").remove();
                                        $("#ContentModalUpdateDataSelected").html("");
                                        $("#ContentModalUpdateDataSelected").html(xaxa);
                                        $("#ContentModalUpdateDataSelected").fadeIn("fast");
                                        $("#FieldNewQtyParent").keypress(function (e) {
                                            if ((e.which < 48 || e.which > 57)) {
                                                e.preventDefault();
                                            }
                                        });
                                        $("#FieldNewQtyParent").on('input', function (e) {
                                            $(this).val(function (i, v) {
                                                if ((e.which != 46) && (e.which < 48 || e.which > 57)) {
                                                    e.preventDefault();
                                                }
                                                return v.replace(/[^0-9]/g, '');
                                            });
                                        });
                                        $("#BtnUpdateQtyParent").click(function () {
                                            if ($("#FieldNewQtyParent").val().trim() == "") {
                                                $("#FieldNewQtyParent").focus();
                                                return false;
                                            }
                                            if (confirm("Apakah anda yakin akan mengganti Qty Parent dari WO Mapping ini?") == true) {
                                                var Location = $("#FieldLocation2").val().trim();
                                                var WOMappingID = $("#FieldWOID2").val().trim();
                                                var ClosedTime = $("#FieldClosedTime2").val().trim();
                                                var NewQtyParent = $("#FieldNewQtyParent").val().trim().toUpperCase();
                                                var TemporaryBtn = $("#BtnUpdateQtyParent").data("temp");
                                                var formdata = new FormData();
                                                formdata.append('ValLocation', Location);
                                                formdata.append('ValWOMappingID', WOMappingID);
                                                formdata.append('ValClosedTime', ClosedTime);
                                                formdata.append('ValNewQtyParent', NewQtyParent);
                                                formdata.append('ValTemporaryBtn', TemporaryBtn);
                                                $.ajax({
                                                    url: 'project/WOMapping/src/srcUpdateQtyParentWOMapping.php',
                                                    dataType: 'text',
                                                    cache: false,
                                                    contentType: false,
                                                    processData: false,
                                                    data: formdata,
                                                    type: 'post',
                                                    beforeSend: function () {
                                                        $("#BtnUpdateQtyParent").attr("disabled", true);
                                                        $("#BtnUpdateQtyQuote").attr("disabled", true);
                                                        $("#InfoUpdateQtyPQ").html("");
                                                        $("#InfoUpdateQtyPQ").append('<div class="col-sm-12 pt-3 d-flex justify-content-center" id="ContentLoading6"><div class="spinner-border text-secondary" role="status"><span class="visually-hidden" id="LoadingViewData">Loading...</span ></div></div>');
                                                    },
                                                    success: function (xaxa) {
                                                        $("#ContentLoading6").remove();
                                                        $("#InfoUpdateQtyPQ").html("");
                                                        var Results = xaxa;
                                                        if (Results == "0") {
                                                            $("#InfoUpdateQtyPQ").append('<div class="alert alert-danger alert-dismissible" role="alert"><strong>Error!</strong><br>Data ada yang gagal diupdate! Mohon dicoba lagi.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                                                            $("#BtnUpdateQtyParent").attr("disabled", false);
                                                            $("#BtnUpdateQtyQuote").attr("disabled", false);
                                                        }
                                                        if (Results == "1") {
                                                            $("#TableWOMapping tr[data-idrows='" + TemporaryBtn + "']").find("td:eq(8)").text(NewQtyParent);
                                                            $("#BackFirst").click();
                                                        }
                                                    },
                                                    error: function () {
                                                        alert("Request cannot proceed!");
                                                        $("#ContentLoading6").remove();
                                                        $("#InfoUpdateQtyPQ").html("");
                                                        $("#BtnUpdateQtyParent").attr("disabled", false);
                                                        $("#BtnUpdateQtyQuote").attr("disabled", false);
                                                    }
                                                });
                                            }
                                        });
                                    },
                                    error: function () {
                                        alert("Request cannot proceed!");
                                        $("#ContentLoading5").remove();
                                        $("#ContentModalUpdateDataSelected").html("");
                                    }
                                });
                            });
                            $("#ActRecalculateQtyQuote").click(function () {
                                $("#TitleModalUpdateDataSelected").text("Recalculate Qty Quote");
                                var formdata = new FormData();
                                formdata.append('ValClosedTime', ClosedTime);
                                formdata.append('ValWOMapping_ID', WOMapping_ID);
                                formdata.append('ValLocation', Location);
                                $.ajax({
                                    url: 'project/WOMapping/ContentRecalculateQtyQuoteManageWOMapping.php',
                                    dataType: 'text',
                                    cache: false,
                                    contentType: false,
                                    processData: false,
                                    data: formdata,
                                    type: 'post',
                                    beforeSend: function () {
                                        $("#ContentModalUpdateDataSelected").html("");
                                        $("#ContentModalUpdateDataSelected").append('<div class="col-sm-12 pt-3 d-flex justify-content-center" id="ContentLoading5"><div class="spinner-border text-secondary" role="status"><span class="visually-hidden" id="LoadingViewData">Loading...</span ></div></div>');
                                    },
                                    success: function (xaxa) {
                                        $("#ContentLoading5").remove();
                                        $("#ContentModalUpdateDataSelected").html("");
                                        $("#ContentModalUpdateDataSelected").html(xaxa);
                                        $("#ContentModalUpdateDataSelected").fadeIn("fast");

                                        $("#BtnRecalculateQty").click(function () {
                                            if(confirm("Apakah anda yakin akan melakukan Recalculate Qty Quote?") == true) {
                                                var formdata = new FormData();
                                                formdata.append('ValDataCheck', "TRUE");
                                                $.ajax({
                                                    url: 'project/WOMapping/ContentListQuoteRecalculateManageWOMapping.php',
                                                    dataType: 'text',
                                                    cache: false,
                                                    contentType: false,
                                                    processData: false,
                                                    data: formdata,
                                                    type: 'post',
                                                    beforeSend: function () {
                                                        $("#ContentInfoRecalculate").html("");
                                                        $("#BtnRecalculateQty").attr('disabled', true);
                                                        $("#BackFirst").attr('disabled', true);
                                                        $("#CacheTemporary").html("");
                                                    },
                                                    success: function (xaxa) {
                                                        $("#ContentInfoRecalculate").append('<div class="progress"><div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 0%">0 %</div></div>');
                                                        var ListQuote = xaxa;
                                                        var obj = jQuery.parseJSON(ListQuote);
                                                        var numloop = 0;
                                                        $.each(obj, function (key, value) {
                                                            numloop++;
                                                            setTimeout(function () { 
                                                                COUNT_RECAL(value.Quote, value.ClosedTime, value.ExpenseAllocation, value.TotalQty, value.Location, value.Percentage, value.Percentage2, value.No,obj.length);
                                                            }, 10 * numloop);
                                                        });
                                                    },
                                                    error: function () {
                                                        alert("Request cannot proceed!");
                                                        $("#ContentInfoRecalculate").html("");
                                                        $("#CacheTemporary").html("");
                                                        $("#BtnRecalculateQty").attr('disabled', false);
                                                        $("#BackFirst").attr('disabled', false);
                                                    }
                                                });
                                            }
                                        });
                                    },
                                    error: function () {
                                        alert("Request cannot proceed!");
                                        $("#ContentLoading5").remove();
                                        $("#ContentModalUpdateDataSelected").html("");
                                    }
                                });
                            });
                            $("#ActUpdatePMDM").click(function () {
                                $("#TitleModalUpdateDataSelected").text("Update PM / CO PM / DM");
                                var formdata = new FormData();
                                formdata.append('ValClosedTime', ClosedTime);
                                formdata.append('ValWOMapping_ID', WOMapping_ID);
                                formdata.append('ValLocation', Location);
                                $.ajax({
                                    url: 'project/WOMapping/ContentUpdatePMCOPMDMManageWOMapping.php',
                                    dataType: 'text',
                                    cache: false,
                                    contentType: false,
                                    processData: false,
                                    data: formdata,
                                    type: 'post',
                                    beforeSend: function () {
                                        $("#ContentModalUpdateDataSelected").html("");
                                        $("#ContentModalUpdateDataSelected").append('<div class="col-sm-12 pt-3 d-flex justify-content-center" id="ContentLoading5"><div class="spinner-border text-secondary" role="status"><span class="visually-hidden" id="LoadingViewData">Loading...</span ></div></div>');
                                    },
                                    success: function (xaxa) {
                                        $("#ContentLoading5").remove();
                                        $("#ContentModalUpdateDataSelected").html("");
                                        $("#ContentModalUpdateDataSelected").html(xaxa);
                                        $("#ContentModalUpdateDataSelected").fadeIn("fast");
                                        $("#BtnUpdatePM").click(function () {
                                            if (confirm("Apakah anda yakin akan mengganti PM dari WO Mapping ini?") == true) {
                                                var Location = $("#FieldLocation2").val().trim();
                                                var WOMappingID = $("#FieldWOID2").val().trim();
                                                var ClosedTime = $("#FieldClosedTime2").val().trim();
                                                var NewPM = $("#FieldNewPM option:selected").val().trim();
                                                var TemporaryBtn = $("#BtnUpdatePM").data("temp");
                                                var formdata = new FormData();
                                                formdata.append('ValLocation', Location);
                                                formdata.append('ValWOMappingID', WOMappingID);
                                                formdata.append('ValClosedTime', ClosedTime);
                                                formdata.append('ValNewPM', NewPM);
                                                formdata.append('ValTemporaryBtn', TemporaryBtn);
                                                $.ajax({
                                                    url: 'project/WOMapping/src/srcUpdatePMWOMapping.php',
                                                    dataType: 'text',
                                                    cache: false,
                                                    contentType: false,
                                                    processData: false,
                                                    data: formdata,
                                                    type: 'post',
                                                    beforeSend: function () {
                                                        $("#BtnUpdatePM").attr("disabled", true);
                                                        $("#BtnUpdateCOPM").attr("disabled", true);
                                                        $("#BtnUpdateDM").attr("disabled", true);
                                                        $("#InfoUpdatePMDM").html("");
                                                        $("#InfoUpdatePMDM").append('<div class="col-sm-12 pt-3 d-flex justify-content-center" id="ContentLoading6"><div class="spinner-border text-secondary" role="status"><span class="visually-hidden" id="LoadingViewData">Loading...</span ></div></div>');
                                                    },
                                                    success: function (xaxa) {
                                                        $("#ContentLoading6").remove();
                                                        $("#InfoUpdatePMDM").html("");
                                                        var Results = xaxa;
                                                        if (Results == "0") {
                                                            $("#InfoUpdatePMDM").append('<div class="alert alert-danger alert-dismissible" role="alert"><strong>Error!</strong><br>Data ada yang gagal diupdate! Mohon dicoba lagi.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                                                            $("#BtnUpdatePM").attr("disabled", false);
                                                            $("#BtnUpdateCOPM").attr("disabled", false);
                                                            $("#BtnUpdateDM").attr("disabled", false);
                                                        }
                                                        if (Results == "1") {
                                                            $("#TableWOMapping tr[data-idrows='" + TemporaryBtn + "']").find("td:eq(12)").text(NewPM);
                                                            $("#BackFirst").click();
                                                        }
                                                    },
                                                    error: function () {
                                                        alert("Request cannot proceed!");
                                                        $("#ContentLoading6").remove();
                                                        $("#InfoUpdatePMDM").html("");
                                                        $("#BtnUpdatePM").attr("disabled", false);
                                                        $("#BtnUpdateCOPM").attr("disabled", false);
                                                        $("#BtnUpdateDM").attr("disabled", false);
                                                    }
                                                });
                                            }
                                        });
                                        $("#BtnUpdateCOPM").click(function () {
                                            if (confirm("Apakah anda yakin akan mengganti CO PM dari WO Mapping ini?") == true) {
                                                var Location = $("#FieldLocation2").val().trim();
                                                var WOMappingID = $("#FieldWOID2").val().trim();
                                                var ClosedTime = $("#FieldClosedTime2").val().trim();
                                                var NewCOPM = $("#FieldNewCOPM option:selected").val().trim();
                                                var TemporaryBtn = $("#BtnUpdateCOPM").data("temp");
                                                var formdata = new FormData();
                                                formdata.append('ValLocation', Location);
                                                formdata.append('ValWOMappingID', WOMappingID);
                                                formdata.append('ValClosedTime', ClosedTime);
                                                formdata.append('ValNewCOPM', NewCOPM);
                                                formdata.append('ValTemporaryBtn', TemporaryBtn);
                                                $.ajax({
                                                    url: 'project/WOMapping/src/srcUpdateCOPMWOMapping.php',
                                                    dataType: 'text',
                                                    cache: false,
                                                    contentType: false,
                                                    processData: false,
                                                    data: formdata,
                                                    type: 'post',
                                                    beforeSend: function () {
                                                        $("#BtnUpdatePM").attr("disabled", true);
                                                        $("#BtnUpdateCOPM").attr("disabled", true);
                                                        $("#BtnUpdateDM").attr("disabled", true);
                                                        $("#InfoUpdatePMDM").html("");
                                                        $("#InfoUpdatePMDM").append('<div class="col-sm-12 pt-3 d-flex justify-content-center" id="ContentLoading6"><div class="spinner-border text-secondary" role="status"><span class="visually-hidden" id="LoadingViewData">Loading...</span ></div></div>');
                                                    },
                                                    success: function (xaxa) {
                                                        $("#ContentLoading6").remove();
                                                        $("#InfoUpdatePMDM").html("");
                                                        var Results = xaxa;
                                                        if (Results == "0") {
                                                            $("#InfoUpdatePMDM").append('<div class="alert alert-danger alert-dismissible" role="alert"><strong>Error!</strong><br>Data ada yang gagal diupdate! Mohon dicoba lagi.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                                                            $("#BtnUpdatePM").attr("disabled", false);
                                                            $("#BtnUpdateCOPM").attr("disabled", false);
                                                            $("#BtnUpdateDM").attr("disabled", false);
                                                        }
                                                        if (Results == "1") {
                                                            $("#TableWOMapping tr[data-idrows='" + TemporaryBtn + "']").find("td:eq(13)").text(NewCOPM);
                                                            $("#BackFirst").click();
                                                        }
                                                    },
                                                    error: function () {
                                                        alert("Request cannot proceed!");
                                                        $("#ContentLoading6").remove();
                                                        $("#InfoUpdatePMDM").html("");
                                                        $("#BtnUpdatePM").attr("disabled", false);
                                                        $("#BtnUpdateCOPM").attr("disabled", false);
                                                        $("#BtnUpdateDM").attr("disabled", false);
                                                    }
                                                });
                                            }
                                        });
                                        $("#BtnUpdateDM").click(function () {
                                            if (confirm("Apakah anda yakin akan mengganti DM dari WO Mapping ini?") == true) {
                                                var Location = $("#FieldLocation2").val().trim();
                                                var WOMappingID = $("#FieldWOID2").val().trim();
                                                var ClosedTime = $("#FieldClosedTime2").val().trim();
                                                var NewDM = $("#FieldNewDM option:selected").val().trim();
                                                var TemporaryBtn = $("#BtnUpdateDM").data("temp");
                                                var formdata = new FormData();
                                                formdata.append('ValLocation', Location);
                                                formdata.append('ValWOMappingID', WOMappingID);
                                                formdata.append('ValClosedTime', ClosedTime);
                                                formdata.append('ValNewDM', NewDM);
                                                formdata.append('ValTemporaryBtn', TemporaryBtn);
                                                $.ajax({
                                                    url: 'project/WOMapping/src/srcUpdateDMWOMapping.php',
                                                    dataType: 'text',
                                                    cache: false,
                                                    contentType: false,
                                                    processData: false,
                                                    data: formdata,
                                                    type: 'post',
                                                    beforeSend: function () {
                                                        $("#BtnUpdatePM").attr("disabled", true);
                                                        $("#BtnUpdateCOPM").attr("disabled", true);
                                                        $("#BtnUpdateDM").attr("disabled", true);
                                                        $("#InfoUpdatePMDM").html("");
                                                        $("#InfoUpdatePMDM").append('<div class="col-sm-12 pt-3 d-flex justify-content-center" id="ContentLoading6"><div class="spinner-border text-secondary" role="status"><span class="visually-hidden" id="LoadingViewData">Loading...</span ></div></div>');
                                                    },
                                                    success: function (xaxa) {
                                                        $("#ContentLoading6").remove();
                                                        $("#InfoUpdatePMDM").html("");
                                                        var Results = xaxa;
                                                        if (Results == "0") {
                                                            $("#InfoUpdatePMDM").append('<div class="alert alert-danger alert-dismissible" role="alert"><strong>Error!</strong><br>Data ada yang gagal diupdate! Mohon dicoba lagi.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                                                            $("#BtnUpdatePM").attr("disabled", false);
                                                            $("#BtnUpdateCOPM").attr("disabled", false);
                                                            $("#BtnUpdateDM").attr("disabled", false);
                                                        }
                                                        if (Results == "1") {
                                                            $("#TableWOMapping tr[data-idrows='" + TemporaryBtn + "']").find("td:eq(14)").text(NewDM);
                                                            $("#BackFirst").click();
                                                        }
                                                    },
                                                    error: function () {
                                                        alert("Request cannot proceed!");
                                                        $("#ContentLoading6").remove();
                                                        $("#InfoUpdatePMDM").html("");
                                                        $("#BtnUpdatePM").attr("disabled", false);
                                                        $("#BtnUpdateCOPM").attr("disabled", false);
                                                        $("#BtnUpdateDM").attr("disabled", false);
                                                    }
                                                });
                                            }
                                        });
                                    },
                                    error: function () {
                                        alert("Request cannot proceed!");
                                        $("#ContentLoading5").remove();
                                        $("#ContentModalUpdateDataSelected").html("");
                                    }
                                });
                            });
                            $("#ActChangeQuoteCategory").click(function () {
                                $("#TitleModalUpdateDataSelected").text("Change Quote Category");
                                var formdata = new FormData();
                                formdata.append('ValClosedTime', ClosedTime);
                                formdata.append('ValWOMapping_ID', WOMapping_ID);
                                formdata.append('ValLocation', Location);
                                $.ajax({
                                    url: 'project/WOMapping/ContentChangeQuoteCategoryManageWOMapping.php',
                                    dataType: 'text',
                                    cache: false,
                                    contentType: false,
                                    processData: false,
                                    data: formdata,
                                    type: 'post',
                                    beforeSend: function () {
                                        $("#ContentModalUpdateDataSelected").html("");
                                        $("#ContentModalUpdateDataSelected").append('<div class="col-sm-12 pt-3 d-flex justify-content-center" id="ContentLoading5"><div class="spinner-border text-secondary" role="status"><span class="visually-hidden" id="LoadingViewData">Loading...</span ></div></div>');
                                    },
                                    success: function (xaxa) {
                                        $("#ContentLoading5").remove();
                                        $("#ContentModalUpdateDataSelected").html("");
                                        $("#ContentModalUpdateDataSelected").html(xaxa);
                                        $("#ContentModalUpdateDataSelected").fadeIn("fast");
                                        $("#BtnUpdateQuoteCategory").click(function () {
                                            if (confirm("Apakah anda yakin akan mengganti Quote Category dari WO Mapping ini?") == true) {
                                                var Location = $("#FieldLocation2").val().trim();
                                                var WOMappingID = $("#FieldWOID2").val().trim();
                                                var ClosedTime = $("#FieldClosedTime2").val().trim();
                                                var NewQuoteCategory = $("#FieldNewQuoteCategory option:selected").val().trim();
                                                var TemporaryBtn = $("#BtnUpdateQuoteCategory").data("temp");
                                                var formdata = new FormData();
                                                formdata.append('ValLocation', Location);
                                                formdata.append('ValWOMappingID', WOMappingID);
                                                formdata.append('ValClosedTime', ClosedTime);
                                                formdata.append('ValNewQuoteCategory', NewQuoteCategory);
                                                formdata.append('ValTemporaryBtn', TemporaryBtn);
                                                $.ajax({
                                                    url: 'project/WOMapping/src/srcUpdateQuoteCategoryWOMapping.php',
                                                    dataType: 'text',
                                                    cache: false,
                                                    contentType: false,
                                                    processData: false,
                                                    data: formdata,
                                                    type: 'post',
                                                    beforeSend: function () {
                                                        $("#BtnUpdateQuoteCategory").attr("disabled", true);
                                                        $("#InfoUpdateQuoteCategory").html("");
                                                        $("#InfoUpdateQuoteCategory").append('<div class="col-sm-12 pt-3 d-flex justify-content-center" id="ContentLoading6"><div class="spinner-border text-secondary" role="status"><span class="visually-hidden" id="LoadingViewData">Loading...</span ></div></div>');
                                                    },
                                                    success: function (xaxa) {
                                                        $("#ContentLoading6").remove();
                                                        var Results = xaxa;
                                                        if (Results == "0") {
                                                            $("#InfoUpdateQuoteCategory").append('<div class="alert alert-danger alert-dismissible" role="alert"><strong>Error!</strong><br>Data ada yang gagal diupdate! Mohon dicoba lagi.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                                                            $("#BtnUpdateQuoteCategory").attr("disabled", false);
                                                        }
                                                        if (Results == "1") {
                                                            $("#TableWOMapping tr[data-idrows='" + TemporaryBtn + "']").find("td:eq(23)").text(NewQuoteCategory);
                                                            $("#BackFirst").click();
                                                        }
                                                    },
                                                    error: function () {
                                                        alert("Request cannot proceed!");
                                                        $("#ContentLoading6").remove();
                                                        $("#InfoUpdateQuoteCategory").html("");
                                                        $("#BtnUpdateQuoteCategory").attr("disabled", false);
                                                    }
                                                });
                                            }
                                        });
                                    },
                                    error: function () {
                                        alert("Request cannot proceed!");
                                        $("#ContentLoading5").remove();
                                        $("#ContentModalUpdateDataSelected").html("");
                                    }
                                });
                            });
                            $("#ActChangeExpense").click(function () {
                                $("#TitleModalUpdateDataSelected").text("Change Expense Allocation");
                                var formdata = new FormData();
                                formdata.append('ValClosedTime', ClosedTime);
                                formdata.append('ValWOMapping_ID', WOMapping_ID);
                                formdata.append('ValLocation', Location);
                                $.ajax({
                                    url: 'project/WOMapping/ContentChangeExpenseAllocationManageWOMapping.php',
                                    dataType: 'text',
                                    cache: false,
                                    contentType: false,
                                    processData: false,
                                    data: formdata,
                                    type: 'post',
                                    beforeSend: function () {
                                        $("#ContentModalUpdateDataSelected").html("");
                                        $("#ContentModalUpdateDataSelected").append('<div class="col-sm-12 pt-3 d-flex justify-content-center" id="ContentLoading5"><div class="spinner-border text-secondary" role="status"><span class="visually-hidden" id="LoadingViewData">Loading...</span ></div></div>');
                                    },
                                    success: function (xaxa) {
                                        $("#ContentLoading5").remove();
                                        $("#ContentModalUpdateDataSelected").html("");
                                        $("#ContentModalUpdateDataSelected").html(xaxa);
                                        $("#ContentModalUpdateDataSelected").fadeIn("fast");
                                        $("#BtnChangeExpenseAllocation").click(function () {
                                            if (confirm("Apakah anda yakin akan mengganti Expense Allocation dari WO Mapping ini?") == true) {
                                                var Location = $("#FieldLocation2").val().trim();
                                                var WOMappingID = $("#FieldWOID2").val().trim();
                                                var ClosedTime = $("#FieldClosedTime2").val().trim();
                                                var ValNewExpense = $("#FieldNewExpenseAllocation option:selected").val().trim();
                                                var TemporaryBtn = $("#BtnChangeExpenseAllocation").data("temp");
                                                var formdata = new FormData();
                                                formdata.append('ValLocation', Location);
                                                formdata.append('ValWOMappingID', WOMappingID);
                                                formdata.append('ValClosedTime', ClosedTime);
                                                formdata.append('ValNewExpense', ValNewExpense);
                                                formdata.append('ValTemporaryBtn', TemporaryBtn);
                                                $.ajax({
                                                    url: 'project/WOMapping/src/srcUpdateExpenseWOMapping.php',
                                                    dataType: 'text',
                                                    cache: false,
                                                    contentType: false,
                                                    processData: false,
                                                    data: formdata,
                                                    type: 'post',
                                                    beforeSend: function () {
                                                        $("#BtnChangeExpenseAllocation").attr("disabled", true);
                                                        $("#InfoChangeExpenseAllocation").html("");
                                                        $("#InfoChangeExpenseAllocation").append('<div class="col-sm-12 pt-3 d-flex justify-content-center" id="ContentLoading6"><div class="spinner-border text-secondary" role="status"><span class="visually-hidden" id="LoadingViewData">Loading...</span ></div></div>');
                                                    },
                                                    success: function (xaxa) {
                                                        $("#ContentLoading6").remove();
                                                        $("#InfoChangeExpenseAllocation").html("");
                                                        console.log(xaxa);
                                                        var Results = xaxa;
                                                        if (Results == "0") {
                                                            $("#InfoChangeExpenseAllocation").append('<div class="alert alert-danger alert-dismissible" role="alert"><strong>Error!</strong><br>Data ada yang gagal diupdate! Mohon dicoba lagi.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                                                            $("#BtnChangeExpenseAllocation").attr("disabled", false);
                                                        }
                                                        if (Results == "1") {
                                                            $("#TableWOMapping tr[data-idrows='" + TemporaryBtn + "']").find("td:eq(4)").text(ValNewExpense);
                                                            var TempWOC = $("#TableWOMapping tr[data-idrows='" + TemporaryBtn + "']").find("td:eq(6)").text();
                                                            $("#TableWOMapping tr[data-idrows='" + TemporaryBtn + "']").find("td:eq(26)").text(ValNewExpense + TempWOC);
                                                            $("#BackFirst").click();
                                                        }
                                                    },
                                                    error: function () {
                                                        alert("Request cannot proceed!");
                                                        $("#ContentLoading6").remove();
                                                        $("#InfoChangeExpenseAllocation").html("");
                                                        $("#BtnChangeExpenseAllocation").attr("disabled", false);
                                                    }
                                                });
                                            }
                                        });
                                    },
                                    error: function () {
                                        alert("Request cannot proceed!");
                                        $("#ContentLoading5").remove();
                                        $("#ContentModalUpdateDataSelected").html("");
                                    }
                                });
                            });
                        },
                        error: function () {
                            alert("Request cannot proceed!");
                            $("#ContentLoading2").remove();
                            $('#ContentModalUpdateWOMapping').html("");
                        }
                    });
                });
                $("#TableWOMapping").on("click", "tbody tr td .UpdateCT", function () {
                    var ClosedTime = $(this).closest("tr").find("td:eq(2)").text();
                    var WOMapping_ID = $(this).closest("tr").find("td:eq(5)").text();
                    var Location = $(this).closest("tr").find("td:eq(27)").text();
                    var DataRows = $(this).closest("tr").data("idrows");
                    var formdata = new FormData();
                    formdata.append('ValClosedTime', ClosedTime);
                    formdata.append('ValWOMapping_ID', WOMapping_ID);
                    formdata.append('ValLocation', Location);
                    formdata.append('ValDataRows', DataRows);
                    $.ajax({
                        url: 'project/WOMapping/ContentManageModuleUpdateWOMappingCT.php',
                        dataType: 'text',
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formdata,
                        type: 'post',
                        beforeSend: function () {
                            $("#ModalUpdateCT").modal("show");
                            $("#ContentModalUpdateClosedTime").html("");
                            $("#ContentModalUpdateClosedTime").append('<div class="col-sm-12 pt-3 d-flex justify-content-center" id="ContentLoading3"><div class="spinner-border text-secondary" role="status"><span class="visually-hidden" id="LoadingViewData">Loading...</span ></div></div>');
                        },
                        success: function (xaxa) {
                            $("#ContentLoading3").remove();
                            $("#ContentModalUpdateClosedTime").html("");
                            $("#ContentModalUpdateClosedTime").html(xaxa);
                            $("#ContentModalUpdateClosedTime").fadeIn("fast");
                            $("#FieldQtyClosed").focus();
                            $("#BtnCloseWO").click(function () {
                                if($("#FieldQtyClosed").val() == "")
                                {
                                    $("#FieldQtyClosed").focus();
                                    return false;
                                }
                                if (confirm("Apakah anda yakin akan Close WO dari WO Mapping ini?") == true) {
                                    var Location = $("#FieldLocation2").val().trim();
                                    var WOMappingID = $("#FieldWOID2").val().trim();
                                    var ClosedTime = $("#FieldClosedTime2").val().trim();
                                    var QtyClosed = $("#FieldQtyClosed").val().trim();
                                    var formdata = new FormData();
                                    formdata.append('ValLocation', Location);
                                    formdata.append('ValWOMappingID', WOMappingID);
                                    formdata.append('ValClosedTime', ClosedTime);
                                    formdata.append('ValQtyClosed', QtyClosed);
                                    $.ajax({
                                        url: 'project/WOMapping/src/srcUpdateClosedTimeWOMapping.php',
                                        dataType: 'text',
                                        cache: false,
                                        contentType: false,
                                        processData: false,
                                        data: formdata,
                                        type: 'post',
                                        beforeSend: function () {
                                            $("#BtnCloseWO").attr("disabled", true);
                                            $("#InfoUpdateCT").html("");
                                            $("#InfoUpdateCT").append('<div class="col-sm-12 pt-3 d-flex justify-content-center" id="ContentLoading8"><div class="spinner-border text-secondary" role="status"><span class="visually-hidden" id="LoadingViewData">Loading...</span ></div></div>');
                                        },
                                        success: function (xaxa) {
                                            $("#ContentLoading8").remove();
                                            $("#InfoUpdateCT").html("");
                                            var Results = xaxa;
                                            var ArrResults = Results.split("#");
                                            var ResOpt = ArrResults[0];                                        
                                            if (ResOpt == "0") {
                                                $("#InfoChangeExpenseAllocation").append('<div class="alert alert-danger alert-dismissible" role="alert"><strong>Error!</strong><br>Data gagal diupdate! Mohon dicoba lagi.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                                                $("#BtnCloseWO").attr("disabled", false);
                                            }
                                            if (ResOpt == "1") {
                                                $("#TableWOMapping tr[data-idrows='" + DataRows + "']").find("td:eq(2)").text(ArrResults[1]);
                                                $("#TableWOMapping tr[data-idrows='" + DataRows + "']").find("td:eq(22)").text(ArrResults[2]);
                                                $("#TableWOMapping tr[data-idrows='" + DataRows + "']").find("td:eq(8)").text(ArrResults[3]);
                                                $("#BtnCloseWO").attr("disabled", false);
                                                $("#ModalUpdateCT").modal("hide");
                                            }
                                        },
                                        error: function () {
                                            alert("Request cannot proceed!");
                                            $("#ContentLoading8").remove();
                                            $('#InfoUpdateCT').html("");
                                            $("#BtnCloseWO").attr("disabled", false);
                                        }
                                    });
                                }
                            });
                            $("#BtnReOpenWO").click(function () {
                                if (confirm("Apakah anda yakin akan Reopen WO dari WO Mapping ini?") == true) {
                                    var Location = $("#FieldLocation2").val().trim();
                                    var WOMappingID = $("#FieldWOID2").val().trim();
                                    var ClosedTime = $("#FieldClosedTime2").val().trim();
                                    var formdata = new FormData();
                                    formdata.append('ValLocation', Location);
                                    formdata.append('ValWOMappingID', WOMappingID);
                                    formdata.append('ValClosedTime', ClosedTime);
                                    $.ajax({
                                        url: 'project/WOMapping/src/srcUpdateClosedTimeWOMapping.php',
                                        dataType: 'text',
                                        cache: false,
                                        contentType: false,
                                        processData: false,
                                        data: formdata,
                                        type: 'post',
                                        beforeSend: function () {
                                            $("#BtnReOpenWO").attr("disabled", true);
                                            $("#InfoUpdateCT").html("");
                                            $("#InfoUpdateCT").append('<div class="col-sm-12 pt-3 d-flex justify-content-center" id="ContentLoading8"><div class="spinner-border text-secondary" role="status"><span class="visually-hidden" id="LoadingViewData">Loading...</span ></div></div>');
                                        },
                                        success: function (xaxa) {
                                            $("#ContentLoading8").remove();
                                            $("#InfoUpdateCT").html("");
                                            var Results = xaxa;
                                            if (Results == "0") {
                                                $("#InfoChangeExpenseAllocation").append('<div class="alert alert-danger alert-dismissible" role="alert"><strong>Error!</strong><br>Data gagal diupdate! Mohon dicoba lagi.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                                                $("#BtnReOpenWO").attr("disabled", false);
                                            }
                                            if (Results == "1") {
                                                $("#TableWOMapping tr[data-idrows='" + DataRows + "']").find("td:eq(2)").text("OPEN");
                                                $("#TableWOMapping tr[data-idrows='" + DataRows + "']").find("td:eq(22)").text("");
                                                $("#BtnReOpenWO").attr("disabled", false);
                                                $("#ModalUpdateCT").modal("hide");
                                            }
                                        },
                                        error: function () {
                                            alert("Request cannot proceed!");
                                            $("#ContentLoading8").remove();
                                            $('#InfoUpdateCT').html("");
                                            $("#BtnReOpenWO").attr("disabled", false);
                                        }
                                    }); 
                                }
                            });
                        },
                        error: function () {
                            alert("Request cannot proceed!");
                            $("#ContentLoading3").remove();
                            $('#ContentModalUpdateClosedTime').html("");
                        }
                    });
                });
                $("#TableWOMapping").on("click", "tbody tr td .DeleteWO", function () {
                    var ClosedTime = $(this).closest("tr").find("td:eq(2)").text();
                    var WOMapping_ID = $(this).closest("tr").find("td:eq(5)").text();
                    var Location = $(this).closest("tr").find("td:eq(27)").text();
                    var DataRows = $(this).closest("tr").data("idrows");
                    var formdata = new FormData();
                    formdata.append('ValClosedTime', ClosedTime);
                    formdata.append('ValWOMapping_ID', WOMapping_ID);
                    formdata.append('ValLocation', Location);
                    formdata.append('ValDataRows', DataRows);
                    $.ajax({
                        url: 'project/WOMapping/ContentManageModuleUpdateWODeleteWO.php',
                        dataType: 'text',
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formdata,
                        type: 'post',
                        beforeSend: function () {
                            $("#ModalDeleteWO").modal("show");
                            $("#ContentModalDeleteWO").html("");
                            $("#ContentModalDeleteWO").append('<div class="col-sm-12 pt-3 d-flex justify-content-center" id="ContentLoading4"><div class="spinner-border text-secondary" role="status"><span class="visually-hidden" id="LoadingViewData">Loading...</span ></div></div>');
                        },
                        success: function (xaxa) {
                            $("#ContentLoading4").remove();
                            $("#ContentModalDeleteWO").html("");
                            $("#ContentModalDeleteWO").html(xaxa);
                            $("#ContentModalDeleteWO").fadeIn("fast");
                            $("#BtnDeleteWOModal").click(function(){
                                if (confirm("Apakah anda yakin akan menghapus data ini?") == true) {
                                    var formdata = new FormData();
                                    formdata.append('ValClosedTime', ClosedTime);
                                    formdata.append('ValWOMappingID', WOMapping_ID);
                                    formdata.append('ValLocation', Location);
                                    formdata.append('ValDataRows', DataRows);
                                    $.ajax({
                                        url: 'project/WOMapping/src/srcDeleteWOMapping.php',
                                        dataType: 'text',
                                        cache: false,
                                        contentType: false,
                                        processData: false,
                                        data: formdata,
                                        type: 'post',
                                        beforeSend: function () {
                                            $("#BtnDeleteWOModal").attr("disabled", true);
                                            $("#InfoDeleteWO").html("");
                                            $("#InfoDeleteWO").append('<div class="col-sm-12 pt-3 d-flex justify-content-center" id="ContentLoading7"><div class="spinner-border text-secondary" role="status"><span class="visually-hidden" id="LoadingViewData">Loading...</span ></div></div>');
                                        },
                                        success: function (xaxa) {
                                            $("#ContentLoading7").remove();
                                            var Results = xaxa;
                                            if (Results == "0") {
                                                $("#InfoDeleteWO").append('<div class="alert alert-danger alert-dismissible" role="alert"><strong>Error!</strong><br>Data gagal dihapus! Mohon dicoba lagi.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                                                $("#BtnDeleteWOModal").attr("disabled", false);
                                            }
                                            if (Results == "1") {
                                                var XX = $("#TableWOMapping tbody tr[data-idrows='" + DataRows + "']");
                                                $("#TableWOMapping").DataTable().row(XX).remove().draw();
                                                $("#ModalDeleteWO").modal("hide");
                                            }
                                        },
                                        error: function () {
                                            alert("Request cannot proceed!");
                                            $("#ContentLoading7").remove();
                                            $("#InfoDeleteWO").html("");
                                            $("#BtnDeleteWOModal").attr("disabled", false);
                                        }
                                    });
                                }
                            });
                        },
                        error: function () {
                            alert("Request cannot proceed!");
                            $("#ContentLoading4").remove();
                            $('#ContentModalDeleteWO').html("");
                        }
                    });
                });
            },
            error: function () {
                alert("Request cannot proceed!");
                $("#ContentLoading1").remove();
                $("#BtnViewWO").attr('disabled', false);
                $('#ContentResult').html("");
                $("#ContentLoading").remove();
                $("#BtnViewWO").blur();
            }
        });
    });
});
function COUNT_RECAL(InputQuote, InputClosedTime, InputExpenseAllocation, InputTotalQty, InputLocation, InputPercentage, InputPercentage2, InputNo, InputTotal)
{   
    var formdata = new FormData();
    formdata.append('ValQuote', InputQuote);
    formdata.append('ValClosedTime', InputClosedTime);
    formdata.append('ValExpenseAllocation', InputExpenseAllocation);
    formdata.append('ValTotalQty', InputTotalQty);
    formdata.append('ValLocation', InputLocation);
    formdata.append('ValNo', InputNo);
    formdata.append('ValTotalRow', InputTotal);
    $.ajax({
        url: 'project/WOMapping/src/srcRecalculateQtyQuoteWOMapping.php',
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        data: formdata,
        type: 'post',
        beforeSend: function () {

        },
        success: function (xaxa) {
            var Result = xaxa;
            if (Result == "1")
            {
                $(".progress-bar").attr("aria-valuenow", InputPercentage2);
                $(".progress-bar").css("width", InputPercentage + "%");
                $(".progress-bar").html(InputPercentage2 + "%");
                if (InputNo == InputTotal) {
                    $("#BtnRecalculateQty").attr('disabled', false);
                    $("#BackFirst").attr('disabled', false);
                    $("#BackFirst").click();
                    alert("Proses recalculate telah selesai!");
                }
            }
            if (Result == "0")
            {
                console.log("Proses Recalculate gagal!");
                return false;               
            }
        },
        error: function () {
            console.log("Request cannot proceed!");
            return false;               
        }
    });
}
</script>