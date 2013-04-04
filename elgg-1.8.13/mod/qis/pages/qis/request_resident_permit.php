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

$title = elgg_echo('qis:request_resident_permit');
$form = elgg_view_form('qis/request_resident_permit', array('enctype' => 'multipart/form-data'));
$body = elgg_view_layout('one_sidebar', array(
        'content' => $form,
        'title' => $title,
));
echo elgg_view_page($title, $body);

}
