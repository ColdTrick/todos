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
	
	$comments_count = $entity->countComments();
	if ($comments_count) {
		$body .= '<span class="todos-item-comments mls">';
		$body .= elgg_echo('comments:count', array($comments_count));
		$body .= '</span>';
	}
		
	$info = array();
	if ($entity->assignee) {
		$assignee = get_user($entity->assignee);
		if (!empty($assignee)) {
			$assignee_text = '<span class="todos-item-assignee">';
			$assignee_text .= elgg_view('output/url', array(
				'text' => $assignee->name,
				'href' => $assignee->getURL(),
				'is_trusted' => true
			));
			$assignee_text .= '</span>';

			$info[] = $assignee_text;
		}
	}
	
	if ($entity->due) {
		$due_text = '<span class="todos-item-due">';
		$due_text .= elgg_view('output/date', array('value' => $entity->due));
		$due_text .= '</span>';
		
		$info[] = $due_text;
	}
	
	if (!empty($info)) {
		$body .= '<span class="todos-item-info mls">';
		$body .= implode('<span class="phs">&#8226;</span>', $info);
		$body .= '</span>';
	}
	
	$body .= elgg_view_menu('todoitem', array(
		'entity' => $entity,
		'class' => 'elgg-menu-hz elgg-menu-todos',
		'sort_by' => 'register'
	));
	
	$params = array();
	if ($entity->isCompleted()) {
		$params['class'] = 'todos-list-item-completed';
	}

	echo elgg_view_image_block($checkbox, $body, $params);
	
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
	
	$activity = elgg_list_river(array(
		'object_guids' => array($entity->guid),
		'action_types' => array('create', 'reopen', 'close'),
		'limit' => false
	));
	
	if ($activity) {
		echo elgg_view_module('info', elgg_echo('activity'), $activity);
	}
}
