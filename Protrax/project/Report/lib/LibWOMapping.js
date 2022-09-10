$(document).ready(function () {
    $('#TableWOMapping').removeAttr('width').DataTable({
        "iDisplayLength": 5,
        "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
        scrollY:        "430px",
        scrollX:        true,
        scrollCollapse: true,
        "autoWidth": true
    });
    // $("#BtnDownloadWO").click(function(){
    //     var Start = $("#txtFilterTanggal1").val();
    //     var End = $("#txtFilterTanggal2").val();
    //     var Type = $("#FilterCustom").val();
    //     var Keywords = $("#FilterKeywords").val().trim();
    //     // alert("Download Is On Process");
    //     window.location.href = 'project/Report/src/DownLoadCSVMaterial.php?ds='+Start+'&&de='+End+'&&typ='+Type+'&&key='+Keywords;
    // });
    $("#BtnDownloadWO").click(function(){
        var UsedCL = "";
        if ($('#UsedClosedTime').is(":checked")) {
            UsedCL = $('#UsedClosedTime').val();
        }
        else{ UsedCL = "off";}
        var Open = "";
        if ($('#UsedOpen').is(":checked")) {
            Open = $('#UsedOpen').val();
        }
        else{ Open = "off";}
        var Season = $("#FilterSeason").val();
        var Type = $("#FilterCustom").val();
        var Key = $("#FilterKeywords").val().trim();
        window.location.href = 'project/Report/src/DownLoadCSVWO.php?sea='+Season+'&&ucl='+UsedCL+'&&typ='+Type+'&&key='+Key+'&&op='+Open;
    });
      
    $("#TableWOMapping tbody").css("font-size", "11px");
    $("#BtnViewWO").click(function(){
        var UsedCL = "";
        if ($('#UsedClosedTime').is(":checked")) {
            UsedCL = $('#UsedClosedTime').val();
        }
        else{ UsedCL = "off";}
        var Open = "";
        if ($('#UsedOpen').is(":checked")) {
            Open = $('#UsedOpen').val();
        }
        else{ Open = "off";}
        var Season = $("#FilterSeason").val();
        var Type = $("#FilterCustom").val();
        var Key = $("#FilterKeywords").val().trim();
        var formdata = new FormData();
        formdata.append('ClosedTime', Season);
        formdata.append('FilterType', Type);
        formdata.append('Keywords', Key);
        formdata.append('UsedCL', UsedCL);
        formdata.append('Open', Open);
        $.ajax({
            url: 'project/Report/WOMappingContent.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: 'post',
            beforeSend: function () {
                $("#BtnViewWO").attr('disabled', true);
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
                $("#BtnViewWO").blur();
                $("#BtnViewWO").attr('disabled', false);
                $('#TableWOMapping').DataTable( {
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
                $("#BtnViewWO").blur();
                $('#ContentResult').html("");
                $("#ContentLoading").remove();
                $("#BtnViewWO").attr('disabled', false);
            }
        });
    });
});