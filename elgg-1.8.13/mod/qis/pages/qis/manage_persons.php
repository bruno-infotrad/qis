<?php

if (elgg_is_logged_in()) {
	$submitter = elgg_get_logged_in_user_entity();
	$submitter_groups = get_users_membership ($submitter->guid);
	$group_guid = $submitter_groups[0]->guid;
	
	$user = elgg_get_logged_in_user_entity();
	$user_role = $user->role;
	$user_group_guid = $user->group;
	
	$title = elgg_echo('qis:manage_persons');
	
	$users = elgg_get_entities_from_relationship(array(
	        'relationship' => 'member',
	        'relationship_guid' => $group_guid,
	        'inverse_relationship' => true,
	        'types' => 'user',
	        //'list_type' => 'gallery',
	        //'gallery_class' => 'elgg-gallery-users',
	));
	$body = elgg_view('qis/manage_persons', array('users' => $users));
	echo elgg_view_page($title, $body);
}
