<?php 
global $CONFIG;

$q = sanitize_string(get_input("q"));
$result = array();

$user_guid = sanitize_string(get_input("user_guid"));
$group_guid = sanitize_string(get_input("group_guid"));
$group = get_entity($group_guid);
$request = sanitize_string(get_input("request"));
$user_groups = get_users_membership ($user_guid);
$submitter = elgg_get_logged_in_user_entity();

if((! $submitter->isAdmin()) && (! $group->isMember($submitter) || ! check_entity_relationship($submitter->getGUID(), "group_admin", $group_guid))){
        register_error(elgg_echo('pas le droit'));
        exit();
}
//if(!empty($q)){
	$citizenships = elgg_get_entities_from_metadata(array(
		'types' => 'object',
		'subtypes' => 'file',
		'container_guid' => $group_guid,
		'metadata_name_value_pairs' => array('name'  => 'employee_guid', 'value' => $user_guid),
		'full_view' => FALSE,
	));
	foreach($citizenships as $citizenship){
		//$result[] = array($citizenship->getGUID(),$citizenship->country);
		if ($request == 'work_visa') {
			elgg_load_library('elgg:qis');
			$user = get_entity($user_guid);
			//$unavailable = check_quota_and_quota_requests($group,$citizenship->country,$user->profession,$user->gender,1,'work_visa',FALSE);
			$unavailable = check_object_state($group,$resident_permit_request,TRUE);
                        if (! $unavailable) {
				$result[] = array('passport_guid'=>$citizenship->getGUID(),'passport_country'=>$citizenship->country);
			}
		} else {
			$result[] = array('passport_guid'=>$citizenship->getGUID(),'passport_country'=>$citizenship->country);
		}
	}
	//if (($request == 'work_visa') && (! $result)){
			//$result[] = array('passport_guid'=>'','passport_country'=>'No quota request');
	//}
	$json = array('success' => TRUE, 'item' => $result);
//}

//header("Content-Type: application/json");
echo json_encode(array_values($result));
//echo json_encode($json);

exit();
