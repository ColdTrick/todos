<?php

$entity = elgg_extract('entity', $vars);

$title = '';
$access_id = get_default_access();
if ($entity) {
	$title = $entity->title;
	$access_id = (int) $entity->access_id;
	
	echo elgg_view('input/hidden', array(
		'name' => 'guid',
		'value' => $entity->getGUID()
	));
}

echo elgg_view('input/text', array(
	'value' => $title,
	'name' => 'title',
	'placeholder' => elgg_echo('todos:todolist:title'),
	'required' => true,
	'class' => 'mvm'
));

echo '<div>';
echo '<label>' . elgg_echo('access') . '</label>';
echo elgg_view('input/access', array(
	'name' => 'access_id',
	'value' => $access_id
));
echo '</div>';

echo '<div class="elgg-foot">';
echo elgg_view('input/submit', array('value' => elgg_echo('save')));
echo '</div>';
