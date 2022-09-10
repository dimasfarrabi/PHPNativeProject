<?php 
require_once("Modules/ModuleKelolaGuest.php"); 
date_default_timezone_set("Asia/Jakarta");
if(!session_is_registered("UIDWebTrax"))
{
    ?>
    <script language="javascript">
        window.location.replace("https://webtrax.formulatrix.com/");
    </script>
    <?php
    exit();
}
if($AccessLogin != "Administrator")
{
    ?>
    <script language="javascript">
        window.location.href = "home.php";
    </script>
    <?php
    exit();
}

if(isset($_SESSION['ManageDataGuest']))
{
    echo $_SESSION['ManageDataGuest'];
    unset($_SESSION['ManageDataGuest']);
}

?><script src="project/kelolaguest/lib/libkelolaguest.js?no=<?php echo base64_encode(date("mdyHis")); ?>"></script>
<style>
.input-radio{margin-top:0px !important;}.ActUpdate{cursor: pointer;color: #337AB7;}.ActDelete{cursor: pointer;color: #337AB7;}</style>
<div class="row">
    <div class="col-sm-12">
        <ol class="breadcrumb breadcrumb-detail">
            <li><a href="home.php">Home</a></li>
            <li class="active"><a href="home.php?link=10">Administration : Manage Guest Access</a></li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-sm-3">
        <div class="row">
            <div class="col-sm-12"><h4 class="TitleGroup">Add New Guest Access</h4></div>
            <form method="post" action="project/kelolaguest/src/srcaddnewguest.php">
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="InputNewGuest">Name</label>
                    <input type="text" class="form-control" name="InputNewGuest" id="InputNewGuest" required>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="InputNewUsername">Username</label>
                    <input type="text" class="form-control" name="InputNewUsername" id="InputNewUsername" required>
                    <p class="help-block">*) Please fill this column with a valid employee email.</p>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="InputNewPassword">Password</label>
                    <input type="password" class="form-control" name="InputNewPassword" id="InputNewPassword" required>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="InputLocationCompany">Company</label>
                    <select class="form-control" name="InputLocationCompany">
                        <option value="FI">Formulatrix Indonesia</option>
                        <option value="PSL">Promanufacture Salatiga</option>
                        <option value="PSM">Promanufacture Semarang</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="InputCategoryGuest">Category</label>
                    <select class="form-control" name="InputCategoryGuest">
                        <option value="Guest">Guest</option>
                        <option value="Administrator">Administrator</option>
                        <option value="Security">Security</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-12">&nbsp;</div>
            <div class="col-sm-12">
                <button type="submit" id="BtnSave" class="btn btn-dark">Add New</button>
            </div>
            </form>
        </div>
    </div>
    <div class="col-sm-9">
        <div class="row">
            <div class="col-sm-12"><h4 class="TitleGroup">Table Guest</h4></div>
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table id="TableGuest" class="table table-hover">
                        <thead>
                            <tr>
                                <th class="text-center" width="20">No</th>
                                <th class="text-center" width="100">Name</th>
                                <th class="text-center" width="100">Username</th>
                                <th class="text-center">Company</th>
                                <th class="text-center">Category</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">#</th>
                            </tr>
                        </thead>
                        <tbody><?php
                        $No = 1;
                        $QDataGuest = LOAD_ALL_DATA_GUEST($linkHRISWebTrax);
                        while($RDataGuest = mssql_fetch_assoc($QDataGuest))
                        {
                            $ValName = trim($RDataGuest['FullName']);
                            $ValUsername = trim($RDataGuest['username']);
                            $ValID = trim($RDataGuest['Idx']);
                            $ValID = base64_encode(base64_encode(trim($ValID)));
                            $ValIsGuest = trim($RDataGuest['Is_Guest']);
                            $ValIsActive = trim($RDataGuest['Is_Active']);
                            $ValIsAdmin = trim($RDataGuest['Is_Admin']);
                            $ValIsSecurity = trim($RDataGuest['Is_Security']);
                            $ValCompany = trim($RDataGuest['Company']);
                            if($ValIsGuest == "1"){$Status = "Guest";}
                            if($ValIsAdmin == "1"){$Status = "Administrator";}
                            if($ValIsSecurity == "1"){$Status = "Security";}
                            $IsActive = "Active";
                            if($ValIsActive == "0"){$IsActive = "Not Active";}
                            if($ValCompany == "")
                            {
                                $Company = "-";
                            }
                            else
                            {
                                switch ($ValCompany) {
                                    case 'FI':
                                        $Company = "Formulatrix Indonesia";
                                        break;
                                    case 'PSL':
                                        $Company = "Promanufacture Salatiga";
                                        break;
                                    case 'PSM':
                                        $Company = "Promanufacture Semarang";
                                        break;
                                    default:
                                        $Company = "-";
                                        break;
                                }
                            }

                            ?>
                            <tr>
                                <td class="text-center"><?php echo $No; ?></td>
                                <td class="text-left"><?php echo $ValName; ?></td>
                                <td class="text-left"><?php echo $ValUsername; ?></td>
                                <td class="text-left"><?php echo $Company; ?></td>
                                <td class="text-center"><?php echo $Status; ?></td>
                                <td class="text-center"><?php echo $IsActive; ?></td>
                                <td class="text-center">
                                <a href="project/kelolaguest/src/srcupdatestatusguest.php?key=<?php echo $ValID; ?>" class="ActActive"><span class="glyphicon glyphicon-user" title="Set Active / Not Active" aria-hidden="true"></span></a>&nbsp;&minus;&nbsp;
                                <span class="ActUpdate" data-toggle="modal" data-target="#DataGuest" data-dataid="<?php echo $ValID; ?>" data-dataname="<?php echo $ValUsername; ?>"><span class="glyphicon glyphicon-pencil" title="Update Password" aria-hidden="true"></span></span>&nbsp;&minus;&nbsp;
                                <a href="project/kelolaguest/src/srcdeleteguest.php?key=<?php echo $ValID; ?>" class="ActDelete"><span class="glyphicon glyphicon-trash" title="Delete" aria-hidden="true"></span></a></td>
                            </tr>
                            <?php
                            $No++;
                        }                        
                        ?></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="DataGuest" tabindex="-1" role="dialog" aria-labelledby="Modal-View" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row">
                    <div class="col-xs-6 text-left"><h5 class="modal-title"><strong>Update Password</strong></h5><span></span></div>
                    <div class="col-xs-6 text-right">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="InputPassword">Password</label>
                    <input type="password" class="form-control" id="InputPassword">
                    <input type="hidden" class="form-control" id="InputID" readonly>
                </div>
                <button type="button" id="BtnUpdate" class="btn btn-dark">Update</button>
                <div id="UpdatePassword"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-dark" data-dismiss="modal">&nbsp;Close&nbsp;</button>
            </div>
        </div>
    </div>
</div>
