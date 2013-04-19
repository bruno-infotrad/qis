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
