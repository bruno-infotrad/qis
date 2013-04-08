<?php

// only extract these elements.
$group = $vars['entity'];
$name = $membership = $vis = $entity = null;
extract($vars, EXTR_IF_EXISTS);

?>
<div class="qis-user-att">
	<?php echo elgg_view_entity_icon($group, 'large', array('href' => '')); ?>
</div>
<div class="qis-user-att">
	<label><?php echo elgg_echo("groups:icon"); ?></label><br />
	<?php 	echo elgg_view("input/file", array('name' => 'icon')); ?>
</div>
<div class="qis-user-att">
	<label><?php echo elgg_echo("groups:name"); ?></label>
	<?php 	echo elgg_echo($name);
		echo elgg_view('input/hidden', array( 'name' => 'name', 'value' => $name,));
	?>
</div>
<div class="qis-user-att">
        <label><?php echo elgg_echo('qis:id_number'); ?></label>
        <?php echo elgg_view('input/text', array('name' => 'company_id_number', 'value' => $group->company_id_number)); ?>
</div>
<div class="qis-user-att">
        <label><?php echo elgg_echo('qis:registration_card_number'); ?></label>
        <?php echo elgg_view('input/text', array('name' => 'company_registration_card_number', 'value' => $vars['entity']->company_registration_card_number)); ?>
</div>

<?php
?>
<div class="elgg-foot">
<?php

if ($entity) {
	echo elgg_view('input/hidden', array(
		'name' => 'group_guid',
		'value' => $entity->getGUID(),
	));
}

echo elgg_view('input/submit', array('value' => elgg_echo('save')));
?>
</div>
