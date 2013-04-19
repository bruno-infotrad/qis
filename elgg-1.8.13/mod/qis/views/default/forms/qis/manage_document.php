<?php
// once elgg_view stops throwing all sorts of junk into $vars, we can use 
$access_id = elgg_extract('access_id', $vars, ACCESS_DEFAULT);
$container_guid = elgg_extract('group_guid', $vars);
$fich_guid = get_input('fich_guid');
$request_guid = elgg_extract('request_guid', $vars, null);
$qis_groups = elgg_extract('qis_groups', $vars, null);
foreach ($qis_groups as $qis_group) {
	$option_groups[$qis_group->guid] = $qis_group->name;
}

$user = elgg_get_logged_in_user_entity();
if ($user->qisusertype == 'Immigration Agency Portal Coordinator') {
	$options_values = array('moi_receipt' => 'MOI Receipt','work_visa' => 'Work Visa', 'resident_permit' => 'Resident Permit','noc' => 'NOC');
} elseif ($user->qisusertype == 'Client Portal Administrator') {
	$options_values = array('noc' => 'NOC');
}

?>
<div class='qis-document-form'>
	<label><?php echo elgg_echo("Client"); ?>: </label>
	<?php echo elgg_view("input/dropdown", array( 'name' => 'container_guid', 'value' => $container_guid, 'options_values' => $option_groups, 'id' => 'qis-client-dropdown')); ?>
</div>
<div id='qis-request-dropdown'>
	<label><?php echo elgg_echo("Request"); ?>: </label>
	<?php echo elgg_view("input/request_dropdown", array('group_guid' => $container_guid,'request_guid' => $request_guid)); ?>
</div>

<?php
if ($fich_guid) {
	$file_label = elgg_echo("file:replace");
	$submit_label = elgg_echo('save');
	$file = get_entity($fich_guid);

} else {
	$file_label = elgg_echo("file:file");
	$submit_label = elgg_echo('upload');
}

?>
<div id='document-form'>
	<label><?php echo $file_label; ?></label>
	<?php echo elgg_view('input/file', array('name' => 'upload', 'id' => 'document-name')); ?>
</div>
<div>
	<label><?php echo elgg_echo('Document Type'); ?></label>
	<?php echo elgg_view('input/dropdown', array(
						'name' => 'document_type',
						'value' => $file->document_type,
						'options_values' => $options_values,
						'id' => 'document-type-dropdown'
						)); ?>
</div>
<div id='document-expiry'>
	<label><?php echo elgg_echo('expiry_date'); ?></label>
	<?php echo elgg_view('input/text', array('name' => 'expiry_date', 'value' => $file->expiry_date, 'class' => 'expiry')); ?>
</div>
<div class="elgg-foot">
<?php

echo elgg_view('input/hidden', array('name' => 'access_id', 'value' => $access_id));

if ($fich_guid) {
	echo elgg_view('input/hidden', array('name' => 'file_guid', 'value' => $fich_guid));
	$delete_label = elgg_echo('delete');
	echo '<div id="document-delete">';
	echo elgg_view('input/submit', array('name' => 'submit', 'value' => $delete_label));
	echo '</div>';
}

echo '<div id="document-upload">';
echo elgg_view('input/submit', array('name' => 'submit', 'value' => $submit_label));
echo '</div>';

?>
</div>

<script type="text/javascript">
        $(document).ready(function() {
                var file = $("#document-name").val();
		if (! file) {
			$("#document-upload").hide();
			$("#document-expiry").hide();
		}
                $("*").change(function() {
                	//alert(file);
                	var file = $("#document-name").val();
			if (file) {
                		var $dropdown = $("#document-type-dropdown");
                		var documentType = $dropdown.children("option").filter(":selected").val();
				if (( documentType == 'noc') || ( documentType == 'moi_receipt')) {
					$("#document-upload").show();
					$("#document-expiry").hide();
				} else {
					$("#document-upload").hide();
					$("#document-expiry").show();
					//$(".expiry").keyup(function() {
					$(".expiry").bind('keyup','paste',function() {
						//alert(this.value.length);
						if ( this.value.length != 8 ) {
							$("#document-upload").hide();
						} else {
							$("#document-upload").show();
						}
					});
				}
			} else {
				$("#document-upload").hide();
			}
                });
        });
</script>
