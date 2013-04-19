<?php
elgg_load_library('elgg:qis');
if (elgg_is_logged_in()) {
        $group_guid = get_input('qis_group_guid');
        if (! $group_guid) {
                system_message(elgg_echo("missing_group_guid"));
                forward('/qis/dashboard');
        }
        $group = get_entity($group_guid);
        $access_id = $group->group_acl;

	
        $user = elgg_get_logged_in_user_entity();
        $content = '<div class="qis-applications-in-progress">';
        $content .= '<h2>'.elgg_echo('to_do').'</h2>'; 
	$quota_requests = elgg_get_entities_from_metadata(array(
		'types' => 'object',
		'subtypes' => 'immigration_request',
		'container_guid' => $group->guid,
		'metadata_name_value_pairs' => array('name'  => 'qistype', 'value' => 'quota_request'),
		'full_view' => FALSE,
	));
	foreach ($quota_requests as $quota_request) {
		$result = check_object_state($group_guid,$quota_request,TRUE);
		if ($result) {
			$mod_but = elgg_view('input/submit', array('value' => elgg_echo('View')));
			$view_form = elgg_view('input/form', array('body' => $mod_but, 'action' => "{$CONFIG->url}qis/manage_quota_request/$group_guid/$quota_request->guid"));
			$result = "<div id='qis-task'>".date('M j, Y',$quota_request->time_updated)." : $result</div><div id='qis-task-button'>$view_form</div><hr>";
			$content .= $result;
		}
	}
	$resident_permit_requests = elgg_get_entities_from_metadata(array(
		'types' => 'object',
		'subtypes' => 'immigration_request',
		'container_guid' => $group->guid,
		'metadata_name_value_pairs' => array('name'  => 'qistype', 'value' => 'resident_permit_request'),
		'full_view' => FALSE,
	));
	foreach ($resident_permit_requests as $resident_permit_request) {
		$result = check_object_state($group_guid,$resident_permit_request,TRUE);
		if ($result) {
			//Ugly but test on returned string to forward to the right page CAREFUL DEPENDS ON STRINGS IN lib/qis.php
			if (preg_match('/schedule/',$result)) {
				$form = 'manage_visit';
			} elseif (preg_match('/passport|registration|labour/',$result)) {
				$form = 'manage_rp_request';
			}
			$mod_but = elgg_view('input/submit', array('value' => elgg_echo('View')));
			$view_form = elgg_view('input/form', array('body' => $mod_but, 'action' => "{$CONFIG->url}qis/$form/$group_guid/$resident_permit_request->guid"));
			$result = "<div id='qis-task'>".date('M j, Y',$resident_permit_request->time_updated)." : $result</div><div id='qis-task-button'>$view_form</div><hr>";
			$content .= $result;
		}
	}
	$content .= '</div>';
        echo $content;
}
?>
