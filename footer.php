<footer>
            <div class="row">
                <div class="col-lg-12">
                    <p class="copyright">Copyright &copy; 2WAY-JuNK 2013-2016　最終更新日:2016/11/14<p class="read">
                    <?php
                    if (!$pun_user['is_guest'])
                    	echo '<a href="misc.php?action=markread&amp;csrf_token='.pun_csrf_token().'">'.$lang_common['Mark all as read'].'</a>';
                    ?>
                    </p></p>
                </div>
            </div>
        </footer>
<?php

// Display debug info (if enabled/defined)
if (defined('PUN_DEBUG'))
{
	echo '<p id="debugtime">[ ';

	// Calculate script generation time
	$time_diff = sprintf('%.3f', get_microtime() - $pun_start);
	echo sprintf($lang_common['Querytime'], $time_diff, $db->get_num_queries());

	if (function_exists('memory_get_usage'))
	{
		echo ' - '.sprintf($lang_common['Memory usage'], file_size(memory_get_usage()));

		if (function_exists('memory_get_peak_usage'))
			echo ' '.sprintf($lang_common['Peak usage'], file_size(memory_get_peak_usage()));
	}

	echo ' ]</p>'."\n";
}


// End the transaction
$db->end_transaction();

// Display executed queries (if enabled)
if (defined('PUN_SHOW_QUERIES'))
	display_saved_queries();

$tpl_temp = trim(ob_get_contents());
$tpl_main = str_replace('<pun_footer>', $tpl_temp, $tpl_main);
ob_end_clean();
// END SUBST - <pun_footer>


// Close the db connection (and free up any result data)
$db->close();

// Spit out the page
exit($tpl_main);
