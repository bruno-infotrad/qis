<?php
//Delete data
$ResidentPermits = elgg_get_entities(array(
        'types' => 'object',
        'subtypes' => 'resident_permit',
));

if ($ResidentPermits) {
	foreach ($ResidentPermits as $RP) {
		$RP->delete();
	}
}
$Quotas = elgg_get_entities(array(
        'types' => 'object',
        'subtypes' => 'quota',
));
if ( $Quotas) {
	foreach ($Quotas as $Q) {
		$Q->delete();
	}
} 
forward();
?>
