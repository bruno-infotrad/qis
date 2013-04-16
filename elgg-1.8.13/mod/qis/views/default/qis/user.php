<?php
$user= elgg_extract('user', $vars, FALSE);
$group_guid= elgg_extract('group_guid', $vars, FALSE);

if (!$user) {
	return TRUE;
}

$name = $user->name;
$type = $user->qisusertype;
$profession = $user->profession;
$mod_but .= elgg_view('input/submit', array('value' => elgg_echo('modify_delete')));
$mod_form .= elgg_view('input/form', array('body' => $mod_but, 'action' => "{$CONFIG->url}qis/manage_person/$group_guid/$user->guid"));

echo "<tr><td>$name</td><td>$profession</td><td>$mod_form</td></tr>";
