<?php

if (elgg_is_logged_in()) {
        $group_guid = get_input('qis_group_guid');
        if (! $group_guid) {
                system_message(elgg_echo("missing_group_guid"));
                forward('/qis/dashboard');
        }
        $group = get_entity($group_guid);
        $access_id = $group->group_acl;

	$submitter = elgg_get_logged_in_user_entity();
	
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
	$body = elgg_view('qis/manage_persons', array('group_guid' => $group_guid,'users' => $users));
	echo elgg_view_page($title, $body);
}
