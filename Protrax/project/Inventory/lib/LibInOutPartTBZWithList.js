$(document).ready(function () {
    $("#txtFilterDateIN1").datetimepicker({
        lang: "en",
        timepicker: false,
        format: "m/d/Y",
        formatDate: "m/d/Y",
        theme: "dark"
    });
    $("#txtFilterDateOUT1").datetimepicker({
        lang: "en",
        timepicker: false,
        format: "m/d/Y",
        formatDate: "m/d/Y",
        theme: "dark"
    });
    $("#txtFilterDateIN2").datetimepicker({
        lang: "en",
        timepicker: false,
        format: "m/d/Y",
        formatDate: "m/d/Y",
        theme: "dark"
    });
    $("#txtFilterDateOUT2").datetimepicker({
        lang: "en",
        timepicker: false,
        format: "m/d/Y",
        formatDate: "m/d/Y",
        theme: "dark"
    });
    $("#InputScan").focus();
    $("#TextInfoLog").css({ "resize": "none", "background-color": "#191970", "color": "#FFFF00", "height": "180px", "font-size": "14px" });
    $("#InputScan").css({ "font-size": "20px" });
    $("#BtnHistory").click(function () {
        var Location = $("#FilterLocation option:selected").val();
        var DateStart = $("#txtFilterDateIN1").val().trim();
        var DateEnd = $("#txtFilterDateOUT1").val().trim();
        var Category = $("#FilterOpt option:selected").val();
        var formdata = new FormData();
        formdata.append("ValLocation", Location);
        formdata.append("ValDateStart", DateStart);
        formdata.append("ValDateEnd", DateEnd);
        formdata.append("ValCategory", Category);
        $.ajax({
            url: "project/Inventory/ContentViewDataInOutPartTBZWithList.php",
            dataType: "text",
            cache: false,
            contentType: false,
            processData: false,
            data: formdata,
            type: "post",
            beforeSend: function () {
                $("#BtnHistory").attr("disabled", true);
                $("#ContentTableInOut").html("");
                $("#ContentTableInOut").append('<div class="col-sm-12 pt-2 pb-2 d-flex justify-content-center" id="ContentLoading1"><div class="spinner-border text-secondary" role="status"><span class="visually-hidden" id="LoadingProcess1">Loading...</span ></div></div>');
            },
            success: function (xaxa) {
                $("#ContentLoading1").remove();
                $("#ContentTableInOut").html(xaxa);
                // console.log(xaxa);
                $("#BtnHistory").attr("disabled", false);
                var HeaderRes = $(".header-result").text();
                var HeaderResSplit = HeaderRes.split("[");
                var Location = HeaderResSplit[1].replace("Lokasi : ", "");
                Location = Location.replace("]", "");
                var TimeLog = HeaderResSplit[2].replace("]", "");
                TimeLog = TimeLog.replace(" - ", "_");
                var Category = HeaderResSplit[3].replace("]", "");
                Category = Category.replace("Kategori : ", "");
                let NewFileName = "DataProsesInOutPartTBZ_" + Location + "_" + Category + "_" + TimeLog;
                $("#TableTBZ").DataTable({
                    "pagingType": "full",
                    "scrollX": true,
                    destroy: true,
                    buttons: [
                        {
                            extend: 'csv',
                            filename: NewFileName
                        }
                    ]
                });
                $(".dataTables_wrapper > .dataTables_filter input").css("margin-bottom", "10px");
                $("#TableTBZ").css("font-size", "13px");
                $("#TableTBZ th, td").css({
                    // "white-space": "nowrap",
                    // "overflow": "hidden",
                    "word-wrap": "break-word"
                });
                $("#BtnDownloadCSV").click(function () {
                    $("#BtnDownloadCSV").attr("disabled", true);
                    setTimeout(function () {
                        $("#TableTBZ").DataTable().button('.buttons-csv').trigger();
                    }, 1000);
                    setTimeout(function () {
                        $("#BtnDownloadCSV").attr("disabled", false);
                    }, 3000);
                });
            },
            error: function () {
                alert("Request cannot proceed!");
                $("#ContentLoading1").remove();
                $("#ContentTableInOut").html("");
                $("#BtnHistory").blur();
                $("#BtnHistory").attr("disabled", false);
            }
        });
    });



})