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
	
	$title = elgg_echo('qis:manage_immigration_requests');

	$visits = elgg_get_entities_from_metadata(array(
		'types' => 'object',
		'subtypes' => 'immigration_request',
		'container_guid' => $group->guid,
		'metadata_name_value_pairs' => array('name'  => 'qistype', 'value' => 'resident_permit_request'),
						//array('name'  => 'qistype', 'value' => 'resident_permit_request'),
						//array('name'  => 'proceed', 'value' => '', 'operand' => '>'),
		//),
		'full_view' => FALSE,
	));
	elgg_log('BRUNO VISITS='.var_export($visits,true), 'NOTICE');
	$body .= elgg_view('qis/manage_visits',array('group_guid' => $group_guid, 'visits' => $visits,'access_id' => $access_id));
	echo elgg_view_page($title, $body);
}
