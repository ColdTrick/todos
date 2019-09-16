<?php
namespace ColdTrick\Todos;

class Menus {
	
	/**
	 * Adds the todos link to the site menu
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:site'
	 *
	 * @return void|\ElggMenuItem[]
	 */
	public static function registerSite(\Elgg\Hook $hook) {

		if (elgg_get_plugin_setting('enable_personal', 'todos') !== 'yes') {
			return;
		}

		$return = $hook->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'todos',
			'text' => elgg_echo('todos'),
			'href' => elgg_generate_url('default:object:todolist'),
		]);
		
		return $return;
	}
	
	/**
	 * Adds the menu items to the todoitem
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:todoitem'
	 *
	 * @return void|\ElggMenuItem[]
	 */
	public static function registerTodoItem(\Elgg\Hook $hook) {
		$entity = $hook->getEntityParam();
		if (!$entity instanceof \TodoItem) {
			return;
		}
		
		if (!$entity->canEdit()) {
			return;
		}
		
		$return = $hook->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'edit',
			'text' => elgg_echo('edit'),
			'href' => "ajax/view/todos/todoitem/form?guid={$entity->guid}",
			'link_class' => 'elgg-lightbox',
		]);
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'delete',
			'text' => elgg_echo('delete'),
			'href' => "action/entity/delete?guid={$entity->getGUID()}",
			'confirm' => elgg_echo('deleteconfirm'),
		]);
		
		return $return;
	}
	
	/**
	 * Adds the menu items to the todoitem
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:entity'
	 *
	 * @return void|\ElggMenuItem[]
	 */
	public static function registerTodoItemEntity(\Elgg\Hook $hook) {
		$entity = $hook->getEntityParam();
		if (!$entity instanceof \TodoItem) {
			return;
		}
		
		if (!$entity->canEdit()) {
			return;
		}
		
		$return = $hook->getValue();

		$return[] = \ElggMenuItem::factory([
			'name' => 'toggle',
			'icon' => $entity->isCompleted() ? 'redo' : 'check',
			'text' => $entity->isCompleted() ? elgg_echo('todos:todoitem:reopen') : elgg_echo('todos:todoitem:close'),
			'href' => "action/todos/todoitem/toggle?guid={$entity->guid}",
			'is_action' => true,
		]);
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'upload',
			'icon' => 'upload',
			'text' => elgg_echo('todos:todoitem:attachment:upload'),
			'href' => "ajax/view/todos/todoitem/attach?guid={$entity->guid}",
			'link_class' => 'elgg-lightbox',
		]);
				
		$return[] = \ElggMenuItem::factory([
			'name' => 'edit',
			'icon' => 'edit',
			'text' => elgg_echo('edit'),
			'href' => "ajax/view/todos/todoitem/form?guid={$entity->guid}",
			'link_class' => 'elgg-lightbox',
		]);
		
		return $return;
	}
	
	/**
	 * Adds the menu items to the todolist
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:entity'
	 *
	 * @return void|\ElggMenuItem[]
	 */
	public static function registerTodoList(\Elgg\Hook $hook) {
		
		$entity = $hook->getEntityParam();
		if (!$entity instanceof \TodoList) {
			return;
		}
		
		if (!$entity->canEdit()) {
			return;
		}
		
		$return = $hook->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'edit',
			'icon' => 'edit',
			'text' => elgg_echo('edit'),
			'href' => "ajax/view/todos/todolist/form?guid={$entity->guid}",
			'link_class' => 'elgg-lightbox',
		]);
		
		return $return;
	}
	
	/**
	 * Adds the filter menu for todos
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:filter:todos'
	 *
	 * @return void|\ElggMenuItem[]
	 */
	public static function registerFilterMenu(\Elgg\Hook $hook) {
		if (elgg_get_context() !== 'todos') {
			return;
		}
		$return = $hook->getValue();
		
		$page_owner = elgg_get_page_owner_entity();
		if (todos_enabled_for_container($page_owner)) {
			$base_url = 'todos';
			if ($page_owner instanceof \ElggGroup) {
				$base_url .= "/group/{$page_owner->guid}/all";
			}
			
			$return[] = \ElggMenuItem::factory(array(
				'name' => 'active',
				'text' => elgg_echo('todos:filter:active'),
				'href' => $base_url
			));
		
			$return[] = \ElggMenuItem::factory(array(
				'name' => 'completed',
				'text' => elgg_echo('todos:filter:completed'),
				'href' => "{$base_url}?filter=completed"
			));
			
			if ($page_owner instanceof ElggGroup) {
				$return[] = \ElggMenuItem::factory(array(
					'name' => 'assigned_per_user',
					'text' => elgg_echo('todos:filter:assigned_per_user'),
					'href' => "todos/assigned_per_user/{$page_owner->guid}",
				));
			}
			
			$return[] = \ElggMenuItem::factory(array(
				'name' => 'overdue',
				'text' => elgg_echo('todos:filter:overdue'),
				'href' => "{$base_url}?filter=overdue"
			));
		}
	
		$user = elgg_get_logged_in_user_entity();
		if (!empty($user)) {
			$href = "todos/assigned/{$user->username}";
			if ($page_owner instanceof \ElggGroup) {
				$href .= "/{$page_owner->guid}";
			}
			$return[] = \ElggMenuItem::factory(array(
				'name' => 'assigned',
				'text' => elgg_echo('todos:filter:assigned'),
				'href' => $href
			));
		}
		
		return $return;
	}

	/**
	 * Adds the menu items to the owner_block of a group
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:owner_block'
	 *
	 * @return void|\ElggMenuItem[]
	 */
	public static function registerGroupOwnerBlock(\Elgg\Hook $hook) {
		$entity = $hook->getEntityParam();
		if (!$entity instanceof \ElggGroup) {
			return;
		}
		
		if (!$entity->isToolEnabled('todos')) {
			return;
		}
		
		$return = $hook->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'todos',
			'text' => elgg_echo('todos:owner_block:group'),
			'href' => "todos/group/{$entity->guid}/all",
		]);
		
		return $return;
	}
	
	/**
	 * Adds the menu items to the owner_block of a user
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:owner_block'
	 *
	 * @return void|\ElggMenuItem[]
	 */
	public static function registerUserOwnerBlock(\Elgg\Hook $hook) {
		
		$user = elgg_get_logged_in_user_entity();
		if (empty($user)) {
			return;
		}
		
		$entity = $hook->getEntityParam();
		if (!$entity instanceof \ElggUser) {
			return;
		}
		
		if ($entity->guid !== $user->guid) {
			return;
		}
		
		if (elgg_get_plugin_setting('enable_personal', 'todos') !== 'yes') {
			return;
		}
		$return = $hook->getValue();
		
		$return[] = \ElggMenuItem::factory([
			'name' => 'todos',
			'text' => elgg_echo('todos:owner_block:user'),
			'href' => elgg_generate_url('collection:object:todolist:all'),
		]);
		
		return $return;
	}
}
