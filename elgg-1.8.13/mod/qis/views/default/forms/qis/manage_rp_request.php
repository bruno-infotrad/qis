<?php
$request_guid = elgg_extract('request_guid', $vars, 0);
$submitter_guid = elgg_extract('submitter_guid', $vars, 0);
$group_guid = elgg_extract('group_guid', $vars, 0);
$site_url = elgg_get_site_url();

if ($request_guid) {
	$submit_text = elgg_echo('save');
	$request = get_entity($request_guid);
	$user_guid = $request->user_guid;
	$user_name = get_entity($user_guid)->name;
	$request_drop_down = elgg_echo($user_name);
	$request_dropdown .= elgg_view('input/hidden', array('name' => 'user_guid', 'value' => $user_guid));
} else {
	$submit_text = elgg_echo('submit');
	$group_members = get_group_members($group_guid,0);
	$request_options = array();
	$request_options[] = '';
	foreach ($group_members as $member) {
		if (! $member->isAdmin()){
	        	$request_options[$member->guid] = $member->name;
		}
	}
	$request_drop_down = elgg_view('input/dropdown', array(
				'name' => 'user_guid',
				'value' => 'user_guid',
				'options_values' => $request_options,
				'id' => 'qisuser-dropdown',
				));
}
?>
<div id="request-immigration-service">
<div>
        <label><?php echo elgg_echo("person"); ?>: </label>
        <?php echo $request_drop_down; ?>
</div>
<div>
        <label><?php echo elgg_echo("passport_country"); ?>: </label>
	<?php echo elgg_view("input/citizenship_document_dropdown", array()); ?>
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




<div class="elgg-foot mts">
<?php
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
		var url = "<?php echo $site_url; ?>qis/view_person/"+user_guid;
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
	$("#qis-current-visa-type").hide();
	$("#qis-in-quatar-dropdown").change(function() {
		var $dropdown = $(this);
		var inQuatar = $dropdown.children("option").filter(":selected").val();
		if (inQuatar == 'yes') {
			$("#qis-current-visa-type").show();
		} else {
			$("#qis-current-visa-type").hide();
		}
	});
});
</script>

