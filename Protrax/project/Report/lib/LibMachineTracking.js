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
    $('#TableMachineTrack').removeAttr('width').DataTable({
        "iDisplayLength": 5,
        "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
        scrollY:        "430px",
        scrollX:        true,
        scrollCollapse: true,
        "autoWidth": true
    });
    $("#BtnDownload").click(function(){
        var Start = $("#txtFilterTanggal1").val();
        var End = $("#txtFilterTanggal2").val();
        var Type = $("#FilterCustom").val();
        var Keywords = $("#FilterKeywords").val().trim();
        // alert("Download Is On Process");
        window.location.href = 'project/Report/src/DownLoadCSVMachine.php?ds='+Start+'&&de='+End+'&&typ='+Type+'&&key='+Keywords;
    });
    $("#BtnDownloadSeason").click(function(){
        var Open = "";
        if ($('#UsedOpen').is(":checked")) {
            Open = $('#UsedOpen').val();
        }
        else{ Open = "off";}
        var Season = $("#FilterSeason").val();
        window.location.href = 'project/Report/src/DownLoadCSVMachineSeason.php?sea='+Season+'&&op='+Open;
    });
      
    $("#TableMachineTrack tbody").css("font-size", "11px");
    $("#BtnViewDataCustom").click(function(){
        var Used = "";
        if ($('#DateCheckDefault').is(":checked")) {
            Used = $('#DateCheckDefault').val();
        }
        else{ Used = "off";}
        var StartDate = $("#txtFilterTanggal1").val();
        var EndDate = $("#txtFilterTanggal2").val();
        var Category = $("#FilterCustom").val();
        var Half = "";
        var Open = "";
        var Keywords = $("#FilterKeywords").val().trim();
        var formdata = new FormData();
        formdata.append('Used', Used);
        formdata.append('Open', Open);
        formdata.append('ValStartDate', StartDate);
        formdata.append('ValEndDate', EndDate);
        formdata.append('ValCategory', Category);
        formdata.append('ValKeywords', Keywords);
        formdata.append('Half', Half);
        $.ajax({
            url: 'project/Report/MachineTrackingContent.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#BtnViewDataCustom").attr('disabled', true);
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
                $("#BtnViewDataCustom").blur();
                $("#BtnViewDataCustom").attr('disabled', false);
                $('#TableMachineTrack').DataTable( {
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
                $("#BtnViewDataCustom").blur();
                $('#ContentResult').html("");
                $("#ContentLoading").remove();
                $("#BtnViewDataCustom").attr('disabled', false);
            }
        });
    });
    $("#BtnViewDataClosedTime").click(function(){
        var Open = "";
        if ($('#UsedOpen').is(":checked")) {
            Open = $('#UsedOpen').val();
        }
        else{ Open = "off";}
        var Used = "";
        var StartDate = "";
        var EndDate = "";
        var Category = "";
        var Keywords = "";
        var Half = $("#FilterSeason").val();
        var formdata = new FormData();
        formdata.append('Used', Used);
        formdata.append('Open', Open);
        formdata.append('ValStartDate', StartDate);
        formdata.append('ValEndDate', EndDate);
        formdata.append('ValCategory', Category);
        formdata.append('ValKeywords', Keywords);
        formdata.append('Half', Half);
        $.ajax({
            url: 'project/Report/MachineTrackingContent.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#BtnViewDataClosedTime").attr('disabled', true);
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
                $("#BtnViewDataClosedTime").blur();
                $("#BtnViewDataClosedTime").attr('disabled', false);
                $('#TableMachineTrack').DataTable( {
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
                $("#BtnViewDataClosedTime").blur();
                $('#ContentResult').html("");
                $("#ContentLoading").remove();
                $("#BtnViewDataClosedTime").attr('disabled', false);
            }
        });
    });
});