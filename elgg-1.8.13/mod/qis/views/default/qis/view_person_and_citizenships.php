<?php

if (elgg_is_logged_in()) {
	$submitter = elgg_get_logged_in_user_entity();
	$submitter_groups = get_users_membership ($submitter->guid);
	$user_guid = elgg_extract('user_guid', $vars,NULL);
	if ($user_guid) {
		$user_groups = get_users_membership ($user_guid);
		if ($submitter_groups[0]->guid != $user_groups[0]->guid) {
	        	register_error(elgg_echo("profile:noaccess"));
	        	forward('/qis');
		} else {
			$group_guid = $submitter_groups[0]->guid;
		}
	} else {
		$group_guid = $submitter_groups[0]->guid;
	}
	
	$title = elgg_echo('qis:view_person');
	
	
	if ($user_guid) {
		$user = get_entity($user_guid);
		if (!$user || ! $user instanceOf ElggUser) {
		        register_error(elgg_echo("profile:notfound"));
		        forward();
		}
		$content = elgg_view('qis/view_person', array('entity' => $user,'submitter_guid' => $submitter->guid, 'group_guid'=> $group_guid,));
	}
	
	if ($user && $user instanceOf ElggUser) {
		$content .= '<table id="qis_ris"><tr><th>Citizenship</th><th>Document Number</th><th>Passport Copy</th><th>Date of Issue</th><th>Expiry Date</th><th>Action</th></tr>';
		$citizenships = elgg_get_entities_from_metadata(array(
	        	'types' => 'object',
	        	'subtypes' => 'file',
	        	'container_guid' => $group->guid,
			'metadata_name_value_pairs' => array('name'  => 'employee_guid', 'value' => $user_guid),
	        	'full_view' => FALSE,
		));
		if ($citizenships) {
			foreach ($citizenships as $citizenship) {
				$content .= elgg_view('qis/citizenship',array('citizenship' => $citizenship, 'user_guid' => $user_guid));
			}
		}
		$content .= '</table>';
	}
	echo $content;
}
