<?php

if (elgg_is_logged_in()) {
	$context = elgg_get_context();
	elgg_set_context('manage_corporate_info');
	$submitter = elgg_get_logged_in_user_entity();
	$submitter_groups = get_users_membership ($submitter->guid);
	$group_guid = $submitter_groups[0]->guid;
	if (! $group_guid) {
		register_error(elgg_echo("company:notfound"));
		forward();
	}
	$title = elgg_echo('qis:manage_corporate_info');
	
	$title = elgg_echo("groups:edit");
	$group = get_entity($group_guid);
	if ($group && $group->canEdit()) {
		elgg_set_page_owner_guid($group->getGUID());
		$content = elgg_view("qis/manage_corporate_info", array('entity' => $group));
	} else {
		$content = elgg_echo('groups:noaccess');
	}
	$params = array(
		'content' => $content,
		'title' => $title,
		'filter' => '',
	);
	
	$body = elgg_view_layout('one_column', $params);
	echo elgg_view_page($title, $body);
	elgg_set_context($context);
}
