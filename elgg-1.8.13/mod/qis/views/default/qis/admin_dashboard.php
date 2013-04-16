<?php
$qis_groups = elgg_extract('qis_groups', $vars, FALSE);
foreach ($qis_groups as $qis_group) {
	$content .= elgg_view('qis/qis_group',array('qis_group' => $qis_group));
}
echo $content;
