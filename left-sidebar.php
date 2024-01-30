 <!-- Main Sidebar Container -->
 <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="http://localhost/admin-dr/" class="brand-link">
      <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Domain Name</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <!-- <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image"> -->
          <div class="img-circle elevation-2"><?=ucfirst(substr($userName, 0, 1));?></div>
        </div>
        <div class="info">
          <a class="d-block">Hello, <?=ucfirst($userName)?></a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <?php if($permissionType == 1 || $permissionType == 2){?>
            <li class="nav-item">
              <a href="admins.php" class="nav-link">
                <i class="nav-icon fas fa-user"></i>
                <p>
                  Admins
                </p>
              </a>
            </li>
          <?php } if($permissionType == 1 || $permissionType == 2){ ?>
          <li class="nav-item">
            <a href="doctors.php" class="nav-link">
              <i class="nav-icon fa fa-user-md"></i>
              <p>
                Doctors
              </p>
            </a>
          </li>
          <?php } if($permissionType == 1 || $permissionType == 2){ ?>
          <li class="nav-item">
            <a href="Patients.php" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Patients
              </p>
            </a>
          </li>
          <?php }  if($permissionType == 3 || $permissionType == 4){ ?>
            <li class="nav-item">
              <a href="index.php" class="nav-link">
                <i class="nav-icon fas fa-user-circle"></i>
                <p>
                  Profile
                </p>
              </a>
            </li>
            <?php }if($permissionType == 4){?>
            <li class="nav-item">
              <a href="report.php" class="nav-link">
                <i class="nav-icon fas fa-users"></i>
                <p>
                  Reports
                </p>
              </a>
            </li>
          <?php } if($permissionType == 3){?>
            <li class="nav-item">
              <a href="view-reports.php" class="nav-link">
                <i class="nav-icon fas fa-users"></i>
                <p>
                  View Reports
                </p>
              </a>
            </li>
            <?php }?>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>