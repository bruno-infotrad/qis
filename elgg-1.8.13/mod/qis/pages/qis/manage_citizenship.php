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
	$user_guid = (int) get_input('user_guid');
	$guid = (int) get_input('guid');
	if (! $user_guid) {
	        	register_error(elgg_echo("profile:notfound"));
	        	forward('/qis');
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
