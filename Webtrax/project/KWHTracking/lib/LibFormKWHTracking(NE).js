$(document).ready(function () {
    $(".btn-labeled").click(function(){
        var IDName = $(this).closest(".btn-labeled").data("button-title");
        switch (IDName)
        {
            case "ImportData":
                var NewPage = 'project/kwhtracking/formimportkwhtrackingpsl.php';
                break;
            case "ViewData":
                var NewPage = 'project/kwhtracking/tablekwhtrackingpsl.php';
                break;
            case "ViewChart":
                var NewPage = 'project/kwhtracking/chartkwhtrackingv2.php';
                break;
            case "GenerateData":
                var NewPage = 'project/kwhtracking/formgeneratedatakwhtrackingpsl.php';
                break;
            default:
                alert("Not found!");
                break;
        }
        $.ajax({
            url: NewPage,
            data: 'data=' + IDName,
            dataType: 'html',
            cache: false,
            type: "POST",
            beforeSend: function () {
                $("#content-div").append('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                $(".btn-bigger").attr('disabled', true);
            },
            success: function (xaxa) {
                $("#ContentLoading").remove();
                $('#content-div').hide();
                $('#content-div').html("");
                $('#content-div').html(xaxa);
                $('#content-div').fadeIn('fast');
                if (IDName == "ImportData") {
                    CheckFile();
                    CloseSubmit();
                    DownloadTemplate();
                    GoToGenerate();
                    AddOneData();
                }
                if (IDName == "ViewData") {
                    $("#TableDataKWHTracking").DataTable({ "pagingType": "simple" });
                    ViewResult();
                }
                if (IDName == "ViewChart") {
                    ViewChart();
                }
                if (IDName == "GenerateData") {
                    GenerateData();
                }
            },
            error: function () {
                $(".btn-bigger").attr('disabled', false);
                $("#ContentLoading").remove();
                alert("Request cannot proceed! Try Again!");
            }
        });
    });
});
function CheckFile()
{
    $('#InputFile').bind("change", function () {
        var selected_file_name = $(this).val();
        if (selected_file_name.length > 0) {
            $("#BtnSubmit").prop('disabled', false);
        }
        else {
            $("#BtnSubmit").prop('disabled', true);
        }
    });
    $('#InputDate').datepicker({
        format: "mm/dd/yyyy",
        autoclose: "true",
        todayHighlight: "true"
    });
    $('#InputUsage').keypress(function (e) {
        if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
            e.preventDefault();
        }
    });
}
function CloseSubmit()
{
    $("#FormImportKWHTracking").submit(function () {
        $("#BtnSubmit").prop('disabled', true);
    });
}
function DownloadTemplate() {
    $("#DownloadTemplate").click(function () {
        window.location.href = 'project/kwhtracking/src/srcdownloadtemplatekwhtrackingpsl.php?accs=1';
    });
}
function ViewResult()
{
    $('#txtFilterTanggal1').datepicker({
        format: "mm/dd/yyyy",
        autoclose: "true",
        todayHighlight: "true"
    });
    $('#txtFilterTanggal2').datepicker({
        format: "mm/dd/yyyy",
        autoclose: "true",
        todayHighlight: "true"
    });
    $("#BtnDownloadResult").click(function(){
        var stringval = $("#ContentResult h4").text();
        window.location.href = 'project/kwhtracking/src/srcdownloadkwhtrackingpsl.php?str=' + stringval + '&&accs=1';
    });
    $("#BtnViewTable").click(function () {
        var Date1 = $("#txtFilterTanggal1").val();
        var Date2 = $("#txtFilterTanggal2").val();
        if (Date.parse(Date1) > Date.parse(Date2)) {
            alert("Pilih tanggal dengan benar!");
            return false;
        }        
        var formdata = new FormData();
        formdata.append('ValDateStart', Date1);
        formdata.append('ValDateEnd', Date2);
        $.ajax({
            url: 'project/kwhtracking/tablekwhtrackingajaxpsl.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#BtnViewTable").attr('disabled', true);
                $("#ContentResult").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                $('#ContentResult').html("");
            },
            success: function (xaxa) {
                $('#ContentResult').html("");
                $('#ContentResult').hide();
                $('#ContentResult').html(xaxa);
                $('#ContentResult').fadeIn('fast');
                $("#TableDataKWHTracking").DataTable({ "pagingType": "simple"});
                $("#ContentLoading").remove();
                $("#BtnViewTable").blur();
                $("#BtnViewTable").attr('disabled', false);
                $("#BtnDownloadResult").click(function () {
                    var stringval = $("#ContentResult h4").text();
                    window.location.href = 'project/kwhtracking/src/srcdownloadkwhtrackingpsl.php?str=' + stringval + '&&accs=1';
                });
            },
            error: function () {              
                alert("Request cannot proceed!");
                $("#BtnViewTable").blur();
                $('#ContentResult').html("");
                $("#ContentLoading").remove();
                $("#BtnViewTable").attr('disabled', false);
            }
        });
    });
}
function ViewChart()
{
    $('#txtFilterTanggal1C').datepicker({
        format: "mm/dd/yyyy",
        autoclose: "true",
        todayHighlight: "true"
    });
    $('#txtFilterTanggal2C').datepicker({
        format: "mm/dd/yyyy",
        autoclose: "true",
        todayHighlight: "true"
    });
    $("#BtnViewChart1").click(function () {
        var Date1 = $("#txtFilterTanggal1C").val();
        var Date2 = $("#txtFilterTanggal2C").val();
        if (Date.parse(Date1) > Date.parse(Date2)) {
            alert("Pilih tanggal dengan benar!");
            return false;
        }
        else
        {
            var formdata = new FormData();
            formdata.append('ValDateStart', Date1);
            formdata.append('ValDateEnd', Date2);
            formdata.append('ValCategory', "1");
            $.ajax({
                url: 'project/kwhtracking/chartkwhtrackingajax.php',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: formdata,
                type: 'post',
                beforeSend: function () {
                    $("#BtnViewChart1").attr('disabled', true);
                    $("#BtnViewChart2").attr('disabled', true);
                    $("#BtnViewChart3").attr('disabled', true);
                    $("#ContentResultChart").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                    $('#ContentResultChart').html("");
                },
                success: function (xaxa) {
                    $('#ContentResultChart').html("");
                    $('#ContentResultChart').hide();
                    $('#ContentResultChart').html(xaxa);
                    $('#ContentResultChart').fadeIn('fast');
                    $("#ContentLoading").remove();
                    $("#BtnViewChart1").blur();
                    $("#BtnViewChart1").attr('disabled', false);
                    $("#BtnViewChart2").attr('disabled', false);
                    $("#BtnViewChart3").attr('disabled', false);
                },
                error: function () {
                    alert("Request cannot proceed!");
                    $("#BtnViewChart1").blur();
                    $('#ContentResultChart').html("");
                    $("#ContentLoading").remove();
                    $("#BtnViewChart1").attr('disabled', false);
                    $("#BtnViewChart2").attr('disabled', false);
                    $("#BtnViewChart3").attr('disabled', false);
                }
            });
        }
    });
    $("#BtnViewChart2").click(function () {
        var ValMonth = $("#MonthTrack").val();
        var formdata = new FormData();
        formdata.append('ValMonth', ValMonth);
        formdata.append('ValCategory', "2");
        $.ajax({
            url: 'project/kwhtracking/chartkwhtrackingajax.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#BtnViewChart1").attr('disabled', true);
                $("#BtnViewChart2").attr('disabled', true);
                $("#BtnViewChart3").attr('disabled', true);
                $("#ContentResultChart").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                $('#ContentResultChart').html("");
            },
            success: function (xaxa) {
                $('#ContentResultChart').html("");
                $('#ContentResultChart').hide();
                $('#ContentResultChart').html(xaxa);
                $('#ContentResultChart').fadeIn('fast');
                $("#ContentLoading").remove();
                $("#BtnViewChart1").blur();
                $("#BtnViewChart1").attr('disabled', false);
                $("#BtnViewChart2").attr('disabled', false);
                $("#BtnViewChart3").attr('disabled', false);
            },
            error: function () {
                alert("Request cannot proceed!");
                $("#BtnViewChart1").blur();
                $('#ContentResultChart').html("");
                $("#ContentLoading").remove();
                $("#BtnViewChart1").attr('disabled', false);
                $("#BtnViewChart2").attr('disabled', false);
                $("#BtnViewChart3").attr('disabled', false);
            }
        });
    });
    $("#BtnViewChart3").click(function () {
        var ValYear = $("#YearTrack").val();
        var formdata = new FormData();
        formdata.append('ValYear', ValYear);
        formdata.append('ValCategory', "3");
        $.ajax({
            url: 'project/kwhtracking/chartkwhtrackingajax.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#BtnViewChart1").attr('disabled', true);
                $("#BtnViewChart2").attr('disabled', true);
                $("#BtnViewChart3").attr('disabled', true);
                $("#ContentResultChart").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                $('#ContentResultChart').html("");
            },
            success: function (xaxa) {
                $('#ContentResultChart').html("");
                $('#ContentResultChart').hide();
                $('#ContentResultChart').html(xaxa);
                $('#ContentResultChart').fadeIn('fast');
                $("#ContentLoading").remove();
                $("#BtnViewChart1").blur();
                $("#BtnViewChart1").attr('disabled', false);
                $("#BtnViewChart2").attr('disabled', false);
                $("#BtnViewChart3").attr('disabled', false);
            },
            error: function () {
                alert("Request cannot proceed!");
                $("#BtnViewChart1").blur();
                $('#ContentResultChart').html("");
                $("#ContentLoading").remove();
                $("#BtnViewChart1").attr('disabled', false);
                $("#BtnViewChart2").attr('disabled', false);
                $("#BtnViewChart3").attr('disabled', false);
            }
        });
    });


    $("#BtnViewChartH").click(function () {
        var formdata = new FormData();
        formdata.append('ValCategory', "1");
        $.ajax({
            url: 'project/kwhtracking/chartkwhtrackingajaxv2.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#BtnViewChartH").attr('disabled', true);
                $("#BtnViewChartM").attr('disabled', true);
                $("#BtnViewChartB").attr('disabled', true);
                $("#BtnViewChartT").attr('disabled', true);
                $("#ContentResultChart").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                $('#ContentResultChart').html("");
            },
            success: function (xaxa) {
                $('#ContentResultChart').html("");
                $('#ContentResultChart').hide();
                $('#ContentResultChart').html(xaxa);
                $('#ContentResultChart').fadeIn('fast');
                $("#ContentLoading").remove();
                $("#BtnViewChartH").blur();
                $("#BtnViewChartH").attr('disabled', false);
                $("#BtnViewChartM").attr('disabled', false);
                $("#BtnViewChartB").attr('disabled', false);
                $("#BtnViewChartT").attr('disabled', false);
            },
            error: function () {
                alert("Request cannot proceed!");
                $("#BtnViewChartH").blur();
                $('#ContentResultChart').html("");
                $("#ContentLoading").remove();
                $("#BtnViewChartH").attr('disabled', false);
                $("#BtnViewChartM").attr('disabled', false);
                $("#BtnViewChartB").attr('disabled', false);
                $("#BtnViewChartT").attr('disabled', false);
            }
        });
    });
    $("#BtnViewChartM").click(function () {
        var formdata = new FormData();
        formdata.append('ValCategory', "2");
        $.ajax({
            url: 'project/kwhtracking/chartkwhtrackingajaxv2.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#BtnViewChartH").attr('disabled', true);
                $("#BtnViewChartM").attr('disabled', true);
                $("#BtnViewChartB").attr('disabled', true);
                $("#BtnViewChartT").attr('disabled', true);
                $("#ContentResultChart").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                $('#ContentResultChart').html("");
            },
            success: function (xaxa) {
                $('#ContentResultChart').html("");
                $('#ContentResultChart').hide();
                $('#ContentResultChart').html(xaxa);
                $('#ContentResultChart').fadeIn('fast');
                $("#ContentLoading").remove();
                $("#BtnViewChartM").blur();
                $("#BtnViewChartH").attr('disabled', false);
                $("#BtnViewChartM").attr('disabled', false);
                $("#BtnViewChartB").attr('disabled', false);
                $("#BtnViewChartT").attr('disabled', false);
            },
            error: function () {
                alert("Request cannot proceed!");
                $("#BtnViewChartM").blur();
                $('#ContentResultChart').html("");
                $("#ContentLoading").remove();
                $("#BtnViewChartH").attr('disabled', false);
                $("#BtnViewChartM").attr('disabled', false);
                $("#BtnViewChartB").attr('disabled', false);
                $("#BtnViewChartT").attr('disabled', false);
            }
        });
    });
    $("#BtnViewChartB").click(function () {
        var formdata = new FormData();
        formdata.append('ValCategory', "3");
        $.ajax({
            url: 'project/kwhtracking/chartkwhtrackingajaxv2.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#BtnViewChartH").attr('disabled', true);
                $("#BtnViewChartM").attr('disabled', true);
                $("#BtnViewChartB").attr('disabled', true);
                $("#BtnViewChartT").attr('disabled', true);
                $("#ContentResultChart").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                $('#ContentResultChart').html("");
            },
            success: function (xaxa) {
                $('#ContentResultChart').html("");
                $('#ContentResultChart').hide();
                $('#ContentResultChart').html(xaxa);
                $('#ContentResultChart').fadeIn('fast');
                $("#ContentLoading").remove();
                $("#BtnViewChartB").blur();
                $("#BtnViewChartH").attr('disabled', false);
                $("#BtnViewChartM").attr('disabled', false);
                $("#BtnViewChartB").attr('disabled', false);
                $("#BtnViewChartT").attr('disabled', false);
            },
            error: function () {
                alert("Request cannot proceed!");
                $("#BtnViewChartB").blur();
                $('#ContentResultChart').html("");
                $("#ContentLoading").remove();
                $("#BtnViewChartH").attr('disabled', false);
                $("#BtnViewChartM").attr('disabled', false);
                $("#BtnViewChartB").attr('disabled', false);
                $("#BtnViewChartT").attr('disabled', false);
            }
        });
    });
    $("#BtnViewChartT").click(function () {
        var formdata = new FormData();
        formdata.append('ValCategory', "4");
        $.ajax({
            url: 'project/kwhtracking/chartkwhtrackingajaxv2.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#BtnViewChartH").attr('disabled', true);
                $("#BtnViewChartM").attr('disabled', true);
                $("#BtnViewChartB").attr('disabled', true);
                $("#BtnViewChartT").attr('disabled', true);
                $("#ContentResultChart").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                $('#ContentResultChart').html("");
            },
            success: function (xaxa) {
                $('#ContentResultChart').html("");
                $('#ContentResultChart').hide();
                $('#ContentResultChart').html(xaxa);
                $('#ContentResultChart').fadeIn('fast');
                $("#ContentLoading").remove();
                $("#BtnViewChartT").blur();
                $("#BtnViewChartH").attr('disabled', false);
                $("#BtnViewChartM").attr('disabled', false);
                $("#BtnViewChartB").attr('disabled', false);
                $("#BtnViewChartT").attr('disabled', false);
            },
            error: function () {
                alert("Request cannot proceed!");
                $("#BtnViewChartT").blur();
                $('#ContentResultChart').html("");
                $("#ContentLoading").remove();
                $("#BtnViewChartH").attr('disabled', false);
                $("#BtnViewChartM").attr('disabled', false);
                $("#BtnViewChartB").attr('disabled', false);
                $("#BtnViewChartT").attr('disabled', false);
            }
        });
    });
}

function GenerateData() {
    $('#txtFilterTanggal1').datepicker({
        format: "mm/dd/yyyy",
        autoclose: "true",
        todayHighlight: "true"
    });
    $('#txtFilterTanggal2').datepicker({
        format: "mm/dd/yyyy",
        autoclose: "true",
        todayHighlight: "true"
    });

    $("#BtnGenerate").click(function () {
        var Date1 = $("#txtFilterTanggal1").val();
        var Date2 = $("#txtFilterTanggal2").val();
        if (Date.parse(Date1) > Date.parse(Date2)) {
            alert("Pilih tanggal dengan benar!");
            return false;
        }
        var formdata = new FormData();
        formdata.append('ValDateStart', Date1);
        formdata.append('ValDateEnd', Date2);
        $.ajax({
            url: 'project/kwhtracking/src/srcgeneratekwhtrackingpsl.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#BtnGenerate").attr('disabled', true);
                $("#ContentResult").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                $('#ContentResult').html("");
            },
            success: function (xaxa) {
                $('#ContentResult').html("");
                $('#ContentResult').hide();
                $('#ContentResult').html(xaxa);
                $('#ContentResult').fadeIn('fast');
                $("#ContentLoading").remove();
                $("#BtnGenerate").blur();
                $("#BtnGenerate").attr('disabled', false);
            },
            error: function () {
                alert("Request cannot proceed!");
                $("#BtnGenerate").blur();
                $('#ContentResult').html("");
                $("#ContentLoading").remove();
                $("#BtnGenerate").attr('disabled', false);
            }
        });
    });
}
function GoToGenerate() {
    $("#BtnMove").click(function () {
        $.ajax({
            url: 'project/kwhtracking/formgeneratedatakwhtrackingpsl.php',
            data: 'data=GenerateData',
            dataType: 'html',
            cache: false,
            type: "POST",
            beforeSend: function () {
                $("#content-div").append('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                $("#BtnSubmit").attr('disabled', true);
                $("#BtnAdd").attr('disabled', true);
                $("#BtnMove").attr('disabled', true);
                $("#BtnMove").text("Please wait..");
            },
            success: function (xaxa) {
                $("#ContentLoading").remove();
                $('#content-div').hide();
                $('#content-div').html("");
                $('#content-div').html(xaxa);
                $('#content-div').fadeIn('fast');
                GenerateData();
            },
            error: function () {
                $("#ContentLoading").remove();
                alert("Request cannot proceed! Try Again!");
            }
        });
    });
}

function AddOneData() {
    $("#BtnAdd").click(function () {
        var ValDateSelected = $("#InputDate").val();
        var ValUsage = $("#InputUsage").val();
        if (ValUsage.trim() == "") {
            $("#InputUsage").focus();
            return false;
        }
        var formdata = new FormData();
        formdata.append('InputDate', ValDateSelected);
        formdata.append('InputUsage', ValUsage);
        $.ajax({
            url: 'project/kwhtracking/src/srcaddnewkwhtrackingpsl.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#BtnAdd").attr('disabled', true);
                $("#BtnSubmit").attr('disabled', true);
                $("#BtnMove").attr('disabled', true);
                $('#ResultMsg').html("");
                $("#ContentLoadingAdd").remove();
                $("#ResultMsg").before('<div class="col-sm-12" id="ContentLoadingAdd"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                $('#ResultMsg').html("");
            },
            success: function (xaxa) {
                $('#ResultMsg').html("");
                $('#ResultMsg').hide();
                $('#ResultMsg').html(xaxa);
                $('#ResultMsg').fadeIn('fast');
                $("#ContentLoadingAdd").remove();
                $("#BtnAdd").blur();
                $("#BtnAdd").attr('disabled', false);
                $("#BtnSubmit").attr('disabled', false);
                $("#BtnMove").attr('disabled', false);
                $("#InputUsage").val("");
                ReloadTable();
            },
            error: function () {
                alert("Request cannot proceed!");
                $("#BtnAdd").blur();
                $('#ResultMsg').html("");
                $("#ContentLoadingAdd").remove();
                $("#BtnAdd").attr('disabled', false);
                $("#BtnSubmit").attr('disabled', false);
                $("#BtnMove").attr('disabled', false);
            }
        });
    });
}
function ReloadTable() {
    $.ajax({
        url: 'project/kwhtracking/src/srcreloadtableresultnewkwhtrackingpsl.php',
        data: 'data=1',
        dataType: 'html',
        cache: false,
        type: "POST",
        beforeSend: function () {
            $('#TableTopData').html("");
        },
        success: function (xaxa) {
            $('#TableTopData').hide();
            $('#TableTopData').html("");
            $('#TableTopData').html(xaxa);
            $('#TableTopData').fadeIn('fast');
        },
        error: function () {
            $("#TableTopData").html("");
        }
    });
}
function GoToGenerate2()
{
    $.ajax({
        url: 'project/kwhtracking/formgeneratedatakwhtrackingpsl.php',
        data: 'data=GenerateData',
        dataType: 'html',
        cache: false,
        type: "POST",
        beforeSend: function () {
            $("#content-div").append('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
            $("#BtnSubmit").attr('disabled', true);
            $("#BtnAdd").attr('disabled', true);
            $("#BtnMove").attr('disabled', true);
            $("#BtnMove").text("Please wait..");
        },
        success: function (xaxa) {
            $("#ContentLoading").remove();
            $('#content-div').hide();
            $('#content-div').html("");
            $('#content-div').html(xaxa);
            $('#content-div').fadeIn('fast');
            GenerateData();
        },
        error: function () {
            $("#ContentLoading").remove();
            alert("Request cannot proceed! Try Again!");
        }
    });
}