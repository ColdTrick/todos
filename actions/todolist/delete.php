<?php

$guid = get_input('guid');

$entity = get_entity($guid);
$entity->delete();

forward(REFERER);