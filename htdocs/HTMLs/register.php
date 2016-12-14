<?php
session_start();

if(isset($_SESSION['usr_id'])) {
    header("Location: index.php");
}

include_once 'dbconnect.php';

//set validation error flag as false
$error = false;

//check if form is submitted
if (isset($_POST['signup'])) {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $cpassword = mysqli_real_escape_string($con, $_POST['cpassword']);
    
    //name can contain only alpha characters and space
    if (!preg_match("/^[a-zA-Z0-9_]+$/",$name)) {
        $error = true;
        $name_error = "アルファベットおよび数字列を入力してください";
    }
    if(!filter_var($email,FILTER_VALIDATE_EMAIL)) {
        $error = true;
        $email_error = "Please Enter Valid Email ID";
    }
    if(strlen($password) < 6) {
        $error = true;
        $password_error = "Password must be minimum of 6 characters";
    }
    if($password != $cpassword) {
        $error = true;
        $cpassword_error = "Password and Confirm Password doesn't match";
    }
    if (!$error) {
        if(mysqli_query($con, "INSERT INTO users(name,email,password) VALUES('" . $name . "', '" . $email . "', '" . md5($password) . "')")) {
            $successmsg = "登録が完了しました！<a href='login.php'>こちらからログイン</a>";
        } else {
            $errormsg = "Error in registering...Please try again later!";
        }
    }
}
require_once 'header.php';
?>



    <div class="container">


        
<div class="aida"></div>
<div class="row">
        <div class="col-md-4 col-md-offset-4 well">
            <form role="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="signupform">
                <fieldset>
                    <legend>新規登録</legend>

                    <div class="form-group">
                        <label for="name">ゲーマーID</label>
                        <div class="mui-textfield">
                        <input type="text" name="name" placeholder="ゲーマーID" required value="<?php if($error) echo $name; ?>" />
                        </div>
                        <span class="text-danger"><?php if (isset($name_error)) echo $name_error; ?></span>
                    </div>
                    
                    <div class="form-group">
                        <label for="name">メールアドレス</label>
                        <div class="mui-textfield">
                        <input type="text" name="email" placeholder="メールアドレス" required value="<?php if($error) echo $email; ?>" />
                        </div>
                        <span class="text-danger"><?php if (isset($email_error)) echo $email_error; ?></span>
                    </div>

                    <div class="form-group">
                        <label for="name">パスワード</label>
                        <div class="mui-textfield">
                        <input type="password" name="password" placeholder="パスワード" required />
                        </div>
                        <span class="text-danger"><?php if (isset($password_error)) echo $password_error; ?></span>
                    </div>

                    <div class="form-group">
                        <label for="name">パスワードの確認</label>
                        <div class="mui-textfield">
                        <input type="password" name="cpassword" placeholder="パスワードの確認" required />
                        </div>
                        <span class="text-danger"><?php if (isset($cpassword_error)) echo $cpassword_error; ?></span>
                    </div>

                    <div class="form-group">
                        <input type="submit" name="signup" value="登録" class="mui-btn mui-btn--primary mui-btn--raised" />
                    </div>
                </fieldset>
            </form>
            <span class="text-success"><?php if (isset($successmsg)) { echo $successmsg; } ?></span>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 col-md-offset-4 text-center">    
        アカウントを持っていますか？<a href="login.php">こちらからログイン</a>
        </div>
    </div>
</div>
<div align="center">
<?php
require_once 'footer.php';
?>