$(document).ready(function () { 
    $("#CompanyIN").focus();
    $("#BtnProceedIn").prop('disabled', true);
    $("#CompanyIN").change(function(){
        $("#ProsesOUT").focus();
    });
    $("#ProsesOUT").change(function(){
        if($("#CompanyIN").val() == "-- Pilih Lokasi --"){
            $("#CompanyIN").focus();
            return false;
        }
        $("#ProsesIN").focus();
    });
    $("#ProsesIN").change(function(){
        if($("#ProsesOUT").val() == "-- Pilih Proses --"){
            $("#ProsesOUT").focus();
            return false;
        }
        $("#ScanIN").focus();
    });
    $("#ScanIN").change(function(){ 
        if($("#CompanyIN").val() == "-- Pilih Lokasi --"){
            $("#CompanyIN").focus();
            $("#ScanIN").val('');
            return false;
        }
        if($("#ProsesIN").val() == "-- Pilih Proses --"){
            $("#ProsesIN").focus();
            $("#ScanIN").val('');
            return false;
        }
        if($("#ProsesOUT").val() == "-- Pilih Proses --"){
            $("#ProsesOUT").focus();
            $("#ScanIN").val('');
            return false;
        }
        var CompanyIN = $("#CompanyIN").val().trim();
        var ProsesOUT = $("#ProsesOUT").val().trim();
        var ProsesIN = $("#ProsesIN").val().trim();
        var BarcodeIN = $("#ScanIN").val().trim();
        if(ProsesOUT == ProsesIN){
            $("#ProsesIN").focus();
            $("#ScanIN").val('');
            return false;
        }
        $("#ScanIN").val('');
        $("#ScanIN").focus();
        var formdata = new FormData();
        formdata.append('Company', CompanyIN);
        formdata.append('ProsesOUT', ProsesOUT);
        formdata.append('ProsesIN', ProsesIN);
        formdata.append('Barcode', BarcodeIN);
        $.ajax({
            url: 'project/WIP2/src/srcBCPengambilan.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
            },
            success: function (xaxa) {
                var result = xaxa;
                const ArrRes = result.split(":");
                if(ArrRes[0] == "TRUE"){
                    const arr = ArrRes[1].split("*");
                    var ValBC = arr[2];
                    var ValPartNo = arr[0];
                    var ValQty = arr[1];
                    var markup = '<tr data-idrows="isx" data-erows="'+ValBC+'"><td class="text-center">'+ValBC+'</td><td class="text-center">'+ValPartNo+'</td><td class="text-center">'+ValQty+'</td><td class="text-center"><i class="bi bi-trash-fill PointerList" aria-hidden="true" data-bs-toggle="modal" data-ecode="'+ValBC+'" data-bs-target="#DeleteTemp" title="Delete"></td></tr>';
                    $("#ScanInInfo tbody").append(markup);
                    $("#BtnProceedIn").prop('disabled', false);
                    $("#CompanyIN").prop('disabled', true);
                    $("#ProsesOUT").prop('disabled', true);
                    $("#ProsesIN").prop('disabled', true);
                }
                else if(ArrRes[0] == "FALSE1"){
                    alert('Barcode Tidak Ditemukan');
                }
                else{
                    alert('Barcode Tidak Dapat Digunakan');
                }
                $("#DeleteTemp").on('show.bs.modal', function (event) {
                    $("#DeleteBtn2").click(function(){
                        var act = $(event.relatedTarget);
                        var DataCode = act.data('ecode');
                        var formdata = new FormData();
                        formdata.append("ValCode", DataCode);
                        $.ajax({
                            url: 'project/WIP2/src/srcDeleteTempPengambilan.php',
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
            },
            error: function () {
                alert('Request cannot proceed!');
            }
        });
    });
    $("#BtnProceedIn").click(function(){
        var arr = [];
        $("#ScanInInfo tr[data-idrows='isx']").each(function(){
            var Id = $(this).find("td:first").text();
            arr.push(Id);
        }); 
        var CompanyIN = $("#CompanyIN").val().trim();
        var ProsesOUT = $("#ProsesOUT").val().trim();
        var ProsesIN = $("#ProsesIN").val().trim();
        var formdata = new FormData();
        formdata.append('Company', CompanyIN);
        formdata.append('ProsesOUT', ProsesOUT);
        formdata.append('ProsesIN', ProsesIN);
        formdata.append('arr', arr);
        if (confirm("Apakah anda yakin untuk memproses ?") == true) {
            $.ajax({
                url: 'project/WIP2/src/srcProceedPengambilan.php',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: formdata,
                type: 'post',
                beforeSend: function () {
                    $('#SubmitContent').html("");
                    $('#SubmitContent').before('<div class="col-sm-2 d-flex justify-content-center" id="LoadingImg"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                },
                success: function (xaxa) {
                    var Res = xaxa;
                    $('#LoadingImg').remove();
                    if(Res == "TRUE"){
                        alert('Success to save');
                        window.location.replace("https://protrax.formulatrix.com/home.php?link=48");
                    }
                    else{
                        alert('Failed to save');
                        return false;
                    }
                },
                error: function () {
                    alert('Request cannot proceed!');
                    $('#LoadingImg').remove();
                }
            });
        }
    });
    $("#ReportModal").on('show.bs.modal', function (event) { 
        $.ajax({
            url: 'project/WIP2/ReportPengambilan.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $('#ReportModalContent').html("");
                $('#ReportModalContent').before('<div class="col-sm-12 d-flex justify-content-center" id="LoadingImg"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
            },
            success: function (xaxa) {
                $('#ReportModalContent').hide();
                $('#ReportModalContent').html(xaxa);
                $('#ReportModalContent').fadeIn('fast');
                $('#LoadingImg').remove();
                $('#ReportTable').DataTable({
                    "iDisplayLength": 10,
                    "lengthChange": false,
                    searching: false,
                });
            },
            error: function () {
                alert('Request cannot proceed!');
                $('#LoadingImg').remove();
            }
        });
    });
});