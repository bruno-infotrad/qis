<?php

if (elgg_is_logged_in()) {
	//$submitter = elgg_get_logged_in_user_entity();
	//if ($submitter->qisusertype != 'Immigration Agency Portal Coordinator') {
	       	//register_error(elgg_echo("Not allowed"));
	       	//forward('/qis');
	//}
        $group_guid = get_input('qis_group_guid');
        if ($group_guid) {
        	$group = get_entity($group_guid);
        	$access_id = $group->group_acl;
        } else {
                //system_message(elgg_echo("missing_group_guid"));
                //forward('/qis/dashboard');
	}
	$qis_groups = elgg_get_entities(array( 'type' => 'group', 'full_view' => false,));
	$request_guid = (int) get_input('request_guid');
	$title = elgg_echo('qis:manage_document');
	$content = elgg_view_form('qis/manage_document', 
			array('enctype' => 'multipart/form-data'),
			array(
				'access_id' => $access_id,
				'request_guid' => $request_guid,
				'group_guid'=> $group_guid,
				'qis_groups'=> $qis_groups,
	));
	$params = array(
	        'content' => $content,
	        'title' => $title,
	);
	$body = elgg_view_layout('one_column', $params);
	
	echo elgg_view_page($title, $body);
}
