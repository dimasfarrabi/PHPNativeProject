<?php
require_once("../webtrax/project/CostTracking/Modules/ModuleCostTracking.php");
require_once("project/Report/Modules/ModuleReport.php");
date_default_timezone_set("Asia/Jakarta");
$YearNow = date("Y");
$DateNow = date("m/d/Y");
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js" integrity="sha512-AIOTidJAcHBH2G/oZv9viEGXRqDNmfdPVPYOYKGy3fti0xIplnlgMHUGfuNRzC6FkzIo0iIxgFnr9RikFxK+sw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" type="text/css" href="lib/datetimepicker-master/jquery.datetimepicker.css"/>
<div class="row">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-detail ">
                <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Report : Material Tracking</li>
            </ol>
        </nav>
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body pt-2">
                        <div class="row">
                            <div class="col-md-12"><h6>Filter Date</h6></div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="controls">
                                        <label for="txtFilterTanggal1" class="form-label fw-bold">Start Date</label>
                                        <div class="input-group input-group-sm">
                                            <input id="txtFilterTanggal1" name="txtFilterTanggal1" type="text" class="date-picker form-control" aria-describedby="txtFilterTanggal1Val" value="<?php echo $DateNow; ?>" readonly />
                                            <label for="txtFilterTanggal1" class="input-group-text" id="txtFilterTanggal1Val"><span class="bi bi-calendar-date text-dark"></span></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="controls">
                                        <label for="txtFilterTanggal2" class="form-label fw-bold">End Date</label>
                                        <div class="input-group input-group-sm">
                                            <input id="txtFilterTanggal2" name="txtFilterTanggal2" type="text" class="date-picker form-control" aria-describedby="txtFilterTanggal2Val" value="<?php echo $DateNow; ?>" readonly /><label for="txtFilterTanggal2" class="input-group-text" id="txtFilterTanggal2Val"><span class="bi bi-calendar-date text-dark"></span></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 pt-3">
                <div class="card">
                    <div class="card-body pt-2">
                        <div class="row">
                            <div class="col-md-12"><h6>Custom Search</h6></div>
                            <div class="col-md-12"><div class="form-check">
                                <input class="form-check-input" type="checkbox" id="DateCheckDefault" checked>
                                <label class="form-check-label" for="DateCheckDefault">
                                Gunakan Tanggal
                                </label></div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="FilterCustom" class="form-label fw-bold">Category</label>
                                    <select class="form-select form-select-sm" id="FilterCustom">
                                        <option>WO Mapping ID</option>
                                        <option>Product</option>
                                        <option>Expense Allocation</option>
                                        <option>Part No</option>
                                        <option>WOP</option>
                                        <option>Location</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="FilterKeywords" class="form-label fw-bold">Keywords</label>
                                    <input type="text" class="form-control form-control-sm" id="FilterKeywords" placeholder="Keywords" value="">
                                </div>
                            </div>                                
                            <div class="col-md-6 d-grid mt-2">
                                <button class="btn btn-sm btn-dark" id="BtnViewDataCustom">View Data</button>
                            </div>
                            <div class="col-md-6 d-grid mt-2">
                                <button class="btn btn-sm btn-dark" id="BtnDownloadMaterial">Download</button>
                            </div> 
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 pt-3">
                <div class="card">
                    <div class="card-body pt-2">
                        <div class="row">
                            <div class="col-md-12"><h6>Filter ClosedTime</h6></div>
                            <div class="col-md-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="UsedOpen">
                                    <label class="form-check-label" for="UsedOpen">Termasuk "OPEN"</label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="FilterSeason" class="form-label fw-bold">Closed Time</label>
                                    <select class="form-select form-select-sm" id="FilterSeason">
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
                            <div class="col-md-6 d-grid mt-2">
                                <button class="btn btn-sm btn-dark" id="ViewSeason">View Data</button>
                            </div>
                            <div class="col-md-6 d-grid mt-2">
                                <button class="btn btn-sm btn-dark" id="BtnDownloadMaterialSeason">Download</button>
                            </div> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-9 pt-2"><div class="row" id="ContentResult"></div></div>
    <div class="col-md-12 mt-4"></div>
</div>
<script>
    
$(document).ready(function () {
    $('#txtFilterTanggal1').datetimepicker({
        lang:'en',
        timepicker:false,
        format:'m/d/Y',
        formatDate:'m/d/Y',
        theme:'dark'
    });
    $('#txtFilterTanggal2').datetimepicker({
        lang:'en',
        timepicker:false,
        format:'m/d/Y',
        formatDate:'m/d/Y',
        theme:'dark'
    });
    $('#TableMaterialTrack').removeAttr('width').DataTable({
        "iDisplayLength": 5,
        "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
        scrollY:        "430px",
        scrollX:        true,
        scrollCollapse: true,
        "autoWidth": true
    });
    $("#BtnDownloadMaterial").click(function(){
        var Used = "";
        if ($('#DateCheckDefault').is(":checked")) {
            Used = $('#DateCheckDefault').val();
        }
        else{ Used = "off";}
        var StartDate = $("#txtFilterTanggal1").val();
        var EndDate = $("#txtFilterTanggal2").val();
        var Category = $("#FilterCustom").val();
        var Half = "";
        var Open = "";
        var Keywords = $("#FilterKeywords").val().trim();
        window.location.href = 'project/Report/src/DownLoadCSVMaterial.php?Used='+Used+'&&StartDate='+StartDate+'&&EndDate='+EndDate+'&&Category='+Category+'&&Half='+Half+'&&Open='+Open+'&&Keywords='+Keywords;
    });
    $("#BtnDownloadMaterialSeason").click(function(){
        var Open = "";
        if ($('#UsedOpen').is(":checked")) {
            Open = $('#UsedOpen').val();
        }
        else{ Open = "off";}
        var Used = "";
        var StartDate = "";
        var EndDate = "";
        var Category = "";
        var Keywords = "";
        var Half = $("#FilterSeason").val();
        window.location.href = 'project/Report/src/DownLoadCSVMaterial.php?Used='+Used+'&&StartDate='+StartDate+'&&EndDate='+EndDate+'&&Category='+Category+'&&Half='+Half+'&&Open='+Open+'&&Keywords='+Keywords;
    });
      
    $("#TableMaterialTrack tbody").css("font-size", "11px");
    $("#BtnViewDataCustom").click(function(){
        var Used = "";
        if ($('#DateCheckDefault').is(":checked")) {
            Used = $('#DateCheckDefault').val();
        }
        else{ Used = "off";}
        var StartDate = $("#txtFilterTanggal1").val();
        var EndDate = $("#txtFilterTanggal2").val();
        var Category = $("#FilterCustom").val();
        var Half = "";
        var Open = "";
        var Keywords = $("#FilterKeywords").val().trim();
        var formdata = new FormData();
        formdata.append('Used', Used);
        formdata.append('Open', Open);
        formdata.append('ValStartDate', StartDate);
        formdata.append('ValEndDate', EndDate);
        formdata.append('ValCategory', Category);
        formdata.append('ValKeywords', Keywords);
        formdata.append('Half', Half);
        $.ajax({
            url: 'project/Report/MaterialTrackingContent.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#BtnViewDataCustom").attr('disabled', true);
                $('#ContentResult').html("");
                $("#ContentResult").before('<div class="col-sm-12 d-flex justify-content-center" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                $('#ContentResult').html("");
            },
            success: function (xaxa) {
                $('#ContentResult').html("");
                $('#ContentResult').hide();
                $('#ContentResult').html(xaxa);
                $('#ContentResult').fadeIn('fast');
                $("#ContentLoading").remove();
                $("#BtnViewDataCustom").blur();
                $("#BtnViewDataCustom").attr('disabled', false);
                $('#TableMaterialTrack').DataTable( {
                    "iDisplayLength": 5,
                    "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
                    scrollY:        "430px",
                    scrollX:        true,
                    scrollCollapse: true,
                    autoWidth: true
                });
            },
            error: function () {
                alert("Request cannot proceed!");
                $("#BtnViewDataCustom").blur();
                $('#ContentResult').html("");
                $("#ContentLoading").remove();
                $("#BtnViewDataCustom").attr('disabled', false);
            }
        });
    });
    $("#ViewSeason").click(function(){
        var Open = "";
        if ($('#UsedOpen').is(":checked")) {
            Open = $('#UsedOpen').val();
        }
        else{ Open = "off";}
        var Used = "";
        var StartDate = "";
        var EndDate = "";
        var Category = "";
        var Keywords = "";
        var Half = $("#FilterSeason").val();
        var formdata = new FormData();
        formdata.append('Used', Used);
        formdata.append('Open', Open);
        formdata.append('ValStartDate', StartDate);
        formdata.append('ValEndDate', EndDate);
        formdata.append('ValCategory', Category);
        formdata.append('ValKeywords', Keywords);
        formdata.append('Half', Half);
        $.ajax({
            url: 'project/Report/MaterialTrackingContent.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#ViewSeason").attr('disabled', true);
                $('#ContentResult').html("");
                $("#ContentResult").before('<div class="col-sm-12 d-flex justify-content-center" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                $('#ContentResult').html("");
            },
            success: function (xaxa) {
                $('#ContentResult').html("");
                $('#ContentResult').hide();
                $('#ContentResult').html(xaxa);
                $('#ContentResult').fadeIn('fast');
                $("#ContentLoading").remove();
                $("#ViewSeason").blur();
                $("#ViewSeason").attr('disabled', false);
                $('#TableMaterialTrack').DataTable( {
                    "iDisplayLength": 5,
                    "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
                    scrollY:        "430px",
                    scrollX:        true,
                    scrollCollapse: true,
                    autoWidth: true
                });
            },
            error: function () {
                alert("Request cannot proceed!");
                $("#ViewSeason").blur();
                $('#ContentResult').html("");
                $("#ContentLoading").remove();
                $("#ViewSeason").attr('disabled', false);
            }
        });
    });
});
</script>