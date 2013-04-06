<?php
$immigration_request= elgg_extract('immigration_request', $vars, FALSE);

if (!$immigration_request) {
	return TRUE;
}

$type = $immigration_request->qistype;
$duration = $immigration_request->duration;
$passport_guid = $immigration_request->passport_guid;
$country = get_entity($passport_guid)->country;
$user_guid = $immigration_request->user_guid;
$user_name = get_entity($user_guid)->name;
$mod_but .= elgg_view('input/submit', array('value' => elgg_echo('modify_delete')));
$mod_form .= elgg_view('input/form', array('body' => $mod_but, 'action' => "{$CONFIG->url}qis/manage_rp_request/$immigration_request->guid"));

echo "<tr><td>$type</td><td>$user_name</td><td>$country</td><td>$duration</td><td>$mod_form</td></tr>";
