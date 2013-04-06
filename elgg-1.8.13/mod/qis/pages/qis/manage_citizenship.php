<?php

if (elgg_is_logged_in()) {
$submitter = elgg_get_logged_in_user_entity();
$submitter_groups = get_users_membership ($submitter->guid);
$user_guid = (int) get_input('user_guid');
$guid = (int) get_input('guid');
if (! $user_guid) {
        	register_error(elgg_echo("profile:notfound"));
        	forward('/qis');
} else {
	$user_groups = get_users_membership ($user_guid);
	if ($submitter_groups[0]->guid != $user_groups[0]->guid) {
        	register_error(elgg_echo("profile:noaccess"));
        	forward('/qis');
	} else {
		$group_guid = $submitter_groups[0]->guid;
		$access_id = $submitter_groups[0]->group_acl;
	}
}

$title = elgg_echo('qis:manage_citizenship');
$content = elgg_view_form('qis/manage_citizenship', 
		array('enctype' => 'multipart/form-data'),
		array(
			'access_id' => $access_id,
			'user_guid' => $user_guid,
			'guid' => $guid,
			'submitter_guid' => $submitter->guid,
			'group_guid'=> $group_guid,
));
$params = array(
        'content' => $content,
        'title' => $title,
);
$body = elgg_view_layout('one_column', $params);

echo elgg_view_page($title, $body);
}
