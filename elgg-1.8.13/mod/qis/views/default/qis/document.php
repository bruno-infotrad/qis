<?php
$full = elgg_extract('full_view', $vars, FALSE);
$file = elgg_extract('document', $vars, FALSE);
$request_guid = elgg_extract('request_guid', $vars, FALSE);
$user_guid = elgg_extract('user_guid', $vars, FALSE);
$group_guid = elgg_extract('group_guid', $vars, FALSE);

if (!$file) {
	return TRUE;
}

$user_name = get_entity($user_guid)->name;
$mime = $file->mimetype;
$base_type = substr($mime, 0, strpos($mime,'/'));

$mod_but = elgg_view('input/hidden', array('name' => 'request_guid', 'value' => $request_guid));
$mod_but .= elgg_view('input/submit', array('value' => elgg_echo('modify_delete')));
$mod_form .= elgg_view('input/form', array('body' => $mod_but, 'action' => "{$CONFIG->url}qis/manage_document/$group_guid/$file->guid"));

echo "<tr><td>$request_guid</td><td>$user_name</td><td>$file->document_type</td><td>$file->expiry_date</td><td>".elgg_view_entity_icon($file, 'small')."</td><td>$mod_form</td></tr>";
