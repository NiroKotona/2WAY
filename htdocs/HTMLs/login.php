<?php
session_start();

if(isset($_SESSION['usr_id'])!="") {
    header("Location: ../");
}

include_once 'dbconnect.php';

//check if form is submitted
if (isset($_POST['login'])) {

    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $result = mysqli_query($con, "SELECT * FROM users WHERE email = '" . $email. "' and password = '" . md5($password) . "'");

    if ($row = mysqli_fetch_array($result)) {
        $_SESSION['usr_id'] = $row['id'];
        $_SESSION['usr_name'] = $row['name'];
        $_SESSION['usr_email'] = $row['email'];
        header("Location: ../");
    } else {
        $errormsg = "Incorrect Email or Password!!!";
    }
}
require_once 'header.php';
?>


    <div class="container">
<div class="aida"></div>
<div class="row">
        <div class="col-md-4 col-md-offset-4 well">
            <form role="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="loginform">
                <fieldset>
                    <legend>ログイン</legend>
                    
                    <div class="form-group">
                        <label for="name">メールアドレス</label>
                        <div class="mui-textfield">
                        <input type="text" name="email" placeholder="メールアドレス" required class="form-control" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name">パスワード</label>
                        <div class="mui-textfield">
                        <input type="password" name="password" placeholder="パスワード" required class="form-control" />
                        </div>
                    </div>

                    <div class="form-group">
                        <input type="submit" name="login" value="ログイン" class="mui-btn mui-btn--primary mui-btn--raised" />
                    </div>
                </fieldset>
            </form>
            <span class="text-danger"><?php if (isset($errormsg)) { echo $errormsg; } ?></span>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 col-md-offset-4 text-center">    
        アカウントを持っていませんか？<a href="register.php">こちらから登録</a>
        </div>
    </div>
</div>
<div align="center">
<?php
require_once 'footer.php';
?>