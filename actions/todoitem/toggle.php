<?php

$guid = get_input('guid');

$entity = get_entity($guid);

if ($entity->isCompleted()) {
	$entity->markAsIncomplete();
	
	elgg_create_river_item([
		'view' => 'river/object/todoitem/toggle',
		'action_type' => 'reopen',
		'subject_guid' => elgg_get_logged_in_user_guid(),
		'object_guid' => $entity->guid,
	]);
	
	system_message(elgg_echo('todos:action:todoitem:toggle:reopened', array($entity->title)));
} else {
	$entity->complete();
	
	elgg_create_river_item([
		'view' => 'river/object/todoitem/toggle',
		'action_type' => 'close',
		'subject_guid' => elgg_get_logged_in_user_guid(),
		'object_guid' => $entity->guid,
	]);
	system_message(elgg_echo('todos:action:todoitem:toggle:closed', array($entity->title)));
}