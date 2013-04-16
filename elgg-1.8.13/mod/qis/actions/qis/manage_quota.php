<?php
elgg_make_sticky_form('manage_quotas');
$user = elgg_get_logged_in_user_entity();
//Set context to limit plugin hook
$context = elgg_get_context();
elgg_set_context('manage_quotas');
//Check operation (save or delete)
$operation = get_input('submit');
// Modify
if ($operation == elgg_echo('Delete')) {
	$input_request->guid = (int) get_input('quota_guid');
	$request = get_entity($input_request->guid);
	$input_request->container_guid = (int) get_input('container_guid');
	$quota = get_entity($input_request->guid);
	if ((!$quota) || ($quota->getSubtype() != 'quota')) {
	        register_error(elgg_echo("request:deletefailed"));
		forward("qis/manage_quotas/$input_request->container_guid");
	}
	
	if (!$request->canEdit()) {
	        register_error(elgg_echo("request:deletefailed"));
		forward("qis/manage_quotas/$input_request->container_guid");
	}
	
	
	if (!$request->delete()) {
	        register_error(elgg_echo("request:deletefailed"));
	} else {
	        system_message(elgg_echo("request:deleted"));
	}
	
	forward("qis/manage_quotas/$input_request->container_guid");
}
elgg_set_context($context);
