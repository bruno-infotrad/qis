<?php

if (elgg_is_logged_in()) {
        $group_guid = get_input('qis_group_guid');
        if (! $group_guid) {
                system_message(elgg_echo("missing_group_guid"));
                forward('/qis/dashboard');
        }
        $group = get_entity($group_guid);
        $access_id = $group->group_acl;

	$submitter = elgg_get_logged_in_user_entity();
	$title = elgg_echo('qis:manage_documents');
	

	if ($submitter->qisusertype == 'Immigration Agency Portal Coordinator') {
		$metadata_name_value_pairs = array('name'  => 'qistype', 'value' => 'document');
		//$metadata_name_value_pairs = array(
			//array('name'  => 'qistype', 'value' => 'document'),
			//array('name'  => 'document_type', 'value' => array('moi_receipt','work_visa','resident_permit')),
		//);
	} elseif ($submitter->qisusertype == 'Client Portal Administrator') {
		$metadata_name_value_pairs = array(
			array('name'  => 'qistype', 'value' => 'document'),
			array('name'  => 'document_type', 'value' => array('noc')),
		);
	}

	$documents = elgg_get_entities_from_metadata(array(
		'types' => 'object',
		'subtypes' => 'file',
		'container_guid' => $group->guid,
		'metadata_name_value_pairs' => $metadata_name_value_pairs,
		'full_view' => FALSE,
	));
	$content = elgg_view('qis/documents',array('group_guid'=> $group_guid,'documents' => $documents,'access_id' => $access_id));
	
	$params = array(
	        'content' => $content,
	        'title' => $title,
	);
	$body = elgg_view_layout('one_column', $params);
	echo elgg_view_page($title, $body);
}
