<?php

$guid = get_input('guid');

$entity = get_entity($guid);

if ($entity->isCompleted()) {
	$entity->markAsIncomplete();
} else {
	$entity->complete();
}