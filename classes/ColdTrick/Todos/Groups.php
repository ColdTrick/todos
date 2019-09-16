<?php
namespace ColdTrick\Todos;

class Groups {
	
	/**
	 * Registers the group tool option
	 *
	 * @param \Elgg\Hook $hook 'tool_options', 'group'
	 *
	 * @return void|\ElggMenuItem[]
	 */
	public static function registerToolOption(\Elgg\Hook $hook) {

		if (elgg_get_plugin_setting('enable_groups', 'todos') !== 'yes') {
			return;
		}

		$tool = new \Elgg\Groups\Tool('todos', [
			'label' => elgg_echo('todos:group:tool_option'),
			'default_on' => false,
		]);
		
		/* @var \Elgg\Collections\Collection */
		$result = $hook->getValue();
		
		$result->add($tool);
		
		return $result;
	}
}
