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

	$title = elgg_echo('qis:dashboard');
	
	$content = <<<__HTML
	<div class="qis-to-do"><h2>To do:</h2>
	<ul><li>Feb 19, 2013: Sign business visa form for Cathy Hill, HP (under QShield Sponsorship)
	<ul><li>Feb 21, 2013: Sign business visa form for Peter Brown, Microsoft (under QShield Sponsorship)
	</ul></div>
__HTML;
	if ($user->isAdmin()) {
		$content=elgg_view('qis/admin_tasks', array());
	} else {
		$content=elgg_view('qis/tasks', array());
	}
	$content.=elgg_view('qis/applications_in_progress', array());
	$content.=elgg_view_menu('qis', array('sort_by' => 'priority'));
	$body = elgg_view_layout('one_column', array(
		'content' => $content,
		'title' => $title,
	));
	echo elgg_view_page($title, $body);
}
