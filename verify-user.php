<?php 
    session_start();
    if(isset($_SESSION['verify-otp-page']) && !empty($_SESSION['verify-otp-page']) && $_SESSION['verify-otp-page'] == 'otp-verification-page'){
        
    }else{
        header('location:login.php');
    }
    $title = "AdminCPA | Forgot password - Collectcent Digital Media";
    include "auth-header.php";
?>
    <div class="card-body">
      <p class="login-box-msg">You forgot your password? Enter OTP to retrieve password.</p>
      <?php if(isset($_GET['err']) && $_GET['err'] == 1){?>
            <p class="login-box-msg" style = "color:red;">Error! Please Enter OTP.</p>
        <?php }else if(isset($_GET['err']) && $_GET['err'] == 2){?>
            <p class="login-box-msg" style = "color:red;">Error! Invalid OTP. Please Enter Valid OTP.</p>
        <?php }else if(isset($_GET['msg']) && $_GET['msg'] == 200){?>
          <p class="login-box-msg" style = "color:green;">Success! OTP successfully sent on Registered Mail.</p>
        <?php }?>
      <form action="login-process.php" method="post">
        <input type = "hidden" name = "actionType" value = "verifyOTPFrogotPass">
        <div class="input-group mb-3">
          <input type="text" class="form-control" placeholder="Enter OTP" name = "otpforgotpassword">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Verify OTP</button>
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
