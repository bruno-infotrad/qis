<?php

if (elgg_is_logged_in()) {
	$group_guid = get_input('qis_group_guid');
	$request_guid = get_input('request_guid');
	if (! $group_guid) {
		system_message(elgg_echo("missing_group_guid"));
		forward('/qis/dashboard');
	}
	if (! $request_guid) {
		system_message(elgg_echo("missing_request_guid"));
		forward('/qis/dashboard');
	}
	$group = get_entity($group_guid);
	$request = get_entity($request_guid);
	$access_id = $group->group_acl;
	
	$user = elgg_get_logged_in_user_entity();
	
	$title = elgg_echo('qis:service_request_tracker');
	$annotations = $request->getAnnotations(
                                       'comment',    // The type of annotation
                                       0,   // The number to return
                                       0,  // Any indexing offset
                                       'asc'   // 'asc' or 'desc' (default 'asc')
                                      );
	foreach ($annotations as $annotation) {
		$body .= $annotation->value;
	}
	echo elgg_view_page($title, $body);
}
