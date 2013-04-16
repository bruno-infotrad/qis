<?php
$immigration_requests = elgg_extract('immigration_requests', $vars, FALSE);
$group_guid= elgg_extract('group_guid', $vars, FALSE);
$expli = elgg_extract('expli', $vars, FALSE);
if ($expli) {
	$content = '<div id="qis-message">'.$expli.'</div>';
}
$content .= '<div><table id="qis_ris"><tr><th>Type</th><th>Person</th><th>Occupation</th><th>Country</th><th>Duration</th><th>Action</th></tr>';
foreach ($immigration_requests as $immigration_request) {
	$content .= elgg_view('qis/immigration_request',array('group_guid'=> $group_guid,'immigration_request' => $immigration_request));
}
$content .= '</table></div>';
$content .= '<div class="elgg-foot">';
//$sub_but = elgg_view('input/submit', array('value' => elgg_echo('create_user')));
//$content .= elgg_view('input/form', array('body' => $sub_but, 'action' => "{$CONFIG->url}qis/manage_person",'class' => 'elgg-button elgg-button-submit'));
//$content .= ' ';
$sub_but = elgg_view('input/submit', array('value' => elgg_echo('main_menu')));
$content .= elgg_view('input/form', array('body' => $sub_but, 'action' => "{$CONFIG->url}",'class' => 'elgg-button elgg-button-submit'));
$content .= '</div>';

echo $content;
