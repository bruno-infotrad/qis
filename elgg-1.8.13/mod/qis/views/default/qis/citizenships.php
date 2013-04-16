<?php
/**
 * File renderer.
 *
 * @package ElggFile
 */

$full = elgg_extract('full_view', $vars, FALSE);
$citizenships = elgg_extract('citizenships', $vars, FALSE);
$user_guid = elgg_extract('user_guid', $vars, FALSE);
$group_guid = elgg_extract('group_guid', $vars, FALSE);
$content = '';
if ($citizenships) {
	$content = '<div><table id="qis_ris"><tr><th>Citizenship</th><th>Document Number</th><th>Passport Copy</th><th>Date of Issue</th><th>Expiry Date</th><th>Action</th></tr>';
	foreach ($citizenships as $citizenship) {
		$content .= elgg_view('qis/citizenship',array('group_guid'=> $group_guid, 'citizenship' => $citizenship, 'user_guid' => $user_guid));
	}
        $content .= '</table></div>';
}
$content .= '<div class="elgg-foot">';
$sub_but = elgg_view('input/submit', array('value' => elgg_echo('new_citizenship')));
$content .= elgg_view('input/form', array('body' => $sub_but, 'action' => "{$CONFIG->url}qis/manage_citizenship/$group_guid/$user_guid",'class' => 'elgg-button elgg-button-submit'));
$content .= ' ';
$sub_but = elgg_view('input/submit', array('value' => elgg_echo('main_menu')));
$content .= elgg_view('input/form', array('body' => $sub_but, 'action' => "{$CONFIG->url}",'class' => 'elgg-button elgg-button-submit'));
$content .= '</div>';
echo $content;
