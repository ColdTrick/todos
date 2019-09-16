<?php

$guid = (int) get_input('guid');
$container_guid = (int) get_input('container_guid');
$pos = (int) get_input('pos');

$entity = get_entity($guid);
$new_container_entity = get_entity($container_guid);

$old_container_entity = null;

if (!$entity instanceof \Todo) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}
	
if ($new_container_entity instanceof \Todo) {
	if ($container_guid !== $entity->container_guid) {
		// moving to a new container
		
		$old_container_entity = $entity->getContainerEntity();
		$entity->container_guid = $container_guid;
		$entity->access_id = $new_container_entity->access_id;
		
		// move to bottom
		$entity->order = time();
		
		// do after order to clear caches
		$entity->save();
	}
}

// update position
$entity->moveToPosition($pos);

if ($old_container_entity instanceof \Todo) {
	// check list completeness on both lists
	$old_container_entity->validateListCompleteness();
	$new_container_entity->validateListCompleteness();
}

return elgg_ok_response();
