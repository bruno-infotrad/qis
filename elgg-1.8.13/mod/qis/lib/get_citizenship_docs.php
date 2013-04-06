<?php 
global $CONFIG;

$q = sanitize_string(get_input("q"));
$result = array();

$user_guid = sanitize_string(get_input("user_guid"));
$user_groups = get_users_membership ($user_guid);
$submitter = elgg_get_logged_in_user_entity();
$submitter_groups = get_users_membership ($subject_guid);
//elgg_log('BRUNO submitter_guid='.$submitter->getGUID().' user_guid='.$user_guid,NOTICE);
if ($submitter_groups[0]->guid != $user_groups[0]->guid) {
	register_error(elgg_echo("profile:noaccess"));
	exit();
} else {
	$group_guid = $submitter_groups[0]->guid;
	$group = get_entity($group_guid);
	$user = get_entity($user_guid);
}

//elgg_log('BRUNO group_guid='.$group_guid.' user_guid='.$user_guid,NOTICE);
if((! $submitter->isAdmin()) && (! $group->isMember($submitter) || ! check_entity_relationship($submitter->getGUID(), "group_admin", $group_guid))){
        register_error(elgg_echo('pas le droit'));
        exit();
}
//elgg_log('BRUNO before empty q',NOTICE);
//if(!empty($q)){
	//elgg_log('BRUNO group_guid='.$group_guid.' user_guid='.$user_guid,NOTICE);
	$citizenships = elgg_get_entities_from_metadata(array(
		'types' => 'object',
		'subtypes' => 'file',
		'container_guid' => $group_guid,
		'metadata_name_value_pairs' => array('name'  => 'employee_guid', 'value' => $user_guid),
		'full_view' => FALSE,
	));
	foreach($citizenships as $citizenship){
		//$result[] = array($citizenship->getGUID(),$citizenship->country);
		$result[] = array('passport_guid'=>$citizenship->getGUID(),'passport_country'=>$citizenship->country);
	}
	$json = array('success' => TRUE, 'item' => $result);
//}

//header("Content-Type: application/json");
echo json_encode(array_values($result));
//echo json_encode($json);

exit();
