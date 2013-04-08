<?php
/**
 * All wire posts
 * 
 */


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

$title = elgg_echo('qis:dashboard');

$content = <<<__HTML
<div class="qis-to-do"><h2>To do:</h2>
<ul><li>Feb 19, 2013: Sign business visa form for Cathy Hill, HP (under QShield Sponsorship)
<ul><li>Feb 21, 2013: Sign business visa form for Peter Brown, Microsoft (under QShield Sponsorship)
</ul></div>
__HTML;
$content.=elgg_view('qis/applications_in_progress', array());
$content.=elgg_view_menu('qis', array('sort_by' => 'priority'));
$body = elgg_view_layout('one_column', array(
	'content' => $content,
	'title' => $title,
));
echo elgg_view_page($title, $body);
}
