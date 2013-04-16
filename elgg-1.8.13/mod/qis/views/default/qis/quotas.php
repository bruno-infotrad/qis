<?php
$full = elgg_extract('full_view', $vars, FALSE);
$quotas = elgg_extract('quotas', $vars, FALSE);
$group_guid = elgg_extract('group_guid', $vars, FALSE);
$content = '';
if ($quotas) {
	$content = '<div><table id="qis_ris"><tr><th>Request ID</th><th>Quantity</th><th>Citizenship</th><th>Gender</th><th>Occupation</th><th>Action</th></tr>';
	foreach ($quotas as $quota) {
		$content .= elgg_view('qis/quota',array('group_guid'=> $group_guid,'quota' => $quota));
	}
        $content .= '</table></div>';
}
$content .= '<div class="elgg-foot">';
$sub_but = elgg_view('input/submit', array('value' => elgg_echo('new_quota_request')));
$content .= elgg_view('input/form', array('body' => $sub_but, 'action' => "{$CONFIG->url}qis/manage_quota_request",'class' => 'elgg-button elgg-button-submit'));
$content .= ' ';
$sub_but = elgg_view('input/submit', array('value' => elgg_echo('main_menu')));
$content .= elgg_view('input/form', array('body' => $sub_but, 'action' => "{$CONFIG->url}",'class' => 'elgg-button elgg-button-submit'));
$content .= '</div>';
echo $content;
