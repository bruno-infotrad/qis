<?php
elgg_load_library('elgg:qis');
$submitter = elgg_get_logged_in_user_entity();
//Set context to limit plugin hook
$context = elgg_get_context();
elgg_set_context('manage_visit');
elgg_make_sticky_form('manage_visit');
//Check operation (save or delete)
$operation = get_input('submit');
// Modify
if ($operation == elgg_echo('Schedule')) {
	// Get variables
	$input_request->medical_visit = get_input('medical_visit');
	$input_request->blood_test = get_input("blood_test");
	$input_request->fingerprints = get_input("fingerprints");

	$input_request->container_guid = (int) get_input('container_guid', 0);
	$input_request->access_id = (int) get_input("access_id");
	$input_request->guid = (int) get_input('request_guid');
	$input_request->tags = get_input("tags");

	if ((! $input_request->container_guid) || 
	    ($input_request->container_guid == 0) || 
	    (! $input_request->guid)) {
		register_error(elgg_echo('rp_request:missing_att'));
		forward(REFERER);
	}
	// check whether this is a new file or an edit
	$new_request = false;
	$request = get_entity($input_request->guid);
	if ((!$request) || ($request->qistype != 'resident_permit_request')) {
		register_error(elgg_echo('request:cannotload'));
		forward(REFERER);
	}
	
	
	// user must be able to edit file
	if (!$request->canEdit()) {
		register_error(elgg_echo('request:noaccess'));
		forward(REFERER);
	}
	$guid = $request->getGUID();
	$request->medical_visit = $input_request->medical_visit;
	$request->blood_test = $input_request->blood_test;
	$request->fingerprints = $input_request->fingerprints;
	
	$request->container_guid = $input_request->container_guid;
	$request->access_id = $input_request->access_id;
	$request->save();

	$modifier_name = $submitter->name;
	$date = date(DATE_RFC822);
	$diff = process_diff($input_request,$request);
	$comment = <<<__HTML
<font color='red'><h3>Modified Resident Permit Request</h3>
<p> Modified on: $date</p>
<p> Modified by: $modifier_name</p>
$diff
</font>
__HTML;
	$request->annotate('comment',$comment,$input_request->access_id);
	
	
	// file saved so clear sticky form
	elgg_clear_sticky_form('manage_visit');
	
	if ($guid) {
		system_message(elgg_echo("request:saved"));
	} else {
		register_error(elgg_echo("request:savefailed"));
	}

	forward("qis/manage_visits/$input_request->container_guid");
	}
elgg_set_context($context);
