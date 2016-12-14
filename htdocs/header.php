<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Style-Type" content="text/css" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <link rel="shortcut icon" href="/favicon.ico" type="image/vnd.microsoft.ico"/>
	<link rel="icon" href="/favicon.png" type="image/png"/>
    <style type="text/css">/* BODYFONTS */
    body{
		font-family:'ヒラギノ角ゴ Pro W3', 'Hiragino Kaku Gothic Pro', 'メイリオ', Meiryo, 'ＭＳ Ｐゴシック', sans-serif !important;
	}
	</style>
	<!--Import jQuery before materialize.js-->
    <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="../js/materialize.js"></script>
    <script src="../js/jquery.js"></script>
	<!-- LOAD MATERIAL DESIGN -->
    <link href="../css/mui.css" rel="stylesheet" type="text/css" />
    <script src="//cdn.muicss.com/mui-0.8.0/js/mui.min.js"></script>
    <link type="text/css" rel="stylesheet" href="../css/materialize.min.css"  media="screen,projection"/>
    <script src="../js/materialize.min.js"></script>
    <link rel="stylesheet" href="../css/materialize.min.css">
    <link rel="stylesheet" href="../css/icon.css">

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
<script type="text/javascript">
    window._pt_lt = new Date().getTime();
    window._pt_sp_2 = [];
    _pt_sp_2.push('setAccount,689baf3b');
    var _protocol = (("https:" == document.location.protocol) ? " https://" : " http://");
    (function() {
        var atag = document.createElement('script'); atag.type = 'text/javascript'; atag.async = true;
        atag.src = _protocol + 'js.ptengine.jp/pta.js';
        var stag = document.createElement('script'); stag.type = 'text/javascript'; stag.async = true;
        stag.src = _protocol + 'js.ptengine.jp/pts.js';
        var s = document.getElementsByTagName('script')[0]; 
        s.parentNode.insertBefore(atag, s); s.parentNode.insertBefore(stag, s);
    })();
</script>
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
            src: url('//typesquare.com/ja/service/ajaxSearch/sample/1378/2slb2adx5n3xohefpfy03ed63ayxct3a63djkbwql4') format('woff'), 
                    url('//typesquare.com/ja/service/ajaxSearch/sample/1378/sv9tl5vf08aajh2bs5z5ep70yxo9g12upzswl3fhv4') format('truetype'), 
                    url('//typesquare.com/ja/service/ajaxSearch/sample/1378/#TypeSquare') format('svg');
            src: url('//typesquare.com/ja/service/ajaxSearch/sample/1378/cbxgwna07rgfpcgfgd7rv1clt0x56v6myfpy8z3em7')\9;
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
    

    <!-- Full Width Image Header -->
    <header class="header-image">
        <div class="headline">
            <div class="container">
                <div align="title"><h1 class="unmo">BFCh.</h1></div>
            </div>
        </div>
    </header>
<?php

/**
 * Copyright (C) 2008-2012 FluxBB
 * based on code by Rickard Andersson copyright (C) 2002-2008 PunBB
 * License: http://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 */

// Make sure no one attempts to run this script "directly"
if (!defined('PUN'))
	exit;

// Send no-cache headers
header('Expires: Thu, 21 Jul 1977 07:30:00 GMT'); // When yours truly first set eyes on this world! :)
header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache'); // For HTTP/1.0 compatibility

// Send the Content-type header in case the web server is setup to send something else
header('Content-type: text/html; charset=utf-8');

// Prevent site from being embedded in a frame unless FORUM_FRAME_OPTIONS is set
// to a valid X-Frame-Options header value or false
if (defined('FORUM_FRAME_OPTIONS'))
{
	if (preg_match('/^(?:allow-from|deny|sameorigin)/i', FORUM_FRAME_OPTIONS))
		header('X-Frame-Options: '.FORUM_FRAME_OPTIONS);
}
else
	header('X-Frame-Options: deny');

// Load the template
if (defined('PUN_ADMIN_CONSOLE'))
	$tpl_file = 'admin.tpl';
else if (defined('PUN_HELP'))
	$tpl_file = 'help.tpl';
else
	$tpl_file = 'main.tpl';

if (file_exists(PUN_ROOT.'style/'.$pun_user['style'].'/'.$tpl_file))
{
	$tpl_file = PUN_ROOT.'style/'.$pun_user['style'].'/'.$tpl_file;
	$tpl_inc_dir = PUN_ROOT.'style/'.$pun_user['style'].'/';
}
else
{
	$tpl_file = PUN_ROOT.'include/template/'.$tpl_file;
	$tpl_inc_dir = PUN_ROOT.'include/user/';
}

$tpl_main = file_get_contents($tpl_file);

// START SUBST - <pun_include "*">
preg_match_all('%<pun_include "([^"]+)">%i', $tpl_main, $pun_includes, PREG_SET_ORDER);

foreach ($pun_includes as $cur_include)
{
	ob_start();

	$file_info = pathinfo($cur_include[1]);
	
	if (!in_array($file_info['extension'], array('php', 'php4', 'php5', 'inc', 'html', 'txt'))) // Allow some extensions
		error(sprintf($lang_common['Pun include extension'], pun_htmlspecialchars($cur_include[0]), basename($tpl_file), pun_htmlspecialchars($file_info['extension'])));
		
	if (strpos($file_info['dirname'], '..') !== false) // Don't allow directory traversal
		error(sprintf($lang_common['Pun include directory'], pun_htmlspecialchars($cur_include[0]), basename($tpl_file)));

	// Allow for overriding user includes, too.
	if (file_exists($tpl_inc_dir.$cur_include[1]))
		require $tpl_inc_dir.$cur_include[1];
	else if (file_exists(PUN_ROOT.'include/user/'.$cur_include[1]))
		require PUN_ROOT.'include/user/'.$cur_include[1];
	else
		error(sprintf($lang_common['Pun include error'], pun_htmlspecialchars($cur_include[0]), basename($tpl_file)));

	$tpl_temp = ob_get_contents();
	$tpl_main = str_replace($cur_include[0], $tpl_temp, $tpl_main);
	ob_end_clean();
}
// END SUBST - <pun_include "*">


// START SUBST - <pun_language>
$tpl_main = str_replace('<pun_language>', $lang_common['lang_identifier'], $tpl_main);
// END SUBST - <pun_language>


// START SUBST - <pun_content_direction>
$tpl_main = str_replace('<pun_content_direction>', $lang_common['lang_direction'], $tpl_main);
// END SUBST - <pun_content_direction>


// START SUBST - <pun_head>
ob_start();

// Define $p if it's not set to avoid a PHP notice
$p = isset($p) ? $p : null;

// Is this a page that we want search index spiders to index?
if (!defined('PUN_ALLOW_INDEX'))
	echo '<meta name="ROBOTS" content="NOINDEX, FOLLOW" />'."\n";

?>
<title><?php echo generate_page_title($page_title, $p) ?></title>
<link rel="stylesheet" type="text/css" href="style/<?php echo $pun_user['style'].'.css?' ?>" />
<?php

if (defined('PUN_ADMIN_CONSOLE'))
{
	if (file_exists(PUN_ROOT.'style/'.$pun_user['style'].'/base_admin.css'))
		echo '<link rel="stylesheet" type="text/css" href="style/'.$pun_user['style'].'/base_admin.css" />'."\n";
	else
		echo '<link rel="stylesheet" type="text/css" href="style/imports/base_admin.css" />'."\n";
}

if (isset($required_fields))
{
	// Output JavaScript to validate form (make sure required fields are filled out)

?>
<script type="text/javascript">
/* <![CDATA[ */
function process_form(the_form)
{
	var required_fields = {
<?php
	// Output a JavaScript object with localised field names
	$tpl_temp = count($required_fields);
	foreach ($required_fields as $elem_orig => $elem_trans)
	{
		echo "\t\t\"".$elem_orig.'": "'.addslashes(str_replace('&#160;', ' ', $elem_trans));
		if (--$tpl_temp) echo "\",\n";
		else echo "\"\n\t};\n";
	}
?>
	if (document.all || document.getElementById)
	{
		for (var i = 0; i < the_form.length; ++i)
		{
			var elem = the_form.elements[i];
			if (elem.name && required_fields[elem.name] && !elem.value && elem.type && (/^(?:text(?:area)?|password|file)$/i.test(elem.type)))
			{
				alert('"' + required_fields[elem.name] + '" <?php echo $lang_common['required field'] ?>');
				elem.focus();
				return false;
			}
		}
	}
	return true;
}
/* ]]> */
</script>
<?php

}

if (!empty($page_head))
	echo implode("\n", $page_head)."\n";

$tpl_temp = trim(ob_get_contents());
$tpl_main = str_replace('<pun_head>', $tpl_temp, $tpl_main);
ob_end_clean();
// END SUBST - <pun_head>


// START SUBST - <body>
if (isset($focus_element))
{
	$tpl_main = str_replace('<body onload="', '<body onload="document.getElementById(\''.$focus_element[0].'\').elements[\''.$focus_element[1].'\'].focus();', $tpl_main);
	$tpl_main = str_replace('<body>', '<body onload="document.getElementById(\''.$focus_element[0].'\').elements[\''.$focus_element[1].'\'].focus()">', $tpl_main);
}
// END SUBST - <body>


// START SUBST - <pun_page>
$tpl_main = str_replace('<pun_page>', htmlspecialchars(basename($_SERVER['SCRIPT_NAME'], '.php')), $tpl_main);
// END SUBST - <pun_page>


// START SUBST - <pun_navlinks>
$links = array();

// Index should always be displayed

?>
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
                <a class="navbar-brand" href="../">BFCh.</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li>
                    	<a href="search.php"><?php echo ''.$lang_common['Search'].''; ?></a>
                    </li>
                    <li>
                    	<a href="userlist.php"><?php echo ''.$lang_common['User list'].''; ?></a>
                    </li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                <?php
                if ($pun_user['is_guest'])
                {
                	echo '<li><form method="post" action="../register.php"><button class="mui-btn mui-btn--primary mui-btn--raised" formaction="../register.php">'.$lang_common['Register'].'</button></form></li>';
                	echo '<li><form method="post" action="../login.php"><button class="mui-btn mui-btn--primary mui-btn--raised" formaction="../login.php">'.$lang_common['Login'].'</button></form></li>';
                }
                else
                {
                	echo '<li class="dropdown">';
                	echo '<a href="#" class="dropdown-toggle" data-toggle="dropdown">';
                	echo ''.$lang_common['Topics'].'';
                	echo '<b class="caret"></b></a><ul class="dropdown-menu"><li><a href="search.php?action=show_replies" title="'.$lang_common['Show posted topics'].'">'.$lang_common['Posted topics'].'</a></li>';
                	echo '<li><a href="search.php?action=show_new" title="'.$lang_common['Show new posts'].'">'.$lang_common['New posts header'].'</a></li>';
                	echo '<li><a href="search.php?action=show_recent" title="'.$lang_common['Show active topics'].'">'.$lang_common['Active topics'].'</a></li>';
                	echo '<li><a href="search.php?action=show_unanswered" title="'.$lang_common['Show unanswered topics'].'">'.$lang_common['Unanswered topics'].'</a></li>';
                	echo "</li>";
                	echo "</ul>";
                	echo "</li>";
                	echo '<li class="dropdown">';
                	echo '<a href="#" class="dropdown-toggle" data-toggle="dropdown">';
                	echo "{$pun_user['username']}";
                	echo "<b class=\"caret\"></b></a><ul class=\"dropdown-menu\"><li><a href=\"profile.php?id={$pun_user['id']}\"".'>'.$lang_common['Profile'].'</a></li>';
                	echo "<li><a href=\"login.php?action=out&amp;id={$pun_user['id']}&amp;",'csrf_token='.pun_csrf_token().'">'.$lang_common['Logout'].'</a>';
                }
                if ($pun_user['is_admmod'])
                	echo '<a href="admin_index.php">'.$lang_common['Admin'].'</a>';
                ?>
                	</li>
                	</li>
                    </ul>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>
    </div>
<?php
// Are there any additional navlinks we should insert into the array before imploding it?
if ($pun_user['g_read_board'] == '1' && $pun_config['o_additional_navlinks'] != '')
{
	if (preg_match_all('%([0-9]+)\s*=\s*(.*?)\n%s', $pun_config['o_additional_navlinks']."\n", $extra_links))
	{
		// Insert any additional links into the $links array (at the correct index)
		$num_links = count($extra_links[1]);
		for ($i = 0; $i < $num_links; ++$i)
			array_splice($links, $extra_links[1][$i], 0, array('<li id="navextra'.($i + 1).'">'.$extra_links[2][$i].'</li>'));
	}
}

// END SUBST - <pun_navlinks>


// START SUBST - <pun_status>
$page_statusinfo = $page_topicsearches = array();

if ($pun_user['is_guest'])
	$page_statusinfo = '';
else
{

	if ($pun_user['is_admmod'])
	{
		if ($pun_config['o_report_method'] == '0' || $pun_config['o_report_method'] == '2')
		{
			$result_header = $db->query('SELECT 1 FROM '.$db->prefix.'reports WHERE zapped IS NULL') or error('Unable to fetch reports info', __FILE__, __LINE__, $db->error());

			if ($db->result($result_header))
				$page_statusinfo[] = '<li class="reportlink"><span><strong><a href="admin_reports.php">'.$lang_common['New reports'].'</a></strong></span></li>';
		}

		if ($pun_config['o_maintenance'] == '1')
			$page_statusinfo[] = '<li class="maintenancelink"><span><strong><a href="admin_options.php#maintenance">'.$lang_common['Maintenance mode enabled'].'</a></strong></span></li>';
	}
}



// Generate all that jazz
$tpl_temp = '<div id="brdwelcome">';

// The status information
if (is_array($page_statusinfo))
{
	$tpl_temp .= "\n\t\t\t".'<ul class="conl">';
	$tpl_temp .= "\n\t\t\t\t".implode("\n\t\t\t\t", $page_statusinfo);
	$tpl_temp .= "\n\t\t\t".'</ul>';
}
else
	$tpl_temp .= "\n\t\t\t".$page_statusinfo;

// Generate quicklinks
if (!empty($page_topicsearches))
{
	$tpl_temp .= "\n\t\t\t".'<ul class="conr">';
	$tpl_temp .= "\n\t\t\t\t".'<li><span>'.$lang_common['Topic searches'].' '.implode(' | ', $page_topicsearches).'</span></li>';
	$tpl_temp .= "\n\t\t\t".'</ul>';
}

$tpl_temp .= "\n\t\t\t".'<div class="clearer"></div>'."\n\t\t".'</div>';

$tpl_main = str_replace('<pun_status>', $tpl_temp, $tpl_main);
// END SUBST - <pun_status>


// START SUBST - <pun_announcement>
if ($pun_user['g_read_board'] == '1' && $pun_config['o_announcement'] == '1')
{
	ob_start();

?>
<div id="announce" class="block">
	<div class="hd"><h2><span><?php echo $lang_common['Announcement'] ?></span></h2></div>
	<div class="box">
		<div id="announce-block" class="inbox">
			<div class="usercontent"><?php echo $pun_config['o_announcement_message'] ?></div>
		</div>
	</div>
</div>
<?php

	$tpl_temp = trim(ob_get_contents());
	$tpl_main = str_replace('<pun_announcement>', $tpl_temp, $tpl_main);
	ob_end_clean();
}
else
	$tpl_main = str_replace('<pun_announcement>', '', $tpl_main);
// END SUBST - <pun_announcement>


// START SUBST - <pun_main>
ob_start();


define('PUN_HEADER', 1);

if ($pun_user['is_guest'])
                {
                	echo '<div class="mui-panel"><font size="4px">クラメン・フレンド募集から雑談などなんでも！<br>まずは、登録してみましょう！</font><form method="post" action="../register.php"><div class="right" align="right"><button class="mui-btn mui-btn--primary mui-btn--raised" formaction="../register.php">'.$lang_common['Register'].'</button></div></form></div>';
                }