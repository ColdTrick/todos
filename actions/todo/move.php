<?php

$guid = sanitise_int(get_input('guid'), false);
$container_guid = sanitise_int(get_input('container_guid'), false);
$pos = sanitise_int(get_input('pos'), false);

$entity = get_entity($guid);
$new_container_entity = get_entity($container_guid);

$old_container_entity = null;

if ($entity instanceof Todo) {
	
	if ($new_container_entity instanceof Todo) {
		if ($container_guid !== $entity->container_guid) {
			// moving to a new container
			
			$old_container_entity = $entity->getContainerEntity();
			$entity->container_guid = $container_guid;
			
			// move to bottom
			$entity->order = time();
			
			// do after order to clear caches
			$entity->save();
		}
	}
	
	// update position
	$entity->moveToPosition($pos);
	
	if ($old_container_entity) {
		// check list completeness on both lists
		$old_container_entity->validateListCompleteness();
		$new_container_entity->validateListCompleteness();
	}
} else {
	register_error('no todo');
}