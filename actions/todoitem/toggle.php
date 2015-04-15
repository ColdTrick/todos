<?php

$guid = get_input('guid');

$entity = get_entity($guid);

if ($entity->isCompleted()) {
	$entity->markAsIncomplete();
	
	add_to_river('river/object/todoitem/toggle', 'reopen', elgg_get_logged_in_user_guid(), $entity->guid);
	
	system_message(elgg_echo('todos:action:todoitem:toggle:reopened', array($entity->title)));
} else {
	$entity->complete();
	
	add_to_river('river/object/todoitem/toggle', 'close', elgg_get_logged_in_user_guid(), $entity->guid);
	
	system_message(elgg_echo('todos:action:todoitem:toggle:closed', array($entity->title)));
}