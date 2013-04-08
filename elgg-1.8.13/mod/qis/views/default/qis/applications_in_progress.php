<?php
if (elgg_is_logged_in()) {
        $submitter = elgg_get_logged_in_user_entity();
        $submitter_groups = get_users_membership ($submitter->guid);
        $group_guid = $submitter_groups[0]->guid;
	if (!$group_guid) {
                        register_error(elgg_echo("company:notfound"));
                        forward();
	}
        $content = '<div class="qis-applications-in-progress">';
        $content .= '<h2>'.elgg_echo('qis_application_in_progress').'</h2>'; 
        $applications_in_progress = elgg_get_entities(array(
                        'types' => 'object',
                        'subtypes' => 'immigration_request',
                        'container_guid' => $group->guid,
                        'full_view' => FALSE,
			'count' => true
        ));
        $content .= '<div id="qis-applications-on-track">'.elgg_echo('qis:ontrack').': '.elgg_echo($applications_in_progress).'</div>';
	$sub_but = elgg_view('input/submit', array('value' => elgg_echo('manage_immigration_requests')));
	$content .= elgg_view('input/form', array('body' => $sub_but, 'action' => "{$CONFIG->url}qis/manage_immigration_requests",'class' => 'elgg-button elgg-button-submit'));
        $content .= '<div id="qis-applications-late">'.elgg_echo('qis:late').': 0</div>';
        $content .= '</div>';
        echo $content;
}

?>
