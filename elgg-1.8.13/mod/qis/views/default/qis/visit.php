<?php
$request= elgg_extract('visit', $vars, FALSE);
$group_guid= elgg_extract('group_guid', $vars, FALSE);

if (!$request) {
	return TRUE;
}
$user_name = get_entity($request->user_guid)->name;
if ($request->proceed) {
	$mod_but .= elgg_view('input/submit', array('value' => elgg_echo('Schedule')));
	$mod_form .= elgg_view('input/form', array('body' => $mod_but, 'action' => "{$CONFIG->url}qis/manage_visit/$group_guid/$request->guid"));
}

echo "<tr><td>$request->guid</td><td>$user_name</td><td>$request->medical_visit</td><td>$request->blood_test</td><td>$request->fingerprints</td><td>$mod_form</td></tr>";
