<?php 
    if(isset($_COOKIE['userEmail']) && isset($_COOKIE['userPassword'])){
        // $userName = $_COOKIE['userName'];
        $userEmail = $_COOKIE['userEmail'];
        $userPass = $_COOKIE['userPassword'];
    }else{
        // $userName = "";
        $userEmail = "";
        $userPass = "";
    }
    $title = "User Login";
    include "auth-header.php";
?> 
<style>
    .login-box-msg {
        padding: 0 20px 10px !important;
    }
</style>
    <div class="card-body">
      <p class="login-box-msg">Sign in to start your session</p>
        <?php if(isset($_GET['err']) && $_GET['err'] == 1){?>
            <p class="login-box-msg" style = "color:red;">Error! Please Enter User Name.</p>
        <?php }else if(isset($_GET['err']) && $_GET['err'] == 2){?>
            <p class="login-box-msg" style = "color:red;">Error! Please Enter Email.</p>
        <?php }else if(isset($_GET['err']) && $_GET['err'] == 3){?>
            <p class="login-box-msg" style = "color:red;">Error! Please Enter Password.</p>
        <?php }else if(isset($_GET['err']) && $_GET['err'] == 4){?>
            <p class="login-box-msg" style = "color:red;">Error! Invalid Login Details.</p>
        <?php }else if(isset($_GET['msg']) && $_GET['msg'] == 1){?>
            <p class="login-box-msg" style = "color:green;">Success! Password Reset Successfully.</p>
        <?php }else if(isset($_GET['err']) && $_GET['err'] == "login"){?>
            <p class="login-box-msg" style = "color:red;">Error! Please login to start your journey.</p>
        <?php }?>
      <form action="login-process.php" method="post">
        <input type = "hidden" name = "actionType" value = "loginUser">
        <!-- <div class="input-group mb-3">
          <input type="text" class="form-control" placeholder="User name" name = "userName" value = "<?=$userName?>">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div> -->
        <div class="input-group mb-3">
          <input type="email" class="form-control" placeholder="Email" name = "userEmail" value = "<?=$userEmail?>">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>        
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Password" name = "userPassword" value = "<?=$userPass?>">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="remember" name = "rememberMeCheck">
              <label for="remember">
                Remember Me
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
      <p class="mb-1">
        <a href="forgot-password.php">I forgot my password</a>
      </p>
      <p class="mb-0">
      Register a new membership <a href="new-user.php" class="text-center">Sign up</a>
      </p>
    </div>
    <!-- /.card-body -->
<?php include "auth-footer.php";?>
