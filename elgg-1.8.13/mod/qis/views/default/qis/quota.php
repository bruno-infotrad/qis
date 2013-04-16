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
$gender= $quota->gender;

$action = 'delete';
$mod_but .= elgg_view('input/hidden', array('name' => 'container_guid', 'value' => $group_guid));
$mod_but .= elgg_view('input/hidden', array('name' => 'quota_guid', 'value' => $quota->guid));
$mod_but .= elgg_view('input/submit', array('name' => 'submit','value' => elgg_echo($action)));
$mod_form .= elgg_view('input/form', array('body' => $mod_but, 'action' => "action/qis/manage_quota"));
//$mod_form .= elgg_view('input/form', array('body' => $mod_but, 'action' => "{$CONFIG->url}qis/manage_quota/$group_guid/$quota->guid"));

if (is_array($quantity)) {
	$num_lines = count($quantity);
	for ($i = 0;$i <$num_lines;$i++) {
		echo "<tr><td>$quota->request_guid</td><td>$quantity[$i]</td><td>$citizenship[$i]</td><td>$gender[$i]</td><td>$occupation[$i]</td><td>$mod_form</td></tr>";
	}
} else {
	echo "<tr><td>$quota->request_guid</td><td>$quantity</td><td>$citizenship</td><td>$gender</td><td>$occupation</td><td>$mod_form</td></tr>";
}
