<?php

use ColdTrick\Todos\Bootstrap;

require_once(dirname(__FILE__) . "/lib/functions.php");
// require_once(dirname(__FILE__) . "/lib/hooks.php");

return [
	'bootstrap' => Bootstrap::class,
	'settings' => [
		'enable_personal' => 'no',
		'enable_groups' => 'no',
	],
	'entities' => [
		[
			'type' => 'object',
			'subtype' => 'todolist',
			'class' => TodoList::class,
			'searchable' => true,
		],
		[
			'type' => 'object',
			'subtype' => 'todoitem',
			'class' => TodoItem::class,
			'searchable' => true,
		],
	],
	'routes' => [
		'view:object:todoitem' => [
			'path' => '/todos/item/view/{guid}/{title?}',
			'resource' => 'todos/item/view',
		],
		'todoitem:attachment:download' => [
			'path' => '/todos/item/attachment/{guid}/{filename}',
			'resource' => 'todos/item/attachment',
		],
		'view:object:todolist' => [
			'path' => '/todos/list/view/{guid}/{title?}',
			'resource' => 'todos/list/view',
		],
		'collection:object:todolist:group' => [
			'path' => '/todos/list/group/{guid}',
			'resource' => 'todos/list/group',
		],
		'collection:object:todoitem:assigned:group' => [
			'path' => '/todos/item/assigned/group/{guid}',
			'resource' => 'todos/item/assigned/group',
		],
		'collection:object:todoitem:assigned:user' => [
			'path' => '/todos/item/assigned/user/{username}',
			'resource' => 'todos/item/assigned/user',
		],
		'collection:object:todoitem:assigned:per_user' => [
			'path' => '/todos/item/assigned/per_user/{guid}',
			'resource' => 'todos/item/assigned/per_user',
		],
		'collection:object:todolist:all' => [
			'path' => '/todos/all',
			'resource' => 'todos/list/all',
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
			],
		],
		'default:object:todolist' => [
			'path' => '/todos',
			'resource' => 'todos/list/all',
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
			],
		],
	],
	'actions' => [
		'todos/todolist/edit' => [],
		
		'todos/todoitem/edit' => [],
		'todos/todoitem/toggle' => [],
		'todos/todoitem/delete_attachment' => [],
		'todos/todoitem/attach' => [],

		'todos/todo/move' => [],
	],
	'widgets' => [
		'todos_assigned' => [
			'name' => elgg_echo('todos:widget:assigned:title'),
			'description' => elgg_echo('todos:widget:assigned:description'),
			'context' => ['dashboard'],
		],
		'todos_closed' => [
			'name' => elgg_echo('todos:widget:closed:title'),
			'description' => elgg_echo('todos:widget:closed:description'),
			'context' => ['index', 'groups'],
		],
		'todos_due' => [
			'name' => elgg_echo('todos:widget:due:title'),
			'description' => elgg_echo('todos:widget:due:description'),
			'context' => ['index', 'groups'],
		],
		'todos_created' => [
			'name' => elgg_echo('todos:widget:due:title'),
			'description' => elgg_echo('todos:widget:due:description'),
			'context' => ['index', 'groups'],
		],
		'todos_list' => [
			'name' => elgg_echo('todos:widget:list:title'),
			'description' => elgg_echo('todos:widget:list:description'),
			'context' => ['dashboard', 'groups'],
			'multiple' => true,
		],
	],
];
