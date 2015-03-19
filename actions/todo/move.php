<?php

$guid = (int) get_input('guid');
$pos = (int) get_input('pos');

$entity = get_entity($guid);

if (empty($pos)) {
	register_error('position missing');
	forward(REFERER);
}

if ($entity instanceof Todo) {
	// update container
	
	
	// update position
	$entity->moveToPosition($pos);
} else {
	register_error('no todo');
}