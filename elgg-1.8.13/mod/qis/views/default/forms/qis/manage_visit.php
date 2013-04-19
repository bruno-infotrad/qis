<?php
elgg_load_library('elgg:qis');
$request_guid = elgg_extract('request_guid', $vars, 0);
$group_guid = elgg_extract('group_guid', $vars, 0);
$access_id = elgg_extract('access_id', $vars, 0);
$site_url = elgg_get_site_url();

//Existing request
if (! $request_guid) {
	system_message(elgg_echo("missing_request_guid"));
	forward('/qis/dashboard');
}
$submitter = elgg_get_logged_in_user_entity();
if ($submitter->qisusertype != 'Immigration Agency Portal Coordinator') {
	system_message(elgg_echo("pas_le_droit"));
	forward('/qis/dashboard');
}
$submit_text = elgg_echo('Schedule');
$request = get_entity($request_guid);
//Check if work visa document is available. If not, can't schedule
$document = elgg_get_entities_from_metadata(array(
                'types' => 'object',
                'subtypes' => 'file',
                'container_guid' => $group->guid,
                'metadata_name_value_pairs' => array(
                					array('name'  => 'qistype', 'value' => 'document'),
                					array('name'  => 'request_guid', 'value' => $request_guid),
		),
                'full_view' => FALSE,
		'count' => true
        ));
if (! $document) {
	$message = '<h3><font color="red">No visa document for this request, can\'t schedule</font></h3>';
}
$user_guid = $request->user_guid;
$user = get_entity($user_guid);
$user_name = $user->name;
$medical_visit = $request->medical_visit;
$blood_test = $request->blood_test;
$fingerprints = $request->fingerprints;
?>
<div id="request-immigration-service">
	<?php echo $message; ?>
	<div id="qis-selected-user">
		<?php echo elgg_view('qis/view_person_and_citizenships',array('user_guid' => $user_guid)); ?>
	</div>
	<div>
	        <label><?php echo elgg_echo("Schedule Medical"); ?>: </label>
	        <?php echo elgg_view('input/text', array('name' => 'medical_visit', 'value' => $medical_visit, 'class' => 'visa')); ?>
	</div>
	<div>
	        <label><?php echo elgg_echo("Schedule Blood Test"); ?>: </label>
	        <?php echo elgg_view('input/text', array('name' => 'blood_test', 'value' => $blood_test, 'class' => 'visa')); ?>
	</div>
	<div>
	        <label><?php echo elgg_echo("Schedule Fingerprinting"); ?>: </label>
	        <?php echo elgg_view('input/text', array('name' => 'fingerprints', 'value' => $fingerprints, 'class' => 'visa')); ?>
	</div>
	<div class="elgg-foot mts">
	<?php 
	if ($document) {
		echo elgg_view('input/hidden', array('name' => 'access_id', 'value' => $access_id));
		echo elgg_view('input/hidden', array('name' => 'container_guid', 'value' => $group_guid));
		echo elgg_view('input/hidden', array('name' => 'request_guid', 'value' => $request_guid));
		echo elgg_view('input/submit', array('name' => 'submit', 'value' => $submit_text, 'id' => 'qis-submit-button',));
	}
	?>
	</div>
</div>
