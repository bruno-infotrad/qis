<?php
elgg_load_library('elgg:qis');
if (elgg_is_logged_in()) {
        $group_guid = get_input('qis_group_guid');
        elgg_log("BRUNO group_guid $group_guid", 'NOTICE');
        if (! $group_guid) {
                system_message(elgg_echo("missing_group_guid"));
                forward('/qis/dashboard');
        }
        $group = get_entity($group_guid);
        $access_id = $group->group_acl;

	
        $user = elgg_get_logged_in_user_entity();
        $content = '<div class="qis-applications-todo">';
        $content .= '<h2>'.elgg_echo('to_do').'</h2>'; 
	$quota_requests = elgg_get_entities_from_metadata(array(
		'types' => 'object',
		'subtypes' => 'immigration_request',
		'container_guid' => $group->guid,
		'metadata_name_value_pairs' => array('name'  => 'qistype', 'value' => 'quota_request'),
		'full_view' => FALSE,
	));
	foreach ($quota_requests as $quota_request) {
		$content .= check_object_state($group_guid,$quota_request,TRUE);
	}
	$resident_permit_requests = elgg_get_entities_from_metadata(array(
		'types' => 'object',
		'subtypes' => 'immigration_request',
		'container_guid' => $group->guid,
		'metadata_name_value_pairs' => array('name'  => 'qistype', 'value' => 'resident_permit_request'),
		'full_view' => FALSE,
	));
	foreach ($resident_permit_requests as $resident_permit_request) {
		$content .= check_object_state($group_guid,$resident_permit_request,TRUE);
	}
	$content .= '</div>';
        echo $content;
}
?>
