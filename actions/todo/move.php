<?php

$guid = sanitise_int(get_input('guid'), false);
$container_guid = sanitise_int(get_input('container_guid'), false);
$pos = sanitise_int(get_input('pos'), false);

$entity = get_entity($guid);
$container_entity = get_entity($container_guid);

if ($entity instanceof Todo) {
	// update container
	if ($container_entity instanceof Todo) {
		$entity->container_guid = $container_guid;
		
		// move to bottom
		$entity->order = time();
		
		// do after order to clear caches
		$entity->save();
	}
	
	// update position
	$entity->moveToPosition($pos);
} else {
	register_error('no todo');
}