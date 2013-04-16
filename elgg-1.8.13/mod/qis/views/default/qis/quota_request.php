<?php
/**
 * File renderer.
 *
 * @package ElggFile
 */

$full = elgg_extract('full_view', $vars, FALSE);
$quota = elgg_extract('quota', $vars, FALSE);
$group_guid = elgg_extract('group_guid', $vars, FALSE);

if (!$quota) {
	return TRUE;
}

$quantity = $quota->quantity;
$citizenship = $quota->citizenship;
$occupation = $quota->occupation;
$gender = $quota->gender;
$status = $quota->status;
if(($user = elgg_get_logged_in_user_entity()) && $user->isAdmin()){
	$action = 'approve_reject';
} else {
	$action = 'modify_delete';
}
$mod_but .= elgg_view('input/submit', array('value' => elgg_echo($action)));
$mod_form .= elgg_view('input/form', array('body' => $mod_but, 'action' => "{$CONFIG->url}qis/manage_quota_request/$group_guid/$quota->guid"));

if (is_array($quantity)) {
	$num_lines = count($quantity);
	for ($i = 0;$i <$num_lines;$i++) {
		echo "<tr><td>$quota->guid</td><td>$quantity[$i]</td><td>$citizenship[$i]</td><td>$gender[$i]</td><td>$occupation[$i]</td><td>$status[$i]</td><td>$mod_form</td></tr>";
	}
} else {
	echo "<tr><td>$quota->guid</td><td>$quantity</td><td>$citizenship</td><td>$gender</td><td>$occupation</td><td>$status</td><td>$mod_form</td></tr>";
}
