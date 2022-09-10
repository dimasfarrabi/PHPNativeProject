<?php
session_start();
if(!session_is_registered("UIDWebTrax"))
{
    ?>
    <script language="javascript">
        window.location.replace("https://webtrax.formulatrix.com/");
    </script>
    <?php
    exit();
}

?><div class="col-sm-12"><h4 class="TitleGroup">Import Data KWH Tracking</h4></div>
<form method="post" action="project/kwhtracking/src/srcImportKWHTracking.php" id="FormImportKWHTracking" enctype="multipart/form-data">
<div class="col-sm-12">
    <div class="form-group">
        <label for="InputFile">File input</label>
        <input type="file" id="InputFile" name="InputFile" accept=".csv">
        <p class="help-block">Format file .csv</p>
    </div>
</div>
<div class="col-sm-12">
    <button type="submit" id="BtnSubmit" class="btn btn-md btn-dark" disabled>Submit</button>
</div>
</form>