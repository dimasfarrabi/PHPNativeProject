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
                GET_LIST_QUALITY_POINT();
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
        },
        success: function (xaxa) {
            $('#SummaryQP').html("");
            $('#SummaryQP').hide();
            $('#SummaryQP').html(xaxa);
            $('#SummaryQP').fadeIn('fast');
            $("#ContentLoading").remove();
        },
        error: function () {
            alert("Request cannot proceed!");
            $('#SummaryQP').html("");
            $("#ContentLoading").remove();
        }
    });
}
function GET_LIST_QUALITY_POINT()
{
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
            var formdata = new FormData();
            formdata.append("ValProjectName", ProjectName);
            formdata.append("ValProjectID", ProjectID);
            formdata.append("ValClosedTime", ClosedTime);
            $.ajax({
                url: 'project/qualitypoint/qualitypointpagecontent.php',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: formdata,
                type: 'post',
                beforeSend: function () {
                    BolPointerListProject = "FALSE";
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
                    BolPointerListProject = "TRUE";
                    POINT_DATA();
                    DETAIL_ACTUAL_PER_PROJECT();
					$('#SummaryQP').hide();
                },
                error: function () {
                    alert("Request cannot proceed!");
                    $('#ResultCategory').html("");
                    $("#ContentLoading").remove();
                    BolPointerListProject = "TRUE";
                }
            });
        }
        else
        {
            return false;
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
        // if ($("#InputGoal").val().trim() == "") { $("#InputGoal").focus(); return false; }
        var InputQuote = $("#InputQuote").val().trim();
        var InputClosedTime = $("#InputClosedTimeM").val().trim();
        var InputDivision = $("#InputDivision").val().trim();
        var InputActual = $("#InputActual").val().trim();
        var InputTargetMin = $("#InputTargetMin").val().trim();
        // var InputTargetMax = $("#InputTargetMax").val().trim();
        // if (InputTargetMax == "") { InputTargetMax = "100";}
        var InputTargetMax = "100";
        // var InputGoal = $("#InputGoal").val().trim();
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
        // if ($("#InputGoal").val().trim() == "") { $("#InputGoal").focus(); return false; }
        var InputQuote = $("#InputQuote").val().trim();
        var InputClosedTime = $("#InputClosedTimeM").val().trim();
        var InputDivision = $("#InputDivision").val().trim();
        var InputActual = $("#InputActual").val().trim();
        var InputTargetMin = $("#InputTargetMin").val().trim();
        // var InputTargetMax = $("#InputTargetMax").val().trim();
        // if (InputTargetMax == "") { InputTargetMax = "100";}
        var InputTargetMax = "100";
        // var InputGoal = $("#InputGoal").val().trim();
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
                url: 'project/qualitypoint/ActualDetail.php',
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
    $("#TableProjectSelectedB tbody tr").click(function () {
        if (typeof $(this).data('details') !== "undefined") {
            $("#TableProjectSelectedA tbody tr").removeClass('PointerListSelected');
            $("#TableProjectSelectedB tbody tr").removeClass('PointerListSelected');
            $(this).closest('.ListRow').addClass("PointerListSelected");
            var DataDetails = $(this).data('details');
            var formdata = new FormData();
            formdata.append("ValData", DataDetails);
            $.ajax({
                url: 'project/qualitypoint/ActualDetail.php',
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
        else {
            $('#ActualDetails').html("");
            $("#TableProjectSelectedA tbody tr").removeClass('PointerListSelected');
            $("#TableProjectSelectedB tbody tr").removeClass('PointerListSelected');
            $(this).closest('.ListRow').addClass("PointerListSelected");
        }
    });
}