<?php
$request_guid = elgg_extract('request_guid', $vars, 0);
$request = get_entity($request_guid);
$submitter_guid = elgg_extract('submitter_guid', $vars, 0);
$group_guid = elgg_extract('group_guid', $vars, 0);
$access_id = elgg_extract('access_id', $vars, 0);
$site_url = elgg_get_site_url();

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
$status_label = array('1'=>'Pending','2'=>'Approved', '3'=> 'Rejected');
/*
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
if ($request->qistype != 'quota_request') {
	register_error(elgg_echo('not_a_quota_request'));
	forward('/qis/manage_quota_requests');
}
$submit_text = elgg_echo('save');
$request = get_entity($request_guid);
$quantity = $request->quantity;
//number of array members
$num_lines = count($quantity);
$citizenship = $request->citizenship;
$gender = $request->gender;
$occupation = $request->occupation;
$status = $request->status;
$mol_number = $request->mol_number;
$paid = $request->paid;
$content = '';
//Get user to present different screens to admin and company portal admin
//Approval by admin only
$user = elgg_get_logged_in_user_entity();
?>
<div id="multiline-form-quota">
<?php
if (is_array($quantity)){
	for ($i=0;$i<$num_lines;$i++) {
		$content .= '<div id="request-quota"><div class="first-line"><label>'.elgg_echo("quantity").': </label>'.elgg_view('input/text', array('name' => 'quantity[]', 'maxlength' => '2', 'size' => '2', 'value' => $quantity[$i], 'class' => 'quantity')).'</div>';
		$content .= '<div class="first-line"><label>'.elgg_echo("citizenship").': </label>'.elgg_view("input/dropdown", array( 'name' => 'citizenship[]', 'value' => $citizenship[$i], 'options' => $citizenships,)).'</div>';
		$content .= '<div class="first-line"><label>'.elgg_echo("gender").': </label>'.elgg_view("input/dropdown", array( 'name' => 'gender[]', 'value' => $gender[$i], 'options' => array('F','M'),)).'</div>';
		$content .= '<div class="first-line"><label>'.elgg_echo("occupation").': </label>'.elgg_view("input/dropdown", array( 'name' => 'occupation[]', 'value' => $occupation[$i], 'options' => $occupations)).'</div>';
		if ($user->isAdmin()){
			$content .= '<div class="first-line"><label>'.elgg_echo("status").': </label>'.elgg_view("input/dropdown", array( 'name' => 'status[]', 'value' => $status[$i], 'options' => array('Pending','Approved','Rejected'),)).'</div></div>';
			$content .= '<div id="request-quota"><div class="first-line"><label>'.elgg_echo("MOL Number").': </label>'.elgg_view('input/text', array('name' => 'mol_number[]', 'maxlength' => '2', 'size' => '2', 'value' => $mol_number[$i], 'class' => 'quantity')).'</div>';
			$content .= '<div id="request-quota"><div class="first-line"><label>'.elgg_echo("Paiement sent").': </label>'.elgg_view('input/dropdown', array('name' => 'paid[]', 'value' => $paid[$i], 'options' => array('No','Yes'))).'</div>';
		} else {
			$content .= '<div class="first-line"><label>'.elgg_echo("status").': </label>'.elgg_echo($status[$i]).elgg_view('input/hidden', array('name' => 'status[]', 'value' => $status[$i])).'</div>';
			$content .= '<div class="first-line">'.elgg_view('input/hidden', array('name' => 'mol_number[]', 'value' => $mol_number[$i])).'</div>';
			$content .= '<div class="first-line">'.elgg_view('input/hidden', array('name' => 'paid[]', 'value' => $paid[$i])).'</div>';
		}
	}
	$content .= '<div class="first-line">'.elgg_view('input/hidden', array('name' => 'paid[]', 'value' => $paid)).'</div>';
	echo $content;
} else {
	$content .= '<div id="request-quota"><div class="first-line"><label>'.elgg_echo("quantity").': </label>'.elgg_view('input/text', array('name' => 'quantity[]', 'maxlength' => '2', 'size' => '2', 'value' => $quantity, 'class' => 'quantity')).'</div>';
	$content .= '<div class="first-line"><label>'.elgg_echo("citizenship").': </label>'.elgg_view("input/dropdown", array( 'name' => 'citizenship[]', 'value' => $citizenship, 'options' => $citizenships,)).'</div>';
	$content .= '<div class="first-line"><label>'.elgg_echo("gender").': </label>'.elgg_view("input/dropdown", array( 'name' => 'gender[]', 'value' => $gender, 'options' => array('F','M'),)).'</div>';
	$content .= '<div class="first-line"><label>'.elgg_echo("occupation").': </label>'.elgg_view("input/dropdown", array( 'name' => 'occupation[]', 'value' => $occupation, 'options' => $occupations)).'</div>';
	if ($user->isAdmin()){
		$content .= '<div class="first-line"><label>'.elgg_echo("status").': </label>'.elgg_view("input/dropdown", array( 'name' => 'status[]', 'value' => $status, 'options' => array('Pending','Approved','Rejected'),)).'</div></div>';
		$content .= '<div id="request-quota"><div class="first-line"><label>'.elgg_echo("MOL Number").': </label>'.elgg_view('input/text', array('name' => 'mol_number[]', 'maxlength' => '2', 'size' => '2', 'value' => $mol_number, 'class' => 'quantity')).'</div>';
		$content .= '<div id="request-quota"><div class="first-line"><label>'.elgg_echo("Paiement sent").': </label>'.elgg_view('input/dropdown', array('name' => 'paid[]', 'value' => $paid, 'options' => array('No','Yes'))).'</div>';
	} else {
		$content .= '<div class="first-line"><label>'.elgg_echo("status").': </label>'.elgg_echo($status).elgg_view('input/hidden', array('name' => 'status[]', 'value' => $status)).'</div>';
		$content .= '<div class="first-line">'.elgg_view('input/hidden', array('name' => 'mol_number[]', 'value' => $mol_number)).'</div>';
		$content .= '<div class="first-line">'.elgg_view('input/hidden', array('name' => 'paid[]', 'value' => $paid)).'</div>';
	}
	$content .= '</div>';
	echo $content;
}
?>
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
