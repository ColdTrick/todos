<?php

$entity = elgg_extract('entity', $vars);
$container_guid = (int) elgg_extract('container_guid', $vars);

$title = '';
$access_id = ACCESS_DEFAULT;
$show_access = false;

if ($entity) {
	$title = $entity->title;
	
	if (elgg_instanceof($entity->getContainerEntity(), 'group')) {
		$show_access = true;
		$access_id = (int) $entity->access_id;
	}
	
	echo elgg_view('input/hidden', array(
		'name' => 'guid',
		'value' => $entity->getGUID()
	));
}

if (!$show_access && !empty($container_guid)) {
	$container = get_entity($container_guid);
	if (elgg_instanceof($container, 'group')) {
		$show_access = true;
	}
}

echo elgg_view('input/text', array(
	'value' => $title,
	'name' => 'title',
	'placeholder' => elgg_echo('todos:todolist:title'),
	'required' => true,
	'class' => 'mvm'
));

if ($show_access) {
	echo '<div>';
	echo '<label>' . elgg_echo('access') . '</label>';
	echo elgg_view('input/access', array(
		'name' => 'access_id',
		'value' => $access_id
	));
	echo '</div>';
}

echo '<div class="elgg-foot">';
echo elgg_view('input/hidden', array('name' => 'container_guid', 'value' => $container_guid));
echo elgg_view('input/submit', array('value' => elgg_echo('save')));
echo '</div>';
