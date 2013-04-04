<?php
//Set context to limit plugin hook
$context = elgg_get_context();
set_context('manage_person');
//Check operation (save or delete)
$operation = get_input('submit');
if ($operation == elgg_echo('save')) {
	$submitter_guid = get_input('submitter_guid');
	$submitter = get_entity($submitter_guid);
	$group_guid = get_input('group_guid');
	$group = get_entity($group_guid);
	elgg_log("BRUNO submitter_guid $submitter_guid group_guid $group_guid", NOTICE);
	//Check submitter is group admin
	if((! $submitter->isAdmin()) && (! $group->isMember($submitter) || ! check_entity_relationship($submitter->getGUID(), "group_admin", $group->getGUID()))){
		register_error(elgg_echo('pas le droit'));
		forward('/qis');
	}
	//Test empty fields
	$email = get_input('email');
	$name = strip_tags(get_input('name'));
	// no blank fields
	if ($email == '' || $name == '') {
		register_error(elgg_echo('register:fields'));
		forward(REFERER);
	}
	
	$guid = get_input('guid');
	//add user piece
	if (! $guid) {
		elgg_make_sticky_form('manage_person');
		
		// Get variables
		$username = get_input('username');
		$password = get_input('password', null, false);
		$password2 = get_input('password2', null, false);
		$email = get_input('email');
		$name = get_input('name');
		
		// no blank fields
		if ($username == '' || $password == '' || $password2 == '' || $email == '' || $name == '') {
			register_error(elgg_echo('register:fields'));
			forward(REFERER);
		}
		
		if (strcmp($password, $password2) != 0) {
			register_error(elgg_echo('RegistrationException:PasswordMismatch'));
			forward(REFERER);
		}
		
		// For now, just try and register the user
		try {
			$guid = register_user($username, $password, $name, $email, TRUE);
		
			if ($guid) {
				$new_user = get_entity($guid);
				$new_user->admin_created = TRUE;
				// @todo ugh, saving a guid as metadata!
				$new_user->created_by_guid = elgg_get_logged_in_user_guid();
	//			Add direcly to group
				if (!$group->isMember($new_user)) {
	                                groups_join_group($group, $new_user);
				}
	/*	
				$subject = elgg_echo('useradd:subject');
				$body = elgg_echo('useradd:body', array(
					$name,
					elgg_get_site_entity()->name,
					elgg_get_site_entity()->url,
					$username,
					$password,
				));
		
				notify_user($new_user->guid, elgg_get_site_entity()->guid, $subject, $body);
		
	*/
				system_message(elgg_echo("adduser:ok", array(elgg_get_site_entity()->name)));
			} else {
				register_error(elgg_echo("adduser:bad"));
			}
		} catch (RegistrationException $r) {
			register_error($r->getMessage());
		}
	}
	$owner = get_entity($guid);
	//Update display name and email
	$owner->email=$email;
	$owner->name=$name;
	
	// grab the defined profile field names and their load the values from POST.
	// each field can have its own access, so sort that too.
	$input = array();
	$accesslevel = get_input('accesslevel');
	
	if (!is_array($accesslevel)) {
		$accesslevel = array();
	}
	
	//
	// wrapper for recursive array walk decoding
	//
	function profile_array_decoder(&$v) {
		$v = html_entity_decode($v, ENT_COMPAT, 'UTF-8');
	}
	
	$profile_fields = elgg_get_config('profile_fields');
	foreach ($profile_fields as $shortname => $valuetype) {
		// the decoding is a stop gap to prevent &amp;&amp; showing up in profile fields
		// because it is escaped on both input (get_input()) and output (view:output/text). see #561 and #1405.
		// must decode in utf8 or string corruption occurs. see #1567.
		$value = get_input($shortname);
		if (is_array($value)) {
			array_walk_recursive($value, 'profile_array_decoder');
		} else {
			$value = html_entity_decode($value, ENT_COMPAT, 'UTF-8');
		}
	
		// limit to reasonable sizes
		// @todo - throwing away changes due to this is dumb!
		if (!is_array($value) && $valuetype != 'longtext' && elgg_strlen($value) > 250) {
			$error = elgg_echo('profile:field_too_long', array(elgg_echo("profile:{$shortname}")));
			register_error($error);
			forward(REFERER);
		}
	
		if ($valuetype == 'tags') {
			$value = string_to_tag_array($value);
		}
	
		$input[$shortname] = $value;
	}
	
	// display name is handled separately
	$name = strip_tags(get_input('name'));
	if ($name) {
		if (elgg_strlen($name) > 50) {
			register_error(elgg_echo('user:name:fail'));
		} elseif ($owner->name != $name) {
			$owner->name = $name;
			$owner->save();
		}
	}
	
	// go through custom fields
	if (sizeof($input) > 0) {
		foreach ($input as $shortname => $value) {
			$options = array(
				'guid' => $owner->guid,
				'metadata_name' => $shortname
			);
			elgg_delete_metadata($options);
			if (isset($accesslevel[$shortname])) {
				$access_id = (int) $accesslevel[$shortname];
			} else {
				// this should never be executed since the access level should always be set
				$access_id = ACCESS_LOGGED_IN;
			}
			if (is_array($value)) {
				$i = 0;
				foreach ($value as $interval) {
					$i++;
					$multiple = ($i > 1) ? TRUE : FALSE;
					create_metadata($owner->guid, $shortname, $interval, 'text', $owner->guid, $access_id, $multiple);
				}
			} else {
				elgg_log('BRUNO just before creat_metadata access_id '.$access_id,NOTICE);
				create_metadata($owner->getGUID(), $shortname, $value, 'text', $owner->getGUID(), $access_id);
			}
		}
	
		$owner->save();
	
		// Notify of profile update
		//elgg_trigger_event('profileupdate', $owner->type, $owner);
	
		system_message(elgg_echo("profile:saved"));
	}
	if ($new_user) {
		$referent = $_SERVER['HTTP_REFERER'];
		$referent .= "/$guid";
	} else {
		$referent = REFERER;
	}
	
	forward($referent);
} elseif ($operation == elgg_echo('delete')) {
	$guid = get_input('guid');
	$user = get_entity($guid);
	
	if ($guid == elgg_get_logged_in_user_guid()) {
	        register_error(elgg_echo('admin:user:self:delete:no'));
	        forward(REFERER);
	}
	
	$name = $user->name;
	$username = $user->username;
	
	if (($user instanceof ElggUser) && ($user->canEdit())) {
	        if ($user->delete()) {
	                system_message(elgg_echo('admin:user:delete:yes', array($name)));
	        } else {
	                register_error(elgg_echo('admin:user:delete:no'));
	        }
	} else {
	        register_error(elgg_echo('admin:user:delete:no'));
	}
	
	forward('qis/manage_persons');
	
}
elgg_set_context($context);
