<?php

$guid = sanitise_int(get_input('guid'), false);
$pos = sanitise_int(get_input('pos'), false);

$entity = get_entity($guid);

if ($entity instanceof Todo) {
	// update container
	
	
	// update position
	$entity->moveToPosition($pos);
} else {
	register_error('no todo');
}