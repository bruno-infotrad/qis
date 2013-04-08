<?php
$entity = elgg_extract('entity', $vars, null);

$form_vars = array(
	'enctype' => 'multipart/form-data',
	'class' => 'elgg-form-alt',
);

echo elgg_view_form('qis/manage_corporate_info', $form_vars, groups_prepare_form_vars($entity));
