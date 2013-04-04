<?php
array('entity' => $request,'submitter_guid' => $submitter->guid, 'group_guid'=> $group_guid));
$entity = elgg_extract('entity', $vars, NULL);
$submitter_guid = elgg_extract('submitter_guid', $vars, 0);
$group_guid = elgg_extract('group_guid', $vars, 0);

$group_members = get_group_members($group_guid,0);
$request_options = array();
foreach ($group_members as $member) {
        $recipients_options[$member->guid] = $friend->name;
}

$recipient_drop_down = elgg_view('input/dropdown', array(
        'name' => 'request_subject',
        'options_values' => $recipients_options,
));

?>
<div>
        <label><?php echo elgg_echo("person"); ?>: </label>
        <?php echo $recipient_drop_down; ?>
</div>
<div class="elgg-foot mts">
<?php

echo elgg_view('input/submit', array(
	'value' => $text,
	'id' => 'qis-submit-button',
));
?>
</div>
