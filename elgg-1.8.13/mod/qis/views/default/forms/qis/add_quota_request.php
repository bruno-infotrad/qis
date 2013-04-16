<?php
$submitter_guid = elgg_extract('submitter_guid', $vars, 0);
$group_guid = elgg_extract('group_guid', $vars, 0);
$access_id = elgg_extract('access_id', $vars, 0);
$site_url = elgg_get_site_url();

//Need ACL for allowing only QSHIELD to approve
//$disabled = false;
$disabled = 'disabled';
//Profile manager piece
// Build fields
$categorized_fields = profile_manager_get_categorized_fields($vars['entity'], true);
$cats = $categorized_fields['categories'];
$fields = $categorized_fields['fields'];

$user_metadata = profile_manager_get_user_profile_data($vars['entity']);

$edit_profile_mode = elgg_get_plugin_setting("edit_profile_mode", "profile_manager");
$simple_access_control = elgg_get_plugin_setting("simple_access_control","profile_manager");

if(!empty($cats)){

        // Profile type selector
        $setting = elgg_get_plugin_setting("profile_type_selection", "profile_manager");
        if(empty($setting)){
                // default value
                $setting = "user";
        }

        foreach($cats as $cat_guid => $cat){
                foreach($fields[$cat_guid] as $field){
                        if ($field->metadata_name == 'profession') {
                                $occupations = $field->getOptions();
                        } elseif ($field->metadata_name == 'pob') {        
                                $citizenships = $field->getOptions();
                        }
                }
        }
}
/*
//get country from table
$countries = elgg_get_entities(array(
                	'types' => 'object',
                	'subtypes' => 'country',
			'limit' => 0,
                	'full_view' => FALSE,
        	));    
foreach ($countries as $country) {
	$citizenships[$country->num] = $country->name;
}
$occupations =  array(	'1'=> 'Accountant',
			'2'=> 'Teacher',
			'3'=> 'Nurse',
			'4'=> 'Doctor',
			'5'=> 'IT specialist',
			'6'=> 'Oil field worker',
			'7'=> 'Interpret',
			'8'=> 'Translator',
		);
*/

$submit_text = elgg_echo('submit');
$status = 'Pending';

?>
<div id="multiline-form-quota">
<div id="request-quota">
	<div class='first-line'>
	        <label><?php echo elgg_echo("quantity"); ?>: </label>
		<?php echo elgg_view('input/text', array('name' => 'quantity[]', 'maxlength' => '2', 'size' => '2', 'value' => $quantity, 'class' => 'quantity')); ?>
	</div>
	<div class='first-line'>
	        <label><?php echo elgg_echo("citizenship"); ?>: </label>
		<?php echo elgg_view("input/dropdown", array(
				'name' => 'citizenship[]',
				'value' => $citizenship,
				'options' => $citizenships,
				));
		?>
	</div>
	<div class='first-line'>
	        <label><?php echo elgg_echo("gender"); ?>: </label>
		<?php echo elgg_view("input/dropdown", array( 'name' => 'gender[]', 'value' => $gender, 'options' => array('F','M'),)); ?>
	</div>
	<div class='first-line'>
	        <label><?php echo elgg_echo("occupation"); ?>: </label>
		<?php echo elgg_view("input/dropdown", array(
				'name' => 'occupation[]',
				'value' => $occupation,
				'options' => $occupations,
				));
		?>
	</div>
	<div class='first-line'>
	        <label><?php echo elgg_echo("status"); ?>: </label>
		<?php //echo elgg_view("input/dropdown", array( 'name' => 'status[]', 'value' => $status, 'disabled' => $disabled, 'options_values' => array('1'=>'Pending','2'=>'Approved','3'=>'Rejected'),)); ?>
		<?php echo elgg_echo($status); ?>
		<?php echo elgg_view('input/hidden', array('name' => 'status[]', 'value' => $status));?>
	</div>
	<div class='first-line'>
		<div><a href='#' id='add-row'>+</a></div>
	</div>
</div>
</div>
<div class="elgg-foot mts" id='quota-submit'>
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
	//$("a#add-row:last").click(function() { $(this).hide();$('#multiline-form-quota').append("\
	$("a#add-row:last").click(function() {$('#multiline-form-quota').append("\
<div id='request-quota-more'><hr />\
	<div class='first-line'><label>quantity: </label><input name='quantity[]' maxlength='2' size='2' type='text' class='elgg-input-text quantity'></div> \
	<div class='first-line'> <label>citizenship: </label> <select name='citizenship[]' class='elgg-input-dropdown'> \
	<?php foreach ($citizenships as $citizenship) {echo '<option>'.$citizenship.'</option>';} ?> \
	</select></div> \
	<div class='first-line'> <label>gender: </label> <select name='gender[]' class='elgg-input-dropdown'><option>F</option><option>M</option></select></div> \
	<div class='first-line'> <label>occupation: </label> <select name='occupation[]' class='elgg-input-dropdown'> \
	<?php foreach ($occupations as $occupation) {echo '<option>'.$occupation.'</option>';} ?> \
	</select></div> \
	<div class='first-line'> <label>status: </label><?php echo elgg_echo ($status);?><input hidden name='status[]' value = <?php echo elgg_echo ($status);?> class='elgg-input-text'></div> \
</div>\
");
});
});
</script>
