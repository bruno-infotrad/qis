<?php
$full = elgg_extract('full_view', $vars, FALSE);
$documents = elgg_extract('documents', $vars, FALSE);
$group_guid = elgg_extract('group_guid', $vars, FALSE);
$content = '';
if ($documents) {
	$content = '<div><table id="qis_ris"><tr><th>Request ID</th><th>Subject Name</th><th>Document Type</th><th>Expiry Date</th><th>Document</th><th>Action</th></tr>';
	foreach ($documents as $document) {
		$request_guid = $document->request_guid;
		$user_guid = get_entity($request_guid)->user_guid;
		$content .= elgg_view('qis/document',array('group_guid'=> $group_guid, 'document' => $document, 'request_guid' => $request_guid, 'user_guid' => $user_guid));
	}
        $content .= '</table></div>';
}
$content .= '<div class="elgg-foot">';
$sub_but = elgg_view('input/submit', array('value' => elgg_echo('new_document')));
$content .= elgg_view('input/form', array('body' => $sub_but, 'action' => "{$CONFIG->url}qis/manage_document/$group_guid",'class' => 'elgg-button elgg-button-submit'));
$content .= ' ';
$sub_but = elgg_view('input/submit', array('value' => elgg_echo('main_menu')));
$content .= elgg_view('input/form', array('body' => $sub_but, 'action' => "{$CONFIG->url}",'class' => 'elgg-button elgg-button-submit'));
$content .= '</div>';
echo $content;
