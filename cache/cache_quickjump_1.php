<?php

if (!defined('PUN')) exit;
define('PUN_QJ_LOADED', 1);
$forum_id = isset($forum_id) ? $forum_id : 0;

?>				<form id="qjump" method="get" action="viewforum.php">
					<div><label><span><?php echo $lang_common['Jump to'] ?><br /></span>
					<select name="id" onchange="window.location=('viewforum.php?id='+this.options[this.selectedIndex].value)">
						<optgroup label="Battlefield 1">
							<option value="2"<?php echo ($forum_id == 2) ? ' selected="selected"' : '' ?>>質問 / Question</option>
							<option value="5"<?php echo ($forum_id == 5) ? ' selected="selected"' : '' ?>>雑談 / General</option>
							<option value="6"<?php echo ($forum_id == 6) ? ' selected="selected"' : '' ?>>【PS4】プレイステーション4全般</option>
							<option value="7"<?php echo ($forum_id == 7) ? ' selected="selected"' : '' ?>>【PC】パソコン版全般</option>
						</optgroup>
						<optgroup label="Battlefield 4">
							<option value="3"<?php echo ($forum_id == 3) ? ' selected="selected"' : '' ?>>質問 / Question</option>
							<option value="8"<?php echo ($forum_id == 8) ? ' selected="selected"' : '' ?>>雑談 / General</option>
							<option value="9"<?php echo ($forum_id == 9) ? ' selected="selected"' : '' ?>>【PS4】プレイステーション4全般</option>
							<option value="10"<?php echo ($forum_id == 10) ? ' selected="selected"' : '' ?>>【PC】パソコン版全般</option>
							<option value="11"<?php echo ($forum_id == 11) ? ' selected="selected"' : '' ?>>【PS3】プレイステーション3全般</option>
						</optgroup>
						<optgroup label="機種別">
							<option value="4"<?php echo ($forum_id == 4) ? ' selected="selected"' : '' ?>>【質問】自作PC・パーツ類</option>
						</optgroup>
					</select></label>
					<input type="submit" value="<?php echo $lang_common['Go'] ?>" accesskey="g" />
					</div>
				</form>
