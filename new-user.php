<?php 
    $title = "New User Register";
     include "auth-header.php";
?>
<style>
    .card-header{
        display:none;
    }
    select{
        color:#969ba0 !important;
    }
    .error-msg{
        position: absolute;
        top: 34px;
        font-size: 14px;
    }
</style>
<div class="register-box">
  <div class="card">
    <div class="card-body register-card-body">
      <p class="login-box-msg">Register a new membership</p>
        <p class= "messageBox"></p>
      <form >
        <div class="input-group mb-3">
          <input type="text" class="form-control" name = "fullName" id = "fullName" placeholder="Full name">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="email" class="form-control" name = "reg_email" id = "reg_email" onblur = "checkEmailExists();" placeholder="Email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="text" class="form-control" name = "phoneNumber" id = "phoneNumber" placeholder="Phone Number">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-mobile"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <select class="form-control" name = "userType" id = "userType">
            <option value = "">User Type</option>
            <option value = 3>Doctor</option>
            <option value = 4>Patient</option>
          </select>
          <div class="input-group-append">
            <div class="input-group-text">
              <!-- <span class="fas fa-lock"></span> -->
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" name = "reg_password" id = "reg_password" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" name = "c_reg_password" id = "c_reg_password" placeholder="Retype password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <!-- <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="agreeTerms" name="terms" value="agree">
              <label for="agreeTerms">
               I agree to the <a href="#">terms</a>
              </label>
            </div>
          </div> -->
          <!-- /.col -->
          <div class="col-4">
            <button type="button" onclick = "registerUser();" class="btn btn-primary btn-block">Register</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
      I already have a membership <a href="login.php" class="text-center">Login</a>
    </div>
    <!-- /.form-box -->
  </div><!-- /.card -->
</div>
<?php include "auth-footer.php";?>
<script>
    //validate full name
    const addElement = document.getElementById('fullName');
    restrictInputToAlphaAndSpace(addElement);
    function restrictInputToAlphaAndSpace(inputElement) {
        inputElement.addEventListener('input', (event) => {
            const inputValue = event.target.value;
            const formattedValue = inputValue.replace(/[^a-zA-Z\s]/g, '');
            event.target.value = formattedValue;
        });
    }
    //validate email
    const otherEmailElement = document.getElementById('reg_email');
    restrictInputToEmailCharacters(otherEmailElement);
    function restrictInputToEmailCharacters(inputElement) {
        inputElement.addEventListener('input', (event) => {
            const inputValue = event.target.value;
            const formattedValue = inputValue.replace(/[^a-zA-Z0-9\-_@.]/g, '');
            event.target.value = formattedValue;
        });
    }
    //validate phone number
    const otherMobileElement = document.getElementById('phoneNumber');
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

    $(document).ready(function () {
        function removeErrorMessages() {
            $('.error-msg').remove(); // Remove any existing error messages
            $('input, select, textarea').css("border", ""); // Reset border styles
        }
        $('#fullName, #reg_email, #phoneNumber, #userType, #reg_password, #c_reg_password').on('input', function () {
            removeErrorMessages();
        });
    });

    function checkEmailExists(){
        var u_email = $('#reg_email').val();
        var expr = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;

        if(u_email.trim() == ""){
            $('#reg_email').after('<span class="error-msg" style="color: #e31e24;">Error! Email is required.</span>');
            $('#reg_email').focus();
            return false;
        }else if(!expr.test(u_email)){
            $('#reg_email').after('<span class="error-msg" style="color: #e31e24;">Error! Invalid Email.</span>');
            $('#reg_email').focus();
            return false;
        }

        $.ajax({
            url: "register-process.php",
            type: "POST",
            data: {
                email_: u_email,
                callType: "checkEmail"
            },
            success: function (response) {
                var resp = JSON.parse(response);
                if(resp['status'] == 200 && resp['message'] == "already"){
                    $('#reg_email').after('<span class="error-msg" style="color: #e31e24;">'+resp['content']+'</span>');
                    $('#reg_email').focus();
                    $('#reg_email').val('');
                }
            },
        });
    }

    function registerUser(){
        var u_name = $('#fullName').val();
        var u_email = $('#reg_email').val();
        var u_phone = $('#phoneNumber').val();
        var u_type = $('#userType').val();
        var u_password = $('#reg_password').val();
        var c_u_password = $('#c_reg_password').val();
        var expr = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;

        if(u_name.trim() == ""){
            $('#fullName').after('<span class="error-msg" style="color: #e31e24;">Error! Full Name is required.</span>');
            $('#fullName').focus();
            return false;
        }else if(u_email.trim() == ""){
            $('#reg_email').after('<span class="error-msg" style="color: #e31e24;">Error! Email is required.</span>');
            $('#reg_email').focus();
            return false;
        }else if(!expr.test(u_email)){
            $('#reg_email').after('<span class="error-msg" style="color: #e31e24;">Error! Invalid Email.</span>');
            $('#reg_email').focus();
            return false;
        }else if(u_phone.trim() ==""){
            $('#phoneNumber').after('<span class="error-msg" style="color: #e31e24;">Error! Phone Number is required.</span>');
            $('#phoneNumber').focus();
            return false;
        }else if (u_phone.length !== 10) {
            $('#phoneNumber').after('<span class="error-msg" style="color: #e31e24;">Error! Phone Number must be exactly 10 digits long.</span>');
            $('#phoneNumber').focus();
            return false;
        }else if(u_type == ""){
            $('#userType').after('<span class="error-msg" style="color: #e31e24;">Error! Select User Type.</span>');
            $('#userType').focus();
            return false;
        }else if(u_password.trim() ==""){
            $('#reg_password').after('<span class="error-msg" style="color: #e31e24;">Error! Password is required.</span>');
            $('#reg_password').focus();
            return false;
        }else if(c_u_password.trim() ==""){
            $('#c_reg_password').after('<span class="error-msg" style="color: #e31e24;">Error! Confirm Password is required.</span>');
            $('#c_reg_password').focus();
            return false;
        }else if(u_password != c_u_password){
            $('#c_reg_password').after('<span class="error-msg" style="color: #e31e24;">Error! Password did not match with confirm password.</span>');
            $('#c_reg_password').focus();
            return false;
        }

        $.ajax({
            url: "register-process.php",
            type: "POST",
            data: {
                name_: u_name,
                email_: u_email,
                phone_: u_phone,
                type_: u_type,
                password_: u_password,
                c_password_: c_u_password,
                callType: "registerUser"
            },
            success: function (response) {
                var resp = JSON.parse(response);
                if(resp['status'] == 200 && resp['message'] == "success"){
                    $('.messageBox').html('<span style="color:green">'+resp['content']+'</span>')
                    $('#fullName').val('');
                    $('#reg_email').val('');
                    $('#phoneNumber').val('');
                    $('#userType').val('');
                    $('#reg_password').val('');
                    $('#c_reg_password').val('');
                }else if(resp['status'] == 400 && resp['message'] == "failed"){
                    $('.messageBox').html('<span style="color:red">'+resp['content']+'</span>')
                }
            },
        });
    }
</script>
