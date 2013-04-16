<?php
//Set context to limit plugin hook
$context = elgg_get_context();
elgg_set_context('manage_wv_request');
elgg_make_sticky_form('manage_wv_request');
//Check operation (save or delete)
$operation = get_input('submit');
// Modify
if (($operation == elgg_echo('save')) || ($operation == elgg_echo('submit'))) {
	// Get variables
	$user_guid = (int) get_input('user_guid');
	$passport_guid = get_input("passport_guid");
	$relationship_with_sponsor = get_input("relationship_with_sponsor");
	$duration = get_input("duration");
	$in_quatar = get_input("in_quatar");
	$current_visa_type = get_input("current_visa_type");
	//$country = htmlspecialchars(get_input('country', '', false), ENT_QUOTES, 'UTF-8');
	$container_guid = (int) get_input('container_guid', 0);
	$access_id = (int) get_input("access_id");
	$guid = (int) get_input('request_guid');
	$tags = get_input("tags");

	if ((! $container_guid) || 
	    ($container_guid == 0) || 
	    (! $passport_guid) || 
	    (! $relationship_with_sponsor) || 
	    (! $duration) || 
	    (! $in_quatar) || 
	    (! $user_guid)) {
		register_error(elgg_echo('wv_request:missing_att'));
		forward(REFERER);
	}
	// check whether this is a new file or an edit
	$new_request = true;
	if ($guid > 0) {
		$new_request = false;
	}
	
	if ($new_request) {
		$request = new qisResidentPermitRequest();
		$request->user_guid = $user_guid;
		$request->passport_guid = $passport_guid;
		$request->relationship_with_sponsor = $relationship_with_sponsor;
		$request->duration = $duration;
		$request->in_quatar = $in_quatar;
		$request->current_visa_type = $current_visa_type;
		$request->qistype = 'work_visa_request';
		$request->container_guid = $container_guid;
		$request->access_id = $access_id;
	} else {
		// load original file object
		$request = get_entity($guid);
		if ((!$request) || ($request->qistype != 'work_visa_request')) {
			register_error(elgg_echo('request:cannotload'));
			forward(REFERER);
		}
	
		// user must be able to edit file
		if (!$request->canEdit()) {
			register_error(elgg_echo('request:noaccess'));
			forward(REFERER);
		}
		$request->user_guid = $user_guid;
		$request->passport_guid = $passport_guid;
		$request->relationship_with_sponsor = $relationship_with_sponsor;
		$request->duration = $duration;
		$request->in_quatar = $in_quatar;
		$request->current_visa_type = $current_visa_type;
		$request->container_guid = $container_guid;
		$request->access_id = $access_id;
	}
	
	$request->save();
	$guid = $request->getGUID();
	
	// file saved so clear sticky form
	elgg_clear_sticky_form('manage_wv_request');
	
	
	// handle results differently for new files and file updates
	if ($new_request) {
		if ($guid) {
			$message = elgg_echo("request:saved");
			system_message($message);
			//add_to_river('river/object/file/create', 'create', elgg_get_logged_in_user_guid(), $file->guid);
		} else {
			// failed to save file object - nothing we can do about this
			$error = elgg_echo("request:savefailed");
			register_error($error);
		}
	
		$container = get_entity($container_guid);
		if (elgg_instanceof($container, 'group')) {
			forward("qis/manage_immigration_requests/$container_guid");
		} else {
			forward("file/owner/$container->username");
		}
	
	} else {
		if ($guid) {
			system_message(elgg_echo("request:saved"));
		} else {
			register_error(elgg_echo("file:savefailed"));
		}

	forward("qis/manage_immigration_requests/$container_guid");
	}
} elseif ($operation == elgg_echo('delete')) {
	$guid = (int) get_input('request_guid');
	$user_guid = (int) get_input('user_guid');
	
	$request = get_entity($guid);
	if ((!$request) || ($request->qistype != 'work_visa_request')) {
	        register_error(elgg_echo("request:deletefailed"));
		forward("qis/manage_immigration_requests/$container_guid");
	}
	
	if (!$request->canEdit()) {
	        register_error(elgg_echo("request:deletefailed"));
		forward("qis/manage_immigration_requests/$container_guid");
	}
	
	
	if (!$request->delete()) {
	        register_error(elgg_echo("request:deletefailed"));
	} else {
	        system_message(elgg_echo("request:deleted"));
	}
	
	forward("qis/manage_immigration_requests/$container_guid");
}
elgg_set_context($context);
