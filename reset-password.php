<?php 
  session_start();
  if(isset($_SESSION['reset-pass-page']) && !empty($_SESSION['reset-pass-page']) && $_SESSION['reset-pass-page'] == 'reset-password-page'){
    // unset($_SESSION['reset-pass-page']);
    unset($_SESSION['verify-otp-page']);
  }
  else{
    header('location:login.php');
  }
    $title = "AdminCPA | Reset password - Collectcent Digital Media";
    include "auth-header.php";
?>
    <div class="card-body">
      <p class="login-box-msg">Forgot your password? Here you can easily reset a new password.</p>
      <?php if(isset($_GET['err']) && $_GET['err'] == 7){?>
            <p class="login-box-msg" style = "color:red;">Error! Please Enter New Password.</p>
        <?php }else if(isset($_GET['err']) && $_GET['err'] == 8){?>
            <p class="login-box-msg" style = "color:red;">Error! Please Enter Confirm Password.</p>
        <?php }else if(isset($_GET['err']) && $_GET['err'] == 9){?>
            <p class="login-box-msg" style = "color:red;">Error! Minimum Password length required is 8.</p>
        <?php }else if(isset($_GET['err']) && $_GET['err'] == 10){?>
            <p class="login-box-msg" style = "color:red;">Error! Confirm Password Not matched to New Password.</p>
        <?php }else if(isset($_GET['err']) && $_GET['err'] == 11){?>
            <p class="login-box-msg" style = "color:red;">Error! Somthing went wrong.please try later.</p>
        <?php }?>
      <form action="login-process.php" method="post">
      <input type = "hidden" name = "actionType" value = "resetPassword">
      <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="New Password" name = "newPassword">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Confirm New Password" name = "confirmPassword">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Update new password</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
      <p class="mt-3 mb-1">
        <a href="login.php">Login</a>
      </p>
    </div>
    <!-- /.login-card-body -->
    <?php include "auth-footer.php";?>
