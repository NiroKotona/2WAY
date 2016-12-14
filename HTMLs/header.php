<?php
session_start();
include_once '../dbconnect.php';
$email= $_SESSION['usr_email'];
$default = "https://www.somewhere.com/homestar.jpg";
$size = 20;
$grav_url = "https://www.gravatar.com/avatar/" . md5( strtolower( trim( $email ) ) ) . "?d=" . urlencode( $default ) . "&s=" . $size;
$Asize = 300;
$Agrav_url = "https://www.gravatar.com/avatar/" . md5( strtolower( trim( $email ) ) ) . "?d=" . urlencode( $default ) . "&s=" . $Asize;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Style-Type" content="text/css" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>ドットゲーマーズ - ゲーマー向けコミュニティサイト</title>
    
    <link rel="shortcut icon" href="/favicon.ico" type="image/vnd.microsoft.ico"/>
	<link rel="icon" href="/favicon.png" type="image/png"/>
    <style type="text/css">/* BODYFONTS */
    body{
		font-family:'ヒラギノ角ゴ Pro W3', 'Hiragino Kaku Gothic Pro', 'メイリオ', Meiryo, 'ＭＳ Ｐゴシック', sans-serif !important;
	}
	</style>
	<!-- LOAD MATERIAL DESIGN -->
    <link href="../css/mui.css" rel="stylesheet" type="text/css" />
    <script src="//cdn.muicss.com/mui-0.8.0/js/mui.min.js"></script>
    <link type="text/css" rel="stylesheet" href="../css/materialize.min.css"  media="screen,projection"/>
    <script src="../css/material.min.js"></script>
    <link rel="stylesheet" href="../css/material.min.css">
    <link rel="stylesheet" href="../css/icon.css">
    <!--Import jQuery before materialize.js-->
    <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="../js/materialize.min.js"></script>
    <script src="../js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../js/bootstrap.min.js"></script>
    
    <!-- Fonts -->
    <link href='https://fonts.googleapis.com/css?family=Yellowtail' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Bangers' rel='stylesheet' type='text/css'>
    <!-- Bootstrap Core CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <style>
@import 'https://fonts.googleapis.com/css?family=Russo+One';
</style>

    <!-- Custom CSS -->
    <link href="../css/one-page-wonder.css" rel="stylesheet">
    <style type="text/css">/* ue no BAR koko tukatte nai */
    	.navbar-brand {
  		float: left;
  		height: 50px;
  		padding: 15px 15px;
  		font-size: 18px;
  		line-height: 20px;
  		font-family: 'Russo One', sans-serif;
}
    </style>
    <script type="text/javascript">
    $(document).ready(function() {
    $('.js-replace-no-image').error(function() {
        $(this).attr({
            src: '../img/noimage.png',
            height: '20px',
            alt: 'no image'
        });
    });
});
    </script>
    <style type="text/css">/* TITLE NO TOKO */
        h1.unmo{
            color: #fafafa;
            letter-spacing: 0;
            /*font-family: 'Yellowtail', cursive;*/
            font-family: 'Russo One', sans-serif;
            text-shadow: 0px 1px 0px #999,
                0px 2px 0px #888,
                0px 3px 0px #777,
                0px 4px 0px #666,
                0px 5px 0px #555,
                0px 6px 0px #444,
                0px 7px 0px #333,
                0px 8px 7px #001135 }
        }
        @font-face {
                font-family: 'Hina2ndGrade';
                src: url('../font/Hina2ndGrade.ttf') format('truetype');
        }
        @font-face {
            font-family: 'stole';
            src: url('http://typesquare.com/ja/service/ajaxSearch/sample/1378/2slb2adx5n3xohefpfy03ed63ayxct3a63djkbwql4') format('woff'), 
                    url('http://typesquare.com/ja/service/ajaxSearch/sample/1378/sv9tl5vf08aajh2bs5z5ep70yxo9g12upzswl3fhv4') format('truetype'), 
                    url('http://typesquare.com/ja/service/ajaxSearch/sample/1378/#TypeSquare') format('svg');
            src: url('http://typesquare.com/ja/service/ajaxSearch/sample/1378/cbxgwna07rgfpcgfgd7rv1clt0x56v6myfpy8z3em7')\9;
        }
    </style>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="../">.GamerZ</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li>
                    	<a href="../#discord">Discord</a>
                    </li>
                    <li>
                    	<a href="../#bbs" target="_blank">掲示板</a>
                    </li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                <?php if (isset($_SESSION['usr_id'])) { ?>
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php if (isset($_SESSION['grav_url'])) { ?><?php } else { ?><img src="<?php echo $grav_url; ?>" alt="<?php echo $_SESSION['usr_name']; ?>" class="avatar js-replace-no-image"><?php } ?> <?php echo $_SESSION['usr_name']; ?> <b class="caret"></b></a>
                <ul class="dropdown-menu">
                	<li><a href="../dashboard.php">ユーザー設定</a></li>
                	<li><a href="../logout.php">ログアウト</a></li>
                	</li>
                    </ul>
                    <?php } else { ?>
                	<li>
                		<form method="post" action="../register.php"><button class="mui-btn mui-btn--primary mui-btn--raised" formaction="../register.php">登録</button></form>
                	</li>
                	<li>
                		<form method="post" action="../login.php"><button class="mui-btn mui-btn--primary mui-btn--raised" formaction="../login.php">ログイン</button></form>
                	</li>
                	<?php } ?>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

    <!-- Full Width Image Header -->
    <header class="header-image">
        <div class="headline">
            <div class="container">
                <div align="title"><h1 class="unmo">.GamerZ</h1></div>
            </div>
        </div>
    </header>

    <div class="container">


        <div id="members">
        <div align="center">

</div>
<div class="aida"></div>
<?php if (isset($_SESSION['usr_id'])) { ?>
<?php } else { ?>
<div class="mui-panel">
<h3>重要なお知らせ</h3>データベース更新のトラブルにより、8件のアカウントデータを削除致しました。<br>お手数をお掛けして申し訳ありませんが、再登録の程、何とぞご協力をお願い申し上げます。
</div>
<?php } ?></div>
