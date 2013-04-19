<?php

if (elgg_is_logged_in()) {
	$group_guid = get_input('qis_group_guid');
	if (! $group_guid) {
		system_message(elgg_echo("missing_group_guid"));
		forward('/qis/dashboard');
	}
	$group = get_entity($group_guid);
	$access_id = $group->group_acl;
	
	$user = elgg_get_logged_in_user_entity();
	$user_role = $user->role;
	$user_group_guid = $user->group;

	
	$title = elgg_echo('qis:manage_immigration_requests');

	$request_guid = get_input('request_guid');
	$expli = get_input('expli');
	
	$immigration_requests = elgg_get_entities_from_metadata(array(
		'types' => 'object',
		'subtypes' => 'immigration_request',
		'container_guid' => $group->guid,
                'metadata_name_value_pairs' => array('name'  => 'qistype', 'value' => array('resident_permit_request','work_visa_request')),
		'full_view' => FALSE,
	));
	$body .= elgg_view('qis/manage_immigration_requests',array('group_guid' => $group_guid, 'immigration_requests' => $immigration_requests,'access_id' => $access_id, 'expli' => $expli));
	echo elgg_view_page($title, $body);
}
