$(document).ready(function () {
    $("#BtnViewProject").click(function () {
        var ValTime = $("#InputClosedTime").children("option:selected").val();
        $('#TempFilter').html(ValTime);
        var formdata = new FormData();
        formdata.append("ValClosedTime", ValTime);
        $.ajax({
            url: 'project/pointachievement/EmpPointPageV2.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $('#EmpPoint').html("");
                $("#EmpPoint").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                $('#EmpPoint').html("");
                $('#TempPM').html("");
                $('#ResultProject').hide();
                $('#DetailProject').hide();
                $('#ResultTimeTrack').hide();
                $('#DetailTimeTrackEmployee').hide();
            },
            success: function (xaxa) {
                $('#EmpPoint').html("");
                $('#EmpPoint').hide();
                $('#EmpPoint').html(xaxa);
                $('#EmpPoint').fadeIn('fast');
                $("#ContentLoading").remove();
                $('#ResultProject').hide();
                $('#DetailProject').hide();
                $('#ResultTimeTrack').hide();
                $('#DetailTimeTrackEmployee').hide();
                $("#TableLeft").DataTable({
                    "iDisplayLength": 25,
                    "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]]
                });
                $("#TableCenter").DataTable({
                    paging: false
                });
                $("#TableRight").DataTable({
                    paging: false
                });
                BTN_DOWNLOAD(ValTime);
            },
            error: function () {
                alert("Request cannot proceed!");
                $("#BtnViewProject").blur();
                $('#EmpPoint').html("");
                $("#ContentLoading").remove();
            }
        });
    });
    $("#BtnViewProject").click(function () {
        var ValTime = $("#InputClosedTime").children("option:selected").val();
        $('#TempFilter').html(ValTime);
        var formdata = new FormData();
        formdata.append("ValClosedTime", ValTime);
        $.ajax({
            url: 'project/pointachievement/listpm.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#BtnViewProject").attr('disabled', true);
                $('#ListPM').html("");
                $("#ListPM").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                $('#ListPM').html("");
                $('#ListEmployee').html("");
                $('#TempPM').html("");
                $('#TempEmployee').html("");
                $('#ResultProject').html("");
                $('#ResultTimeTrack').html("");
                $("#TempFilter2").html("");
                $('#TempFilter3').html("");
                $("#DetailProject").html("");
                $('#DetailTimeTrack').html("");
                $('#DetailTimeTrackEmployee').html("");
            },
            success: function (xaxa) {
                $('#ListPM').html("");
                $('#ListPM').hide();
                $('#ListPM').html(xaxa);
                $('#ListPM').fadeIn('fast');
                $("#ContentLoading").remove();
                $("#BtnViewProject").blur();
                $("#BtnViewProject").attr('disabled', false);
                GET_LIST_EMPLOYEE();
                GET_PROJECT();
                // GET_PROJECT_DM();
                // DOWNLOAD_CSV();
            },
            error: function () {
                alert("Request cannot proceed!");
                $("#BtnViewProject").blur();
                $('#ListPM').html("");
                $("#ContentLoading").remove();
                $("#BtnViewProject").attr('disabled', false);
            }
        });
    });
});
function BTN_DOWNLOAD(ClosedTime)
{
    $("#BtnDownload").click(function(){
        window.location.href = 'project/PointAchievement/src/DownloadPeoplePoint.php?CL='+ClosedTime;
    });
}
function GET_LIST_EMPLOYEE() {
    var formdata = new FormData();
    var ClosedTime = "";
    var PMName = "";
    formdata.append("ValClosedTime", ClosedTime);
    formdata.append("ValPM", PMName);
    $.ajax({
        url: 'project/pointachievement/listemployee.php',
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        data: formdata,
        type: 'post',
        beforeSend: function () {
            $("#BtnViewProject").attr('disabled', true);
            $('#TempEmployee').html("");
            $('#ResultTimeTrack').html("");
            $("#TempFilter2").html("");
            $('#TempFilter3').html("");
            $("#DetailProject").html("");
            $('#DetailTimeTrack').html("");
            $('#DetailTimeTrackEmployee').html("");
        },
        success: function (xaxa) {
            $('#ListEmployee').html("");
            $('#ListEmployee').hide();
            $('#ListEmployee').html(xaxa);
            $('#ListEmployee').fadeIn('fast');
            $("#ContentLoading").remove();
            $("#BtnViewProject").blur();
            $("#BtnViewProject").attr('disabled', false);
            FIND_EMPLOYEE();
            GET_DATA_EMPLOYEE();
            DETAIL_TIMETRACK_EMPLOYEE();
        },
        error: function () {
            alert("Request cannot proceed!");
            $("#BtnViewProject").blur();
            $('#ListEmployee').html("");
            $("#ContentLoading").remove();
            $("#BtnViewProject").attr('disabled', false);
        }
    });
}
// function DOWNLOAD_CSV(InputTemplate)
// {
//     $("#Download_CSV").click(function(){
//         window.location.href = '';
//     });
// }

function FIND_EMPLOYEE() {
    var BolPointerListEmployee = "TRUE";
    $("#BtnSearchEmployee").click(function () {
        if (BolPointerListEmployee == "TRUE") {
            var Employee = $("#InputFindEmployee").val().trim();
            var PMName = $("#TempPM").text();
            var ClosedTime = $("#TempFilter").text();
            var formdata = new FormData();
            formdata.append("ValClosedTime", ClosedTime);
            formdata.append("ValPM", PMName);
            formdata.append("ValEmployee", Employee);
            $.ajax({
                url: 'project/pointachievement/listemployeefind.php',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: formdata,
                type: 'post',
                beforeSend: function () {
                    $("#BtnViewProject").attr('disabled', true);
                    $("#BtnSearchEmployee").attr('disabled', true);
                    $("#InputFindEmployee").attr('disabled', true);
                    $("#ListEmployee").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                    $('#TempEmployee').html("");
                    $('#ContentEmployee').html("");
                    $("#TempFilter2").html("");
                    $('#TempFilter3').html("");
                    $('#DetailTimeTrack').html("");
                    $('#EmpPoint').hide();
                    BolPointerListEmployee = "FALSE";
                },
                success: function (xaxa) {
                    $('#ListEmployee').html("");
                    $('#ListEmployee').hide();
                    $('#ListEmployee').html(xaxa);
                    $('#ListEmployee').fadeIn('fast');
                    $("#ContentLoading").remove();
                    $("#BtnSearchEmployee").blur();
                    $("#BtnViewProject").attr('disabled', false);
                    $("#BtnSearchEmployee").attr('disabled', false);
                    $("#InputFindEmployee").attr('disabled', false);
                    $('#EmpPoint').hide();
                    FIND_EMPLOYEE();
                    GET_DATA_EMPLOYEE();
                    DETAIL_TIMETRACK_EMPLOYEE();
                    BolPointerListEmployee = "TRUE";
                },
                error: function () {
                    alert("Request cannot proceed!");
                    $("#BtnSearchEmployee").blur();
                    $('#ListEmployee').html("");
                    $("#ContentLoading").remove();
                    $("#BtnViewProject").attr('disabled', false);
                    $("#BtnSearchEmployee").attr('disabled', false);
                    $("#InputFindEmployee").attr('disabled', false);
                    BolPointerListEmployee = "TRUE";
                }
            });
        }
        else {
            return false;
        }
    });
    $("#InputFindEmployee").on('keypress', function (e) {
        if (e.which == 13) {
            if (BolPointerListEmployee == "TRUE") {
                var Employee = $("#InputFindEmployee").val().trim();
                var PMName = $("#TempPM").text();
                var ClosedTime = $("#TempFilter").text();
                var formdata = new FormData();
                formdata.append("ValClosedTime", ClosedTime);
                formdata.append("ValPM", PMName);
                formdata.append("ValEmployee", Employee);
                $.ajax({
                    url: 'project/pointachievement/listemployeefind.php',
                    dataType: 'text',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formdata,
                    type: 'post',
                    beforeSend: function () {
                        $("#BtnViewProject").attr('disabled', true);
                        $("#BtnSearchEmployee").attr('disabled', true);
                        $("#InputFindEmployee").attr('disabled', true);
                        $("#ListEmployee").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                        $('#TempEmployee').html("");
                        $('#ContentEmployee').html("");
                        $('#EmpPoint').hide();
                        BolPointerListEmployee = "FALSE";
                    },
                    success: function (xaxa) {
                        $('#ListEmployee').html("");
                        $('#ListEmployee').hide();
                        $('#ListEmployee').html(xaxa);
                        $('#ListEmployee').fadeIn('fast');
                        $("#ContentLoading").remove();
                        $("#BtnSearchEmployee").blur();
                        $("#BtnViewProject").attr('disabled', false);
                        $("#BtnSearchEmployee").attr('disabled', false);
                        $("#InputFindEmployee").attr('disabled', false);
                        $('#EmpPoint').hide();
                        FIND_EMPLOYEE();
                        GET_DATA_EMPLOYEE();
                        DETAIL_TIMETRACK_EMPLOYEE();
                        BolPointerListEmployee = "TRUE";
                    },
                    error: function () {
                        alert("Request cannot proceed!");
                        $("#BtnSearchEmployee").blur();
                        $('#ListEmployee').html("");
                        $("#ContentLoading").remove();
                        $("#BtnViewProject").attr('disabled', false);
                        $("#BtnSearchEmployee").attr('disabled', false);
                        $("#InputFindEmployee").attr('disabled', false);
                        BolPointerListEmployee = "TRUE";
                    }
                });
            }
            else {
                return false;
            }
        }
    });
}
function GET_PROJECT()
{
    var BolPointerListPM = "TRUE";
    $(".PointerListPM").click(function () {
        if (BolPointerListPM == "TRUE")
        {
            $("#ListTablePM tr").removeClass('PointerListSelected');
            $(this).closest('.PointerListPM').addClass("PointerListSelected");
            var PMName = $(this).text().trim();
            $('#TempPM').html(PMName);
            var ClosedTime = $("#TempFilter").text();
            var Roles = $(this).data('roles');
            $("#TempFilter2").html(Roles);
            var formdata = new FormData();
            formdata.append("ValClosedTime", ClosedTime);
            formdata.append("ValPM", PMName);
            formdata.append("ValRoles", Roles);
            $.ajax({
                url: 'project/pointachievement/listresultproject.php',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: formdata,
                type: 'post',
                beforeSend: function () {
                    $("html, body").animate({ scrollTop: $("#ResultProject").offset().top - 20 }, "fast");
                    $('#ResultTimeTrack').html("");
                    $('#ResultProject').html("");
                    $("#ResultProject").before('<div class="col-sm-12" id="ContentLoadingQuote"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                    $('#ResultProject').html("");
                    $('#TempFilter3').html("");
                    $("#DetailProject").html("");
                    $('#DetailTimeTrack').html("");
                    $('#DetailTimeTrackEmployee').html("");
                    $('#EmpPoint').hide();
                    BolPointerListPM = "FALSE";
                },
                success: function (xaxa) {
                    $('#ResultProject').html("");
                    $('#ResultProject').hide();
                    $('#ResultProject').html(xaxa);
                    $('#ResultProject').fadeIn('fast');
                    $("#ContentLoadingQuote").remove();
                    $('#EmpPoint').hide();
                    DETAIL_TIMETRACK_LEADER();
                    BolPointerListPM = "TRUE";
                },
                error: function () {
                    alert("Request cannot proceed!");
                    $('#ResultProject').html("");
                    $("#ContentLoadingQuote").remove();
                    BolPointerListPM = "TRUE";
                }
            });
        }
        else
        {
            return false;
        }
    });   
}
function GET_DATA_EMPLOYEE()
{
    var BolPointerListTT = "TRUE";
    $(".PointerListEmployee").click(function () {
        if (BolPointerListTT == "TRUE") {
            $("#ListTableEmp tr").removeClass('PointerListSelected');
            $(this).closest('.PointerListEmployee').addClass("PointerListSelected");
            var EmpName = $(this).text().trim();
            $('#TempEmployee').html(EmpName);
            var ClosedTime = $("#TempFilter").text();
            var Roles = $(this).data('roles');
            $("#TempFilter3").html(Roles);
            var formdata = new FormData();
            formdata.append("ValClosedTime", ClosedTime);
            formdata.append("ValEmployee", EmpName);
            formdata.append("ValRoles", Roles);
            $.ajax({
                url: 'project/pointachievement/listresulttt.php',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: formdata,
                type: 'post',
                beforeSend: function () {
                    $("html, body").animate({ scrollTop: $("#ResultProject").offset().top - 20 }, "fast");
                    $('#ResultProject').html("");
                    $('#ResultTimeTrack').html("");
                    $("#ResultTimeTrack").before('<div class="col-sm-12" id="ContentLoadingTT"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                    $('#ResultTimeTrack').html("");
                    $("#TempFilter2").html("");
                    $('#TempFilter3').html("");
                    $("#DetailProject").html("");
                    $('#DetailTimeTrack').html("");
                    $('#DetailTimeTrackEmployee').html("");
                    $('#EmpPoint').hide();
                    BolPointerListTT = "FALSE";
                },
                success: function (xaxa) {
                    $('#ResultTimeTrack').html("");
                    $('#ResultTimeTrack').hide();
                    $('#ResultTimeTrack').html(xaxa);
                    $('#ResultTimeTrack').fadeIn('fast');
                    $("#ContentLoadingTT").remove();
                    $('#EmpPoint').hide();
                    DETAIL_TIMETRACK_EMPLOYEE();
                    BolPointerListTT = "TRUE";
                },
                error: function () {
                    alert("Request cannot proceed!");
                    $('#ResultTimeTrack').html("");
                    $("#ContentLoadingTT").remove();
                    BolPointerListTT = "TRUE";
                }
            });
        }
        else {
            return false;
        }
    });
}
function DETAIL_TIMETRACK_LEADER()
{
    var BolPointerDetailTT = "TRUE";
    $(".FloatTT").click(function () {
        if (BolPointerDetailTT == "TRUE") {
            $("#ListTableProjectPM tr").removeClass('PointerListSelected');
            $(this).closest('.FloatTT').addClass("PointerListSelected");
            var FloatData = $(this).data('float');
            var formdata = new FormData();
            formdata.append("ValFloat", FloatData);
            $.ajax({
                url: 'project/pointachievement/detailttleader.php',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: formdata,
                type: 'post',
                beforeSend: function () {
                    $("html, body").animate({ scrollTop: $("#DetailProject").offset().top - 20 }, "fast");
                    $('#DetailProject').html("");
                    $('#DetailProject').html("");
                    $("#DetailProject").before('<div class="col-sm-12" id="ContentLoadingTT"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                    $('#DetailProject').html("");
                    $("#TempFilter2").html("");
                    $("#DetailProject").html("");
                    $('#DetailProject').html("");
                    $('#EmpPoint').hide();
                    BolPointerDetailTT = "FALSE";
                },
                success: function (xaxa) {
                    $('#DetailProject').html("");
                    $('#DetailProject').hide();
                    $('#DetailProject').html(xaxa);
                    $('#DetailProject').fadeIn('fast');
                    $("#ContentLoadingTT").remove();
                    $('#EmpPoint').hide();
                    BolPointerDetailTT = "TRUE";
                },
                error: function () {
                    alert("Request cannot proceed!");
                    $('#DetailProject').html("");
                    $("#ContentLoadingTT").remove();
                    BolPointerDetailTT = "TRUE";
                }
            });
        }
        else {
            return false;
        }
    });
}
function DETAIL_TIMETRACK_EMPLOYEE()
{
    var BolPointerDetailTT = "TRUE";
    $(".FloatTT").click(function () {
        if (BolPointerDetailTT == "TRUE")
        {
            $("#ListSummaryTT tr").removeClass('PointerListSelected');
            $(this).closest('.FloatTT').addClass("PointerListSelected");
            var FloatData = $(this).data('float');
            var formdata = new FormData();
            formdata.append("ValFloat", FloatData);
            $.ajax({
                url: 'project/pointachievement/detailttemployee.php',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: formdata,
                type: 'post',
                beforeSend: function () {
                    $("html, body").animate({ scrollTop: $("#DetailTimeTrackEmployee").offset().top - 20 }, "fast");
                    $('#DetailTimeTrackEmployee').html("");
                    $('#DetailTimeTrackEmployee').html("");
                    $("#DetailTimeTrackEmployee").before('<div class="col-sm-12" id="ContentLoadingTT"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                    $('#DetailTimeTrackEmployee').html("");
                    $("#TempFilter3").html("");
                    $("#DetailProject").html("");
                    $('#DetailTimeTrackEmployee').html("");
                    $('#EmpPoint').hide();
                    BolPointerDetailTT = "FALSE";
                },
                success: function (xaxa) {
                    $('#DetailTimeTrackEmployee').html("");
                    $('#DetailTimeTrackEmployee').hide();
                    $('#DetailTimeTrackEmployee').html(xaxa);
                    $('#DetailTimeTrackEmployee').fadeIn('fast');
                    $("#ContentLoadingTT").remove();
                    $('#EmpPoint').hide();
                    BolPointerDetailTT = "TRUE";
                },
                error: function () {
                    alert("Request cannot proceed!");
                    $('#DetailTimeTrackEmployee').html("");
                    $("#ContentLoadingTT").remove();
                    BolPointerDetailTT = "TRUE";
                }
            });
        }
        else
        {
            return false;
        }
    });
}