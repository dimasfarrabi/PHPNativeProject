$(document).ready(function () {
    $("#BtnViewProject").click(function () {
        var ValTime = $("#InputClosedTime").children("option:selected").val();
        $('#TempDataTime').html(ValTime);
        var formdata = new FormData();
        formdata.append("ValClosedTime", ValTime);
        $.ajax({
            url: 'project/QualityPoint/QualityPointPageListWO.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#BtnViewProject").attr('disabled', true);
                $("#InputClosedTime").attr('disabled', true);
                $('#ListQuote').html("");
                $("#ListQuote").before('<div class="col-sm-12 d-flex justify-content-center" id="ContentLoading"><img src="images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                $('#ListQuote').html("");
                $('#ResultCategory').html("");
                $('#ActualDetails').html("");
                $('#TempQuote').html("");
                $('#TempSelect').html("");
            },
            success: function (xaxa) {
                $('#ListQuote').html("");
                $('#ListQuote').hide();
                $('#ListQuote').html(xaxa);
                $('#ListQuote').fadeIn('fast');
                $("#ContentLoading").remove();
                $("#BtnViewProject").blur();
                $("#BtnViewProject").attr('disabled', false);
                $("#InputClosedTime").attr('disabled', false);
                GET_LIST_QUALITY_POINT();
            },
            error: function () {
                alert("Request cannot proceed!");
                $("#BtnViewProject").blur();
                $('#ListQuote').html("");
                $("#ContentLoading").remove();
                $("#BtnViewProject").attr('disabled', false);
                $("#InputClosedTime").attr('disabled', false);
            }
        });
    });
	$("#BtnDownload").click(function () {
        var ValTime = $("#InputClosedTime").children("option:selected").val();
		var DataKeywords = "??";        
        window.location.href = 'project/QualityPoint/DownloadQualityPointPerSeason.php?time='+ValTime+'&&key='+DataKeywords;
    });
});
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
                url: 'project/QualityPoint/QualityPointPageContent.php',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: formdata,
                type: 'post',
                beforeSend: function () {
                    BolPointerListProject = "FALSE";
                    $("html, body").animate({ scrollTop: $("#ResultCategory").offset().top - 80 }, "fast");
                    $('#ResultCategory').html("");
                    $('#ActualDetails').html("");
                    $("#ContentLoading").remove();
                    $("#ResultCategory").before('<div class="col-sm-12 d-flex justify-content-center" id="ContentLoading"><img src="images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                    $('#ResultCategory').html("");
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
            url: 'project/QualityPoint/ModalUpdateQualityPoint.php',
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
            url: 'project/QualityPoint/ModalUpdateQualityPoint.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $('#LoadingImgPSM').show();
                $('#ContentSelectedPSM').html("");
            },
            success: function (xaxa) {
                $('#LoadingImgPSM').hide();
                $('#ContentSelectedPSM').hide();
                $('#ContentSelectedPSM').html(xaxa);
                $('#ContentSelectedPSM').fadeIn('fast');
                MODAL_UPDATE_PSM();                
            },
            error: function () {
                $('#LoadingImgPSM').hide();
                alert('Request cannot proceed!');
            }
        });
    });
}
function MODAL_UPDATE_PSL()
{
    $('#InputActualA').keypress(function (e) {
        if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
            e.preventDefault();
        }
    });
    $('#InputTargetMinA').keypress(function (e) {
        if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
            e.preventDefault();
        }
    });
    $('#InputTargetMaxA').keypress(function (e) {
        if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
            e.preventDefault();
        }
    });
    $('#InputGoalA').keypress(function (e) {
        if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
            e.preventDefault();
        }
    });
    $("#BtnUpdateQualityPointA").click(function () {
        if ($("#InputActualA").val().trim() == "") { $("#InputActualA").focus(); return false; }
        if ($("#InputTargetMinA").val().trim() == "") { $("#InputTargetMinA").focus(); return false; }
        var InputQuote = $("#InputQuoteA").val().trim();
        var InputClosedTime = $("#InputClosedTimeMA").val().trim();
        var InputDivision = $("#InputDivisionA").val().trim();
        var InputActual = $("#InputActualA").val().trim();
        var InputTargetMin = $("#InputTargetMinA").val().trim();
        // var InputTargetMax = "100";
        var InputTargetMax = $("#InputTargetMaxA").val().trim();
        var DataCookies = $(this).closest("#BtnUpdateQualityPointA").data('cookies');
        var formdata = new FormData();
        formdata.append("ValQuote", InputQuote);
        formdata.append("ValClosedTime", InputClosedTime);
        formdata.append("ValDivision", InputDivision);
        formdata.append("ValActual", InputActual);
        formdata.append("ValTargetMin", InputTargetMin);
        formdata.append("ValTargetMax", InputTargetMax);
        formdata.append("ValDataCookies", DataCookies);
        $.ajax({
            url: 'project/QualityPoint/src/srcNewDataPoint.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#BtnUpdateQualityPointA").attr('disabled', true);
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
                    var ValQA = BolValRes[2];
                    var ValQAID = BolValRes[3];
                    InputActual = parseFloat(InputActual).toFixed(2);
                    InputTargetMin = parseFloat(InputTargetMin).toFixed(2);
                    InputTargetMax = parseFloat(InputTargetMax).toFixed(2);
                    ValGoal = parseFloat(ValGoal).toFixed(2);
                    ValQA = parseFloat(ValQA).toFixed(2);
                    $("#BtnUpdateQualityPointA").attr('disabled', false);
                    $('#TableProjectSelectedA tr[data-cookies="' + DataCookies + '"] td:eq(2)').text(InputActual);
                    $('#TableProjectSelectedA tr[data-cookies="' + DataCookies + '"] td:eq(3)').text(InputTargetMin);
                    $('#TableProjectSelectedA tr[data-cookies="' + DataCookies + '"] td:eq(4)').text(InputTargetMax);
                    $('#TableProjectSelectedA tr[data-cookies="' + DataCookies + '"] td:eq(5)').text(ValGoal);
                    $('#TableProjectSelectedA tr[data-cookies="' + ValQAID + '"] td:eq(2)').text(ValQA);
                    $("#ModalUpdatePSL").modal("hide");
                    $('#AccQ').html("");
                }
                else
                {
                    var ValError = BolValRes[1];
                    $('#AccQ').html("");
                    alert(ValError);
                    $("#BtnUpdateQualityPointA").attr('disabled', false);
                    return false;
                }
            },
            error: function () {
                alert('Error! Request cannot proceed!');
                $("#BtnUpdateQualityPointA").attr('disabled', false);
                return false;
            }
        });
    });
    
    
    $("#BtnDeleteQualityPointA").click(function () {
        var InputQuote = $("#InputQuoteA").val().trim();
        var InputClosedTime = $("#InputClosedTimeMA").val().trim();
        var InputDivision = $("#InputDivisionA").val().trim();
        var DataCookies = $(this).closest("#BtnDeleteQualityPointA").data('cookies');
        if (confirm("Are you sure to delete this record?") == true) {
            var formdata = new FormData();
            formdata.append("ValQuote", InputQuote);
            formdata.append("ValClosedTime", InputClosedTime);
            formdata.append("ValDivision", InputDivision);
            formdata.append("ValDataCookies", DataCookies);
            $.ajax({
                url: 'project/QualityPoint/src/srcDeleteDataPoint.php',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: formdata,
                type: 'post',
                beforeSend: function () {
                    $("#BtnDeleteQualityPointA").attr('disabled', true);
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
                        var ValQA = BolValRes[1];
                        var ValQAID = BolValRes[2];
                        ValQA = parseFloat(ValQA).toFixed(2);
                        $("#BtnDeleteQualityPointA").attr('disabled', false);
                        $('#TableProjectSelectedA tr[data-cookies="' + DataCookies + '"] td:eq(2)').text("");
                        $('#TableProjectSelectedA tr[data-cookies="' + DataCookies + '"] td:eq(3)').text("");
                        $('#TableProjectSelectedA tr[data-cookies="' + DataCookies + '"] td:eq(4)').text("");
                        $('#TableProjectSelectedA tr[data-cookies="' + DataCookies + '"] td:eq(5)').text("");
                        $('#TableProjectSelectedA tr[data-cookies="' + ValQAID + '"] td:eq(2)').text(ValQA);
                        $("#ModalUpdatePSL").modal("hide");
                        $('#AccQ').html("");
                    }
                    else
                    {
                        var ValError = BolValRes[1];
                        $('#AccQ').html("");
                        alert(ValError);
                        $("#BtnDeleteQualityPointA").attr('disabled', false);
                        return false;
                    }
                },
                error: function () {
                    alert('Error! Request cannot proceed!');
                    $("#BtnDeleteQualityPointA").attr('disabled', false);
                    return false;
                }
            });
            
            
        }
    });

}
function MODAL_UPDATE_PSM() {
    $('#InputActualB').keypress(function (e) {
        if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
            e.preventDefault();
        }
    });
    $('#InputTargetMinB').keypress(function (e) {
        if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
            e.preventDefault();
        }
    });
    $('#InputTargetMaxB').keypress(function (e) {
        if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
            e.preventDefault();
        }
    });
    $('#InputGoalB').keypress(function (e) {
        if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
            e.preventDefault();
        }
    });
    $("#BtnUpdateQualityPointB").click(function () {
        if ($("#InputActualB").val().trim() == "") { $("#InputActualB").focus(); return false; }
        if ($("#InputTargetMinB").val().trim() == "") { $("#InputTargetMinB").focus(); return false; }
        var InputQuote = $("#InputQuoteB").val().trim();
        var InputClosedTime = $("#InputClosedTimeMB").val().trim();
        var InputDivision = $("#InputDivisionB").val().trim();
        var InputActual = $("#InputActualB").val().trim();
        var InputTargetMin = $("#InputTargetMinB").val().trim();
        var InputTargetMax = "100";
        var DataCookies = $(this).closest("#BtnUpdateQualityPointB").data('cookies');
        var formdata = new FormData();
        formdata.append("ValQuote", InputQuote);
        formdata.append("ValClosedTime", InputClosedTime);
        formdata.append("ValDivision", InputDivision);
        formdata.append("ValActual", InputActual);
        formdata.append("ValTargetMin", InputTargetMin);
        formdata.append("ValTargetMax", InputTargetMax);
        formdata.append("ValDataCookies", DataCookies);
        $.ajax({
            url: 'project/QualityPoint/src/srcNewDataPoint.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#BtnUpdateQualityPointB").attr('disabled', true);
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
                    $("#BtnUpdateQualityPointB").attr('disabled', false);
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
                    $("#BtnUpdateQualityPointB").attr('disabled', false);
                    return false;
                }
            },
            error: function () {
                alert('Error! Request cannot proceed!');
                $("#BtnUpdateQualityPointB").attr('disabled', false);
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
                url: 'project/QualityPoint/ActualDetail.php',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: formdata,
                type: 'post',
                beforeSend: function () {
                    $("html, body").animate({ scrollTop: $("#ActualDetails").offset().top - 80 }, "fast");
                    $('#ActualDetails').html("");
                    $("#ActualDetails").before('<div class="col-sm-12 d-flex justify-content-center" id="ContentLoading"><img src="images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
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
                url: 'project/QualityPoint/ActualDetail.php',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: formdata,
                type: 'post',
                beforeSend: function () {
                    $("html, body").animate({ scrollTop: $("#ActualDetails").offset().top - 80 }, "fast");
                    $('#ActualDetails').html("");
                    $("#ActualDetails").before('<div class="col-sm-12 d-flex justify-content-center" id="ContentLoading"><img src="images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
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