<?php
$user= elgg_extract('user', $vars, FALSE);

if (!$user) {
	return TRUE;
}

$name = $user->name;
$type = $user->type;
$mod_but .= elgg_view('input/submit', array('value' => elgg_echo('modify_delete')));
$mod_form .= elgg_view('input/form', array('body' => $mod_but, 'action' => "{$CONFIG->url}qis/manage_person/$user->guid"));

echo "<tr><td>$name</td><td>$type</td><td>$mod_form</td></tr>";
