<?php

$full = (bool) elgg_extract('full_view', $vars, false);
$entity = elgg_extract('entity', $vars);

if (!$full) {
	$checkbox = '';
	if (!elgg_in_context('todos_sidebar') && !elgg_in_context('widgets')) {
		$checkbox = elgg_view('input/checkbox', array(
			'rel' => $entity->guid,
			'checked' => $entity->isCompleted(),
			'disabled' => !$entity->canEdit()
		));
	}
	
	$body = elgg_view('output/url', array(
		'text' => $entity->title,
		'href' => $entity->getURL(),
		'is_trusted' => true
	));
	
	if ($entity->due) {
		$body .= '<span class="elgg-subtext mls">';
		$body .= elgg_view('output/date', array('value' => $entity->due));
		$body .= '</span>';
	}
	
	if ($entity->assignee) {
		$assignee = get_user($entity->assignee);
		if (!empty($assignee)) {
			$body .= '<span class="elgg-subtext mls">';
			$body .= elgg_view('output/url', array(
				'text' => $assignee->name,
				'href' => $assignee->getURL(),
				'is_trusted' => true
			));
			$body .= '</span>';
		}
	}
	
	$body .= elgg_view_menu('todoitem', array(
		'entity' => $entity,
		'class' => 'elgg-menu-hz elgg-menu-todos',
		'sort_by' => 'register'
	));

	echo elgg_view_image_block($checkbox, $body);
	
} else {
	
	if ($entity->due) {
		echo '<div>';
		echo '<label>' . elgg_echo('todos:todoitem:due') . ': </label>';
		echo elgg_view('output/date', array('value' => $entity->due));
		echo '</div>';
	}
	
	if ($entity->assignee) {
		$assignee = get_user($entity->assignee);
		if (!empty($assignee)) {
			echo '<div>';
			echo '<label>' . elgg_echo('todos:todoitem:assignee') . ': </label>';
			echo elgg_view('output/url', array(
				'text' => $assignee->name,
				'href' => $assignee->getURL(),
				'is_trusted' => true
			));
			echo '</div>';
		}
	}
	
	echo elgg_view_comments($entity);
}
