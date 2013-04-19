<?php
elgg_load_library('elgg:qis');
$submitter = elgg_get_logged_in_user_entity();
//Set context to limit plugin hook
$context = elgg_get_context();
elgg_set_context('manage_rp_request');
elgg_make_sticky_form('manage_rp_request');
//Check operation (save or delete)
$operation = get_input('submit');
// Modify
if (($operation == elgg_echo('save')) || ($operation == elgg_echo('submit')) ||($operation == 'Proceed with egov') || ($operation == 'Proceed with paper application')) {
	// Get variables
	$input_request->user_guid = (int) get_input('user_guid');
	$input_request->passport_guid = get_input("passport_guid");
	$input_request->relationship_with_sponsor = get_input("relationship_with_sponsor");
	$input_request->duration = get_input("duration");
	$input_request->in_quatar = get_input("in_quatar");
	$input_request->current_visa_type = get_input("current_visa_type");
	$input_request->visa_number = get_input("visa_number");
	$input_request->visa_expiry_date = get_input("visa_expiry_date");
	$input_request->sponsored = get_input("sponsored");
	$input_request->same_sponsor = get_input("same_sponsor");
	$input_request->noc = get_input("noc");
	$input_request->passport_photocopy = get_input("passport_photocopy");
	$input_request->registration_card = get_input("registration_card");
	$input_request->labour_contract = get_input("labour_contract");
	if (($operation == 'Proceed with egov') || ($operation == 'Proceed with paper application')) {
		$input_request->proceed = $operation;
	}

	$input_request->container_guid = (int) get_input('container_guid', 0);
	$input_request->access_id = (int) get_input("access_id");
	$input_request->guid = (int) get_input('request_guid');
	$input_request->tags = get_input("tags");

	if ((! $input_request->container_guid) || 
	    ($input_request->container_guid == 0) || 
	    (! $input_request->passport_guid) || 
	    (! $input_request->relationship_with_sponsor) || 
	    (! $input_request->duration) || 
	    (! $input_request->in_quatar) || 
	    (! $input_request->user_guid)) {
		register_error(elgg_echo('rp_request:missing_att'));
		forward(REFERER);
	}
	//some business logic as example can be turned off if needed and then checked in the to do
	//if ($input_request->in_quatar == 'yes') {
		//if ( ! $input_request->current_visa_type || ! $input_request->visa_number || ! $input_request->visa_expiry_date) {
			//register_error(elgg_echo('rp_request:missing_att'));
			//forward(REFERER);
		//}
	//}
	
	// check whether this is a new file or an edit
	$new_request = true;
	if ($input_request->guid > 0) {
		$new_request = false;
		$request = get_entity($input_request->guid);
	}
	
	if ($new_request) {
		$request = new qisResidentPermitRequest();
		$request->user_guid = $input_request->user_guid;
		$request->passport_guid = $input_request->passport_guid;
		$request->relationship_with_sponsor = $input_request->relationship_with_sponsor;
		$request->duration = $input_request->duration;
		$request->in_quatar = $input_request->in_quatar;
		$request->current_visa_type = $input_request->current_visa_type;
		$request->visa_number = $input_request->visa_number;
		$request->visa_expiry_date = $input_request->visa_expiry_date;
		$request->sponsored = $input_request->sponsored;
		$request->same_sponsor = $input_request->same_sponsor;
		$request->noc = $input_request->noc;

		$request->qistype = 'resident_permit_request';
		$request->container_guid = $input_request->container_guid;
		$request->access_id = $input_request->access_id;
		$request->save();
		$guid = $request->getGUID();
		//Add annotation for historical reasons
		$state_message = check_object_state($container,$request,TRUE);
		$creator_name = $submitter->name;
		$date = date(DATE_RFC822);
		$comment = <<<__HTML
<font color='red'><h3>New Resident Permit Request</h3>
<p> Created on: $date</p>
<p> Created by: $creator_name</p>
$state_message
</font>
__HTML;
		$request->annotate('comment',$comment,$input_request->access_id);
	} else {
		// load original object
		if ((!$request) || ($request->qistype != 'resident_permit_request')) {
			register_error(elgg_echo('request:cannotload'));
			forward(REFERER);
		}
	
		// user must be able to edit file
		if (!$request->canEdit()) {
			register_error(elgg_echo('request:noaccess'));
			forward(REFERER);
		}
		$old_state_message = check_object_state($container,$request,TRUE);
		$guid = $request->getGUID();
		$request->user_guid = $input_request->user_guid;
		$request->passport_guid = $input_request->passport_guid;
		$request->relationship_with_sponsor = $input_request->relationship_with_sponsor;
		$request->duration = $input_request->duration;
		$request->in_quatar = $input_request->in_quatar;
		$request->current_visa_type = $input_request->current_visa_type;
		$request->visa_number = $input_request->visa_number;
		$request->visa_expiry_date = $input_request->visa_expiry_date;
		$request->sponsored = $input_request->sponsored;
		$request->same_sponsor = $input_request->same_sponsor;
		$request->noc = $input_request->noc;
		$request->passport_photocopy = $input_request->passport_photocopy;
		$request->registration_card = $input_request->registration_card;
		$request->labour_contract = $input_request->labour_contract;
		$request->proceed = $input_request->proceed;
	
		$request->container_guid = $input_request->container_guid;
		$request->access_id = $input_request->access_id;
		$request->save();

		$new_state_message = check_object_state($container,$request,TRUE);
		if ($old_state_message != $new_state_message) {
			$message = $new_state_message;
		}
		$modifier_name = $submitter->name;
		$date = date(DATE_RFC822);
		$diff = process_diff($input_request,$request);
		$comment = <<<__HTML
<font color='red'><h3>Modified Resident Permit Request</h3>
<p> Modified on: $date</p>
<p> Modified by: $modifier_name</p>
<p> $diff </p>
$message
</font>
__HTML;
		$request->annotate('comment',$comment,$input_request->access_id);
	}
	
	
	// file saved so clear sticky form
	elgg_clear_sticky_form('manage_rp_request');
	
	
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
	
		$container = get_entity($input_request->container_guid);
		$user = get_entity($input_request->user_guid);
		$passport = get_entity($input_request->passport_guid);
		//$qis_message = check_quota_and_quota_requests($container,$passport->country,$user->profession,$user->gender,'resident_permit',1,FALSE);
		$qis_message = check_object_state($container,$request,FALSE);
		if (elgg_instanceof($container, 'group')) {
			forward("qis/manage_immigration_requests/$input_request->container_guid/$request->guid/$qis_message");
		} else {
			forward("file/owner/$container->username");
		}
	
	} else {
		if ($guid) {
			system_message(elgg_echo("request:saved"));
		} else {
			register_error(elgg_echo("file:savefailed"));
		}

	forward("qis/manage_immigration_requests/$input_request->container_guid");
	}
} elseif ($operation == elgg_echo('delete')) {
	$input_request->guid = (int) get_input('request_guid');
	$input_request->container_guid = (int) get_input('container_guid', 0);
	
	$request = get_entity($input_request->guid);
	if ((!$request) || ($request->qistype != 'resident_permit_request')) {
	        register_error(elgg_echo("request:deletefailed"));
		forward("qis/manage_immigration_requests/$input_request->container_guid");
	}
	
	if (!$request->canEdit()) {
	        register_error(elgg_echo("request:deletefailed"));
		forward("qis/manage_immigration_requests/$input_request->container_guid");
	}
	
	
	if (!$request->delete()) {
	        register_error(elgg_echo("request:deletefailed"));
	} else {
	        system_message(elgg_echo("request:deleted"));
	}
	
	forward("qis/manage_immigration_requests/$input_request->container_guid");
}
elgg_set_context($context);
