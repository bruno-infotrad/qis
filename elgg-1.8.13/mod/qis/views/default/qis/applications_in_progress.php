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

        //$submitter = elgg_get_logged_in_user_entity();
        //$submitter_groups = get_users_membership ($submitter->guid);
        //$group_guid = $submitter_groups[0]->guid;
	//if (!$group_guid) {
                        //register_error(elgg_echo("company:notfound"));
                        //forward();
	//}
        $content = '<div class="qis-applications-in-progress">';
        $content .= '<h2>'.elgg_echo('qis_application_in_progress').'</h2>'; 
        $applications_in_progress = elgg_get_entities(array(
                        'types' => 'object',
                        'subtypes' => 'immigration_request',
                        'container_guid' => $group->guid,
                        'full_view' => FALSE,
        ));
	foreach ($applications_in_progress as $application_in_progress) {
		$status = check_object_state($group_guid,$application_in_progress,TRUE);
		if ($status) {
			$to_be_actioned++;
		} else {
			$on_track++;
		}
	}
        $content .= '<div id="qis-applications-on-track">'.elgg_echo('qis:ontrack').': '.$on_track.'</div>';
	//$sub_but = elgg_view('input/submit', array('value' => elgg_echo('manage_immigration_requests')));
	//$content .= elgg_view('input/form', array('body' => $sub_but, 'action' => "{$CONFIG->url}qis/manage_immigration_requests",'class' => 'elgg-button elgg-button-submit'));
        $content .= '<div id="qis-applications-late">'.elgg_echo('qis:to_be_actioned').': '.$to_be_actioned.'</div>';
        $content .= '</div>';
        echo $content;
}

?>
