$(document).ready(function () {
    $('#txtFilterTanggal1').datetimepicker({
        lang:'en',
        timepicker:false,
        format:'m/d/Y',
        formatDate:'m/d/Y',
        theme:'dark'
    });
    $('#txtFilterTanggal2').datetimepicker({
        lang:'en',
        timepicker:false,
        format:'m/d/Y',
        formatDate:'m/d/Y',
        theme:'dark'
    });
    $('#TableBarcodeStatus').removeAttr('width').DataTable({
        "iDisplayLength": 5,
        "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
        scrollY:        "430px",
        scrollX:        true,
        scrollCollapse: true,
        "autoWidth": true,
        "searching": false
    });
    $("#BtnDownloadBarcode").click(function(){
        var str = $("#txtFilterTanggal1").val();
        var end = $("#txtFilterTanggal2").val();
        var dt = $("#DateType").val();
        var fil = $('input[name=RadioFilter]:checked', '#RadioFilter').val();
        var chk = "on"
        var cat = "";
        var key = "";
        window.location.href = 'project/Report/src/DownloadCSVBarcode.php?ds='+str+'&&de='+end+'&&typ='+dt+'&&fil='+fil+'&&chk='+chk+'&&cat='+cat+'&&key='+key;
    });
    $("#BtnDownloadBarcode2").click(function(){
        var str = "";
        var end = "";
        var dt = "";
        var fil = $('input[name=RadioFilter]:checked', '#RadioFilter').val();
        var chk = "";
        if ($('#DateCheckDefault').is(":checked")) {
            chk = $('#DateCheckDefault').val();
            str = $("#txtFilterTanggal1").val();
            end = $("#txtFilterTanggal2").val();
            dt = $("#DateType").val();
            var cat = $("#FilterCustom").val();
            var key = $("#FilterKeywords").val().trim();
        }
        else{
            chk = "off";
            cat = $("#FilterCustom").val();
            key = $("#FilterKeywords").val().trim();
        }
        window.location.href = 'project/Report/src/DownloadCSVBarcode.php?ds='+str+'&&de='+end+'&&typ='+dt+'&&fil='+fil+'&&chk='+chk+'&&cat='+cat+'&&key='+key;
    });
      
    $("#TableBarcodeStatus tbody").css("font-size", "11px");
    $("#ViewButton").click(function(){
        var StartDate = $("#txtFilterTanggal1").val();
        var EndDate = $("#txtFilterTanggal2").val();
        var TipeDate = $("#DateType").val();
        var DataType = $('input[name=RadioFilter]:checked', '#RadioFilter').val();
        var Category = "";
        var Keywords = "";
        var formdata = new FormData();
        formdata.append('ValStartDate', StartDate);
        formdata.append('ValEndDate', EndDate);
        formdata.append('ValTipeDate', TipeDate);
        formdata.append('ValDataType', DataType);
        formdata.append('ValUsedDate', "on");
        formdata.append('ValCategory', Category);
        formdata.append('ValKeywords', Keywords);
        $.ajax({
            url: 'project/Report/BarcodeStatusContent.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#ViewButton").attr('disabled', true);
                $('#ContentResult').html("");
                $("#ContentResult").before('<div class="col-sm-12 d-flex justify-content-center" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                $('#ContentResult').html("");
            },
            success: function (xaxa) {
                $('#ContentResult').html("");
                $('#ContentResult').hide();
                $('#ContentResult').html(xaxa);
                $('#ContentResult').fadeIn('fast');
                $("#ContentLoading").remove();
                $("#ViewButton").blur();
                $("#ViewButton").attr('disabled', false);
                $('#TableBarcodeStatus').DataTable( {
                    "iDisplayLength": 5,
                    "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
                    scrollY:        "430px",
                    scrollX:        true,
                    scrollCollapse: true,
                    autoWidth: true,
                    "searching": false
                });
            },
            error: function () {
                alert("Request cannot proceed!");
                $("#ViewButton").blur();
                $('#ContentResult').html("");
                $("#ContentLoading").remove();
                $("#ViewButton").attr('disabled', false);
            }
        });
    });
    $("#ViewButton2").click(function(){
        var StartDate = "";
        var EndDate = "";
        var TipeDate = "";
        var DataType = $('input[name=RadioFilter]:checked', '#RadioFilter').val();
        var UsedDate = "";
        if ($('#DateCheckDefault').is(":checked")) {
            UsedDate = $('#DateCheckDefault').val();
            StartDate = $("#txtFilterTanggal1").val();
            EndDate = $("#txtFilterTanggal2").val();
            TipeDate = $("#DateType").val();
            var Category = $("#FilterCustom").val();
            var Keywords = $("#FilterKeywords").val().trim();
        }
        else{
            UsedDate = "off";
            Category = $("#FilterCustom").val();
            Keywords = $("#FilterKeywords").val().trim();
        }
            var formdata = new FormData();
            formdata.append('ValStartDate', StartDate);
            formdata.append('ValEndDate', EndDate);
            formdata.append('ValTipeDate', TipeDate);
            formdata.append('ValDataType', DataType);
            formdata.append('ValUsedDate', UsedDate);
            formdata.append('ValCategory', Category);
            formdata.append('ValKeywords', Keywords);
        $.ajax({
            url: 'project/Report/BarcodeStatusContent.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#ViewButton2").attr('disabled', true);
                $('#ContentResult').html("");
                $("#ContentResult").before('<div class="col-sm-12 d-flex justify-content-center" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                $('#ContentResult').html("");
            },
            success: function (xaxa) {
                $('#ContentResult').html("");
                $('#ContentResult').hide();
                $('#ContentResult').html(xaxa);
                $('#ContentResult').fadeIn('fast');
                $("#ContentLoading").remove();
                $("#ViewButton2").blur();
                $("#ViewButton2").attr('disabled', false);
                $('#TableBarcodeStatus').DataTable( {
                    "iDisplayLength": 5,
                    "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
                    scrollY:        "430px",
                    scrollX:        true,
                    scrollCollapse: true,
                    autoWidth: true
                });
            },
            error: function () {
                alert("Request cannot proceed!");
                $("#ViewButton").blur();
                $('#ContentResult').html("");
                $("#ContentLoading").remove();
                $("#ViewButton").attr('disabled', false);
            }
        });
    });
    
});