<?php
namespace ColdTrick\Todos;

class Widgets {
	
	/**
	 * Adds urls to the widgets
	 *
	 * @param \Elgg\Hook $hook 'entity:url', 'object'
	 *
	 * @return string
	 */
	public static function addWidgetURLs(\Elgg\Hook $hook) {

		$return_value = $hook->getValue();
		if (!empty($return_value)) {
			return;
		}
		
		$entity = $hook->getEntityParam();
		if (!$entity instanceof \ElggWidget) {
			return;
		}
		
		switch ($entity->handler) {
			case 'todos_assigned':
				return elgg_generate_url('collection:object:todoitem:assigned:user', [
					'username' => $entity->getOwnerEntity()->username,
				]);
				break;
			case 'todos_list':
				$list_guid = (int) $entity->list_guid;
				if (!empty($list_guid)) {
					$list = get_entity($list_guid);
					if (!$list instanceof \TodoList) {
						return $list->getURL();
					}
				}
				break;
		}
	}
}
