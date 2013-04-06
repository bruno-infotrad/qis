<?php

if (elgg_is_logged_in()) {
	$submitter = elgg_get_logged_in_user_entity();
	$submitter_groups = get_users_membership ($submitter->guid);
	$group_guid = $submitter_groups[0]->guid;
	
	$user = elgg_get_logged_in_user_entity();
	$user_role = $user->role;
	$user_group_guid = $user->group;
	
	$title = elgg_echo('qis:manage_immigration_requests');
	
	$immigration_requests = elgg_get_entities(array(
		'types' => 'object',
		'subtypes' => 'immigration_request',
		'container_guid' => $group->guid,
		'full_view' => FALSE,
	));
	$body = elgg_view('qis/manage_immigration_requests',array('immigration_requests' => $immigration_requests));
	echo elgg_view_page($title, $body);
}
