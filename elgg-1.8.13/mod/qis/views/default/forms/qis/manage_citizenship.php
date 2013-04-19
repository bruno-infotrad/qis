<?php
// once elgg_view stops throwing all sorts of junk into $vars, we can use 
$access_id = elgg_extract('access_id', $vars, ACCESS_DEFAULT);
$container_guid = elgg_extract('group_guid', $vars);
$user_guid = elgg_extract('user_guid', $vars);
$guid = elgg_extract('guid', $vars, null);

elgg_log("BRUNO citizenship guid=$guid",'NOTICE');

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
                        if ($field->metadata_name == 'pob') {
                                $citizenships = $field->getOptions();
                        }
                }
        }
}


if ($guid) {
	$file_label = elgg_echo("file:replace");
	$submit_label = elgg_echo('save');
	$file = get_entity($guid);

} else {
	$file_label = elgg_echo("file:file");
	$submit_label = elgg_echo('upload');
}

?>
<div>
	<label><?php echo elgg_echo('country'); ?></label><br />
	<?php echo elgg_view('input/dropdown', array('name' => 'country', 'value' => $file->country, 'options' => $citizenships)); ?>
</div>
<div>
	<label><?php echo elgg_echo('number'); ?></label><br />
	<?php echo elgg_view('input/text', array('name' => 'number', 'value' => $file->number)); ?>
</div>
<div>
	<label><?php echo $file_label; ?></label><br />
	<?php echo elgg_view('input/file', array('name' => 'upload')); ?>
</div>
<div>
	<label><?php echo elgg_echo('date_of_issue'); ?></label><br />
	<?php echo elgg_view('input/text', array('name' => 'date_of_issue', 'value' => $file->date_of_issue)); ?>
</div>
<div>
	<label><?php echo elgg_echo('expiry_date'); ?></label><br />
	<?php echo elgg_view('input/text', array('name' => 'expiry_date', 'value' => $file->expiry_date)); ?>
</div>
<div>
	<label><?php echo elgg_echo('access'); ?></label><br />
	<?php echo elgg_view('input/hidden', array('name' => 'access_id', 'value' => $access_id)); ?>
	<?php //echo elgg_view('input/access', array('name' => 'access_id', 'value' => $access_id)); ?>
</div>
<div class="elgg-foot">
<?php

echo elgg_view('input/hidden', array('name' => 'container_guid', 'value' => $container_guid));
echo elgg_view('input/hidden', array('name' => 'user_guid', 'value' => $user_guid));

if ($guid) {
	echo elgg_view('input/hidden', array('name' => 'file_guid', 'value' => $guid));
}

echo elgg_view('input/submit', array('name' => 'submit', 'value' => $submit_label));
if ($guid) {
	$delete_label = elgg_echo('delete');
	echo elgg_view('input/submit', array('name' => 'submit', 'value' => $delete_label));
}

?>
</div>
