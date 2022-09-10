$(document).ready(function () {
    $('#TableDataListBarcodeSementara').dataTable({
        "pagingType": "simple"
    });
    $('#TableDataListBarcodeProses').dataTable({
        "pagingType": "simple"
    });
    $("#InputID").focus();
    $('#InputID').keypress(function (e) {
        if (e.which == 13) {
            $("#InfoNotes").val("");
            var ValInput = $('#InputID').val().trim().toUpperCase();
            var CheckInput = $.isNumeric(ValInput);
            if (ValInput == "")
            {
                $("#InputID").focus();
                return false;
            }
            switch (ValInput)
            {
                case "START":
                    STARTTIMETRACK();           
                    break;
                case "STOP":
                    STOPTIMETRACK();
                    break;
                default:
                    if (CheckInput == true)
                    {
                        CHECK_BARCODE(ValInput);
                    }
                    else
                    {
                        $("#InfoNotes").val("Barcode tidak ditemukan!");
                        $("#InputID").val("");
                        $("#InputID").focus();
                    }
            }
        }
    });
});
function CHECK_BARCODE(InputBarcode)
{
    var GroupNo = $("#TableIdentitasKaryawan tr:nth-child(2) td:nth-child(3)").text();
    var Activity = $("#TableIdentitasKaryawan tr:nth-child(6) td:nth-child(3)").text();
    var SubActivity = $("#TableIdentitasKaryawan tr:nth-child(7) td:nth-child(3)").text();
    var formdata = new FormData();
    formdata.append('ValBarcode', InputBarcode);
    formdata.append('ValGroupNo', GroupNo);
    formdata.append('ValActivity', Activity);
    formdata.append('ValSubActivity', SubActivity);
    $.ajax({
        url: 'src/addbarcodetotemporarylist.php',
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        data: formdata,
        type: 'post',
        beforeSend: function () {
            $("#InfoNotes").val("");
            $('#ResultCheck').html("");
            $("#LabelNotes").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
            $("#InputID").attr('disabled', true);
        },
        success: function (xaxa) {
            $('#ResultCheck').html("");
            $('#ResultCheck').hide();
            $('#ResultCheck').html(xaxa);
            $('#ResultCheck').fadeIn('fast');
            $("#ContentLoading").remove();
            $('#ResultCheck').html("");
            $("#InputID").val("");
            $("#InputID").attr('disabled', false);
            $("#InputID").focus();
        },
        error: function () {
            $("#InfoNotes").val("Request cannot proceed!");
            $('#ResultCheck').html("");
            $("#ContentLoading").remove();
            $("#InputID").val("");
            $("#InputID").attr('disabled', false);
            $("#InputID").focus();
        }
    });
}
function STARTTIMETRACK()
{
    var TableRow = $("#TableDataListBarcodeSementara").DataTable().rows().count();
    if (TableRow != "0")
    {
        var DataTemp = [];
        $('#TableDataListBarcodeSementara > tbody > tr').each(function (a, b) {
            var $row = $(b);
            var VarEmployee = $row.find('td:eq(0)').text();
            var VarWO = $row.find('td:eq(1)').text();
            var VarProduct = $row.find('td:eq(2)').text();
            var VarOrderType = $row.find('td:eq(3)').text();
            var VarExp = $row.find('td:eq(4)').text();
            var VarActivity = $row.find('td:eq(5)').text();
            var VarSubActivity = $row.find('td:eq(6)').text();
            var VarBarcode = $row.find('td:eq(7)').text();
            var VarGroupNo = $row.find('td:eq(8)').text();
            DataTemp.push({
                "Employee": VarEmployee,
                "WO": VarWO,
                "Product": VarProduct,
                "OrderType": VarOrderType,
                "Exp": VarExp,
                "Activity": VarActivity,
                "SubActivity": VarSubActivity,
                "Barcode": VarBarcode,
                "GroupNo": VarGroupNo
            });

        });
        var DataTemp2 = JSON.stringify(DataTemp);
        var GroupNo = $("#TableIdentitasKaryawan tr:nth-child(2) td:nth-child(3)").text();
        var Activity = $("#TableIdentitasKaryawan tr:nth-child(6) td:nth-child(3)").text();
        var SubActivity = $("#TableIdentitasKaryawan tr:nth-child(7) td:nth-child(3)").text();
        var formdata = new FormData();
        formdata.append('ValGroupNo', GroupNo);
        formdata.append('ValActivity', Activity);
        formdata.append('ValSubActivity', SubActivity);
        formdata.append('ValScan', DataTemp2);
        $.ajax({
            url: 'src/starttimetracking.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#InfoNotes").val("");
                $('#ResultCheck').html("");
                $("#LabelNotes").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                $("#InputID").attr('disabled', true);
            },
            success: function (xaxa) {
                $('#ResultCheck').html("");
                $('#ResultCheck').hide();
                $('#ResultCheck').html(xaxa);
                $('#ResultCheck').fadeIn('fast');
                $("#ContentLoading").remove();
                $('#ResultCheck').html("");
                $("#InputID").val("");
                $("#InputID").attr('disabled', false);
                $("#InputID").focus();
            },
            error: function () {
                $("#InfoNotes").val("Request cannot proceed!");
                $('#ResultCheck').html("");
                $("#ContentLoading").remove();
                $("#InputID").val("");
                $("#InputID").attr('disabled', false);
                $("#InputID").focus();
            }
        });
    }
    else
    {
        $("#InfoNotes").val("List sementara barcode masih kosong!");
        $("#InputID").val("");
        $("#InputID").focus();
    }
}
function STOPTIMETRACK() 
{
    var TableRow = $("#TableDataListBarcodeProses").DataTable().rows().count();
    if (TableRow != "0") {
        var GroupNo = $("#TableIdentitasKaryawan tr:nth-child(2) td:nth-child(3)").text();
        var Activity = $("#TableIdentitasKaryawan tr:nth-child(6) td:nth-child(3)").text();
        var SubActivity = $("#TableIdentitasKaryawan tr:nth-child(7) td:nth-child(3)").text();
        var formdata = new FormData();
        formdata.append('ValGroupNo', GroupNo);
        formdata.append('ValActivity', Activity);
        formdata.append('ValSubActivity', SubActivity);
        $.ajax({
            url: 'src/stoptimetracking.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#InfoNotes").val("");
                $('#ResultCheck').html("");
                $("#LabelNotes").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                $("#InputID").attr('disabled', true);
            },
            success: function (xaxa) {
                $('#ResultCheck').html("");
                $('#ResultCheck').hide();
                $('#ResultCheck').html(xaxa);
                $('#ResultCheck').fadeIn('fast');
                $("#ContentLoading").remove();
                $('#ResultCheck').html("");
                $("#InputID").val("");
                $("#InputID").attr('disabled', false);
                $("#InputID").focus();
            },
            error: function () {
                $("#InfoNotes").val("Request cannot proceed!");
                $('#ResultCheck').html("");
                $("#ContentLoading").remove();
                $("#InputID").val("");
                $("#InputID").attr('disabled', false);
                $("#InputID").focus();
            }
        });
    }
    else {
        $("#InfoNotes").val("Tidak ada proses berjalan!");
        $("#InputID").val("");
        $("#InputID").focus();
    }
}


