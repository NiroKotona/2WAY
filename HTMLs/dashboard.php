<?php
require_once 'header.php';
?>

    <div class="container">


        <div id="members">
        <div align="center">

</div>
<div class="aida"></div>
	<?php if (isset($_SESSION['usr_id'])) { ?>
                <div class="mui-panel">
                <img src="<?php echo $Agrav_url; ?>" " class="featurette-image img-circle img-responsive pull-left js-replace-no-image-icon">
                <h2 class="featurette-heading1">アバター</h2>
                <p class="lead"><a href="https://ja.gravatar.com/" target="_blank">Gravatar</a>でアバターを変更</p><br><p class="lead">メールアドレスを [ <?php echo $_SESSION['usr_email']; ?> ] で設定してください</p>
                    <?php } else { ?>
                    <a href="../login.php">ログイン</a>してください
                	<?php } ?></div>
<?php
require_once 'footer.php';
?>