<?php
//Set context to limit plugin hook
$context = elgg_get_context();
elgg_set_context('manage_citizenhip');
//Check operation (save or delete)
$operation = get_input('submit');
// Upload or modify
if ($operation == elgg_echo('save') || $operation == elgg_echo('upload')) {
	// Get variables
	$country = htmlspecialchars(get_input('country', '', false), ENT_QUOTES, 'UTF-8');
	$number = get_input("number");
	$date_of_issue = get_input("date_of_issue");
	$expiry_date= get_input("expiry_date");
	$access_id = (int) get_input("access_id");
	$container_guid = (int) get_input('container_guid', 0);
	$user_guid = (int) get_input('user_guid');
	$guid = (int) get_input('file_guid');
	$tags = get_input("tags");
	
	if ($container_guid == 0) {
		$container_guid = elgg_get_logged_in_user_guid();
	}
	
	elgg_make_sticky_form('manage_citizenship');
	
	// check if upload failed
	if (!empty($_FILES['upload']['name']) && $_FILES['upload']['error'] != 0) {
		register_error(elgg_echo('file:cannotload'));
		forward(REFERER);
	}
	
	// check whether this is a new file or an edit
	$new_file = true;
	if ($guid > 0) {
		$new_file = false;
	}
	
	if ($new_file) {
		// must have a file if a new file upload
		if (empty($_FILES['upload']['name'])) {
			$error = elgg_echo('file:nofile');
			register_error($error);
			forward(REFERER);
		}
	
		$file = new FilePluginFile();
		$file->subtype = "file";
		//$file->owner_guid = $user_guid;
		$file->employee_guid = $user_guid;
		$file->qistype = 'passport';
	
		// if no title on new upload, grab filename
		if (empty($title)) {
			$title = htmlspecialchars($_FILES['upload']['name'], ENT_QUOTES, 'UTF-8');
		}
	
	} else {
		// load original file object
		$file = new FilePluginFile($guid);
		if (!$file) {
			register_error(elgg_echo('file:cannotload'));
			forward(REFERER);
		}
	
		// user must be able to edit file
		if (!$file->canEdit()) {
			register_error(elgg_echo('file:noaccess'));
			forward(REFERER);
		}
	
		if (!$title) {
			// user blanked title, but we need one
			$title = $file->title;
		}
	}
	
	$file->id = 'passport';
	$file->country = $country;
	$file->number = $number;
	$file->date_of_issue = $date_of_issue;
	$file->expiry_date = $expiry_date;
	$file->access_id = $access_id;
	$file->container_guid = $container_guid;
	$file->qistype = 'passport';
	
	$tags = explode(",", $tags);
	$file->tags = $tags;
	
	// we have a file upload, so process it
	if (isset($_FILES['upload']['name']) && !empty($_FILES['upload']['name'])) {
	
		$prefix = "file/";
	
		// if previous file, delete it
		if ($new_file == false) {
			$filename = $file->getFilenameOnFilestore();
			if (file_exists($filename)) {
				unlink($filename);
			}
	
			// use same filename on the disk - ensures thumbnails are overwritten
			$filestorename = $file->getFilename();
			$filestorename = elgg_substr($filestorename, elgg_strlen($prefix));
		} else {
			$filestorename = elgg_strtolower(time().$_FILES['upload']['name']);
		}
	
		$file->setFilename($prefix . $filestorename);
		$mime_type = ElggFile::detectMimeType($_FILES['upload']['tmp_name'], $_FILES['upload']['type']);
	
		// hack for Microsoft zipped formats
		$info = pathinfo($_FILES['upload']['name']);
		$office_formats = array('docx', 'xlsx', 'pptx');
		if ($mime_type == "application/zip" && in_array($info['extension'], $office_formats)) {
			switch ($info['extension']) {
				case 'docx':
					$mime_type = "application/vnd.openxmlformats-officedocument.wordprocessingml.document";
					break;
				case 'xlsx':
					$mime_type = "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet";
					break;
				case 'pptx':
					$mime_type = "application/vnd.openxmlformats-officedocument.presentationml.presentation";
					break;
			}
		}
	
		// check for bad ppt detection
		if ($mime_type == "application/vnd.ms-office" && $info['extension'] == "ppt") {
			$mime_type = "application/vnd.ms-powerpoint";
		}
	
		$file->setMimeType($mime_type);
		$file->originalfilename = $_FILES['upload']['name'];
		$file->simpletype = file_get_simple_type($mime_type);
	
		// Open the file to guarantee the directory exists
		$file->open("write");
		$file->close();
		move_uploaded_file($_FILES['upload']['tmp_name'], $file->getFilenameOnFilestore());
	
		$guid = $file->save();
	
		// if image, we need to create thumbnails (this should be moved into a function)
		if ($guid && $file->simpletype == "image") {
			$file->icontime = time();
			
			$thumbnail = get_resized_image_from_existing_file($file->getFilenameOnFilestore(), 60, 60, true);
			if ($thumbnail) {
				$thumb = new ElggFile();
				$thumb->setMimeType($_FILES['upload']['type']);
	
				$thumb->setFilename($prefix."thumb".$filestorename);
				$thumb->open("write");
				$thumb->write($thumbnail);
				$thumb->close();
	
				$file->thumbnail = $prefix."thumb".$filestorename;
				unset($thumbnail);
			}
	
			$thumbsmall = get_resized_image_from_existing_file($file->getFilenameOnFilestore(), 153, 153, true);
			if ($thumbsmall) {
				$thumb->setFilename($prefix."smallthumb".$filestorename);
				$thumb->open("write");
				$thumb->write($thumbsmall);
				$thumb->close();
				$file->smallthumb = $prefix."smallthumb".$filestorename;
				unset($thumbsmall);
			}
	
			$thumblarge = get_resized_image_from_existing_file($file->getFilenameOnFilestore(), 600, 600, false);
			if ($thumblarge) {
				$thumb->setFilename($prefix."largethumb".$filestorename);
				$thumb->open("write");
				$thumb->write($thumblarge);
				$thumb->close();
				$file->largethumb = $prefix."largethumb".$filestorename;
				unset($thumblarge);
			}
		}
	} else {
		// not saving a file but still need to save the entity to push attributes to database
		$file->save();
	}
	
	// file saved so clear sticky form
	elgg_clear_sticky_form('file');
	
	
	// handle results differently for new files and file updates
	if ($new_file) {
		if ($guid) {
			$message = elgg_echo("file:saved");
			system_message($message);
			add_to_river('river/object/file/create', 'create', elgg_get_logged_in_user_guid(), $file->guid);
		} else {
			// failed to save file object - nothing we can do about this
			$error = elgg_echo("file:uploadfailed");
			register_error($error);
		}
	
		$container = get_entity($container_guid);
		if (elgg_instanceof($container, 'group')) {
			forward("qis/manage_person/$container_guid/$user_guid");
		} else {
			forward("file/owner/$container->username");
		}
	
	} else {
		if ($guid) {
			system_message(elgg_echo("file:saved"));
		} else {
			register_error(elgg_echo("file:uploadfailed"));
		}

	forward("qis/manage_person/$container_guid/$user_guid");
	}
} elseif ($operation == elgg_echo('delete')) {
	$guid = (int) get_input('file_guid');
	$user_guid = (int) get_input('user_guid');
	
	$file = new FilePluginFile($guid);
	if (!$file->guid) {
	        register_error(elgg_echo("file:deletefailed"));
	        forward('file/all');
	}
	
	if (!$file->canEdit()) {
	        register_error(elgg_echo("file:deletefailed"));
	        forward($file->getURL());
	}
	
	$container = $file->getContainerEntity();
	
	if (!$file->delete()) {
	        register_error(elgg_echo("file:deletefailed"));
	} else {
	        system_message(elgg_echo("file:deleted"));
	}
	
	forward("qis/manage_person/$container_guid/$user_guid");
}
elgg_set_context($context);
