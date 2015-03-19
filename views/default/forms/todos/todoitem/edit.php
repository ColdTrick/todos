<?php

$entity = elgg_extract('entity', $entity);

$title = '';
$container_guid = get_input('container_guid');

if ($entity) {
	$title = $entity->title;
}

if (empty($container_guid)) {
	echo 'missing container_guid';
	return;
}


echo elgg_view('input/hidden', array(
	'value' => $container_guid,
	'name' => 'container_guid'
));

echo elgg_view('input/text', array(
	'value' => $title,
	'name' => 'title',
	'required' => true,
	'placeholder' => elgg_echo('todos:todoitem:title')
));

echo '<label>' . elgg_echo('todos:todoitem:assignee') . '<label>';
echo '<span class="ui-front">';
echo elgg_view('input/userpicker', array(
	'value' => $assignee,
	'name' => 'assignee'
));
echo '</span>';

echo '<label>' . elgg_echo('todos:todoitem:due') . '<label>';
echo elgg_view('input/date', array(
	'value' => $due,
	'name' => 'due'
));

echo elgg_view('input/submit', array('value' => elgg_echo('save')));
