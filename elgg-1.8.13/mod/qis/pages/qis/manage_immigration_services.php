<?php
if (elgg_is_logged_in()) {
/* Will have to check wich group the user belongs to and which role he has to present the right screen*/
$db_prefix = elgg_get_config('dbprefix');
$user = elgg_get_logged_in_user_entity();
/*
if ($user->qistype == 'Portal Administrator') {
	roles_set_role('portal_administrator', $user);
}
$user_role = roles_get_role();


/* now we have user role and group, present screen accordingly*/

$title = elgg_echo('qis:manage_immigration_services');

$options = array(
	'count' => true,
	"type" => "object",
	"subtype" => 'resident_permit',
	"metadata_name_value_pairs" => array(
						"name" => "duration",
						"value" => 1
                                ),
                                //"wheres" => array("(e.guid <> " . $object->getGUID() . ")") // prevent deadloops
);
$ResidentPermits_1y = elgg_get_entities_from_metadata($options);
$options = array(
	'count' => false,
	"type" => "object",
	"subtype" => 'quota',
	"metadata_name_value_pairs" => array(
						"name" => "quota_type_duration",
						"value" => 1
                                ),
                                //"wheres" => array("(e.guid <> " . $object->getGUID() . ")") // prevent deadloops
);
$Quotas_1y = elgg_get_entities_from_metadata($options);
$Quota_1y_number = 0;
if ($Quotas_1y) {
	$Quota_1y_number = $Quotas_1y[0]->quota_number;
}
$Quotas_1y_remaining = $Quota_1y_number-$ResidentPermits_1y;
$options = array(
	'count' => true,
	"type" => "object",
	"subtype" => 'resident_permit',
	"metadata_name_value_pairs" => array(
						"name" => "duration",
						"value" => 2
                                ),
                                //"wheres" => array("(e.guid <> " . $object->getGUID() . ")") // prevent deadloops
);
$ResidentPermits_2y = elgg_get_entities_from_metadata($options);
$options = array(
	'count' => false,
	"type" => "object",
	"subtype" => 'quota',
	"metadata_name_value_pairs" => array(
						"name" => "quota_type_duration",
						"value" => 2
                                ),
                                //"wheres" => array("(e.guid <> " . $object->getGUID() . ")") // prevent deadloops
);
$Quotas_2y = elgg_get_entities_from_metadata($options);
$Quota_2y_number = 0;
if ($Quotas_2y) {
	$Quota_2y_number = $Quotas_2y[0]->quota_number;
}
$Quotas_2y_remaining = $Quota_2y_number-$ResidentPermits_2y;
$options = array(
	'count' => true,
	"type" => "object",
	"subtype" => 'resident_permit',
	"metadata_name_value_pairs" => array(
						"name" => "duration",
						"value" => 3
                                ),
                                //"wheres" => array("(e.guid <> " . $object->getGUID() . ")") // prevent deadloops
);
$ResidentPermits_3y = elgg_get_entities_from_metadata($options);
$options = array(
	'count' => false,
	"type" => "object",
	"subtype" => 'quota',
	"metadata_name_value_pairs" => array(
						"name" => "quota_type_duration",
						"value" => 3
                                ),
                                //"wheres" => array("(e.guid <> " . $object->getGUID() . ")") // prevent deadloops
);
$Quotas_3y = elgg_get_entities_from_metadata($options);
$Quota_3y_number = 0;
if ($Quotas_3y) {
	$Quota_3y_number = $Quotas_3y[0]->quota_number;
}
$Quotas_3y_remaining = $Quota_3y_number-$ResidentPermits_3y;
$content = <<<__HTML
<table id="qis_ris"><tr><th>Service</th><th>In Scope of Service Agreement</th><th>Already Requested</th><th>Remaining</th><th>Request Immigration Service</th></tr>
<tr><td>qis:quotas_1y</td><td>$Quota_1y_number</td><td>$ResidentPermits_1y</td><td>$Quotas_1y_remaining</td><td></td></tr>
<tr><td>qis:quotas_2y</td><td>$Quota_2y_number</td><td>$ResidentPermits_2y</td><td>$Quotas_2y_remaining</td><td></td></tr>
<tr><td>qis:quotas_3y</td><td>$Quota_3y_number</td><td>$ResidentPermits_3y</td><td>$Quotas_3y_remaining</td><td></td></tr>
</table>
__HTML;
$body = elgg_view_layout('one_column', array(
        'content' => $content,
        'title' => $title,
));
echo elgg_view_page($title, $body);

}
