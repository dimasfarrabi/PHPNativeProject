$(document).ready(function () {
    $("#BtnBarcodeIn").click(function(){
        PROCESS_IN();
    });
    $("#BtnBarcodeOut").click(function () {
        PROCESS_OUT();
    });
    $("#InputBarcodeIn").keypress(function(e){
        if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)){
            e.preventDefault();
        }
    });
    $("#InputBarcodeIn").on('input', function (e) {
        $(this).val(function (i, v) {
            if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                e.preventDefault();
            }
            return v.replace(/[^0-9]/g, '');
        });
    });
    $("#InputBarcodeIn").on("keypress", function (e) {
        if (e.which == 13) {
            PROCESS_IN();
        }
    });
    $("#InputBarcodeOut").keypress(function (e) {
        if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
            e.preventDefault();
        }
    });
    $("#InputBarcodeOut").on('input', function (e) {
        $(this).val(function (i, v) {
            if ((e.which != 46 || $(this).val().indexOf('.') != -1) && (e.which < 48 || e.which > 57)) {
                e.preventDefault();
            }
            return v.replace(/[^0-9]/g, '');
        });
    });
    $("#InputBarcodeOut").on("keypress", function (e) {
        if (e.which == 13) {
            PROCESS_OUT();
        }
    });
})
function PROCESS_IN()
{
    if ($("#InputBarcodeIn").val().trim() == "")
    {
        $("#InputBarcodeIn").focus();
        return false;
    }
    if (confirm("Apakah anda yakin akan melakukan proses Check In pada barcode ini?") == false)
    {
        $("#InputBarcodeIn").focus();
        return false;
    }
    const LocationInput = $("#FilterLocationIn option:selected").val();
    const AreaInput = $("#FilterAreaWork option:selected").val();
    const TypeBarcodeInput = $("#FilterTypeIn option:selected").val();
    const BarcodeInput = $("#InputBarcodeIn").val().trim(); 
    var formdata = new FormData();
    formdata.append('LocationInput', LocationInput);
    formdata.append('AreaInput', AreaInput);
    formdata.append('TypeBarcodeInput', TypeBarcodeInput);
    formdata.append('BarcodeInput', BarcodeInput);
    $.ajax({
        url: 'project/KaShift/src/srcAddHistoryCheckIn.php',
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        data: formdata,
        type: 'post',
        beforeSend: function () {
            $("#InputBarcodeIn").attr('readonly', true);
            $("#InputBarcodeOut").attr('readonly', true);
            $("#BtnBarcodeIn").attr('disabled', true);
            $("#BtnBarcodeOut").attr('disabled', true);
            $("#BtnBarcodeIn").text("");
            $("#BtnBarcodeIn").append('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span class="visually-hidden"> Loading...</span >');
            $("#InfoBarcodeIn").html("");
        },
        success: function (xaxa) {
            $("#InputBarcodeIn").blur();
            $("#BtnBarcodeIn").blur();
            $("#InputBarcodeIn").attr('readonly', false);
            $("#InputBarcodeOut").attr('readonly', false);
            $("#BtnBarcodeIn").attr('disabled', false);
            $("#BtnBarcodeOut").attr('disabled', false);
            $("#BtnBarcodeIn").html("");
            $("#BtnBarcodeIn").text("Check In");
            $("#InputBarcodeIn").val("");
            $("#InputBarcodeIn").focus();
            var Res = xaxa;
            switch (Res) {
                case "1":
                    $("#InputBarcodeIn").val("");
                    $("#InputBarcodeIn").focus();
                    $("#InfoBarcodeIn").append('<div class="alert alert-danger alert-dismissible" role="alert"><strong>Barcode ' + TypeBarcodeInput + ' [' + BarcodeInput + '] tidak terdaftar!</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                    setTimeout(function () {
                        $("#InfoBarcodeIn").html("");
                    }, 1000 * 5);
                    break;
                case "2":
                    $("#InputBarcodeIn").val("");
                    $("#InputBarcodeIn").focus();
                    $("#InfoBarcodeIn").append('<div class="alert alert-danger alert-dismissible" role="alert"><strong>Tidak ada barcode part yang aktif di barcode material ini!</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                    setTimeout(function () {
                        $("#InfoBarcodeIn").html("");
                    }, 1000 * 5);
                    break;
                case "3":
                    $("#InputBarcodeIn").val("");
                    $("#InputBarcodeIn").focus();
                    $("#InfoBarcodeIn").append('<div class="alert alert-danger alert-dismissible" role="alert"><strong>Part di barcode material ini sudah pernah dicheck in sebelumnya!</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                    setTimeout(function () {
                        $("#InfoBarcodeIn").html("");
                    }, 1000 * 5);
                    break;
                case "4":
                    $("#InputBarcodeIn").val("");
                    $("#InputBarcodeIn").focus();
                    $("#InfoBarcodeIn").append('<div class="alert alert-danger alert-dismissible" role="alert"><strong>Data barcode ini belum tersinkron, mohon hubungi IT untuk melakukan sinkron!</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                    setTimeout(function () {
                        $("#InfoBarcodeIn").html("");
                    }, 1000 * 5);
                    break;
                case "5":
                    $("#InputBarcodeIn").val("");
                    $("#InputBarcodeIn").focus();
                    $("#InfoBarcodeIn").append('<div class="alert alert-danger alert-dismissible" role="alert"><strong>Part ini sudah pernah dicheck in sebelumnya!</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                    setTimeout(function () {
                        $("#InfoBarcodeIn").html("");
                    }, 1000 * 5);
                    break;
                case "OK":
                    $("#InputBarcodeIn").val("");
                    $("#InputBarcodeIn").focus();
                    $("#InfoBarcodeIn").append('<div class="alert alert-success alert-dismissible" role="alert"><strong>Proses check in barcode [' + BarcodeInput + '] berhasil!</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                    setTimeout(function () {
                        $("#InfoBarcodeIn").html("");
                    }, 1000 * 5);
                    REFRESH_LOG(AreaInput);
                    break;
                default:
                    $("#InputBarcodeIn").val("");
                    $("#InputBarcodeIn").focus();
                    break;
            }
        },
        error: function () {
            alert("Request cannot proceed!");
            $("#InfoBarcodeIn").html("");
            $("#InputBarcodeIn").blur();
            $("#BtnBarcodeIn").blur();
            $("#InputBarcodeIn").attr('readonly', false);
            $("#InputBarcodeOut").attr('readonly', false);
            $("#BtnBarcodeIn").attr('disabled', false);
            $("#BtnBarcodeOut").attr('disabled', false);
            $("#BtnBarcodeIn").html("");
            $("#BtnBarcodeIn").text("Check In");
        }
    });
}
function PROCESS_OUT()
{
    if ($("#InputBarcodeOut").val().trim() == "") {
        $("#InputBarcodeOut").focus();
        return false;
    }
    if (confirm("Apakah anda yakin akan melakukan proses Check Out pada barcode ini?") == false) {
        $("#InputBarcodeOut").focus();
        return false;
    }
    const LocationInput = $("#FilterLocationOut option:selected").val();
    const AreaInput = $("#FilterAreaWorkOut option:selected").val();
    const TypeBarcodeInput = $("#FilterTypeOut option:selected").val();
    const BarcodeInput = $("#InputBarcodeOut").val().trim();
    var formdata = new FormData();
    formdata.append('LocationInput', LocationInput);
    formdata.append('AreaInput', AreaInput);
    formdata.append('TypeBarcodeInput', TypeBarcodeInput);
    formdata.append('BarcodeInput', BarcodeInput);
    $.ajax({
        url: 'project/KaShift/src/srcAddHistoryCheckOut.php',
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        data: formdata,
        type: 'post',
        beforeSend: function () {
            $("#InputBarcodeIn").attr('readonly', true);
            $("#InputBarcodeOut").attr('readonly', true);
            $("#BtnBarcodeIn").attr('disabled', true);
            $("#BtnBarcodeOut").attr('disabled', true);
            $("#InfoBarcodeOut").html("");
            $("#BtnBarcodeOut").text("");
            $("#BtnBarcodeOut").append('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span class="visually-hidden"> Loading...</span >');
        },
        success: function (xaxa) {
            $("#InputBarcodeIn").blur();
            $("#BtnBarcodeIn").blur();
            $("#InputBarcodeIn").attr('readonly', false);
            $("#InputBarcodeOut").attr('readonly', false);
            $("#BtnBarcodeIn").attr('disabled', false);
            $("#BtnBarcodeOut").attr('disabled', false);
            $("#InputBarcodeOut").val("");
            $("#InputBarcodeOut").focus();
            $("#BtnBarcodeOut").html("");
            $("#BtnBarcodeOut").text("Check Out");
            var Res = xaxa;
            switch (Res) {
                case "1":
                    $("#InputBarcodeOut").val("");
                    $("#InputBarcodeOut").focus();
                    $("#InfoBarcodeOut").append('<div class="alert alert-danger alert-dismissible" role="alert"><strong>Barcode ' + TypeBarcodeInput + ' [' + BarcodeInput + '] tidak terdaftar!</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                    setTimeout(function () {
                        $("#InfoBarcodeOut").html("");
                    }, 1000 * 5);
                    break;
                case "2":
                    $("#InputBarcodeOut").val("");
                    $("#InputBarcodeOut").focus();
                    $("#InfoBarcodeOut").append('<div class="alert alert-danger alert-dismissible" role="alert"><strong>Tidak ada barcode part yang aktif di barcode material ini!</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                    setTimeout(function () {
                        $("#InfoBarcodeOut").html("");
                    }, 1000 * 5);
                    break;
                case "3":
                    $("#InputBarcodeOut").val("");
                    $("#InputBarcodeOut").focus();
                    $("#InfoBarcodeOut").append('<div class="alert alert-danger alert-dismissible" role="alert"><strong>Part di barcode material ini sudah pernah dicheck out sebelumnya!</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                    setTimeout(function () {
                        $("#InfoBarcodeOut").html("");
                    }, 1000 * 5);
                    break;
                case "4":
                    $("#InputBarcodeOut").val("");
                    $("#InputBarcodeOut").focus();
                    $("#InfoBarcodeOut").append('<div class="alert alert-danger alert-dismissible" role="alert"><strong>Data barcode ini belum tersinkron, mohon hubungi IT untuk melakukan sinkron!</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                    setTimeout(function () {
                        $("#InfoBarcodeOut").html("");
                    }, 1000 * 5);
                    break;
                case "5":
                    $("#InputBarcodeOut").val("");
                    $("#InputBarcodeOut").focus();
                    $("#InfoBarcodeOut").append('<div class="alert alert-danger alert-dismissible" role="alert"><strong>Part ini sudah pernah dicheck out sebelumnya!</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                    setTimeout(function () {
                        $("#InfoBarcodeOut").html("");
                    }, 1000 * 5);
                    break;
                case "6":
                    $("#InputBarcodeOut").val("");
                    $("#InputBarcodeOut").focus();
                    $("#InfoBarcodeOut").append('<div class="alert alert-danger alert-dismissible" role="alert"><strong>Ada part yang belum di check in pada material ini!</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                    setTimeout(function () {
                        $("#InfoBarcodeOut").html("");
                    }, 1000 * 5);
                    break;
                case "7":
                    $("#InputBarcodeOut").val("");
                    $("#InputBarcodeOut").focus();
                    $("#InfoBarcodeOut").append('<div class="alert alert-danger alert-dismissible" role="alert"><strong>Part belum di check in sebelumnya!</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                    setTimeout(function () {
                        $("#InfoBarcodeOut").html("");
                    }, 1000 * 5);
                    break;
                case "OK":
                    $("#InputBarcodeOut").val("");
                    $("#InputBarcodeOut").focus();
                    $("#InfoBarcodeOut").append('<div class="alert alert-success alert-dismissible" role="alert"><strong>Proses check out barcode [' + BarcodeInput + '] berhasil!</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                    setTimeout(function () {
                        $("#InfoBarcodeOut").html("");
                    }, 1000 * 5);
                    REFRESH_LOG(AreaInput);
                    break;
                default:
                    $("#InputBarcodeOut").val("");
                    $("#InputBarcodeOut").focus();
                    break;
            }
        },
        error: function () {
            alert("Request cannot proceed!");
            $("#InfoBarcodeOut").html("");
            $("#InputBarcodeOut").blur();
            $("#BtnBarcodeOut").blur();
            $("#InputBarcodeIn").attr('readonly', false);
            $("#InputBarcodeOut").attr('readonly', false);
            $("#BtnBarcodeIn").attr('disabled', false);
            $("#BtnBarcodeOut").attr('disabled', false);
            $("#BtnBarcodeOut").html("");
            $("#BtnBarcodeOut").text("Check Out");
        }
    });
}
function REFRESH_LOG(Area)
{
    var formdata = new FormData();
    formdata.append('Area', Area);
    $.ajax({
        url: 'project/KaShift/ContentHistoryCheckInOut.php',
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        data: formdata,
        type: 'post',
        beforeSend: function () {
            
        },
        success: function (xaxa) {
            $("#LogHistoryIn").html(xaxa);
        },
        error: function () {
            alert("Request load log history cannot proceed!");
        }
    });
}
