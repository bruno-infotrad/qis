<?php
elgg_register_event_handler('init', 'system', 'qis_init');

/**
 * The Wire initialization
 */
function qis_init() {

	//elgg_register_plugin_hook_handler('roles:config','role','qis_roles_config',501);
	// for md only
	//elgg_register_plugin_hook_handler('permissions_check:metadata', 'user', 'qis_permissions_override');
	// for whole entities
	elgg_register_plugin_hook_handler('permissions_check', 'user', 'qis_user_permissions_override');
	elgg_register_plugin_hook_handler('permissions_check', 'object', 'qis_file_permissions_override');
	//elgg_register_plugin_hook_handler('permissions_check:group', 'user', 'qis_group_permissions_override');
	// register the wire's JavaScript
	$qis_js = elgg_get_simplecache_url('js', 'qis');
	elgg_register_simplecache_view('js/qis');
	elgg_register_js('elgg.qis', $qis_js);
	elgg_extend_view('css/elgg', 'qis/css');

	//elgg_register_ajax_view('thewire/previous');

	// add menu items
	$item = new ElggMenuItem('qis_manage_corporate_information', elgg_echo('qis:manage_corporate_information'), 'qis/manage_corporate_information');
	elgg_register_menu_item('qis', $item);
	$item = new ElggMenuItem('qis_manage_persons', elgg_echo('qis:manage_persons'), 'qis/manage_persons');
	elgg_register_menu_item('qis', $item);
	$item = new ElggMenuItem('qis_request_immigration_services', elgg_echo('qis:request_immigration_services'), 'qis/request_immigration_services');
	elgg_register_menu_item('qis', $item);
	$item = new ElggMenuItem('qis_request_resident_permit', elgg_echo('qis:request_resident_permit'), 'qis/request_resident_permit');
	elgg_register_menu_item('qis', $item);
	$item = new ElggMenuItem('qis_view_reports', elgg_echo('qis:view_reports'), 'qis/view_reports');
	elgg_register_menu_item('qis', $item);
	// owner block menu
	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'qis_owner_block_menu');

	// remove edit and access and add thread, reply, view previous
//	elgg_register_plugin_hook_handler('register', 'menu:entity', 'thewire_setup_entity_menu_items');
	
	// Extend system CSS with our own styles, which are defined in the thewire/css view
	elgg_extend_view('css/elgg', 'qis/css');

	//extend views
	//elgg_extend_view('activity/thewire', 'thewire/activity_view');
	//elgg_extend_view('profile/status', 'thewire/profile_status');
	//elgg_extend_view('js/initialise_elgg', 'thewire/js/textcounter');

	// Register a page handler, so we can have nice URLs
	elgg_register_page_handler('qis', 'qis_page_handler');

	// Register a URL handler for thewire posts
	elgg_register_entity_url_handler('object', 'qis', 'qis_url');

	//elgg_register_widget_type('thewire', elgg_echo('thewire'), elgg_echo("thewire:widget:desc"));

	// Register for search
	elgg_register_entity_type('object', 'qisResidentPermitRequest');
	elgg_register_entity_type('object', 'qisQuotaRequest');
	elgg_register_entity_type('object', 'qisResidentPermit');
	elgg_register_entity_type('object', 'qisQuota');

	// Register granular notification for this type
	//register_notification_object('object', 'thewire', elgg_echo('thewire:notify:subject'));

	// Listen to notification events and supply a more useful message
	//elgg_register_plugin_hook_handler('notify:entity:message', 'object', 'thewire_notify_message');

	// Register actions
	$action_base = elgg_get_plugins_path() . 'qis/actions/qis';
	elgg_register_action("qis/add_citizenship", "$action_base/add_citizenship.php");
	elgg_register_action("qis/manage_person", "$action_base/manage_person.php");
	elgg_register_action("qis/ResidentPermitRequest", "$action_base/RPRequest.php");
	elgg_register_action("qis/QuotaRequest", "$action_base/QuotaRequest.php");
}

/**
 * The wire page handler
 *
 * Supports:
 * thewire/all                  View site wire posts
 * thewire/owner/<username>     View this user's wire posts
 * thewire/following/<username> View the posts of those this user follows
 * thewire/reply/<guid>         Reply to a post
 * thewire/view/<guid>          View a post
 * thewire/thread/<id>          View a conversation thread
 * thewire/tag/<tag>            View wire posts tagged with <tag>
 *
 * @param array $page From the page_handler function
 * @return bool
 */
function qis_page_handler($page) {

	$base_dir = elgg_get_plugins_path() . 'qis/pages/qis';

	if (!isset($page[0])) {
		$page = array('dashboard');
	}

	switch ($page[0]) {
		case "activate":
			include "$base_dir/activate.php";
			break;

		case "add_citizenship":
			if (isset($page[1])) {
				set_input('user_guid', $page[1]);
			}
			if (isset($page[2])) {
				set_input('guid', $page[2]);
			}
			include "$base_dir/add_citizenship.php";
			break;

		case "dashboard":
			include "$base_dir/dashboard.php";
			break;

		case "deactivate":
			include "$base_dir/deactivate.php";
			break;

		case "manage_corporate_information":
			include "$base_dir/manage_corporate_information.php";
			break;

		case "manage_person":
			if (isset($page[1])) {
				set_input('guid', $page[1]);
			}
			include "$base_dir/manage_person.php";
			break;

		case "manage_persons":
			include "$base_dir/manage_persons.php";
			break;

		case "request_immigration_service":
			if (isset($page[1])) {
				set_input('request_guid', $page[1]);
			}
			include "$base_dir/request_immigration_service.php";
			break;

		case "request_immigration_services":
			include "$base_dir/request_immigration_services.php";
			break;

		case "request_resident_permit":
			include "$base_dir/request_resident_permit.php";
			break;

		case "view":
			if (isset($page[1])) {
				set_input('guid', $page[1]);
			}
			include "$base_dir/view.php";
			break;

		case "view_reports":
			include "$base_dir/view_reports.php";
			break;

		default:
			return false;
	}
	return true;
}

function qis_user_permissions_override($hook_name, $entity_type, $return_value, $params) {
	if (elgg_get_context() == 'manage_person') {
	$user = $params['user'];
	if ($user) {
		$user_groups = get_users_membership ($user->guid);
		if ($user_groups) {
			if (check_entity_relationship($user->getGUID(), "group_admin", $user_groups[0]->getGUID())) {
				return true;
			} else {
				return null;
			}
		}
	}
	}
	return null;
}

function qis_file_permissions_override($hook_name, $entity_type, $return_value, $params) {
	if ((elgg_get_context() == 'add_citizenship') ||
	    (elgg_get_context() == 'request_immigration_service')) {
	$user = $params['user'];
	$object = $params['entity'];
	if ($user && $object) {
		$user_groups = get_users_membership ($user->guid);
		if ($user_groups) {
			if (check_entity_relationship($user->getGUID(), "group_admin", $user_groups[0]->getGUID())) {
				return true;
			} else {
				return null;
			}
		}
	}
	}
	return null;
}

function qis_group_permissions_override($hook_name, $entity_type, $return_value, $params) {
	$user = $params['user'];
	if ($user) {
		$user_groups = get_users_membership ($user->guid);
		if ($user_groups) {
			if (check_entity_relationship($user->getGUID(), "group_admin", $user_groups[0]->getGUID())) {
				return true;
			} else {
				return false;
			}
		}
	}
}

function qis_roles_config($hook_name, $entity_type, $return_value, $params) {
	$roles = array(
		’portal_administrator’ => array(
			'title' => 'qis_company_portal_administrator',
			'permissions' => array(
				'actions' => array(
					'profile/edit' => array('rule' => 'allow')
				),
                                'views' => array(
					'admin/users/add' => array('rule' => 'allow'),
					'profile/edit' => array('rule' => 'allow')
				),
			),
		)
	);
	if (!is_array($return_value)) {
		return $roles;
	} else {
		return array_merge($return_value, $roles);
	}
}
