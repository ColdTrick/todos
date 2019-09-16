<?php

namespace ColdTrick\Todos;

use Elgg\DefaultPluginBootstrap;

class Bootstrap extends DefaultPluginBootstrap {
	
	/**
	 * {@inheritdoc}
	 */
	public function init() {
		$this->registerViews();
		$this->registerHooks();
	}
	
	protected function registerViews() {
		elgg_extend_view('css/elgg', 'css/todos');
		elgg_extend_view('js/elgg', 'js/todos');
		
		elgg_register_ajax_view('todos/todolist/form');
		elgg_register_ajax_view('todos/todoitem/form');
		elgg_register_ajax_view('todos/todoitem/attach');
	}
	
	protected function registerHooks() {
		$hooks = $this->elgg()->hooks;
		
		$hooks->registerHandler('register', 'menu:site', '\ColdTrick\Todos\Menus::registerSite');
		$hooks->registerHandler('register', 'menu:todoitem', '\ColdTrick\Todos\Menus::registerTodoItem');
		$hooks->registerHandler('register', 'menu:entity', '\ColdTrick\Todos\Menus::registerTodoItemEntity');
		$hooks->registerHandler('register', 'menu:entity', '\ColdTrick\Todos\Menus::registerTodoList');
		$hooks->registerHandler('register', 'menu:filter:todos', '\ColdTrick\Todos\Menus::registerFilterMenu');
		$hooks->registerHandler('register', 'menu:owner_block', '\ColdTrick\Todos\Menus::registerGroupOwnerBlock');
		$hooks->registerHandler('register', 'menu:owner_block', '\ColdTrick\Todos\Menus::registerUserOwnerBlock');
		
		$hooks->registerHandler('cron', 'daily', '\ColdTrick\Todos\Menus::notifyDueTodoItems');

		$hooks->registerHandler('tool_options', 'group', '\ColdTrick\Todos\Groups::registerToolOption');
		
		// todo check
		//$hooks->registerHandler('permissions_check:comment', 'object', 'todos_todoitem_can_comment');
		
		$hooks->registerHandler('entity:url', 'object', '\ColdTrick\Todos\Widgets::addWidgetURLs');
	}
}
