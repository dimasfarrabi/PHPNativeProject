var DateSelected;
var Locations;
var ValInputDate;
$(document).ready(function () {
    var d = new Date();
    if(d.getDate() < 10){dayEndDate = "0" + d.getDate();}else{dayEndDate = d.getDate();}
    if((d.getMonth() + 1) < 10){monthEndDate = "0" + (d.getMonth() + 1);}else{monthEndDate = (d.getMonth() + 1);}
    var EndDate = d.getFullYear() + "/" + monthEndDate + "/" + dayEndDate;
    $('#InputDate').datetimepicker({
        lang:'en',
        timepicker:false,
        format:'m/d/Y',
        formatDate:'m/d/Y',
        theme:'dark',
        maxDate:EndDate
    });
    $('#InputUsage').keypress(function (e) {
        if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
            e.preventDefault();
        }
    });
    Locations = $("#InputLocation").text();
    $("#TempData").text(Locations);
    ValInputDate = $("#InputDate").val();
    $('#InputDate').change(function () {
        DateSelected = $("#InputDate").val();
        if(ValInputDate != DateSelected)
        {
            ValInputDate = DateSelected;
            Locations = $("#InputLocation").text();
            $("#TempData").text(Locations);
            CHECK_DATE();
            LOADDATA();
        }
    });
    $("#BtnAdd").click(function () {
        var ResDate = $("#InputDate").val();
        var ResUsage = $("#InputUsage").val();
        var ResLocation = $("#TempData").text();    
        if (ResUsage == "" || ResUsage == ".") {
            $("#InputUsage").focus();
            return false;
        }
        var formdata = new FormData();
        formdata.append("ValDate", ResDate);
        formdata.append("ValUsage", ResUsage);
        formdata.append("ValLocation", ResLocation);
        $.ajax({
            url: 'project/Security/src/srcAddNewDataKWHTracking.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $('#ResultMsg').html("");
                $("#ResultMsg").before('<div class="col-sm-12 text-center" id="ContentLoading2"><img src="images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                $("#InputDate").attr('disabled', true);
                $("#InputUsage").attr('disabled', true);
                $("#BtnAdd").attr('disabled', true);
            },
            success: function (xaxa) {
                LOADDATA();
                $('#ResultMsg').html("");
                $('#ResultMsg').hide();
                $('#ResultMsg').html(xaxa);
                $('#ResultMsg').fadeIn('fast');
                $("#ContentLoading2").remove();
                $("#InputDate").attr('disabled', false);
            },
            error: function () {
                alert("Request cannot proceed!");
                $('#ResultMsg').html("");
                $("#ContentLoading2").remove();
                $("#InputDate").attr('disabled', false);
                $("#InputUsage").attr('disabled', false);
                $("#BtnAdd").attr('disabled', false);
            }
        });
    });    
});
function CHECK_DATE()
{
    var formdata = new FormData();
    formdata.append("ValDate", DateSelected);
    formdata.append("ValLocation", Locations);
    $.ajax({
        url: 'project/Security/CheckDateKWHTracking.php',
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        data: formdata,
        type: 'post',
        beforeSend: function () {
            $('#ContentResult').html("");
            if($("#ContentLoading").length == 0)
            {
                $("#ContentResult").before('<div class="col-sm-12 text-center" id="ContentLoading"><img src="images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
            }
            $("#InputDate").attr('disabled', true);
        },
        success: function (xaxa) {
            $('#ContentResult').html("");
            $('#ContentResult').hide();
            $('#ContentResult').html(xaxa);
            $('#ContentResult').fadeIn('fast');
            $("#ContentLoading").remove();
            $("#InputDate").attr('disabled', false);
            ADD_DATA();
        },
        error: function () {
            alert("Request cannot proceed!");
            $('#ContentResult').html("");
            $("#ContentLoading").remove();
            $("#InputDate").attr('disabled', false);
        }
    });
}
function ADD_DATA()
{
    $('#InputUsage').keypress(function (e) {
        if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
            e.preventDefault();
        }
    });
    $("#BtnAdd").click(function () {
        var ResDate = $("#InputDate").val();
        var ResUsage = $("#InputUsage").val();
        var ResLocation = $("#TempData").text();

        if (ResUsage == "" || ResUsage == ".")
        {
            $("#InputUsage").focus();
            return false;
        }
        var formdata = new FormData();
        formdata.append("ValDate", ResDate);
        formdata.append("ValUsage", ResUsage);
        formdata.append("ValLocation", ResLocation);
        $.ajax({
            url: 'project/Security/src/srcAddNewDataKWHTracking.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $('#ResultMsg').html("");
                $("#ResultMsg").before('<div class="col-sm-12 text-center" id="ContentLoading2"><img src="images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                $('#ResultMsg').html("");
                $("#InputDate").attr('disabled', true);
                $("#InputUsage").attr('disabled', true);
                $("#BtnAdd").attr('disabled', true);
            },
            success: function (xaxa) {
                LOADDATA();
                $('#ResultMsg').html("");
                $('#ResultMsg').hide();
                $('#ResultMsg').html(xaxa);
                $('#ResultMsg').fadeIn('fast');
                $("#ContentLoading2").remove();
                $("#InputDate").attr('disabled', false);
            },
            error: function () {
                alert("Request cannot proceed!");
                $('#ResultMsg').html("");
                $("#ContentLoading2").remove();
                $("#InputDate").attr('disabled', false);
                $("#InputUsage").attr('disabled', false);
                $("#BtnAdd").attr('disabled', false);
            }
        });
    });   
}
function LOADDATA()
{
    var ResLocation = $("#TempData").text();
    var formdata = new FormData();
    formdata.append("ValLocation", ResLocation);
    $.ajax({
        url: 'project/Security/ListResultKWHTracking.php',
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        data: formdata,
        type: 'post',
        beforeSend: function () {
            $('#TableTopData').html("");
            if($("#ContentLoadingTop").length == 0)
            {
                $("#TableTopData").before('<div class="col-sm-12 text-center" id="ContentLoadingTop"><img src="images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
            }
        },
        success: function (xaxa) {
            $('#TableTopData').html("");
            $('#TableTopData').hide();
            $('#TableTopData').html(xaxa);
            $('#TableTopData').fadeIn('fast');
            $("#ContentLoadingTop").remove();
        },
        error: function () {
            alert("Request cannot proceed!");
            $('#TableTopData').html("");
            $("#ContentLoadingTop").remove();
        }
    });
}