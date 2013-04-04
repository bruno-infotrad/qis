<?php

if (elgg_is_logged_in()) {
$submitter = elgg_get_logged_in_user_entity();
$submitter_groups = get_users_membership ($submitter->guid);
$group_guid = $submitter_groups[0]->guid;

$user = elgg_get_logged_in_user_entity();
$user_role = $user->role;
$user_group_guid = $user->group;

$title = elgg_echo('qis:manage_persons');

//$groups = get_users_membership($user->guid);
$users = elgg_get_entities_from_relationship(array(
        'relationship' => 'member',
        'relationship_guid' => $group_guid,
        'inverse_relationship' => true,
        'types' => 'user',
        //'list_type' => 'gallery',
        //'gallery_class' => 'elgg-gallery-users',
));
$body = '<table id="qis_ris"><tr><th>Person</th><th>Type</th><th>Action</th></tr>';
foreach ($users as $user) {
	if (! $user->isAdmin()) {
		$body .= elgg_view('qis/user',array('user' => $user));
	}
}
$body .= '</table>';
$sub_but = elgg_view('input/submit', array('value' => elgg_echo('create_user')));
$body .= elgg_view('input/form', array('body' => $sub_but, 'action' => "{$CONFIG->url}qis/manage_person"));
echo elgg_view_page($title, $body);
}
