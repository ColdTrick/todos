<?php

$guid = get_input('guid');

$entity = get_entity($guid);

if ($entity->isCompleted()) {
	$entity->markAsIncomplete();
	add_to_river('river/object/todoitem/toggle', 'reopen', elgg_get_logged_in_user_guid(), $entity->guid);
} else {
	add_to_river('river/object/todoitem/toggle', 'close', elgg_get_logged_in_user_guid(), $entity->guid);
	$entity->complete();
}