<?php 
session_start();
require_once("../../src/Modules/ModuleLogin.php");
require_once("Modules/ModuleCCTV.php");

if(!session_is_registered("UIDWebTrax"))
{
    ?>
    <script language="javascript">
        window.location.replace("https://webtrax.formulatrix.com/");
    </script>
    <?php
    exit();
}

if($_SERVER['REQUEST_METHOD'] == "POST")
{   
    $ValCategoryName = htmlspecialchars(trim($_POST['ValCategoryName']), ENT_QUOTES, "UTF-8");
    $ValCategoryID = htmlspecialchars(trim($_POST['ValCategoryID']), ENT_QUOTES, "UTF-8");
    $ValCategoryID = base64_decode($ValCategoryID);
    $ValCategoryID = str_replace("ID","",$ValCategoryID);
    
    ?>
<script src="project/cctvsurveilance/lib/libactcctv.js?no=<?php echo base64_encode(date("mdyHis")); ?>"></script>
<style>
    .Info{font-style:italic;font-weight:bold;background-color:#fff600;}
    .Info2{font-weight:bold;color:#ff0000;}
    #PointerCopy,#PointerCopyUser{cursor: pointer;color: #337AB7;font-size:10px;}
    .DownloadLink{cursor: pointer;color: #337AB7;}
</style>
<div class="col-md-12"><h5><strong>Indo Surveilance Access</strong></h5></div>
<?php /*<div class="col-md-12"><p><strong>Please use this credentials to access CCTV</strong><br>Username : <strong><span id="TxtUsr">guest</span>&nbsp;<span id="PointerCopyUser">Copy</span></strong><br>Password : <strong><span id="TxtPwd">gue5t@fmlx13</span>&nbsp;<span id="PointerCopy">Copy</span></strong></p></div>
<div class="col-md-12">[<span class="DownloadLink" id="DownloadPlugin">Download Plugin</span>]</div>
<div class="col-md-12"><div class="Info"><h5><strong>*) All the link below only can be open using Internet Explorer</strong></h5><h5><strong>Set Internet Explorer as your default web browser</strong></h5></div></div>*/ ?>
<div class="col-md-12">
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="TableListCCTV">
            <thead class="theadCustom">
                <tr>
                    <td width="50" class="text-center">No</td>
                    <td class="text-center">Name</td>
                    <?php //<td class="text-center" colspan="2">Link</td> ?>
                    <td class="text-center">Link</td>
					<td class="text-center">PIC</td>
                </tr>
            </thead>
            <tbody><?php 
            $No = 1;
            $QData = GET_LIST_ACTVITY_CCTV_BY_CATEGORY($ValCategoryID,$linkHRISWebTrax);
            while($RData = mssql_fetch_assoc($QData))
            {
                $ValCCTVName = trim($RData['CCTVName']);
                $ValCCTVLink = trim($RData['Link']);
				$ValCCTVPICName = trim($RData['PICName']);
				$ValCCTVPICEmail = "mailto:".trim($RData['PICEmail']);
                $ValLocal = trim($RData['Local']);
                // $LinkLocal = 'https://webtrax.formulatrix.com'.$ValLocal;
                $LinkLocal = ''.$ValLocal;
				
				
                if(trim($ValCCTVLink) != "")
                {
                    if(trim($ValLocal) != "")
                    {
                        ?>
                    <tr>
                        <td class="text-center"><?php echo $No; ?></td>
                        <td class="text-left"><?php echo $ValCCTVName; ?></td>
                        <?php /*<td width="10"><button class="btn btn-xs btn-dark btn-labeled" onClick="javascript:window.open ('<?php echo $ValCCTVLink; ?>','','width='+screen.width+',height=' + screen.height + ',resizeable=no,scrollbars=no,toolbar=no,status=no')">&nbsp;IE View&nbsp;</button></td>*/ ?>
                        <td width="10" class="text-center"><button class="btn btn-xs btn-dark btn-labeled" onClick="javascript:window.open ('<?php echo $LinkLocal; ?>','','width=900vw,height=540vh,resizeable=no,scrollbars=no,toolbar=no,status=no')">&nbsp;Direct View&nbsp;</button></td>
                        <td width="200"><a href="<?php echo $ValCCTVPICEmail;?>"><?php echo $ValCCTVPICName; ?></a></td>
                    </tr>
                        <?php
                    }
                    else
                    {
                        ?>
                    <tr>
                        <td class="text-center"><?php echo $No; ?></td>
                        <td class="text-left"><?php echo $ValCCTVName; ?></td>
                        <?php /*<td width="10"><button class="btn btn-xs btn-dark btn-labeled" onClick="javascript:window.open ('<?php echo $ValCCTVLink; ?>','','width='+screen.width+',height=' + screen.height + ',resizeable=no,scrollbars=no,toolbar=no,status=no')">&nbsp;IE View&nbsp;</button></td>*/ ?>
                        <td width="10" class="text-center"><button class="btn btn-xs btn-dark btn-labeled" title="Underdevelopment!" disabled>&nbsp;Direct View&nbsp;</button></td>
                        <td width="200"><a href="<?php echo $ValCCTVPICEmail;?>"><?php echo $ValCCTVPICName; ?></a></td>
                    </tr>
                        <?php
                    }
                }
                else
                {
                    if(trim($ValLocal) != "")
                    {
                        ?>
                    <tr>
                        <td class="text-center"><?php echo $No; ?></td>
                        <td class="text-left"><?php echo $ValCCTVName; ?></td>
                        <?php //<td width="10"><button class="btn btn-xs btn-dark btn-labeled" title="Underdevelopment!" disabled>&nbsp;IE View&nbsp;</button></td> ?>
                        <td width="10" class="text-center"><button class="btn btn-xs btn-dark btn-labeled" onClick="javascript:window.open ('<?php echo $LinkLocal; ?>','','width=900vw,height=540vh,resizeable=no,scrollbars=no,toolbar=no,status=no')">&nbsp;Direct View&nbsp;</button></td>
                        <td width="200"><a href="<?php echo $ValCCTVPICEmail;?>"><?php echo $ValCCTVPICName; ?></a></td>
                    </tr>
                        <?php
                    }
                    else
                    {
                        ?>
                    <tr>
                        <td class="text-center"><?php echo $No; ?></td>
                        <td class="text-left"><?php echo $ValCCTVName; ?></td>
                        <?php //<td width="10"><button class="btn btn-xs btn-dark btn-labeled" title="Underdevelopment!" disabled>&nbsp;IE View&nbsp;</button></td> ?>
                        <td width="10" class="text-center"><button class="btn btn-xs btn-dark btn-labeled" title="Underdevelopment!" disabled>&nbsp;Direct View&nbsp;</button></td>
                        <td width="200"><a href="<?php echo $ValCCTVPICEmail;?>"><?php echo $ValCCTVPICName; ?></a></td>
                    </tr>
                        <?php
                    }
                }
                
                $No++; 
            }
            ?></tbody>
        </table>
    </div>
</div>
<div class="col-md-12 Info2">*) If you have a problem with CCTV access, please send your feedback to <span class="Info"><a href="mailto:forindo.it@formulatrix.com">forindo.it@formulatrix.com</a></span></div>
    <?php
}
else
{
    echo "";    
}
?>