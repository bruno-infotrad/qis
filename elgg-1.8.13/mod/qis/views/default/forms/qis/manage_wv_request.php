<?php
$request_guid = elgg_extract('request_guid', $vars, 0);
$submitter_guid = elgg_extract('submitter_guid', $vars, 0);
$group_guid = elgg_extract('group_guid', $vars, 0);
$group = get_entity($group_guid);
$access_id = elgg_extract('access_id', $vars, 0);
$site_url = elgg_get_site_url();

if ($request_guid) {
	$submit_text = elgg_echo('save');
	$request = get_entity($request_guid);
	$user_guid = $request->user_guid;
	$user = get_entity($user_guid);
	$user_name = $user->name;
	$passports = elgg_get_entities_from_metadata(array(
                'types' => 'object',
                'subtypes' => 'file',
                'container_guid' => $group->guid,
                'metadata_name_value_pairs' => array(
                                                        array('name'  => 'employee_guid', 'value' => $user_guid),
                                                        array('name'  => 'qistype', 'value' => 'passport'),
                                                ),
                'full_view' => FALSE,
        ));
	foreach ($passports as $passport) {
		$passport_values[$passport->guid] = $passport->country;
	}
	$passport_guid = $request->passport_guid;
	$relationship_with_sponsor = $request->relationship_with_sponsor;
	$duration = $request->duration;
	$in_quatar = $request->in_quatar;
	$current_visa_type = $request->current_visa_type;
	?>
	<div id="request-immigration-service">
	<div>
	        <label><?php echo elgg_echo("person"); ?>: </label>
	        <?php echo elgg_echo($user_name); ?>
	        <?php echo elgg_view('input/hidden', array('name' => 'user_guid', 'value' => $user_guid)); ?>
	</div>
	<div>
	        <label><?php echo elgg_echo("passport_country"); ?>: </label>
		<?php echo elgg_view("input/dropdown", array(
				'name' => 'passport_guid',
				'value' => $passport_guid,
				'options_values' => $passport_values,
				));
		?>
	</div>
	<div>
	        <label><?php echo elgg_echo("sponsor"); ?>: </label>
		<?php echo elgg_view("input/dropdown", array(
				'name' => 'relationship_with_sponsor',
				'value' => $relationship_with_sponsor,
				'options_values' => array('one'=>'I have no idea','two'=>'what this is supposed to be'),
				));
		?>
	</div>
	<div>
	        <label><?php echo elgg_echo("duration"); ?>: </label>
		<?php echo elgg_view("input/dropdown", array(
				'name' => 'duration',
				'value' => $duration,
				'options_values' => array('1'=>1,'2'=>2,'3'=>3),
				));
		?>
	</div>
	<div>
	        <label><?php echo elgg_echo("in_quatar"); ?>: </label>
		<?php echo elgg_view("input/dropdown", array(
				'name' => 'in_quatar',
				'value' => $in_quatar,
				'options_values' => array('no'=>'no','yes'=>'yes'),
				'id' => 'qis-in-quatar-dropdown',
				));
		?>
	</div>
	<div id='qis-current-visa-type'>
	        <label><?php echo elgg_echo("current_visa_type"); ?>: </label>
		<?php echo elgg_view("input/dropdown", array(
				'name' => 'current_visa_type',
				'value' => $current_visa_type,
				'options_values' => array('tourist'=>'tourist','business'=>'business'),
				));
		?>
	</div>

	<div id="qis-selected-user">
		<?php echo elgg_view('qis/view_person_and_citizenships',array('user_guid' => $user_guid)); ?>
	</div>
	</div>
<?php
} else {
	elgg_load_library('elgg:qis');
	$submit_text = elgg_echo('submit');
	$group_members = get_group_members($group_guid,0);
	$request_options = array();
	$request_options[] = '';
//Work visa business logic: if no request of if no/not enough quot, do not allow to select user
	foreach ($group_members as $member) {
		if (! $member->isAdmin()){
			$unavailable = check_quota_and_quota_requests($group,NULL,$member->profession,$member->gender,'work_visa',1,FALSE);
			if (! $unavailable) {
	        		$request_options[$member->guid] = $member->name;
			}
		}
	}
	$request_drop_down = elgg_view('input/dropdown', array(
				'name' => 'user_guid',
				'value' => 'user_guid',
				'options_values' => $request_options,
				'id' => 'qisuser-dropdown',
				));
	?>
	<div id="request-immigration-service">
	<div>
	        <label><?php echo elgg_echo("person"); ?>: </label>
	        <?php echo $request_drop_down; ?>
	</div>
	<div>
	        <label><?php echo elgg_echo("passport_country"); ?>: </label>
		<?php echo elgg_view("input/citizenship_document_dropdown", array('request'=> 'work_visa')); ?>
	</div>
	<div>
	        <label><?php echo elgg_echo("sponsor"); ?>: </label>
		<?php echo elgg_view("input/dropdown", array(
				'name' => 'relationship_with_sponsor',
				'value' => 'relationship_with_sponsor',
				'options_values' => array('one'=>'I have no idea','two'=>'what this is supposed to be'),
				));
		?>
	</div>
	<div>
	        <label><?php echo elgg_echo("duration"); ?>: </label>
		<?php echo elgg_view("input/dropdown", array(
				'name' => 'duration',
				'value' => 'duration',
				'options_values' => array('1'=>1,'2'=>2,'3'=>3),
				));
		?>
	</div>
	<div>
	        <label><?php echo elgg_echo("in_quatar"); ?>: </label>
		<?php echo elgg_view("input/dropdown", array(
				'name' => 'in_quatar',
				'value' => 'in_quatar',
				'options_values' => array('no'=>'no','yes'=>'yes'),
				'id' => 'qis-in-quatar-dropdown',
				));
		?>
	</div>
	<div id='qis-current-visa-type'>
	        <label><?php echo elgg_echo("current_visa_type"); ?>: </label>
		<?php echo elgg_view("input/dropdown", array(
				'name' => 'current_visa_type',
				'value' => 'current_visa_type',
				'options_values' => array('tourist'=>'tourist','business'=>'business'),
				));
		?>
	</div>
	
	<div id="qis-selected-user">
	</div>
	</div>
<?php	
}
?>
<div class="elgg-foot mts">
<?php
echo elgg_view('input/hidden', array('name' => 'access_id', 'value' => $access_id));
echo elgg_view('input/hidden', array('name' => 'container_guid', 'value' => $group_guid));
echo elgg_view('input/hidden', array('name' => 'request_guid', 'value' => $request_guid));
echo elgg_view('input/submit', array('name' => 'submit', 'value' => $submit_text, 'id' => 'qis-submit-button',));
if ($request_guid) {
	echo elgg_view('input/submit', array('name' => 'submit', 'value' => elgg_echo('delete'), 'id' => 'qis-submit-button',));
}
?>
</div>
<script type="text/javascript">
$(document).ready(function() {
	$("#qisuser-dropdown").change(function() {
		var $dropdown = $(this);
		//alert($dropdown.prop('outerHTML'));
		var user_guid = $dropdown.children("option").filter(":selected").val();
		var url = "<?php echo $site_url; ?>qis/view_person/<?php echo $group_guid;?>/"+user_guid;
		$.get( url, {},function(data){
			//alert($dropdown.children("option").filter(":selected").val());
			//alert(data.toSource());
			var $selectedUser = $("#qis-selected-user");
			$selectedUser.empty();
			$selectedUser.html(data);
		});
	});
});
</script>
<script type="text/javascript">
$(document).ready(function() {
	var $dropdown = $("#qis-in-quatar-dropdown");
	var inQuatar = $dropdown.children("option").filter(":selected").val();
	if (inQuatar == 'yes') {
		$("#qis-current-visa-type").show();
	} else {
		$("#qis-current-visa-type").hide();
	};
	$("#qis-in-quatar-dropdown").change(function() {
		var $thisdropdown = $(this);
		var inQuatar = $thisdropdown.children("option").filter(":selected").val();
		if (inQuatar == 'yes') {
			$("#qis-current-visa-type").show();
		} else {
			$("#qis-current-visa-type").hide();
		}
	});
});
</script>

