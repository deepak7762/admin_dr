<?php 
    include "header.php";
    include "databaseConnect.php";

    $selectSql = "SELECT * FROM tbladmins WHERE id = :userId AND (adminpermission = 4 OR adminpermission = 3)";
    $stmt = $conn->prepare($selectSql);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        // print_r($data);
    }
?>

<style>
    .fa-user-circle1{
        color: #3291f6;
    }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        <?php if($permissionType == 1 || $permissionType == 2){?><i class="nav-icon fas fa-tachometer-alt"></i>Dashboard<?php }else{?><i class="nav-icon fas fa-user-circle fa-user-circle1"></i>Profile<?php }?>
                    </h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">Home</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content" style="min-height:454px;">
        <div class="container-fluid">
            <?php if($permissionType == 3 || $permissionType == 4){?>
            <div class="card">
                <div class="card-header pb-0">

                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table>
                            <tr>
                                <th>Name</th>
                                <td><?=$data['adminname']?></td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td><?=$data['adminemail']?></td>
                            </tr>
                            <tr>
                                <th>Phone Number</th>
                                <td><?=$data['mobile']?></td>
                            </tr>
                            <tr>
                                <th>Last Login</th>
                                <td><?=date('d M Y H:i:s', strtotime($data['lastlogindatetime']))?></td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td><?php if($data['status'] == 1){echo "<span style='color:green'>Active</span>";}else {echo "<span style='color:red'>Inactive</span>";}?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="card-footer">

                </div>
            </div>
            <?php }?>

        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<?php include "footer.php"?>