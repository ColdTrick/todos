<?php
namespace ColdTrick\Todos;

class Cron {
	
	/**
	 * Send notifications about due todo items
	 *
	 * @param \Elgg\Hook $hook 'cron', 'daily'
	 *
	 * @return void|\ElggMenuItem[]
	 */
	public static function notifyDueTodoItems(\Elgg\Hook $hook) {
				
		$time = (int) $hook->getParam('time', time());
		
		// items due in the next 24 hours
		$upper = $time + (24 * 60 * 60);
		
		$due_soon_options = [
			'type' => 'object',
			'subtype' => \TodoItem::SUBTYPE,
			'limit' => false,
			'metadata_name_value_pairs' => [
				[
					'name' => 'due',
					'value' => $time,
					'operand' => '>='
				],
				[
					'name' => 'due',
					'value' => $upper,
					'operand' => '<='
				],
			],
			'metadata_names' => [
				'order',
			],
		];
		
		// items due yesterday (past 24 hours)
		$lower = $time - (24 * 60 * 60);
		
		$due_yesterday_options = [
			'type' => 'object',
			'subtype' => \TodoItem::SUBTYPE,
			'limit' => false,
			'metadata_name_value_pairs' => [
				[
					'name' => 'due',
					'value' => $time,
					'operand' => '<='
				],
				[
					'name' => 'due',
					'value' => $lower,
					'operand' => '>='
				],
			],
			'metadata_names' => [
				'order',
			],
		];
		
		elgg_call(ELGG_IGNORE_ACCESS, function() use ($due_soon_options, $due_yesterday_options) {
			// loop thought due soon
			$batch = new ElggBatch('elgg_get_entities', $due_soon_options);
			foreach ($batch as $entity) {
				$list = $entity->getContainerEntity();
				if (empty($list)) {
					// orphaned to-to item, should not happen
					continue;
				}
				
				$subject = elgg_echo('todos:notify:todoitem:due_soon:subject', [$entity->getDisplayName()]);
				$message = elgg_echo('todos:notify:todoitem:due_soon:message', [
					$entity->getDisplayName(),
					date('Y-m-d', $entity->due),
					$entity->getURL(),
				]);
				
				$entity->notifyUsers($subject, $message, $list->getContainerGUID());
			}
			
			// loop through due yesterday
			$batch = new ElggBatch('elgg_get_entities', $due_yesterday_options);
			foreach ($batch as $entity) {
				$list = $entity->getContainerEntity();
				if (empty($list)) {
					// orphaned to-to item, should not happen
					continue;
				}
				
				$subject = elgg_echo('todos:notify:todoitem:due_yesterday:subject', [$entity->getDisplayName()]);
				$message = elgg_echo('todos:notify:todoitem:due_yesterday:message', [
					$entity->getDisplayName(),
					date('Y-m-d', $entity->due),
					$entity->getURL(),
				]);
				
				$entity->notifyUsers($subject, $message, $list->getContainerGUID());
			}
		});
	}
}
