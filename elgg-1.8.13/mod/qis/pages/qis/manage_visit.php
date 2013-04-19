<?php

if (elgg_is_logged_in()) {
        $group_guid = get_input('qis_group_guid');
        if (! $group_guid) {
                system_message(elgg_echo("missing_group_guid"));
                forward('/qis/dashboard');
        }
	$request_guid = (int) get_input('request_guid');
        if (! $request_guid) {
                system_message(elgg_echo("missing_request_guid"));
                forward('/qis/dashboard');
        }
        $group = get_entity($group_guid);
        $access_id = $group->group_acl;
	$context = elgg_get_context();
	elgg_set_context('manage_visit');
	$submitter = elgg_get_logged_in_user_entity();
	$title = elgg_echo('qis:manage_visits');
	
	
	$request = get_entity($request_guid);
	if (!$request){
	        register_error(elgg_echo("request:notfound"));
	        forward('/qis/manage_immigration_services');
	}
	// check if logged in user can edit this profile
	if (!$request->canEdit()) {
	        register_error(elgg_echo("request:noaccess"));
	        forward();
	}

	$content = elgg_view_form('qis/manage_visit', array(), array('request_guid' => $request_guid,'submitter_guid' => $submitter->guid, 'group_guid'=> $group_guid, 'access_id' => $access_id));
	
	$params = array(
	        'content' => $content,
	        'title' => $title,
	);
	$body = elgg_view_layout('one_column', $params);
	echo elgg_view_page($title, $body);
	elgg_set_context($context);
}
