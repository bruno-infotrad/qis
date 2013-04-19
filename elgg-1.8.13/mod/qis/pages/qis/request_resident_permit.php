<?php

if (elgg_is_logged_in()) {
        $group_guid = get_input('qis_group_guid');
        if (! $group_guid) {
                system_message(elgg_echo("missing_group_guid"));
                forward('/qis/dashboard');
        }
        $group = get_entity($group_guid);
        $access_id = $group->group_acl;

	$title = elgg_echo('qis:request_resident_permit');
	$form = elgg_view_form('qis/request_resident_permit', array('enctype' => 'multipart/form-data'));
	$body = elgg_view_layout('one_column', array(
	        'content' => $form,
	        'title' => $title,
	));
	echo elgg_view_page($title, $body);
}
