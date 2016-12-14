<?php
require_once 'header.php';
?>

<?php if (isset($_SESSION['usr_id'])) { ?>
<?php } else { ?>
<div class="mui-panel">
<h3>ゲーマー向け次世代コミュニティサイト「ドットゲーマーズ」</h3>完全アカウント制の情報交換サイト！<br>仲間を見つけてプレイしよう！<div align="right"><form method="post" action="../register.php"><button class="mui-btn mui-btn--primary mui-btn--raised" formaction="../register.php">今すぐ登録</button></form></div>
</div><?php } ?>
<div class="mui-panel">
<div id="bbs"><h2 class="featurette-heading1">掲示板</h2></div>
<h4>ゲーム</h4>
<span class="mdl-chip mdl-chip--contact"><span class="mdl-chip__contact mdl-color--teal mdl-color-text--white"><img src="../icons/minecraft.png" height="18px"></span><span class="mdl-chip__text"><a href="#" class="blist">マインクラフト</a></span></span>
</div>
	

        <hr class="featurette-divider">
        
        
        
        
        <div id="discord">
        <script type="text/javascript" src="js/discord.min.js"></script>
<script type="text/javascript">
discordWidget.init({
  serverId: '194402106294140928',
  title: 'オンライン:',
  join: true,
  alphabetical: false,
  theme: 'light',
  showAllUsers: false,
  allUsersDefaultState: false,
  showNick: true
});
discordWidget.render();
</script>
</div>

<div class="discord-widget"></div>
<?php
require_once 'footer.php';
?>