<?php
$immigration_request= elgg_extract('immigration_request', $vars, FALSE);
$group_guid= elgg_extract('group_guid', $vars, FALSE);

if (!$immigration_request) {
	return TRUE;
}

$type = $immigration_request->qistype;
if ($type == 'resident_permit_request') {
	$target ='manage_rp_request';
} elseif ($type == 'work_visa_request') {
	$target ='manage_wv_request';
}
$duration = $immigration_request->duration;
$passport_guid = $immigration_request->passport_guid;
$country = get_entity($passport_guid)->country;
$user_guid = $immigration_request->user_guid;
$user_name = get_entity($user_guid)->name;
$user_occupation = get_entity($user_guid)->profession;
$mod_but .= elgg_view('input/submit', array('value' => elgg_echo('modify_delete')));
$mod_form .= elgg_view('input/form', array('body' => $mod_but, 'action' => "{$CONFIG->url}qis/$target/$group_guid/$immigration_request->guid"));

echo "<tr><td>$type</td><td>$user_name</td><td>$user_occupation</td><td>$country</td><td>$duration</td><td>$mod_form</td></tr>";
