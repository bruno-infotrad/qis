<?php
$entity = elgg_extract('entity', $vars, NULL);
$submitter_guid = elgg_extract('submitter_guid', $vars, 0);
$group_guid = elgg_extract('group_guid', $vars, 0);

$group_members = get_group_members($group_guid,0);
$request_options = array();
foreach ($group_members as $member) {
	if (! $member->isAdmin()){
        	$request_options[$member->guid] = $member->name;
	}
}

$request_drop_down = elgg_view('input/dropdown', array(
        'name' => 'request_subject',
	'value' => NULL,
        'options_values' => $request_options,
));

?>
<div>
        <label><?php echo elgg_echo("person"); ?>: </label>
        <?php echo $request_drop_down; ?>
</div>
<div class="elgg-foot mts">
<?php

echo elgg_view('input/submit', array(
	'value' => $text,
	'id' => 'qis-submit-button',
));
?>
</div>
<script>
$("#first-choice").change(function() {

	var $dropdown = $(this);

	$.getJSON("jsondata/data.json", function(data) {
	
		var key = $dropdown.val();
		var vals = [];
							
		switch(key) {
			case 'beverages':
				vals = data.beverages.split(",");
				break;
			case 'snacks':
				vals = data.snacks.split(",");
				break;
			case 'base':
				vals = ['Please choose from above'];
		}
		
		var $secondChoice = $("#second-choice");
		$secondChoice.empty();
		$.each(vals, function(index, value) {
			$secondChoice("<option>" + value + "</option>");
		});

	});
});
</script>
