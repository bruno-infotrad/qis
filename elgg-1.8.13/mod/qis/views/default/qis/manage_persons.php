<?php
$users = elgg_extract('users', $vars, FALSE);
$content = '<div><table id="qis_ris"><tr><th>Person</th><th>Type</th><th>Action</th></tr>';
foreach ($users as $user) {
	if (! $user->isAdmin()) {
		$content .= elgg_view('qis/user',array('user' => $user));
	}
}
$content .= '</table></div>';
$content .= '<div class="elgg-foot">';
$sub_but = elgg_view('input/submit', array('value' => elgg_echo('create_user')));
$content .= elgg_view('input/form', array('body' => $sub_but, 'action' => "{$CONFIG->url}qis/manage_person",'class' => 'elgg-button elgg-button-submit'));
$content .= ' ';
$sub_but = elgg_view('input/submit', array('value' => elgg_echo('main_menu')));
$content .= elgg_view('input/form', array('body' => $sub_but, 'action' => "{$CONFIG->url}",'class' => 'elgg-button elgg-button-submit'));
$content .= '</div>';

echo $content;
