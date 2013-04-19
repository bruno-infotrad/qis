<?php 
global $CONFIG;

$q = sanitize_string(get_input("q"));
$result = array();

$group_guid = sanitize_string(get_input("group_guid"));
$group = get_entity($group_guid);
$submitter = elgg_get_logged_in_user_entity();

if((! $submitter->isAdmin()) && (! $group->isMember($submitter) || ! check_entity_relationship($submitter->getGUID(), "group_admin", $group_guid))){
        register_error(elgg_echo('pas le droit'));
        exit();
}
$requests = elgg_get_entities_from_metadata(array(
	'types' => 'object',
	'subtypes' => 'immigration_request',
	'container_guid' => $group->guid,
	'metadata_name_value_pairs' => array('name'  => 'qistype', 'value' => array('resident_permit_request','work_visa_request')),
	'full_view' => FALSE,
));

foreach($requests as $request){
		if ($request->qistype == 'resident_permit_request') {
			if ($submitter->qisusertype == 'Immigration Agency Portal Coordinator') {
				//if ($request->proceed) {
					$user = get_entity($request->user_guid)->name;
					$result[] = array('request_guid'=>$request->getGUID(),'request_user' => get_entity($request->user_guid)->name);
				//}
			} elseif ($submitter->qisusertype == 'Client Portal Administrator') {
				$user = get_entity($request->user_guid)->name;
				$result[] = array('request_guid'=>$request->getGUID(),'request_user' => get_entity($request->user_guid)->name);
			}
		}
}
$json = array('success' => TRUE, 'item' => $result);

//header("Content-Type: application/json");
echo json_encode(array_values($result));
//echo json_encode($json);

exit();
