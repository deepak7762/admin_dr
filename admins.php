<?php 
    include "databaseConnect.php";
    include "header.php";
  
    if(isset($_REQUEST['numRecords']) && !empty($_REQUEST['numRecords'])){
        $numRecordPerPage = $_REQUEST['numRecords'];
    } else {
        $numRecordPerPage = 10;
    }
    include "pagination.php";

    $where = "1=1";
    $searchAdName = "";
    if(isset($_REQUEST['searchAdName']) && !empty($_REQUEST['searchAdName'])){
        $searchAdName = $_REQUEST['searchAdName'];
        $where .= " AND adminname = '$searchAdName'";
    }

    $searchAdEmail = "";
    if(isset($_REQUEST['searchAdEmail']) && !empty($_REQUEST['searchAdEmail'])){
        $searchAdEmail = $_REQUEST['searchAdEmail'];
        $where .= " AND adminemail = '$searchAdEmail'";
    }

    $searchByAdType = "";
    if(isset($_REQUEST['searchByAdType']) && $_REQUEST['searchByAdType'] != '' ){
        $searchByAdType = $_REQUEST['searchByAdType'];
        $where .= " AND status = '$searchByAdType'";
    }

    if (empty($numrecords) || !is_numeric($numrecords)) {
        $sql1 = "SELECT count(*) as total FROM \"tbladmins\" where $where AND (adminPermission = 1 OR adminPermission = 2)";
        $result1 = $conn->query($sql1);
        $row1 = $result1->fetch(PDO::FETCH_ASSOC);
        $totalRecord = $row1["total"];
    }
    $queryLimit = $start . "," . $numRecordPerPage; 
?>

<style>
    .dataTables_paginate {
        display: flex;
        flex-direction: row;
        justify-content: end;
    }

    .pagination {
        background-color: #f2f2f2;
    }

    .pagination a {
        color: black;
        padding: 8px 16px;
        text-decoration: none;
    }

    .pagination li {
        padding: 8px;
    }

    .pagination .active {
        background-color: #007bff;
        color: white;
    }

    .pagination li:hover:not(.active) {
        background-color: #ddd;
    }

    .edit-icon-td i {
        color: #007bff;
        font-size: 21px;
    }

    .btn-default {
        background-color: #fff;
        border-color: #fff;
    }

    .btn-default:hover {
        background-color: #fff;
    }
    .red-border{
        border-color:red !important;
    }
</style>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Admin</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/admin-dr/">Home</a></li>
                        <li class="breadcrumb-item active">Admin</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header pb-0">
                    <form method="get" id="filterForm">
                        <input type="hidden" name="numRecords" id="rowcount">
                        <input type="hidden" name="searchAdName" value="<?=$searchAdName?>">
                        <input type="hidden" name="searchAdEmail" value="<?=$searchAdEmail?>">
                        <input type="hidden" name="searchByAdType" value="<?=$searchByAdType?>">
                    </form>
                    <form method="get">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <input type="text" class="form-control" id="searchAdName" name="searchAdName"
                                        placeholder="Admin Name" value="<?=$searchAdName?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <input type="text" class="form-control" id="searchAdEmail" name="searchAdEmail"
                                        placeholder="Admin Email" value="<?=$searchAdEmail?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <select class="form-control" id="searchByAdType" name="searchByAdType">
                                        <option value="">Status</option>
                                        <option value=1 <?php if($searchByAdType == 1){echo "selected";}?>>Active
                                        </option>
                                        <option value=0 <?php if($searchByAdType == 0){echo "selected";}?>>Inactive
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary">Search</button>
                                <button type="button" class="btn btn-primary" onclick="resetBtnFun();">Reset</button>
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-md-6">
                            <p>Total <b><?=$totalRecord?></b> Record Found.</p>
                        </div>
                        <div class="col-md-6">
                            <?php if($permissionType == 1){?>
                            <button type="button" class="btn btn-outline-primary float-right" fdprocessedid="a74cte"
                                onclick="showAddAdminModel();"><i class="fas fa-plus"></i> Add User</button>
                            <?php }?>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <?php 
                        try {
                            // $selectSql = "SELECT * FROM tbladmins where $where AND (adminPermission = 1 OR adminPermission = 2) ORDER BY `id` DESC LIMIT $numRecordPerPage OFFSET $start";
                           $selectSql = "SELECT * FROM tbladmins where $where AND (adminPermission = 1 OR adminPermission = 2) ORDER BY \"id\" DESC LIMIT $numRecordPerPage OFFSET $start";
                            $stmt = $conn->prepare($selectSql);
                            $stmt->execute();

                            if ($stmt->rowCount() > 0) {
                        ?>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Admin Name</th>
                                    <th>Admin Email</th>
                                    <th>Phone Number</th>
                                    <th>Status</th>
                                    <th>Created DateTime</th>
                                    <?php if($permissionType == 1){?>
                                    <th style="text-align: center;">Action</th>
                                    <?php }?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i=1;
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                                <tr>
                                    <td><?=$i?></td>
                                    <td><?=$row['adminname']?></td>
                                    <td><?=$row['adminemail']?></td>
                                    <td><?=$row['mobile']?></td>
                                    <?php if($row['status'] == 1){$style = "style = 'color:green'";}else{$style = "style = 'color:red'";}?>
                                    <td <?=$style?>>
                                        <?php if($row['status'] == 1){echo "Active";} else if($row['status'] == 0){echo "Inactive";}?>
                                    </td>
                                    <td><?=date('d M Y H:i:s', strtotime($row['createdat']))?></td>
                                    <?php if($permissionType == 1){?>
                                    <td class="edit-icon-td" style="text-align: center;">
                                        <button type="button" class="btn btn-default"
                                            onclick="getEditInfo(<?=$row['id']?>);">
                                            <i class='fas fa-edit'></i>
                                        </button>
                                    </td>
                                    <?php }?>
                                </tr>
                                <?php
                                $i++;
                                }
                        ?>
                            </tbody>
                        </table>
                    </div>
                    <?php
                            } else {
                                echo "No Data Found.";
                            }
                        } catch (PDOException $e) {
                            echo "Error: " . $e->getMessage();
                        } 
                        ?>
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-2 col-lg-2 pt-2">
                            <div class="form-group">
                                <select class="form-control" name="rowcount" onchange="sortOrder(this.value)">
                                    <?php
                                    $numrows_arr = array("8","10","25","50","100","200");
                                    foreach($numrows_arr as $nrow){ ?>
                                    <option value="<?=$nrow?>"
                                        <?php if($numRecordPerPage == $nrow){ echo 'selected'; } ?>><?=$nrow?>
                                    </option>
                                    <?php }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-10 col-lg-10 pt-2">
                            <div class="dataTables_paginate paging_simple_numbers table-responsive">
                                <ul class="pagination">
                                    <?php
                                        $pageName = "admins.php";
                                        $parameters = "numRecords=".$numRecordPerPage."&searchAdName=".$searchAdName."&searchAdEmail=".$searchAdEmail."&searchByAdType=".$searchByAdType;				
                                        printPagination($pageName,$numRecordPerPage,$totalRecord,$parameters);
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<div class="modal fade" id="modal-add-user">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add User</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="hide" id="DisplayErrors" style="color:red;"></p>
                <div class="form-group">
                    <input type="text" class="form-control" id="addAdName" name="addAdName" placeholder="User Name"
                        value="">
                </div>
                <div class="form-group">
                    <input type="email" class="form-control" id="addAdEmail" name="addAdEmail" placeholder="User Email"
                        value="" onblur = "verifyEmail();">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" id="addAdMobile" name="addAdMobile"
                        placeholder="User Phone Number" value="">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" id="addAdPassword" name="addAdPassword"
                        placeholder="Password" value="">
                </div>
                <div class="form-group">
                    <select class="form-control" id="addStatus" name="addStatus">
                        <option value="">Select Status</option>
                        <option value=1>Active</option>
                        <option value=0>Inactive</option>
                    </select>
                </div>
                <div class="form-group">
                    <select class="form-control" id="addPermission" name="addPermission">
                        <option value="">User Type</option>
                        <option value=1>Super Admin</option>
                        <option value=2>Admin</option>
                        <option value=3>Doctor</option>
                        <option value=4>Patient</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="addData();">Add User</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<div class="modal fade" id="modal-lg">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="hide" id="errorPara" style="color:red;"></p>
                <div class="form-group">
                    <input type="text" class="form-control" id="editAdName" name="editAdName" placeholder="Admin Name"
                        value="">
                </div>
                <div class="form-group">
                    <input type="email" class="form-control" id="editAdEmail" name="editAdEmail"
                        placeholder="Admin Email" value="">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" id="editAdMobile" name="editAdMobile"
                        placeholder="Admin Phone Number" value="">
                </div>
                <div class="form-group">
                    <select class="form-control" id="editStatus" name="editStatus">
                        <option value=1>Active</option>
                        <option value=0>Inactive</option>
                    </select>
                </div>
                <div class="form-group">
                    <select class="form-control" id="editPermission" name="editPermission">
                        <option value=1>Super Admin</option>
                        <option value=2>Admin</option>
                        <option value=3>Doctor</option>
                        <option value=4>Patient</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="updateData();">Save changes</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<script>
    const editAdNameElement = document.getElementById('editAdName');
    restrictInputToAlphaAndSpace(editAdNameElement);
    const addElement = document.getElementById('addAdName');
    restrictInputToAlphaAndSpace(addElement);

    function restrictInputToAlphaAndSpace(inputElement) {
        inputElement.addEventListener('input', (event) => {
            const inputValue = event.target.value;
            const formattedValue = inputValue.replace(/[^a-zA-Z\s]/g, '');
            event.target.value = formattedValue;
        });
    }

    const editAdEmailElement = document.getElementById('editAdEmail');
    restrictInputToEmailCharacters(editAdEmailElement);
    const otherEmailElement = document.getElementById('addAdEmail');
    restrictInputToEmailCharacters(otherEmailElement);

    function restrictInputToEmailCharacters(inputElement) {
        inputElement.addEventListener('input', (event) => {
            const inputValue = event.target.value;
            const formattedValue = inputValue.replace(/[^a-zA-Z0-9\-_@.]/g, '');
            event.target.value = formattedValue;
        });
    }

    const editAdMobileElement = document.getElementById('editAdMobile');
    restrictInputToMobileNumber(editAdMobileElement);
    const otherMobileElement = document.getElementById('addAdMobile');
    restrictInputToMobileNumber(otherMobileElement);

    function restrictInputToMobileNumber(inputElement) {
        inputElement.addEventListener('input', (event) => {
            const inputValue = event.target.value;
            let formattedValue = inputValue.replace(/^(?!6|7|8|9).*/g, '');
            if (formattedValue.length > 10) {
                formattedValue = formattedValue.slice(0, 10);
            }
            event.target.value = formattedValue;
        });
    }

    function sortOrder(colName) {
        if ($.isNumeric(colName) == true) {
            $("#rowcount").val(colName);
        }
        $("form#filterForm").submit();
    }

    function resetBtnFun() {
        $('#searchAdName').val('');
        $('#searchByAdType').val('');
        window.location.href = "admins.php";
    }

    function showAddAdminModel() {
        $('#modal-add-user').modal('show');
    }

    function verifyEmail() {
        var email = $("#addAdEmail").val();
        $.ajax({
            url: "admins-process.php",
            type: "POST",
            data: {
                user_email: email,
                callType: "verifyEmail"
            },
            success: function (response) {
                var resp = JSON.parse(response);
                if(resp['status'] == 300){
                    $('#DisplayErrors').removeClass('hide');
                    $('#DisplayErrors').html(resp['content']);
                    $("#addAdEmail").val("");
                    $("#addAdEmail").addClass("red-border");
                }
            },
        });
    }

    function addData() {
        var addadminname = $('#addAdName').val();
        var addadminemail = $('#addAdEmail').val();
        var addAdminMobile = $('#addAdMobile').val();
        var addadminpassword = $('#addAdPassword').val();
        var addAdminStatus = $('#addStatus').val();
        var addAdminPermission = $('#addPermission').val();
        if (addadminname == "") {
            $('#DisplayErrors').removeClass('hide');
            $('#DisplayErrors').html("<strong>Error! </strong>Please Enter User Name.");
            return false;
        } else if (addadminemail == "") {
            $('#DisplayErrors').removeClass('hide');
            $('#DisplayErrors').html("<strong>Error! </strong>Please Enter User Email.");
            return false;
        } else if (addAdminMobile == "") {
            $('#DisplayErrors').removeClass('hide');
            $('#DisplayErrors').html("<strong>Error! </strong>Please Enter User Mobile.");
            return false;
        } else if (addAdminMobile.length !== 10) {
            $('#DisplayErrors').removeClass('hide');
            $('#DisplayErrors').html("<strong>Error! </strong>Please Enter a 10-digit Mobile Number.");
            return false;
        } else if (addadminpassword == "") {
            $('#DisplayErrors').removeClass('hide');
            $('#DisplayErrors').html("<strong>Error! </strong>Please Enter Password.");
            return false;
        } else if (addAdminStatus == "") {
            $('#DisplayErrors').removeClass('hide');
            $('#DisplayErrors').html("<strong>Error! </strong>Please Select Status.");
            return false;
        } else if (addAdminPermission == "") {
            $('#DisplayErrors').removeClass('hide');
            $('#DisplayErrors').html("<strong>Error! </strong>Please Select Permission Type.");
            return false;
        }
        $.ajax({
            url: "admins-process.php",
            type: "POST",
            data: {
                add_name: addadminname,
                add_email: addadminemail,
                add_mobile: addAdminMobile,
                add_password: addadminpassword,
                add_status: addAdminStatus,
                add_permission: addAdminPermission,
                callType: 'addInfo'
            },
            success: function (response) {
                var resp = JSON.parse(response);
                if (resp['status'] == 200) {
                    toastr.success(resp['content']);
                    setTimeout(function () {
                        location.reload();
                    }, 2000);
                } else if (resp['status'] == 400) {
                    toastr.error(resp['content']);
                }
                $('#modal-add-user').modal('hide');
            },
        });
    }

    function getEditInfo(id) {
        $.ajax({
            url: "admins-process.php",
            type: "POST",
            data: {
                editId: id,
                callType: 'getInfo'
            },
            success: function (response) {
                var resp = JSON.parse(response);
                // console.log(resp.adminpermission);
                $('#editAdName').val(resp.adName);
                $('#editAdEmail').val(resp.adEmail);
                $('#editAdMobile').val(resp.adMobile);
                $('#editStatus').val(resp.status).change();
                $('#editPermission').val(resp.adminpermission).change();
                $('#modal-lg').modal('show');
            },
        });
    }

    function updateData() {
        var adname = $('#editAdName').val();
        var adEmail = $('#editAdEmail').val();
        var adMobile = $('#editAdMobile').val();
        var status = $('#editStatus').val();
        var adPermission = $('#editPermission').val();
        if (adname == "") {
            $('#errorPara').removeClass('hide');
            $('#errorPara').html("<strong>Error! </strong>Please Enter Admin Name.");
            return false;
        } else if (adEmail == "") {
            $('#errorPara').removeClass('hide');
            $('#errorPara').html("<strong>Error! </strong>Please Enter Admin Email.");
            return false;
        } else if (adMobile == "") {
            $('#errorPara').removeClass('hide');
            $('#errorPara').html("<strong>Error! </strong>Please Enter Admin Mobile.");
            return false;
        } else if (adMobile.length !== 10) {
            $('#errorPara').removeClass('hide');
            $('#errorPara').html("<strong>Error! </strong>Please Enter a 10-digit Mobile Number.");
            return false;
        } else if (status == "") {
            $('#errorPara').removeClass('hide');
            $('#errorPara').html("<strong>Error! </strong>Please Select Status.");
            return false;
        } else if (adPermission == "") {
            $('#errorPara').removeClass('hide');
            $('#errorPara').html("<strong>Error! </strong>Please Select Permission Type.");
            return false;
        }
        $.ajax({
            url: "admins-process.php",
            type: "POST",
            data: {
                ad_name: adname,
                ad_email: adEmail,
                ad_mobile: adMobile,
                ad_status: status,
                ad_permission: adPermission,
                callType: 'updateInfo'
            },
            success: function (response) {
                var resp = JSON.parse(response);
                if (resp['status'] == 200) {
                    toastr.success(resp['content']);
                    setTimeout(function () {
                        location.reload();
                    }, 2000);
                } else if (resp['status'] == 400) {
                    toastr.error(resp['content']);
                }
                $('#modal-lg').modal('hide');
            },
        });

    }
</script>
<?php include "footer.php"?>