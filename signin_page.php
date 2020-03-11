<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>eMenu - Sign In</title>
    <link rel="stylesheet" type="text/css" href="signin_styles.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<body>

<div id="blanket"></div>
<div id="content">
    <img src="eMenu_logo.png" alt="eMenu Logo">
    <form method="post" action="eMenu_controller.php" id="signin-form">
        <input type="hidden" name="page" value="SigninPage">
        <input type="hidden" name="command" value="SignIn">
        <label class="form-label">Account ID</label><br>
        <input class="field" type="text" name="accountid" required autofocus>
        <br>
        <label class="form-label">Password</label><br>
        <input  class="field" type="password" name="password" required>
        <br>
        <input id="submit" type="submit" value="Sign In"><br>
        <label id="forgot_password" style="left: 50%">forgot password?</label>

        <?php if (!empty($error_msg_signin)) echo '<br><br>' . $error_msg_signin; ?>
    </form>
</div>

<!--==================================Modals=======================================-->
<div id="forgot_password_modal" style="display: none">
    <h2 style="text-align: center">Forgot Password?</h2>
    <br><br>
    <p>Enter your email and click the "reset password" button below and check your email inbox</p>
    <p>We will send you a password reset link.<br>If you do not see an email don't forget to
        check your junk mail folder.</p>
    <br>
    <label class="form-label">Email:</label>
    <input id="password_reset_email" type="text" required><br><br>
    <button id="modal_reset">Reset Password</button><br><br>
    <p id="modal_resend">Didn't get an email? Click here to Re-send</p>
    <p id="modal_cancel">Cancel</p>
</div>
</body>

<script>
    window.addEventListener('load', function () {
        document.getElementById('content').style.top = 'calc(50% - 161px)';
        document.getElementById('forgot_password_modal').style.top = 'calc(50% - 200px)';
    });

    $('#forgot_password').click( function () {
        $('#blanket').css("display", "block");
        $('#forgot_password_modal').css("display", "block");
    });

    $('#modal_cancel').click( function () {
        $('#blanket').css("display", "none");
        $('#forgot_password_modal').css("display", "none");
    });

    $('#blanket').click( function () {
        $('#blanket').css("display", "none");
        $('#forgot_password_modal').css("display", "none");
    });

</script>
</html>