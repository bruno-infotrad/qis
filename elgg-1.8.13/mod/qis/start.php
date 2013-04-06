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
	//Index page handler
	elgg_register_plugin_hook_handler('index', 'system', 'qis_index_handler');
	//Page handlers
	elgg_register_page_handler('get_citizenship_docs', 'get_citizenship_docs');
	//elgg_register_ajax_view('thewire/previous');

	// add menu items
	elgg_register_menu_item('qis', array(
                        'name' => 'manage_persons',
                        'href' => "qis/manage_persons",
                        'text' => elgg_echo('manage_persons'),
                        'title' => elgg_echo('manage_persons'),
                        'class' => "elgg-button elgg-button-submit elgg-button-dashboard",
                        'priority' => 300,
                ));
	elgg_register_menu_item('qis', array(
                        'name' => 'add_person',
                        'href' => "qis/manage_person",
                        'text' => elgg_echo('add_person'),
                        'title' => elgg_echo('add_person'),
                        'class' => "elgg-button elgg-button-submit elgg-button-dashboard",
                        'priority' => 310,
                ));
	elgg_register_menu_item('qis', array(
                        'name' => 'manage_immigration_services',
                        'href' => "qis/manage_immigration_services",
                        'text' => elgg_echo('manage_immigration_services'),
                        'title' => elgg_echo('manage_immigration_services'),
                        'class' => "elgg-button elgg-button-submit elgg-button-dashboard",
                        'priority' => 320,
                ));
	elgg_register_menu_item('qis', array(
                        'name' => 'manage_immigration_requests',
                        'href' => "qis/manage_immigration_requests",
                        'text' => elgg_echo('manage_immigration_requests'),
                        'title' => elgg_echo('manage_immigration_requests'),
                        'class' => "elgg-button elgg-button-submit elgg-button-dashboard",
                        'priority' => 330,
                ));
	elgg_register_menu_item('qis', array(
                        'name' => 'request_resident_permit',
                        'href' => "qis/manage_rp_request",
                        'text' => elgg_echo('request_resident_permit'),
                        'title' => elgg_echo('request_resident_permit'),
                        'class' => "elgg-button elgg-button-submit elgg-button-dashboard",
                        'priority' => 340,
                ));
	elgg_register_menu_item('qis', array(
                        'name' => 'manage_corporate_information',
                        'href' => "",
                        'text' => elgg_echo('manage_corporate_information'),
                        'title' => elgg_echo('manage_corporate_information'),
                        'class' => "elgg-button elgg-button-submit elgg-button-dashboard",
                        'priority' => 350,
                ));
/*
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
*/
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
	elgg_register_action("qis/manage_citizenship", "$action_base/manage_citizenship.php");
	elgg_register_action("qis/manage_person", "$action_base/manage_person.php");
	elgg_register_action("qis/manage_rp_request", "$action_base/manage_rp_request.php");
	//custom look
	elgg_unregister_menu_item('topbar', 'elgg_logo');
}

// Index page handler
function qis_index_handler() {
        if (elgg_is_logged_in()) {
                forward('qis/dashboard');
        }
}

function qis_page_handler($page) {

	$base_dir = elgg_get_plugins_path() . 'qis/pages/qis';

	if (!isset($page[0])) {
		$page = array('dashboard');
	}

	switch ($page[0]) {
		case "activate":
			include "$base_dir/activate.php";
			break;

		case "view_person":
			if (isset($page[1])) {
				set_input('guid', $page[1]);
			}
			include "$base_dir/ajax/view_person.php";
			break;

		case "dashboard":
			include "$base_dir/dashboard.php";
			break;

		case "deactivate":
			include "$base_dir/deactivate.php";
			break;

		case "manage_citizenship":
			if (isset($page[1])) {
				set_input('user_guid', $page[1]);
			}
			if (isset($page[2])) {
				set_input('guid', $page[2]);
			}
			include "$base_dir/manage_citizenship.php";
			break;

		case "manage_corporate_information":
			include "$base_dir/manage_corporate_information.php";
			break;

		case "manage_immigration_services":
			include "$base_dir/manage_immigration_services.php";
			break;

		case "manage_immigration_requests":
			include "$base_dir/manage_immigration_requests.php";
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

		case "manage_rp_request":
			if (isset($page[1])) {
				set_input('request_guid', $page[1]);
			}
			include "$base_dir/manage_rp_request.php";
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
	if ((elgg_get_context() == 'manage_citizenship') ||
	    (elgg_get_context() == 'manage_rp_request')) {
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

function get_citizenship_docs() {
        require_once elgg_get_plugins_path() . 'qis/lib/get_citizenship_docs.php';
        return true;
}