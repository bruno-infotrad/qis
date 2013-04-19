<?php
if (elgg_is_logged_in()) {
	$user = elgg_get_logged_in_user_entity();
	if (! $user->isAdmin()) {
		register_error(elgg_echo("qis:not_an_admin"));
		forward();
	}
	
	$title = elgg_echo('qis:admin_dashboard');
	//list all groups
	$qis_groups = elgg_get_entities(array( 'type' => 'group', 'full_view' => false,));
	if (!$qis_groups) {
		$content = elgg_echo('groups:none');
	} else {
		$content=elgg_view('qis/admin_dashboard', array('qis_groups' => $qis_groups,'user' => $user));
	}
	$body = elgg_view_layout('one_column', array(
		'content' => $content,
		'title' => $title,
	));
	echo elgg_view_page($title, $body);
}
