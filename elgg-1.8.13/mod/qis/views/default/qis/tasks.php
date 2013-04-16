<?php
if (elgg_is_logged_in()) {
	elgg_load_library('elgg:qis');
        $group_guid = get_input('qis_group_guid');
        elgg_log("BRUNO group_guid $group_guid", 'NOTICE');
        if (! $group_guid) {
                system_message(elgg_echo("missing_group_guid"));
                forward('/qis/dashboard');
        }
        $group = get_entity($group_guid);
        $access_id = $group->group_acl;

	
        $user = elgg_get_logged_in_user_entity();
        //$submitter_groups = get_users_membership ($submitter->guid);
        //$group_guid = $submitter_groups[0]->guid;
	//if (!$group_guid) {
                        //register_error(elgg_echo("company:notfound"));
                        //forward();
	//}
        $content = '<div class="qis-applications-in-progress">';
        $content .= '<h2>'.elgg_echo('to_do').'</h2>'; 
	$resident_permit_requests = elgg_get_entities_from_metadata(array(
		'types' => 'object',
		'subtypes' => 'immigration_request',
		'container_guid' => $group->guid,
		'metadata_name_value_pairs' => array('name'  => 'qistype', 'value' => 'resident_permit_request'),
		'full_view' => FALSE,
	));
	foreach ($resident_permit_requests as $resident_permit_request) {
		$content .= check_object_state($group,$resident_permit_request,TRUE);
	}
	
        $content .= '</div>';
        echo $content;
}

?>
