<?php
//Initialize some data
$ResidentPermits = elgg_list_entities(array(
        'types' => 'object',
        'subtypes' => 'resident_permit',
        'full_view' => FALSE,
));

if (! $ResidentPermits) {
	$RP0 = new qisResidentPermit();
	$RP0->subject_guid =41;
	$RP0->creator_guid =43;
	$RP0->owner_guid =44;
	$RP0->duration = 1;
	$RP0->access_id = 3;
	$RP0->save();
	$RP1 = new qisResidentPermit();
	$RP1->subject_guid =43;
	$RP1->creator_guid =35;
	$RP1->owner_guid =44;
	$RP1->duration = 1;
	$RP1->access_id = 3;
	$RP1->save();
	$RP2 = new qisResidentPermit();
	$RP2->subject_guid =41;
	$RP2->creator_guid =43;
	$RP2->owner_guid =44;
	$RP2->duration = 3;
	$RP2->access_id = 3;
	$RP2->save();
} /*else {
$title = "RP";
$body = elgg_view_layout('content', array(
        'content' => $ResidentPermits,
));

echo elgg_view_page($title, $body);

}
*/
$Quotas = elgg_list_entities(array(
        'types' => 'object',
        'subtypes' => 'quota',
        'full_view' => FALSE,
));
if (! $Quotas) {
	$Q0 = new qisQuota();
	$Q0->subject_guid =44;
	$Q0->creator_guid =35;
	$Q0->owner_guid =35;
	$Q0->quota_type = 'resident_permit';
	$Q0->quota_type_duration = 1;
	$Q0->quota_number = 5;
	$Q0->access_id = 3;
	$Q0->save();
	$Q1 = new qisQuota();
	$Q1->subject_guid =44;
	$Q1->creator_guid =35;
	$Q1->owner_guid =35;
	$Q1->quota_type = 'resident_permit';
	$Q1->quota_type_duration = 2;
	$Q1->quota_number = 10;
	$Q1->access_id = 3;
	$Q1->save();
	$Q2 = new qisQuota();
	$Q2->subject_guid =44;
	$Q2->creator_guid =35;
	$Q2->owner_guid =35;
	$Q2->quota_type = 'resident_permit';
	$Q2->quota_type_duration = 3;
	$Q2->quota_number = 5;
	$Q2->access_id = 3;
	$Q2->save();
} 
forward();
?>
