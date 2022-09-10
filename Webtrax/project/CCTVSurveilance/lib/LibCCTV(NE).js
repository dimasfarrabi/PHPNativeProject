$(document).ready(function () {
    var BolClickListCategory = "TRUE";
    $(".PointerListCategory").click(function () {
        if (BolClickListCategory == "TRUE") {
            $("#ListCategory tr").removeClass('PointerCCTVCategorySelected');
            $(this).closest('.PointerListCategory').addClass("PointerCCTVCategorySelected");
            var CategoryName = $(this).text();
            var CategoryID = $(this).data('id');
            var formdata = new FormData();
            formdata.append('ValCategoryName', CategoryName);
            formdata.append('ValCategoryID', CategoryID);
            $.ajax({
                url: 'project/cctvsurveilance/cctvpagecontent.php',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: formdata,
                type: 'post',
                beforeSend: function () {
                    BolClickListCategory = "FALSE";
                    $("html, body").animate({ scrollTop: $("#ResultCategory").offset().top - 20 }, "fast");
                    $('#ResultCategory').html("");
                    $("#ContentLoading").remove();
                    $("#ResultCategory").before('<div class="col-sm-12" id="ContentLoading"><img src="../images/ajax-loader1.gif" id="LoadingCheck" class="load_img"/></div>');
                    $('#ResultCategory').html("");
                },
                success: function (xaxa) {
                    $('#ResultCategory').html("");
                    $('#ResultCategory').hide();
                    $('#ResultCategory').html(xaxa);
                    $('#ResultCategory').fadeIn('fast');
                    $("#ContentLoading").remove();
                    BolClickListCategory = "TRUE";
                },
                error: function () {
                    alert("Request cannot proceed!");
                    $('#ResultCategory').html("");
                    $("#ContentLoading").remove();
                    BolClickListCategory = "TRUE";
                }
            });
        }
        else
        {
            return false;
        }
    });    
});