<?php
if (elgg_is_logged_in()) {
        $group_guid = get_input('qis_group_guid');
        if (! $group_guid) {
                system_message(elgg_echo("missing_group_guid"));
                forward('/qis/dashboard');
        }
        $group = get_entity($group_guid);
        $access_id = $group->group_acl;

	$context = elgg_get_context();
	elgg_set_context('manage_person');
	$submitter = elgg_get_logged_in_user_entity();
	$user_guid = (int) get_input('guid');
	
	$title = elgg_echo('qis:manage_person');
	
	
	if ($user_guid) {
		$user = get_entity($user_guid);
		if (!$user || ! $user instanceOf ElggUser) {
		        register_error(elgg_echo("profile:notfound"));
		        forward();
		}
		// check if logged in user can edit this profile
		if (!$user->canEdit()) {
		        register_error(elgg_echo("profile:noaccess"));
		        forward();
		}
	
		$content = elgg_view_form('qis/manage_person', array(), array('entity' => $user,'submitter_guid' => $submitter->guid, 'group_guid'=> $group_guid));
	} else {
		$content = elgg_view_form('qis/manage_person', array(), array('submitter_guid' => $submitter->guid, 'group_guid'=> $group_guid));
	}
	
	if ($user && $user instanceOf ElggUser) {
		$citizenships = elgg_get_entities_from_metadata(array(
	        	'types' => 'object',
	        	'subtypes' => 'file',
	        	'container_guid' => $group->guid,
			//'metadata_name_value_pairs' => array('name'  => 'employee_guid', 'value' => $user_guid),
			'metadata_name_value_pairs' => array(
								array('name'  => 'employee_guid', 'value' => $user_guid),
								array('name'  => 'qistype', 'value' => 'passport'),
							),
	        	'full_view' => FALSE,
		));
		$content .= elgg_view('qis/citizenships',array('group_guid'=> $group_guid, 'citizenships' => $citizenships, 'user_guid' => $user_guid));
	}
	
	$params = array(
	        'content' => $content,
	        'title' => $title,
	);
	$body = elgg_view_layout('one_column', $params);
	echo elgg_view_page($title, $body);
	elgg_set_context($context);
}
