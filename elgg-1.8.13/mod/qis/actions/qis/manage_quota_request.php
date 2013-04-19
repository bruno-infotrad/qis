<?php
elgg_load_library('elgg:qis');
elgg_make_sticky_form('manage_quota_request');
$user = elgg_get_logged_in_user_entity();
//Set context to limit plugin hook
$context = elgg_get_context();
elgg_set_context('manage_quota_request');
//Check operation (save or delete)
$operation = get_input('submit');
// Modify
if (($operation == elgg_echo('save')) || ($operation == elgg_echo('submit'))) {
	// Get variables
	$input_request->quantity = get_input("quantity");
	$input_request->num_lines = count($input_request->quantity);
	$input_request->citizenship = get_input("citizenship");
	$input_request->gender = get_input("gender");
	$input_request->occupation = get_input("occupation");
	$input_request->status = get_input("status");
	$input_request->mol_number = get_input("mol_number");
	$input_request->paid = get_input("paid");
	$input_request->container_guid = (int) get_input('container_guid', 0);
	$input_request->access_id = (int) get_input("access_id");
	$input_request->guid = (int) get_input('request_guid');
	$input_request->tags = get_input("tags");

	
	// check whether this is a new file or an edit
	$new_request = true;
	if ($input_request->guid > 0) {
		$new_request = false;
		$request = get_entity($input_request->guid);
	}
	//sanity checks
	$is_empty = false;
	for ($i = 0;$i <$input_request->num_lines;$i++) {
		// check if one empty value, set rewrite form and send error message if true
		if ((! $input_request->quantity[$i]) || (! $input_request->citizenship[$i]) || (! $input_request->gender[$i]) || (! $input_request->occupation[$i]) || (! $input_request->status[$i])) {
			$is_empty = true;
		}
		//Check permission to update status and mol_number
		if  (! $user->isAdmin()) {
			if  ($input_request->status[$i] != 'Pending') {
				register_error(elgg_echo('quota_request:not_allowed_to_change_status'));
				forward(REFERER);
			}
		} else {
		// Admin check mol_number and status
			if ((! $input_request->status[$i]) || (($input_request->status[$i] == 'Approved') && (! $input_request->mol_number[$i]))) {
				$is_empty = true;
			}
		}
	}
	if ((! $input_request->container_guid) || ($input_request->container_guid == 0) || $is_empty ) {
		register_error(elgg_echo('quota_request:missing_att'));
		forward(REFERER);
	}

	
	
	if ($new_request) {
		$request = new qisQuotaRequest();
		$request->quantity = $input_request->quantity;
		$request->citizenship = $input_request->citizenship;
		$request->gender = $input_request->gender;
		$request->occupation = $input_request->occupation;
		$request->status = $input_request->status;
		$request->mol_number = $input_request->mol_number;
		$request->paid = $input_request->paid;
		$request->qistype = 'quota_request';
		$request->container_guid = $input_request->container_guid;
		$request->access_id = $input_request->access_id;
		$request->save();
		$guid = $request->getGUID();

                $creator_name = $user->name;
                $date = date(DATE_RFC822);
                $diff = process_diff($input_request,$request);
                $comment = <<<__HTML
<font color='red'><h3>Modified Quota Request</h3>
<p> Modified on: $date</p>
<p> Modified by: $modifier_name</p>
$diff
</font>
__HTML;
                $request->annotate('comment',$comment,$input_request->access_id);
	} else {
		// load original file object
		if ((!$request) || ($request->qistype != 'quota_request')) {
			register_error(elgg_echo('request:cannotload'));
			forward(REFERER);
		}
	
		// user must be able to edit file
		if (!$request->canEdit()) {
			register_error(elgg_echo('request:noaccess'));
			forward(REFERER);
		}
		$guid = $request->getGUID();
		$request->quantity = $input_request->quantity;
		$request->citizenship = $input_request->citizenship;
		$request->gender = $input_request->gender;
		$request->occupation = $input_request->occupation;
		$request->status = $input_request->status;
		$request->mol_number = $input_request->mol_number;
		$request->paid = $input_request->paid;
		$request->qistype = 'quota_request';
		$request->container_guid = $input_request->container_guid;
		$request->access_id = $input_request->access_id;
		$request->save();

                $modifier_name = $user->name;
                $date = date(DATE_RFC822);
                $diff = process_diff($input_request,$request);
                $comment = <<<__HTML
<font color='red'><h3>Modified Quota Request</h3>
<p> Modified on: $date</p>
<p> Modified by: $modifier_name</p>
$diff
</font>
__HTML;
                $request->annotate('comment',$comment,$input_request->access_id);

		if (is_array($request->status)) {
			for ($i=0;$i<count($request->status);$i++) {
				if ($request->status[$i] == 'Approved') {
					$quota = new qisQuota();
					$quota->quantity = $request->quantity[$i];
					$quota->citizenship = $request->citizenship[$i];
					$quota->gender = $request->gender[$i];
					$quota->occupation = $request->occupation[$i];
					$quota->request_guid = $request->guid;
					$quota->mol_number = $request->mol_number;
					$quota->paid = $request->paid;
                			$quota->container_guid = $container_guid;
                			$quota->access_id = $input_request->access_id;
                			$quota->save();
				}
			}
		} else {
			if ($request->status == 'Approved') {
				$quota = new qisQuota();
				$quota->quantity = $request->quantity;
				$quota->citizenship = $request->citizenship;
				$quota->gender = $request->gender;
				$quota->occupation = $request->occupation;
				$quota->request_guid = $request->guid;
				$quota->mol_number = $request->mol_number;
				$quota->paid = $request->paid;
                		$quota->container_guid = $container_guid;
                		$quota->access_id = $input_request->access_id;
                		$quota->save();
			}
		}
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
		if (elgg_instanceof($container, 'group')) {
			forward("qis/manage_quota_requests/$input_request->container_guid");
		} else {
			forward("file/owner/$container->username");
		}
	} else {
		if ($guid) {
			system_message(elgg_echo("request:saved"));
		} else {
			register_error(elgg_echo("file:savefailed"));
		}
		forward("qis/manage_quota_requests/$input_request->container_guid");
	}
} elseif ($operation == elgg_echo('delete')) {
	$input_request->guid = (int) get_input('request_guid');
	$input_request->container_guid = (int) get_input('container_guid', 0);
	//$input_request->user_guid = (int) get_input('user_guid');
	
	$request = get_entity($input_request->guid);
	if ((!$request) || ($request->qistype != 'quota_request')) {
	        register_error(elgg_echo("request:deletefailed"));
		forward("qis/manage_quota_requests/$input_request->container_guid");
	}
	
	if (!$request->canEdit()) {
	        register_error(elgg_echo("request:deletefailed"));
		forward("qis/manage_quota_requests/$input_request->container_guid");
	}
	
	
	if (!$request->delete()) {
	        register_error(elgg_echo("request:deletefailed"));
	} else {
	        system_message(elgg_echo("request:deleted"));
	}
	
	forward("qis/manage_quota_requests/$input_request->container_guid");
}
elgg_set_context($context);
