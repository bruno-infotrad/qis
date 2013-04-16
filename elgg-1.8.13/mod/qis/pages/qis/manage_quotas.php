<?php

if (elgg_is_logged_in()) {
        $group_guid = get_input('qis_group_guid');
        elgg_log("BRUNO group_guid $group_guid", 'NOTICE');
        if (! $group_guid) {
                system_message(elgg_echo("missing_group_guid"));
                forward('/qis/dashboard');
        }
        $group = get_entity($group_guid);
        $access_id = $group->group_acl;

	$submitter = elgg_get_logged_in_user_entity();
	//$submitter_groups = get_users_membership ($submitter->guid);
	//$group_guid = $submitter_groups[0]->guid;
	//$access_id = $submitter_groups[0]->group_acl;
	
	$title = elgg_echo('qis:manage_quotas');
	
	$quotas = elgg_get_entities(array(
		'types' => 'object',
		'subtypes' => 'quota',
		'container_guid' => $group->guid,
		'full_view' => FALSE,
	));
	$content = elgg_view('qis/quotas',array('group_guid'=> $group_guid,'quotas' => $quotas,'access_id' => $access_id));
	
	$params = array(
	        'content' => $content,
	        'title' => $title,
	);
	$body = elgg_view_layout('one_column', $params);
	echo elgg_view_page($title, $body);
}