<?php
/**
 * Main file for this plugin
 */

require_once(dirname(__FILE__) . "/lib/functions.php");
require_once(dirname(__FILE__) . "/lib/hooks.php");

elgg_register_event_handler('init', 'system', 'todos_init');

/**
 * Initialize the plugin
 *
 * @return void
 */
function todos_init() {
	
	elgg_extend_view('css/elgg', 'css/todos');
	elgg_extend_view('js/elgg', 'js/todos');
	
	elgg_register_page_handler('todos', 'todos_page_handler');
	
	elgg_register_event_handler('pagesetup', 'system', 'todos_pagesetup');
	
	$actions_path = dirname(__FILE__) . '/actions/';
	elgg_register_action('todos/todolist/edit', $actions_path . 'todolist/edit.php');
	elgg_register_action('todos/todolist/delete', $actions_path . 'todolist/delete.php');
	
	elgg_register_action('todos/todoitem/edit', $actions_path . 'todoitem/edit.php');
	elgg_register_action('todos/todoitem/delete', $actions_path . 'todoitem/delete.php');
	elgg_register_action('todos/todoitem/toggle', $actions_path . 'todoitem/toggle.php');
	
	elgg_register_action('todos/todo/move', $actions_path . 'todo/move.php');
	
	elgg_register_ajax_view('todos/todolist/form');
	elgg_register_ajax_view('todos/todoitem/form');
	
	elgg_register_plugin_hook_handler('register', 'menu:todoitem', 'todos_todoitem_menu_register');
	elgg_register_plugin_hook_handler('register', 'menu:todolist', 'todos_todolist_menu_register');
	elgg_register_plugin_hook_handler('register', 'menu:filter', 'todos_filter_menu_register');
	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'todos_owner_block_menu_register');
	
	// groups
	if (todos_group_enabled()) {
		add_group_tool_option('todos', elgg_echo('todos:group:tool_option'), false);
	}
	
	// widgets
	elgg_register_plugin_hook_handler("widget_url", "widget_manager", "todos_widget_title_url");
	
	elgg_register_widget_type('todos_assigned', elgg_echo('todos:widget:assigned:title'), elgg_echo('todos:widget:assigned:description'), 'dashboard');
	elgg_register_widget_type('todos_closed', elgg_echo('todos:widget:closed:title'), elgg_echo('todos:widget:closed:description'), 'index,groups');
	elgg_register_widget_type('todos_due', elgg_echo('todos:widget:due:title'), elgg_echo('todos:widget:due:description'), 'index,groups');
	elgg_register_widget_type('todos_created', elgg_echo('todos:widget:created:title'), elgg_echo('todos:widget:created:description'), 'index,groups');
	elgg_register_widget_type('todos_list', elgg_echo('todos:widget:list:title'), elgg_echo('todos:widget:list:description'), 'dashboard,groups', true);
}

/**
 * Page handler
 *
 * @return void | bool
 */
function todos_page_handler($pages) {
		
	switch ($pages[0]) {
		case 'view':
			$guid = elgg_extract(1, $pages);
			set_input('guid', $guid);
			
			include(dirname(__FILE__) . '/pages/view.php');
			break;
		case 'assigned':
			if (isset($pages[1])) {
				$user = get_user_by_username($pages[1]);
				if ($user) {
					elgg_set_page_owner_guid($user->getGUID());
				}
			}
			
			include(dirname(__FILE__) . '/pages/assigned.php');
			break;
		case 'group':
		default:
			include(dirname(__FILE__) . '/pages/all.php');
	}
	
	return true;
}

/**
 * Page setup function for todos plugin
 *
 * @return void
 */
function todos_pagesetup() {
	$item = new ElggMenuItem('todos', elgg_echo('todos'), 'todos');
	elgg_register_menu_item('site', $item);
}
