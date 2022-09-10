<?php 
require_once("project/QualityPoint/Modules/ModuleQualityPoint.php"); 
require_once("project/CostTracking/Modules/ModuleCostTracking.php"); 
date_default_timezone_set("Asia/Jakarta");
$YearNow = date("Y");
$DateNow = date("m/d/Y");
/*
if(!session_is_registered("UIDWebTrax"))
{
    ?>
    <script language="javascript">
        window.location.replace("https://webtrax.formulatrix.com/");
    </script>
    <?php
    exit();
}
if($AccessLogin == "Reguler")
{
    ?>
    <script language="javascript">
        window.location.href = "home.php";
    </script>
    <?php
    exit();
}<script src="project/qualitypoint/lib/libqualitypoint.js?no=<?php echo base64_encode(date("mdyHis")); ?>"></script>
*/
?>
<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb breadcrumb-detail">
            <li><a href="home.php">Home</a></li>
            <li class="active"><a href="home.php?link=18">Cost Tracking : Quality Point</a></li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="form-inline">
            <div class="form-group">
                <label for="InputClosedTime">Season</label>
                <select class="form-control" id="InputClosedTime"><?php 
                $QListClosedTime = GET_LIST_CLOSEDTIME_NOT_OPEN("",$linkMACHWebTrax);
                while($RListClosedTime = sqlsrv_fetch_array($QListClosedTime))
                {
                    $ClosedTime = $RListClosedTime['ClosedTime'];
                    ?>
                    <option><?php echo $ClosedTime; ?></option>
                    <?php
                }                
                ?></select>
            </div>
            <div class="form-group">
                <button class="btn btn-dark btn-labeled" id="BtnViewProject">View Data</button> 
            </div>           
        </div>
    </div>
    <div class="col-sm-12"><hr></div>

    <div class="col-md-3">
        <div class="row" id="ListQuote"></div>
        <span id="AccQ" class="InvisibleText"></span>
    </div>
    <div class="col-md-9">
        <div class="row" id="ResultCategory"></div>
        <div class="row" id="ActualDetails"></div>
		<div class="row" id="SummaryQP"></div>
        <span id="TempDataTime" class="InvisibleText"></span>
        <span id="TempQuote" class="InvisibleText"></span>
        <span id="TempSelect" class="InvisibleText"></span>
    </div>
</div>
<script>
$(document).ready(function () {
    $("#BtnViewProject").click(function () {
        var ValTime = $("#InputClosedTime").children("option:selected").val();
        $('#TempDataTime').html(ValTime);
        var formdata = new FormData();
        formdata.append("ValClosedTime", ValTime);
        $.ajax({
            url: 'project/qualitypoint/qualitypointpagelistwo.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#BtnViewProject").attr('disabled', true);
                $('#ListQuote').html("");
                $("#ListQuote").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                $('#ListQuote').html("");
                $('#ResultCategory').html("");
                $('#ActualDetails').html("");
                $('#TempQuote').html("");
                $('#TempSelect').html("");
                $('#SummaryQP').hide();
            },
            success: function (xaxa) {
                $('#ListQuote').html("");
                $('#ListQuote').hide();
                $('#ListQuote').html(xaxa);
                $('#ListQuote').fadeIn('fast');
                $("#ContentLoading").remove();
                $("#BtnViewProject").blur();
                $("#BtnViewProject").attr('disabled', false);
                var BolPointerListProject = "TRUE";
                $(".PointerListProject").click(function () {
                    if (BolPointerListProject == "TRUE")
                    {
                        $("#ListProject tr").removeClass('PointerListSelected');
                        $(this).closest('.PointerListProject').addClass("PointerListSelected");
                        var ProjectName = $(this).text();
                        var ClosedTime = $("#TempDataTime").text();
                        var ProjectID = $(this).data('row');
                        $("#TempQuote").html(ProjectName);
                        $('#TempSelect').html(ProjectID);
                        SHOW_CONTENT(ProjectName,ProjectID,ClosedTime);
                    }
                    else
                    {
                        return false;
                    }
                });
				GET_SUMMARY(ValTime);
            },
            error: function () {
                alert("Request cannot proceed!");
                $("#BtnViewProject").blur();
                $('#ListQuote').html("");
                $("#ContentLoading").remove();
                $("#BtnViewProject").attr('disabled', false);
            }
        });
    });
    $("#BtnViewProject").trigger('click'); 
});
function GET_SUMMARY(ClosedTime)
{
    var formdata = new FormData();
    formdata.append("ValClosedTime", ClosedTime);
    $.ajax({
        url: 'project/qualitypoint/summaryqualitypointV2.php',
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        data: formdata,
        type: 'post',
        beforeSend: function () {
            $("html, body").animate({ scrollTop: $("#ResultCategory").offset().top - 20 }, "fast");
            $('#SummaryQP').html("");
            $('#SummaryQP').html("");
            $("#ContentLoading").remove();
            $("#SummaryQP").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
            $('#SummaryQP').html("");
            $('#ResultCategory').hide();
            $('#ActualDetails').hide();
        },
        success: function (xaxa) {
            $('#SummaryQP').html("");
            $('#SummaryQP').hide();
            $('#SummaryQP').html(xaxa);
            $('#SummaryQP').fadeIn('fast');
            $("#ContentLoading").remove();
            var Bol = "TRUE";
            $(".SummaryPointer").click(function () {
                if (Bol == "TRUE")
                {
                    $("#ListSummary tr").removeClass('PointerListSelected');
                    $(this).closest('.SummaryPointer').addClass("PointerListSelected");
                    var Enc = $(this).data('row');
                    const Arr = Enc.split("*");
                    SHOW_CONTENT(Arr[0],'-',Arr[1]);
                }
                else
                {
                    return false;
                }
            });
        },
        error: function () {
            alert("Request cannot proceed!");
            $('#SummaryQP').html("");
            $("#ContentLoading").remove();
        }
    });
}
function SHOW_CONTENT(Input1,Input2,Input3)
{
    var formdata = new FormData();
    formdata.append("ValProjectName", Input1);
    formdata.append("ValProjectID", Input2);
    formdata.append("ValClosedTime", Input3);
    $.ajax({
        url: 'project/qualitypoint/qualitypointpagecontent.php',
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        data: formdata,
        type: 'post',
        beforeSend: function () {
            Bol = "FALSE";
            $("html, body").animate({ scrollTop: $("#ResultCategory").offset().top - 20 }, "fast");
            $('#ResultCategory').html("");
            $('#ActualDetails').html("");
            $("#ContentLoading").remove();
            $("#ResultCategory").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
            $('#ResultCategory').html("");
            $('#SummaryQP').hide();
        },
        success: function (xaxa) {
            $('#ResultCategory').html("");
            $('#ResultCategory').hide();
            $('#ResultCategory').html(xaxa);
            $('#ResultCategory').fadeIn('fast');
            $("#ContentLoading").remove();
            Bol = "TRUE";
            POINT_DATA();
            DETAIL_ACTUAL_PER_PROJECT();
            $('#SummaryQP').hide();
            $("#BackToSummary").click(function () { 
                GET_SUMMARY(Input3);
            });
        },
        error: function () {
            alert("Request cannot proceed!");
            $('#ResultCategory').html("");
            $("#ContentLoading").remove();
            Bol = "TRUE";
        }
    });
}
function POINT_DATA()
{
    $("#ModalUpdatePSL").on('show.bs.modal', function (event) {
        var act = $(event.relatedTarget);
        var DataCode = act.data('ecode');
        var DataTemp = act.data('dcode');
        var formdata = new FormData();
        formdata.append("ValCode", DataCode);
        formdata.append("ValTemp", DataTemp);
        $.ajax({
            url: 'project/qualitypoint/modalupdatequalitypoint.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $('#LoadingImg').show();
                $('#ContentSelected').html("");
            },
            success: function (xaxa) {
                $('#LoadingImg').hide();
                $('#ContentSelected').hide();
                $('#ContentSelected').html(xaxa);
                $('#ContentSelected').fadeIn('fast');
                MODAL_UPDATE_PSL();
            },
            error: function () {
                $('#LoadingImg').hide();
                alert('Request cannot proceed!');
            }
        });
    });
    $("#ModalUpdatePSM").on('show.bs.modal', function (event) {
        var act = $(event.relatedTarget);
        var DataCode = act.data('ecode');
        var DataTemp = act.data('dcode');
        var formdata = new FormData();
        formdata.append("ValCode", DataCode);
        formdata.append("ValTemp", DataTemp);
        $.ajax({
            url: 'project/qualitypoint/modalupdatequalitypoint.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $('#LoadingImg').show();
                $('#ContentSelectedPSM').html("");
            },
            success: function (xaxa) {
                $('#LoadingImg').hide();
                $('#ContentSelectedPSM').hide();
                $('#ContentSelectedPSM').html(xaxa);
                $('#ContentSelectedPSM').fadeIn('fast');
                MODAL_UPDATE_PSM();
            },
            error: function () {
                $('#LoadingImg').hide();
                alert('Request cannot proceed!');
            }
        });
    });
}
function MODAL_UPDATE_PSL()
{
    $('#InputActual').keypress(function (e) {
        if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
            e.preventDefault();
        }
    });
    $('#InputTargetMin').keypress(function (e) {
        if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
            e.preventDefault();
        }
    });
    $('#InputTargetMax').keypress(function (e) {
        if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
            e.preventDefault();
        }
    });
    $('#InputGoal').keypress(function (e) {
        if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
            e.preventDefault();
        }
    });
    $("#BtnUpdateQualityPoint").click(function () {
        if ($("#InputActual").val().trim() == "") { $("#InputActual").focus(); return false; }
        if ($("#InputTargetMin").val().trim() == "") { $("#InputTargetMin").focus(); return false; }
        var InputQuote = $("#InputQuote").val().trim();
        var InputClosedTime = $("#InputClosedTimeM").val().trim();
        var InputDivision = $("#InputDivision").val().trim();
        var InputActual = $("#InputActual").val().trim();
        var InputTargetMin = $("#InputTargetMin").val().trim();
        var InputTargetMax = "100";
        var DataCookies = $(this).closest("#BtnUpdateQualityPoint").data('cookies');
        var formdata = new FormData();
        formdata.append("ValQuote", InputQuote);
        formdata.append("ValClosedTime", InputClosedTime);
        formdata.append("ValDivision", InputDivision);
        formdata.append("ValActual", InputActual);
        formdata.append("ValTargetMin", InputTargetMin);
        formdata.append("ValTargetMax", InputTargetMax);
        formdata.append("ValDataCookies", DataCookies);
        $.ajax({
            url: 'project/qualitypoint/src/srcnewdatapoint.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#BtnUpdateQualityPoint").attr('disabled', true);
            },
            success: function (xaxa) {
                $('#AccQ').hide();
                $('#AccQ').html(xaxa);
                $('#AccQ').fadeIn('fast');
                var ValRes = $('#AccQ').text();
                var BolValRes = ValRes.split("#");
                var ValRes0 = BolValRes[0];
                if (ValRes0 == "True")
                {
                    var ValGoal = BolValRes[1];
                    InputActual = parseFloat(InputActual).toFixed(2);
                    InputTargetMin = parseFloat(InputTargetMin).toFixed(2);
                    InputTargetMax = parseFloat(InputTargetMax).toFixed(2);
                    ValGoal = parseFloat(ValGoal).toFixed(2);
                    $("#BtnUpdateQualityPoint").attr('disabled', false);
                    $('#TableProjectSelectedA tr[data-cookies="' + DataCookies + '"] td:eq(2)').text(InputActual);
                    $('#TableProjectSelectedA tr[data-cookies="' + DataCookies + '"] td:eq(3)').text(InputTargetMin);
                    $('#TableProjectSelectedA tr[data-cookies="' + DataCookies + '"] td:eq(4)').text(InputTargetMax);
                    $('#TableProjectSelectedA tr[data-cookies="' + DataCookies + '"] td:eq(5)').text(ValGoal);
                    $("#ModalUpdatePSL").modal("hide");
                    $('#AccQ').html("");
                }
                else
                {
                    var ValError = BolValRes[1];
                    $('#AccQ').html("");
                    alert(ValError);
                    $("#BtnUpdateQualityPoint").attr('disabled', false);
                    return false;
                }
            },
            error: function () {
                alert('Error! Request cannot proceed!');
                $("#BtnUpdateQualityPoint").attr('disabled', false);
                return false;
            }
        });
    });
}
function MODAL_UPDATE_PSM() {
    $('#InputActual').keypress(function (e) {
        if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
            e.preventDefault();
        }
    });
    $('#InputTargetMin').keypress(function (e) {
        if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
            e.preventDefault();
        }
    });
    $('#InputTargetMax').keypress(function (e) {
        if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
            e.preventDefault();
        }
    });
    $('#InputGoal').keypress(function (e) {
        if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
            e.preventDefault();
        }
    });
    $("#BtnUpdateQualityPoint").click(function () {
        if ($("#InputActual").val().trim() == "") { $("#InputActual").focus(); return false; }
        if ($("#InputTargetMin").val().trim() == "") { $("#InputTargetMin").focus(); return false; }
        var InputQuote = $("#InputQuote").val().trim();
        var InputClosedTime = $("#InputClosedTimeM").val().trim();
        var InputDivision = $("#InputDivision").val().trim();
        var InputActual = $("#InputActual").val().trim();
        var InputTargetMin = $("#InputTargetMin").val().trim();
        var InputTargetMax = "100";
        var DataCookies = $(this).closest("#BtnUpdateQualityPoint").data('cookies');
        var formdata = new FormData();
        formdata.append("ValQuote", InputQuote);
        formdata.append("ValClosedTime", InputClosedTime);
        formdata.append("ValDivision", InputDivision);
        formdata.append("ValActual", InputActual);
        formdata.append("ValTargetMin", InputTargetMin);
        formdata.append("ValTargetMax", InputTargetMax);
        formdata.append("ValDataCookies", DataCookies);
        $.ajax({
            url: 'project/qualitypoint/src/srcnewdatapoint.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#BtnUpdateQualityPoint").attr('disabled', true);
            },
            success: function (xaxa) {
                $('#AccQ').hide();
                $('#AccQ').html(xaxa);
                $('#AccQ').fadeIn('fast');
                var ValRes = $('#AccQ').text();
                var BolValRes = ValRes.split("#");
                var ValRes0 = BolValRes[0];
                if (ValRes0 == "True") {
                    var ValGoal = BolValRes[1];
                    InputActual = parseFloat(InputActual).toFixed(2);
                    InputTargetMin = parseFloat(InputTargetMin).toFixed(2);
                    InputTargetMax = parseFloat(InputTargetMax).toFixed(2);
                    ValGoal = parseFloat(ValGoal).toFixed(2);
                    $("#BtnUpdateQualityPoint").attr('disabled', false);
                    $('#TableProjectSelectedB tr[data-cookies="' + DataCookies + '"] td:eq(2)').text(InputActual);
                    $('#TableProjectSelectedB tr[data-cookies="' + DataCookies + '"] td:eq(3)').text(InputTargetMin);
                    $('#TableProjectSelectedB tr[data-cookies="' + DataCookies + '"] td:eq(4)').text(InputTargetMax);
                    $('#TableProjectSelectedB tr[data-cookies="' + DataCookies + '"] td:eq(5)').text(ValGoal);
                    $("#ModalUpdatePSM").modal("hide");
                    $('#AccQ').html("");
                }
                else {
                    var ValError = BolValRes[1];
                    $('#AccQ').html("");
                    alert(ValError);
                    $("#BtnUpdateQualityPoint").attr('disabled', false);
                    return false;
                }
            },
            error: function () {
                alert('Error! Request cannot proceed!');
                $("#BtnUpdateQualityPoint").attr('disabled', false);
                return false;
            }
        });
    });
}
function DETAIL_ACTUAL_PER_PROJECT()
{
    $("#TableProjectSelectedA tbody tr").click(function () {
        if (typeof $(this).data('details') !== "undefined")
        {
            $("#TableProjectSelectedA tbody tr").removeClass('PointerListSelected');
            $("#TableProjectSelectedB tbody tr").removeClass('PointerListSelected');
            $(this).closest('.ListRow').addClass("PointerListSelected");
            var DataDetails = $(this).data('details');
            var formdata = new FormData();
            formdata.append("ValData", DataDetails);
            $.ajax({
                url: 'project/qualitypoint/ContentActualPerDivision.php',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: formdata,
                type: 'post',
                beforeSend: function () {
                    $("html, body").animate({ scrollTop: $("#ActualDetails").offset().top - 20 }, "fast");
                    $('#ActualDetails').html("");
                    $("#ActualDetails").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                    $('#ActualDetails').html("");
                },
                success: function (xaxa) {
                    $('#ActualDetails').hide();
                    $('#ActualDetails').html(xaxa);
                    $('#ActualDetails').fadeIn('fast');
                    $("#ContentLoading").remove();
                },
                error: function () {
                    $('#ActualDetails').html("");
                    $("#ContentLoading").remove();
                    alert('Request cannot proceed!');
                }
            });
        }
        else
        {
            $('#ActualDetails').html("");
            $("#TableProjectSelectedA tbody tr").removeClass('PointerListSelected');
            $("#TableProjectSelectedB tbody tr").removeClass('PointerListSelected');
            $(this).closest('.ListRow').addClass("PointerListSelected");
        }
    });
}
</script>