<?php
$visits = elgg_extract('visits', $vars, FALSE);
$group_guid= elgg_extract('group_guid', $vars, FALSE);
$content .= '<div><table id="qis_ris"><tr><th>Request ID</th><th>Person</th><th>Medical</th><th>Blood Test</th><th>Fingerprinting</th><th>Action</th></tr>';
foreach ($visits as $visit) {
	$content .= elgg_view('qis/visit',array('group_guid'=> $group_guid,'visit' => $visit));
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
