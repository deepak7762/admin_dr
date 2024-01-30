<?php 
    $title = "AdminCPA | Forgot password - Collectcent Digital Media";
    include "auth-header.php";
?>
    <div class="card-body">
      <p class="login-box-msg">You forgot your password? Here you can easily retrieve a new password.</p>
      <?php if(isset($_GET['err']) && $_GET['err'] == 5){?>
            <p class="login-box-msg" style = "color:red;">Error! Please Enter Email.</p>
        <?php }else if(isset($_GET['err']) && $_GET['err'] == 6){?>
            <p class="login-box-msg" style = "color:red;">Error! Invalid Verification Details.</p>
        <?php }else if(isset($_GET['err']) && $_GET['err'] == 400){?>
          <p class="login-box-msg" style = "color:red;">Error! Failed to send OTP.</p>
        <?php }?>
      <form action="login-process.php" id="loginForm" method="post">
        <input type = "hidden" name = "actionType" value = "verifyEmailFrogotPass">
        <div class="input-group mb-3">
          <input type="email" class="form-control" placeholder="Email" name = "emailforgotpassword">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <button type="submit" id="submitBtn" class="btn btn-primary btn-block">Verify Email</button>
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
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
      $(document).ready(function () {
          $('#loginForm').submit(function () {
            $('#submitBtn').html('<i class="fa fa-spinner fa-spin"></i>');
            $('#submitBtn').prop('disabled', true);
          });
      });
  </script>
