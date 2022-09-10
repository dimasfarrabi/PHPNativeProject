$(document).ready(function () { 
    $("#StockType").on("change", function () {
        var ValLoc = $('#InputCompany').val();
        if(ValLoc == "Pilih Company")
        {
            $('#InputCompany').focus();
            return false;
        }
        if ($("#StockType").val() != "--Pilih Jenis Stock--") {
            if($("#StockType").val().trim() == "Bin Kitting")
            {
                $('#ListGudang').hide();
            }
            else
            {
                var ValLoc = $('#InputCompany').val();
                var formdata = new FormData();
                formdata.append("ValLoc", ValLoc);
                $.ajax({
                    url: 'project/Inventory/FilterGudangKecil.php',
                    dataType: 'text',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formdata,
                    type: 'POST',
                    beforeSend: function () {
                        $('#ListGudang').html("");
                    },
                    success: function(xaxa){
                        $('#ListGudang').hide();
                        $('#ListGudang').html(xaxa);
                        $('#ListGudang').fadeIn('fast');
                        $("#InputGudang").focus();
                        $("#InputCompany").attr('disabled', true);
                    },
                    error: function() {
                        alert('Request cannot proceed!');
                    }
                });
            }
        }
        else
        {
            $("#StockType").focus();
            return false;
        }
    });
    $("#ButtonOK").click(function () { 
        var Loc = $("#InputCompany").val();
        var Stock = $("#StockType").val();
        var Gudang = '';
        if(Loc == "Pilih Company")
        {
            $('#InputCompany').focus();
            return false;
        }
        if(Stock == "Gudang Kecil")
        {
            Gudang = $("#InputGudang").val();
        }
        else if(Stock == "-- Pilih Stock --")
        {
            $('#StockType').focus();
            return false;
        }
        if(Gudang == "-- Pilih Gudang Kecil --")
        {
            $("#InputGudang").focus();
            return false;
        }
        PartNo = '';
        var formdata = new FormData();
        formdata.append("Loc", Loc);
        formdata.append("Stock", Stock);
        formdata.append("Gudang", Gudang);
        $.ajax({
            url: 'project/Inventory/StockOpnameFormV2.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'POST',
            beforeSend: function () {
                $('#FormContent').html("");
                $("#ButtonOK").prop('disabled', true);
                $("#FormContent").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                $('#FormContent').html("");
            },
            success: function(xaxa){
                $('#FormContent').html("");
                $('#FormContent').html(xaxa);
                $('#FormContent').fadeIn('fast');
                $("#ContentLoading").remove();
                $("#InputPartNo").focus();
                $("#ButtonOK").prop('disabled', false);
                $("#BtnProses").attr('disabled', true);
                var FormID = $("#FormID").val().trim();
                var pic = $("#PPICForm").val().trim();
                var Gudang = $("#BinForm").val().trim();
                var SODate = $("#txtFilterTanggal1").val();
                var Lokasi = $("#InputCompany").val();
                var StockType = $("#StockType").val();
                SEARCH_TABLE(Loc,Gudang,PartNo,Stock);
                TEMP_TABLE(PartNo,FormID,pic,Gudang,SODate,Lokasi,StockType);
                $('#txtFilterTanggal1').datetimepicker({
                    lang:'en',
                    timepicker:false,
                    format:'Y-m-d',
                    formatDate:'Y-m-d'
                });
                $("#BtnPart").click(function(){
                    var PartNo = $("#InputPartNo").val().trim();
                    SEARCH_TABLE(Lokasi,Gudang,PartNo,StockType);
                });
                $("#InputPartNo").on("keypress", function (e) {
                    if (e.which == 13) {
                        $('#BtnPart').trigger('click');
                    }
                });
            },
            error: function() {
                alert('Request cannot proceed!');
            }
        });
    });
});
function SEARCH_TABLE(Lokasi,BinForm,PartNo,StockType)
{
    var formdata = new FormData();
    formdata.append('PartNo', PartNo);
    formdata.append('StockType', StockType);
    formdata.append('Gudang', BinForm);
    formdata.append('Lokasi', Lokasi);
    $.ajax({
        url: 'project/Inventory/StockOpnameSearchTable.php',
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        data: formdata,
        type: 'post',
        beforeSend: function () {
            $('#SearchTable').html("");
            $("#BtnPart").prop('disabled', true);
            $("#SearchTable").before('<div class="col-sm-6" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
            $('#SearchTable').html("");
        },
        success: function (xaxa) {
            $('#SearchTable').html("");
            $('#SearchTable').html(xaxa);
            $('#SearchTable').fadeIn('fast');
            $("#ContentLoading").remove();
            $("#BtnPart").prop('disabled', false);
            $("#InputPartNo").val('');
            $("#InputPartNo").focus();
            $('#TableStock').DataTable({
                "iDisplayLength": 10,
                "lengthChange": false,
                searching: false,
            });
            $(".BtnChoose").click(function(event){
                var Enc = $(this).data("ecode");
                const arrEnc = Enc.split("*");
                var Val1 = arrEnc[0];
                var Val2 = arrEnc[1];
                var formdata = new FormData();
                formdata.append('PartNo', Val1);
                formdata.append('Qty', Val2);
                formdata.append('Lokasi', Lokasi);
                formdata.append('StockType', StockType);
                formdata.append('Gudang', BinForm);
                $.ajax({
                    url: 'project/Inventory/src2/srcTempTable.php',
                    dataType: 'text',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formdata,
                    type: 'post',
                    beforeSend: function () {
                    },
                    success: function (xaxa) {
                        var Res = xaxa;
                        const arrRes = Res.split(":");
                        if(arrRes[0] == "FALSE"){
                            if(arrRes[1] == "1"){
                                alert('GAGAL MENYIMPAN');
                            }
                            else{
                                alert('PART TIDAK DAPAT DIGUNAKAN DUA KALI');
                            }
                        }
                        else{
                            var markup = '<tr data-idrows="isx" data-erows="'+Val1+'"><td class="text-center">'+Val1+'</td><td class="text-center">'+Val2+'</td><td class="text-center"><input type="text" class="form-control form-control-sm" style="direction: RTL;" value="'+Val2+'" data-id="'+Val1+'" id="FormPart" readonly></td><td class="text-center"><input class="form-check-input checkID" type="checkbox" data-id="'+Val1+'"></td><td class="text-center"><i class="bi bi-trash-fill PointerList" aria-hidden="true" data-bs-toggle="modal" data-ecode="'+Val1+'" data-bs-target="#DeleteTemp" title="Delete"></td></tr>';
                            $("#FormSO tbody").append(markup);
                        }
                        $(".checkID").change(function() {
                            var CheckVal = $(this).data("id");
                            $("#FormPart[data-id='"+CheckVal+"']").attr("readonly", false); 
                        });
                    },
                    error: function () {
                        alert('Request cannot proceed!');
                    }
                });
            });
        },
        error: function () { 
            alert("Request cannot proceed!");
            $('#SearchTable').html("");
            $("#ContentLoading").remove();
            $("#BtnPart").prop('disabled', false);
        }
    });
}
function TEMP_TABLE(PartNo,FormID,pic,BinForm,SODate,Lokasi,StockType)
{
    var formdata = new FormData();
    formdata.append('PartNo', PartNo);
    formdata.append('FormID', FormID);
    formdata.append('pic', pic);
    formdata.append('BinForm', BinForm);
    formdata.append('SODate', SODate);
    formdata.append('Lokasi', Lokasi);
    formdata.append('StockType', StockType);
    $.ajax({
        url: 'project/Inventory/StockOpnameTempTableV2.php',
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        data: formdata,
        type: 'post',
        beforeSend: function () {
            $('#TempTable').html("");
            $("#BtnPart").prop('disabled', true);
            $("#TempTable").before('<div class="col-sm-6" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
            $('#TempTable').html("");
        },
        success: function (xaxa) {
            $('#TempTable').html("");
            $('#TempTable').html(xaxa);
            $('#TempTable').fadeIn('fast');
            $("#ContentLoading").remove();
            $("#BtnPart").prop('disabled', false);
            $("#BtnProses").prop('disabled', false);
            $("#InputPartNo").val('');
            $("#InputPartNo").focus();
            $(".checkID").change(function() {
                var CheckVal = $(this).data("id");
                $("#FormPart[data-id='"+CheckVal+"']").attr("readonly", false); 
            });
            $("#DeleteTemp").on('show.bs.modal', function (event) {
                $("#DeleteBtn2").click(function(){
                    var act = $(event.relatedTarget);
                    var DataCode = act.data('ecode');
                    var formdata = new FormData();
                    formdata.append("ValCode", DataCode);
                    $.ajax({
                        url: 'project/Inventory/src2/srcDeleteTempTable.php',
                        dataType: 'text',
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formdata,
                        type: 'post',
                        beforeSend: function () {
                            $('#DeleteContent').html("");
                            $('#DeleteContent').before('<div class="col-sm-12 d-flex justify-content-center" id="LoadingImg"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                        },
                        success: function (xaxa) {
                            $('#DeleteContent').hide();
                            $('#DeleteContent').html(xaxa);
                            $('#DeleteContent').fadeIn('fast');
                            $('#LoadingImg').remove();
                        },
                        error: function () {
                            alert('Request cannot proceed!');
                            $('#LoadingImg').remove();
                        }
                    });
                });
            });
            $("#BtnProses").click(function(){
                var bool = 'TRUE'; 
                var Lokasi = $("#InputCompany").val();
                var StockType = $("#StockType").val();
                var Gudang = $("#BinForm").val().trim();
                var Pic = $("#PPICForm").val().trim();
                var SODate = $("#txtFilterTanggal1").val();
                var FormID = $("#FormID").val().trim();
                var Header = Lokasi+'*'+StockType+'*'+Gudang+'*'+Pic+'*'+SODate+'*'+FormID;
                var arr = [];
                $("#FormSO tr[data-idrows='isx']").each(function(){
                    var ValPart = $(this).find("td:first").text();
                    var ValNewQty = $(this).find("td:eq(2) input[type='text']").val();
                    if(ValNewQty == ""){
                        alert('Kolom Actual Qty Tidak Boleh Kosong');
                        bool = 'FALSE'; 
                        return false;
                    }
                    bool = 'TRUE';
                    var IsAdjust = $(this).find("td:eq(3) input[type='checkbox']:checked").val();
                    if(IsAdjust == undefined){
                        IsAdjust = 'off';
                    }
                    arr.push(ValPart+'*'+ValNewQty+'*'+IsAdjust); 
                });
                if(bool == 'FALSE'){
                    return false;
                }
                if (confirm("Apakah anda yakin memproses Stock Opname?") == true) {
                    var formdata = new FormData();
                    formdata.append("Header", Header);
                    formdata.append("arr", arr);
                    $.ajax({
                        url: 'project/Inventory/src2/srcSaveTempTable.php',
                        dataType: 'text',
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formdata,
                        type: 'post',
                        beforeSend: function () {
                            $('#ProceedTempTable').html("");
                            $('#ProceedTempTable').before('<div class="col-sm-12 d-flex justify-content-center" id="LoadingImg"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                        },
                        success: function (xaxa) {
                            $('#ProceedTempTable').hide();
                            $('#ProceedTempTable').html(xaxa);
                            $('#ProceedTempTable').fadeIn('fast');
                            $('#LoadingImg').remove();
                        },
                        error: function () {
                            alert('Request cannot proceed!');
                            $('#LoadingImg').remove();
                        }
                    });
                }
            });
        },
        error: function () { 
            alert("Request cannot proceed!");
            $('#TempTable').html("");
            $("#ContentLoading").remove();
        }
    });
} 